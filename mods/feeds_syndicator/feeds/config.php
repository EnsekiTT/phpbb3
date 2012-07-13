<?php

$feeds_config = array(
	//
	//		Target Setting
	//
	// target selection (0 means all, overrided by "c=", "f=", "t=", "u=" query string)
	'category' => 0,
	'forum'    => 0,
	'topic'    => 0,
	'user'     => 0,

	// ids of excluded forums
	'exclude_forums' => array( ),

	// munber of the posts (overrided by "n=" query string)
	'count' => 15,
	'count_min' => 1,
	'count_max' => 50,

	// include all posts or 1 post per topic (overrided by "r=" query string)
	// 0: all, 1: first post only, -1: last post only
	'exclude_replies' => 0,


	//
	//		Format Setting
	//
	// feed format ('atom', 'rss', 'rss2')
	'format' => 'rss',

	// limit bytes included in <description>
	'description_limit' => 400,

	// omit content or not
	'nocontent' => false,

	// language code of output
	'langcode' => 'ja',

	// Content-Type: header
	//'content_type' => 'application/rdf+xml'
	//'content_type' => 'application/rss+xml'
	//'content_type' => 'application/atom+xml'
	'content_type' => 'application/xml',


	//
	//		Autodiscovery Setting
	//
	'autodiscovery_interface' => 'rss.'.$phpEx
);

define('FEEDS_INSTALLED', true);

?>
