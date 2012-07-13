<?php
/***************************************************************************
 *                                  feeds.php
 *                             -------------------
 *   author               : dynamis (Tomoya Asai)
 *   version              : 0.6.3
 *   begin                : Saturday, July 2, 2005
 *   notes                : This code is based on the work of the original
 *                          developers below.
 *   site                 : http://firehacks.org/phpbb/
 *
 ***************************************************************************/

/***************************************************************************
 *                                  rss.php
 *                            -------------------
 *   begin                : Monday, July 7, 2003
 *   notes                : This code is based on the work of the original
 *                          developer below.  Portions of this code
 *                          'borrowed' from phpbb_fetch_posts, an
 *                          untitled rdf content syndicator posted at
 *                          phpbb.com, and phpbb itself.
 *   email                : rss@wickedwisdom.com
 *
 *   $Id$
 *
 ***************************************************************************/

/***************************************************************************
 *                                  rdf.php
 *                            -------------------
 *   begin                : Saturday, Mar 2, 2002
 *   copyright            : (C) 2002 Matthijs van de Water
 *                          Sascha Carlin
 *   email                : phpbb@matthijs.net
 *                          sc@itst.org
 *
 *   $Id$
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

$phpbb_root_path = './';

if ( !defined("IN_PHPBB") )
{
	define ('IN_PHPBB', true);
}
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

include($phpbb_root_path . 'feeds/config.'.$phpEx);
if( !defined("FEEDS_INSTALLED") )
{
	message_die(GENERAL_ERROR, 'Feeds Syndicator is not installed correctlly.', '', __LINE__, __FILE__);
}

//
// Determine the Target
//

// select a category/forum/topic only
$category = ( isset($HTTP_GET_VARS['c']) ) ? intval($HTTP_GET_VARS['c']) : $feeds_config['category'];
$sql_target_where = ( $category == 0 ) ? '' : " AND f.cat_id = $category";

$forum    = ( isset($HTTP_GET_VARS['f']) ) ? intval($HTTP_GET_VARS['f']) : $feeds_config['forum'];
$sql_target_where = ( $forum == 0 ) ? $sql_target_where : " AND f.forum_id = $forum";

$topic    = ( isset($HTTP_GET_VARS['t']) ) ? intval($HTTP_GET_VARS['t']) : $feeds_config['topic'];
$sql_target_where = ( $topic == 0 ) ? $sql_target_where : " AND t.topic_id = $topic";

// select specific user's post only
$user     = ( isset($HTTP_GET_VARS['u']) ) ? intval($HTTP_GET_VARS['u']) : $feeds_config['user'];
$sql_select_poster_where = ( $user == 0 ) ? '' : " AND u.user_id = $user";

// exclude forums
$sql_exclude_forums_where = ( count($feeds_config['exclude_forums']) ) ? ' AND f.forum_id NOT IN (' . implode(', ', $feeds_config['exclude_forums']) . ')' : '';

// output item counts
$count = ( isset($HTTP_GET_VARS['n']) ) ? intval($HTTP_GET_VARS['n']) : $feeds_config['count'];
$count = ( $count < $feeds_config['count_min'] ) ? $feeds_config['count_min'] : $count;
$count = ( $count > $feeds_config['count_max'] ) ? $feeds_config['count_max'] : $count;

// topics or posts
$exclude_replies = ( isset($HTTP_GET_VARS['r']) ) ? intval($HTTP_GET_VARS['r']) : $feeds_config['exclude_replies'];
$sql_exclude_replies_where = ( $exclude_replies > 0 ) ? ' AND p.post_id = t.topic_first_post_id' : '';
$sql_exclude_replies_where = ( $exclude_replies < 0 ) ? ' AND p.post_id = t.topic_last_post_id'  : $sql_exclude_replies_where;

//
// Determine the Format
//

// feed format
$format = ( $format ) ? $format : $feeds_config['format'];

// template file
$template_filename = ( $template_filename ) ? $template_filename : "$format.tpl";

//
// Session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

//
// Create main board information (some code borrowed from functions_post.php)
//
// Build URL components
$script_name		= preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
$index				= ( $script_name != '' ) ? $script_name . '/index.' . $phpEx : 'index.'. $phpEx;
$viewforum			= ( $script_name != '' ) ? $script_name . '/viewforum.' . $phpEx : 'viewforum.'. $phpEx;
$viewtopic			= ( $script_name != '' ) ? $script_name . '/viewtopic.' . $phpEx : 'viewtopic.'. $phpEx;
$posting			= ( $script_name != '' ) ? $script_name . '/posting.' . $phpEx : 'posting.'. $phpEx;
$server_name		= trim($board_config['server_name']);
$server_protocol	= ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port		= ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';
// Assemble URL components
$board_url			= $server_protocol . $server_name . $server_port . $script_name . '/';
$index_url			= $server_protocol . $server_name . $server_port . $index;
$viewforum_url		= $server_protocol . $server_name . $server_port . $viewforum;
$viewtopic_url		= $server_protocol . $server_name . $server_port . $viewtopic;
$posting_url 		= $server_protocol . $server_name . $server_port . $posting;
$board_tag_url		= "tag:$server_name," . date('Y', $board_config['board_startdate']) . ":/$script_name/";
// Reformat site name and description
$site_name			= strip_tags($board_config['sitename']);
$site_description	= strip_tags($board_config['site_desc']);
// Set the fully qualified url to your smilies folder
$smilies_path		= $board_config['smilies_path'];
$smilies_url		= $board_url . $smilies_path;
$smilies_path		= preg_replace("/\//", "\/", $smilies_path);

//
// Initialise template
//
$template->set_filenames(array(
	"body" => $phpbb_root_path . 'feeds/' . $template_filename)
);

//
// Common variables of the template
//
$template->assign_vars(array(
	'ENCODING'				=> $lang['ENCODING'],
	'LANGCODE'				=> $feeds_config['langcode'],
	'BOARD_URL'				=> $board_url,
	'BOARD_TAG_URI'			=> "tag:$server_name," . date('Y', $board_config['board_startdate']) . ":/$script_name/",
	'BOARD_TITLE'			=> "$site_name " . $lang['Forum'],
	'BOARD_DESCRIPTION'		=> $site_description,
	'BOARD_EMAIL'			=> $board_config['board_email'],
	'BUILD_DATE_RFC2822'	=> date('r', time()),
	'BUILD_DATE_ISO8601'	=> preg_replace('/^(.+)(..)$/', '$1:$2', date('Y-m-d\TH:i:sO', time())),
	'L_CATEGORY'			=> $lang['Category'],
	'L_FORUM'				=> $lang['Forum'],
	'L_TOPIC'				=> $lang['Topic'],
	'L_POSTER'				=> $lang['Poster'],
	'L_AUTHOR'				=> $lang['Author'],
	'L_POSTED'				=> $lang['Posted'],
	'L_POST'				=> $lang['Post'],
	'L_POST_SUBJECT'		=> $lang['Post_subject'],
	'L_POST_TIME'			=> $lang['Posted'],
	'L_REPLY_WITH_QUOTE'	=> $lang['Reply_with_quote']
	)
);

// Override variable of the template if needed
if ( $category != 0 || $forum != 0 || $topic != 0 )
{
	$sql = "SELECT c.cat_title, f.forum_name, t.topic_title
		FROM " . CATEGORIES_TABLE . " AS c, " . FORUMS_TABLE . " AS f, " . TOPICS_TABLE . " AS t
		WHERE
			f.cat_id = c.cat_id 
				AND t.forum_id = f.forum_id
				AND f.auth_view = " . AUTH_ALL . "
				$sql_target_where
				$sql_exclude_forums_where
		LIMIT 1";
	$title_query = $db->sql_query($sql);
	
	if ( !$title_query )
	{
		message_die(GENERAL_ERROR, "Could not query the title.", "", __LINE__, __FILE__, $sql);
	}
	else if ( !$db->sql_numrows($posts_query) )
	{
		message_die(GENERAL_MESSAGE, "Invalid setting or query strings");
	}
	$title = $db->sql_fetchrow($posts_query);

	if ( $category != 0 )
	{
		$template->assign_vars(array(
			'BOARD_TITLE'	=> htmlspecialchars("$site_name － " . $title['cat_title'] . ' ' . $lang['Category']),
			'BOARD_URL'		=> $index_url . '?' . POST_CAT_URL . "=$category",
			'BOARD_TAG_URI'	=> "tag:$server_name," . date('Y', $board_config['board_startdate']) . ":/$script_name/category/$category",
			)
		);
	}
	if ( $forum != 0 )
	{
		$template->assign_vars(array(
			'BOARD_TITLE'	=> htmlspecialchars("$site_name － " . $title['forum_name'] . ' ' . $lang['Forum']),
			'BOARD_URL'		=> $viewforum_url . '?' . POST_FORUM_URL . "=$forum",
			'BOARD_TAG_URI'	=> "tag:$server_name," . date('Y', $board_config['board_startdate']) . ":/$script_name/forum/$forum",
			)
		);
	}
	if ( $topic != 0 )
	{
		$template->assign_vars(array(
			//'BOARD_TITLE'	=> htmlspecialchars("$site_name － " . $title['topic_title']),
			'BOARD_TITLE'	=> htmlspecialchars("$site_name － ") . $title['topic_title'],
			'BOARD_URL'		=> $viewtopic_url . '?' . POST_TOPIC_URL . "=$topic",
			'BOARD_TAG_URI'	=> "tag:$server_name," . date('Y', $board_config['board_startdate']) . ":/$script_name/topic/$topic",
			)
		);
	}
}

//
// SQL query to fetch active posts of public forums
//
$sql = "SELECT f.forum_id, f.forum_name, t.topic_id, t.topic_title, t.topic_type, u.user_id, u.username, u.user_sig, u.user_sig_bbcode_uid, p.post_id, pt.post_text, pt.post_subject, pt.bbcode_uid, p.post_time, t.topic_replies, t.topic_first_post_id
	FROM " . FORUMS_TABLE . " AS f, " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u, " . POSTS_TABLE . " AS p, " . POSTS_TEXT_TABLE . " as pt
	WHERE
		t.forum_id = f.forum_id
			AND f.auth_view = " . AUTH_ALL . "
			AND p.topic_id = t.topic_id
			AND p.poster_id = u.user_id
			AND pt.post_id = p.post_id
			$sql_target_where
			$sql_select_poster_where
			$sql_exclude_replies_where
			$sql_exclude_forums_where
	ORDER BY p.post_time DESC LIMIT $count";
$posts_query = $db->sql_query($sql);

if ( !$posts_query )
{
	message_die(GENERAL_ERROR, "Could not query list of active posts", "", __LINE__, __FILE__, $sql);
}
else if ( !$db->sql_numrows($posts_query) )
{
	message_die(GENERAL_MESSAGE, "Invalid setting or query strings");
}

//
// BEGIN "item" loop
//
while ($post = $db->sql_fetchrow($posts_query))
{
	// replace $template->root to use custom bbcode.tpl in bbencode_second_pass()
	$template_root = $template->root;
	$template->root = $phpbb_root_path . "feeds";

	// prepare post text
	$post_text = $post['post_text'];
	$post_text = bbencode_second_pass($post_text, $post['bbcode_uid']);
	$post_text = str_replace("\r\n", "\n", $post_text);
	$post_text = str_replace("\n", "<br/>\n", $post_text);
	$post_text = smilies_pass($post_text);
	$post_text = preg_replace("/$smilies_path/", $smilies_url, $post_text);
	$post_text = make_clickable($post_text);

	$post_description = strip_tags($post_text);
	$post_description = preg_replace("/(\s|\n)+/", " ", $post_description);
	//$post_description = mb_strlen($post_description, $lang['ENCODING']) > $feeds_config['description_limit'] ? mb_substr($post_description, 0, $feeds_config['description_limit'], $lang['ENCODING']) . "..." : $post_description;
	if (strlen($post_description) > $feeds_config['description_limit'])
	{
		$post_description = substr($post_description, 0, $feeds_config['description_limit']);
		$post_description = mb_substr($post_description, 0, mb_strlen($post_description, $lang['ENCODING']) - 1, $lang['ENCODING']) . "...";
	}
	
	// prepare user sig
	$user_sig = $post['user_sig'];
	$user_sig = bbencode_second_pass($user_sig, $post['user_sig_bbcode_uid']);
	$user_sig = smilies_pass($user_sig);
	$user_sig = preg_replace("/$smilies_path/", $smilies_url, $user_sig);
	$user_sig = make_clickable($user_sig);
	if ( $user_sig != '' )
	{
		$user_sig = str_replace("\r\n", "\n", $user_sig);
		$user_sig = "<hr/>\n<p>" . str_replace("\n", "<br/>\n", $user_sig) . '</p>';
	}
	
	// reset $template->root after using bbencode_second_pass()
	$template->root = $template_root;
	
	// prepare topic type label
	$topic_type = '';
	$topic_type = ( $post['topic_type'] == POST_ANNOUNCE )	? $lang['Topic_Announcement']	: $topic_type;
	$topic_type = ( $post['topic_type'] == POST_STICKY )	? $lang['Topic_Sticky']			: $topic_type;
	
	// Assign "item" variables to template
	$template->assign_block_vars('post_li', array(
		'POST_URL'			=> $viewtopic_url . '?' . POST_POST_URL . '=' . $post['post_id'] . '#' . $post['post_id']
		)
	);
	$template->assign_block_vars('post_item', array(
		'FORUM_URL'			=> $viewforum_url . '?' . POST_FORUM_URL . '=' . $post['forum_id'],
		'TOPIC_URL'			=> $viewtopic_url . '?' . POST_TOPIC_URL . '=' . $post['topic_id'],
		'POST_URL'			=> $viewtopic_url . '?' . POST_POST_URL . '=' . $post['post_id'] . '#' . $post['post_id'],
		'POST_TAG_URI'		=> "tag:$server_name," . date('Y-m-d', $post['post_time']) . ":/$script_name/post/" . $post['post_id'],
		'QUOTE_URL'			=> $posting_url . '?mode=quote&' . POST_POST_URL . '=' . $post['post_id'] . '#' . $post['post_id'],
		'FORUM_NAME'		=> htmlspecialchars($post['forum_name']),
		//'TOPIC_TITLE'		=> htmlspecialchars($post['topic_title']),
		'TOPIC_TITLE'		=> $post['topic_title'],
		'TOPIC_TYPE'		=> $topic_type,
		'TOPIC_REPLIES'		=> $post['topic_replies'],
		'AUTHOR'			=> htmlspecialchars($post['username']),
		//'POST_SUBJECT'		=> ( $post['post_subject'] ) ? htmlspecialchars($post['post_subject']) : '(no subject)',
		'POST_SUBJECT'		=> ( $post['post_subject'] ) ? $post['post_subject'] : '(no subject)',
		'POST_TIME'			=> create_date($board_config['default_dateformat'], $post['post_time'], $board_config['board_timezone']) . ' (GMT ' . $board_config['board_timezone'] . ')',
		'POST_TIME_RFC2822'	=> date('r', $post['post_time']),
		//'POST_TIME_ISO8601'	=> date('c', $post['post_time']), // date format 'c' will be added in PHP 5
		'POST_TIME_ISO8601'	=> preg_replace('/^(.+)(..)$/', '$1:$2', date('Y-m-d\TH:i:sO', $post['post_time'])),
		'POST_DESCRIPTION'	=> htmlspecialchars($post_description)
		)
	);
	if ( !$feeds_config['nocontent'] )
	{
		$template->assign_block_vars('post_item.content', array(
			'POST_TEXT'			=> $post_text,
			'USER_SIG'			=> $user_sig
			)
		);
		if ( !$exclude_replies )
		{
			$template->assign_block_vars('post_item.content.post_subject', array());
		}
	}
}
//
// END "item" loop
//

//
// XML and nocaching headers (copied from page_header.php)
//
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ("Content-Type: " . $feeds_config['content_type'] . "; charset=" . $lang['ENCODING']);


//
// Output the feed
//
$template->pparse('body');

?>
