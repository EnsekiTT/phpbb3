<?php
/**
*
* @author Username (almsamim) almsamim@gmail.com
* @package umil
* @copyright (c) 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}
if ($config['wysiwyg_show'] && !$config['wysiwyg_version']){
	set_config('wysiwyg_version' , '0.0.8');
}
$mod_name = 'WYSIWYG_MOD';
$version_config_name = 'wysiwyg_version';
$language_file = 'mods/wysiwyg_install';
$options = array(
	'EXP'	=> array('lang' => 'WYSIWYG_EXPT', 'type' => 'text:40:255', 'explain' => true),
	'wysiwyg_exp'	=> array('lang' => 'WYSIWYG_EXP', 'type' => 'textarea:5:50', 'explain' => true, 'default' => 'Hello
Welcome
Cheers
GoodBye
&lt;img src=http://www.phpbb.com/theme/images/logo_phpbb.png /&gt;
&lt;u&gt;HT&lt;/u&gt;&lt;i&gt;ML&lt;/i&gt;', 'select_user' => true),
	'wysiwyg_fonts'	=> array('lang' => 'WYSIWYG_FONTS', 'type' => 'textarea:5:50', 'explain' => true, 'default' => 'Arial
Courier
Tahoma
Times New Roman
Verdana
Impact
Lucida Console
Georgia
Comic Sans MS', 'select_user' => true),
	'wdir'	=> array('lang' => 'WYSIWYG_DIR', 'type' => 'select', 'function' => 'select_direction' , 'explain' => false),
);
// Versions of the editor
$versions = array(
	// Version 0.0.8
	'0.0.8'	=> array(
		'config_add' => array(
			array('bbcode_image_show', 1),
			array('wysiwyg_show', 1),
			array('link_p', 0),
			array('wysiwyg_bg', '#fff'),
			array('wysiwyg_color', '#000'),
			array('wysiwyg_fonts', utf8_normalize_nfc(request_var('wysiwyg_fonts','',true))),
			array('EXP', utf8_normalize_nfc(request_var('EXP','',true))),
			array('wdir', request_var('wdir','')),
			array('wysiwyg_exp', utf8_normalize_nfc(request_var('wysiwyg_exp','',true))),
		),
		// Alright, now lets add some modules to the ACP
		'module_add' => array(
			array('acp', 'ACP_CAT_DOT_MODS', 'ACP_WYSIWYG'),
			array('acp', 'ACP_WYSIWYG', array(
					'module_basename'		=> 'wysiwyg',
					'module_langname'	=> 'WYSIWYG',
					'module_mode'		=> 'overview',
					'module_auth'		=> 'acl_a_board',
				),
			),
		),
		'custom'	=> 'install_bbcode',
	),
	// Version 0.0.9
	'0.0.9'	=> array(
		'table_column_add' => array(
			array(USERS_TABLE, 'user_wysiwyg', array('BOOL', 1)),
		),
	),
);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);
/*
* Build regular expression for custom bbcode
*/
function build_regexp(&$bbcode_match, &$bbcode_tpl)
{
	$bbcode_match = trim($bbcode_match);
	$bbcode_tpl = trim($bbcode_tpl);

	$fp_match = preg_quote($bbcode_match, '!');
	$fp_replace = preg_replace('#^\[(.*?)\]#', '[$1:$uid]', $bbcode_match);
	$fp_replace = preg_replace('#\[/(.*?)\]$#', '[/$1:$uid]', $fp_replace);

	$sp_match = preg_quote($bbcode_match, '!');
	$sp_match = preg_replace('#^\\\\\[(.*?)\\\\\]#', '\[$1:$uid\]', $sp_match);
	$sp_match = preg_replace('#\\\\\[/(.*?)\\\\\]$#', '\[/$1:$uid\]', $sp_match);
	$sp_replace = $bbcode_tpl;

	// @todo Make sure to change this too if something changed in message parsing
	$tokens = array(
		'URL'	 => array(
			'!(?:(' . str_replace(array('!', '\#'), array('\!', '#'), get_preg_expression('url')) . ')|(' . str_replace(array('!', '\#'), array('\!', '#'), get_preg_expression('www_url')) . '))!ie'	=>	"\$this->bbcode_specialchars(('\$1') ? '\$1' : 'http://\$2')"
		),
		'LOCAL_URL'	 => array(
			'!(' . str_replace(array('!', '\#'), array('\!', '#'), get_preg_expression('relative_url')) . ')!e'	=>	"\$this->bbcode_specialchars('$1')"
		),
		'EMAIL' => array(
			'!(' . get_preg_expression('email') . ')!ie'	=>	"\$this->bbcode_specialchars('$1')"
		),
		'TEXT' => array(
			'!(.*?)!es'	 =>	"str_replace(array(\"\\r\\n\", '\\\"', '\\'', '(', ')'), array(\"\\n\", '\"', '&#39;', '&#40;', '&#41;'), trim('\$1'))"
		),
		'SIMPLETEXT' => array(
			'!([a-zA-Z0-9-+.,_ ]+)!'	 =>	"$1"
		),
		'IDENTIFIER' => array(
			'!([a-zA-Z0-9-_]+)!'	 =>	"$1"
		),
		'COLOR' => array(
			'!([a-z]+|#[0-9abcdef]+)!i'	=>	'$1'
		),
		'NUMBER' => array(
			'!([0-9]+)!'	=>	'$1'
		)
	);

	$sp_tokens = array(
		'URL'	 => '(?i)((?:' . str_replace(array('!', '\#'), array('\!', '#'), get_preg_expression('url')) . ')|(?:' . str_replace(array('!', '\#'), array('\!', '#'), get_preg_expression('www_url')) . '))(?-i)',
		'LOCAL_URL'	 => '(?i)(' . str_replace(array('!', '\#'), array('\!', '#'), get_preg_expression('relative_url')) . ')(?-i)',
		'EMAIL' => '(' . get_preg_expression('email') . ')',
		'TEXT' => '(.*?)',
		'SIMPLETEXT' => '([a-zA-Z0-9-+.,_ ]+)',
		'IDENTIFIER' => '([a-zA-Z0-9-_]+)',
		'COLOR' => '([a-zA-Z]+|#[0-9abcdefABCDEF]+)',
		'NUMBER' => '([0-9]+)',
	);

	$pad = 0;
	$modifiers = 'i';

	if (preg_match_all('/\{(' . implode('|', array_keys($tokens)) . ')[0-9]*\}/i', $bbcode_match, $m))
	{
		foreach ($m[0] as $n => $token)
		{
			$token_type = $m[1][$n];

			reset($tokens[strtoupper($token_type)]);
			list($match, $replace) = each($tokens[strtoupper($token_type)]);

			// Pad backreference numbers from tokens
			if (preg_match_all('/(?<!\\\\)\$([0-9]+)/', $replace, $repad))
			{
				$repad = $pad + sizeof(array_unique($repad[0]));
				$replace = preg_replace('/(?<!\\\\)\$([0-9]+)/e', "'\${' . (\$1 + \$pad) . '}'", $replace);
				$pad = $repad;
			}

			// Obtain pattern modifiers to use and alter the regex accordingly
			$regex = preg_replace('/!(.*)!([a-z]*)/', '$1', $match);
			$regex_modifiers = preg_replace('/!(.*)!([a-z]*)/', '$2', $match);

			for ($i = 0, $size = strlen($regex_modifiers); $i < $size; ++$i)
			{
				if (strpos($modifiers, $regex_modifiers[$i]) === false)
				{
					$modifiers .= $regex_modifiers[$i];

					if ($regex_modifiers[$i] == 'e')
					{
						$fp_replace = "'" . str_replace("'", "\\'", $fp_replace) . "'";
					}
				}

				if ($regex_modifiers[$i] == 'e')
				{
					$replace = "'.$replace.'";
				}
			}

			$fp_match = str_replace(preg_quote($token, '!'), $regex, $fp_match);
			$fp_replace = str_replace($token, $replace, $fp_replace);

			$sp_match = str_replace(preg_quote($token, '!'), $sp_tokens[$token_type], $sp_match);
			$sp_replace = str_replace($token, '${' . ($n + 1) . '}', $sp_replace);
		}

		$fp_match = '!' . $fp_match . '!' . $modifiers;
		$sp_match = '!' . $sp_match . '!s';

		if (strpos($fp_match, 'e') !== false)
		{
			$fp_replace = str_replace("'.'", '', $fp_replace);
			$fp_replace = str_replace(".''.", '.', $fp_replace);
		}
	}
	else
	{
		// No replacement is present, no need for a second-pass pattern replacement
		// A simple str_replace will suffice
		$fp_match = '!' . $fp_match . '!' . $modifiers;
		$sp_match = $fp_replace;
		$sp_replace = '';
	}

	// Lowercase tags
	$bbcode_tag = preg_replace('/.*?\[([a-z0-9_-]+=?).*/i', '$1', $bbcode_match);
	$bbcode_search = preg_replace('/.*?\[([a-z0-9_-]+)=?.*/i', '$1', $bbcode_match);

	if (!preg_match('/^[a-zA-Z0-9_-]+=?$/', $bbcode_tag))
	{
		global $user;
		trigger_error($user->lang['BBCODE_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
	}

	$fp_match = preg_replace('#\[/?' . $bbcode_search . '#ie', "strtolower('\$0')", $fp_match);
	$fp_replace = preg_replace('#\[/?' . $bbcode_search . '#ie', "strtolower('\$0')", $fp_replace);
	$sp_match = preg_replace('#\[/?' . $bbcode_search . '#ie', "strtolower('\$0')", $sp_match);
	$sp_replace = preg_replace('#\[/?' . $bbcode_search . '#ie', "strtolower('\$0')", $sp_replace);

	return array(
		'bbcode_tag'				=> $bbcode_tag,
		'first_pass_match'			=> $fp_match,
		'first_pass_replace'		=> $fp_replace,
		'second_pass_match'			=> $sp_match,
		'second_pass_replace'		=> $sp_replace
	);
}
// select direction menu
function select_direction($selected_value, $key)
	{
		global $user;

		$dir_type_ary = array('ltr','rtl');
		$dir_type_lang = array('ltr' => 'WYSIWYG_LTR' ,'rtl' => 'WYSIWYG_RTL');
		$dir_char_options = '';
		foreach ($dir_type_ary as $dir_type)
		{
			$selected = ($selected_value == $dir_type) ? ' selected="selected"' : '';
			$dir_char_options .= '<option value="' . $dir_type . '"' . $selected . '>' . $user->lang[$dir_type_lang[$dir_type]] . '</option>';
		}

		return $dir_char_options;
}
// installing new BBcode
function install_bbcode($action, $version){
	global $db, $table_prefix, $umil;
	$bbcode_data = array(
		's'				=> array( "s", "[s]{TEXT}[/s]", "<strike>{TEXT}</strike>", 0 ,''),
		'sup'				=> array( "sup", "[sup]{TEXT}[/sup]", "<sup>{TEXT}</sup>", 0 ,''),
		'sub'				=> array( "sub", "[sub]{TEXT}[/sub]", "<sub>{TEXT}</sub>", 0 ,''),
		'h1'				=> array( "h1", "[h1]{TEXT}[/h1]", "<h1>{TEXT}</h1>", 0 ,''),
		'h2'				=> array( "h2", "[h2]{TEXT}[/h2]", "<h2>{TEXT}</h2>", 0 ,''),
		'h3'				=> array( "h3", "[h3]{TEXT}[/h3]", "<h3>{TEXT}</h3>", 0 ,''),
		'h4'				=> array( "h4", "[h4]{TEXT}[/h4]", "<h4>{TEXT}</h4>", 0 ,''),
		'h5'				=> array( "h5", "[h5]{TEXT}[/h5]", "<h5>{TEXT}</h5>", 0 ,''),
		'h6'				=> array( "h6", "[h6]{TEXT}[/h6]", "<h6>{TEXT}</h6>", 0 ,''),
		'center'				=> array( "center", "[center]{TEXT}[/center]", "<div align=\"center\">{TEXT}</div>", 0 ,''),
		'right'				=> array( "right", "[right]{TEXT}[/right]", "<div align=\"right\">{TEXT}</div>", 0 ,''),
		'left'				=> array( "left", "[left]{TEXT}[/left]", "<div align=\"left\">{TEXT}</div>", 0 ,''),
		'justify'				=> array( "justify", "[justify]{TEXT}[/justify]", "<div align=\"justify\">{TEXT}</div>", 0 ,''),
		'font='				=> array( "font=", "[font={TEXT1}]{TEXT}[/font]", "<font face=\"{TEXT1}\">{TEXT}</font>", 0 ,''),
		'hr'				=> array( "hr", "[hr][/hr]", "<hr />", 0 ,''),
		'video'				=> array( "video", "[video]{TEXT}[/video]", "<script type=\"text/javascript\" src=\"video.php?link={TEXT}\"></script>", 1 ,'[video]link[/video]')
	);
	if ($action == 'install')
	{
	foreach ( $bbcode_data as $bbcode_name => $bbcode_values ){
		$data = build_regexp($bbcode_values[1], $bbcode_values[2]);
		$sql_ary = array(
			'bbcode_tag'			=> $bbcode_values[0],
			'bbcode_match'			=> $bbcode_values[1],
			'bbcode_tpl'			=> $bbcode_values[2],
			'first_pass_match'		=> $data['first_pass_match'],
			'first_pass_replace'	=> $data['first_pass_replace'],
			'second_pass_match'		=> $data['second_pass_match'],
			'second_pass_replace'	=> $data['second_pass_replace'],
			'display_on_posting'	=> $bbcode_values[3],
			'bbcode_helpline'	=> $bbcode_values[4]
		);
		$sql = 'SELECT MAX(bbcode_id) as max_bbcode_id FROM ' . BBCODES_TABLE;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if ($row)
		{
			$bbcode_id = $row['max_bbcode_id'] + 1;
			// Make sure it is greater than the core bbcode ids...
			if ($bbcode_id <= NUM_CORE_BBCODES)
			{
				$bbcode_id = NUM_CORE_BBCODES + 1;
			}
		}
		else
		{
			$bbcode_id = NUM_CORE_BBCODES + 1;
		}
		$sql_ary['bbcode_id'] = (int) $bbcode_id;
		$db->sql_query('INSERT INTO ' . BBCODES_TABLE . $db->sql_build_array('INSERT', $sql_ary));
	}
	return array('command' => 'ADD_BBCODE_ROW', 'result' => 'SUCCESS');
	} elseif ($action == 'uninstall')
	{
		foreach ( $bbcode_data as $bbcode_name => $bbcode_values ){
			$sql = 'DELETE FROM ' . BBCODES_TABLE ." WHERE bbcode_tag = '$bbcode_name'";
			$db->sql_query($sql);
		}
		return array('command' => 'REMOVE_BBCODE_ROW', 'result' => 'SUCCESS');
	}
	}
?>