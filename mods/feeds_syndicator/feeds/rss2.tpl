<?xml version="1.0" encoding="{ENCODING}"?>
<rss version="2.0" xml:lang="{LANGCODE}"
	xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
	<title>{BOARD_TITLE}</title>
	<link>{BOARD_URL}</link>
	<description>{BOARD_DESCRIPTION}</description>
	<language>{LANGCODE}</language>
	<pubDate>{BUILD_DATE_RFC2822}</pubDate>
	<generator>Feeds Syndicator</generator>
<!-- BEGIN post_item -->
	<item>
		<title>{post_item.POST_SUBJECT}</title>
		<guid>{post_item.POST_URL}</guid>
		<link>{post_item.POST_URL}</link>
		<pubDate>{post_item.POST_TIME_RFC2822}</pubDate>
		<description>{post_item.POST_DESCRIPTION}</description>
<!-- BEGIN content -->
		<content:encoded><![CDATA[
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
		]]></content:encoded>
<!-- END content -->
	</item>
<!-- END post_item -->
</channel>
</rss>
