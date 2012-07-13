<?xml version="1.0" encoding="{ENCODING}"?>
<!DOCTYPE contents [ <!ENTITY nbsp "&#160;"><!ENTITY copy "&#169;"> ]>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="{LANGCODE}">
	<title>{BOARD_TITLE}</title>
	<subtitle>{BOARD_DESCRIPTION}</subtitle>
	<id>{BOARD_TAG_URI}</id>
	<link href="{BOARD_URL}"/>
	<updated>{BUILD_DATE_ISO8601}</updated>
	<generator uri="http://dynamis.jp/phpbb/FeedsSyndicator">Feeds Syndicator</generator>
<!-- BEGIN post_item -->
	<entry>
		<title>{post_item.POST_SUBJECT}</title>
		<id>{post_item.POST_TAG_URI}</id>
		<link href="{post_item.POST_URL}"/>
		<author>
			<name>{post_item.AUTHOR}</name>
		</author>
		<updated>{post_item.POST_TIME_ISO8601}</updated>
		<summary>{post_item.POST_DESCRIPTION}</summary>
<!-- BEGIN content -->
		<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">
<table>
<tr><th style="text-align: right;">{L_FORUM}</th><td>: &nbsp;</td><td><a href="{post_item.FORUM_URL}">{post_item.FORUM_NAME}</a></td></tr>
<tr><th style="text-align: right;">{L_TOPIC}</th><td>: &nbsp;</td><td>{post_item.TOPIC_TYPE} <a href="{post_item.TOPIC_URL}">{post_item.TOPIC_TITLE}</a></td></tr>
<!-- BEGIN post_subject -->
<tr><th style="text-align: right;">{L_POST_SUBJECT}</th><td>: &nbsp;</td><td><a href="{post_item.POST_URL}">{post_item.POST_SUBJECT}</a></td></tr>
<!-- END post_subject -->
</table>
<hr/>
{post_item.content.POST_TEXT}
{post_item.content.USER_SIG}
		</div></content>
<!-- END content -->
	</entry>
<!-- END post_item -->
</feed>
