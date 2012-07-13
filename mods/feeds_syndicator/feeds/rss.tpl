<?xml version="1.0" encoding="{ENCODING}"?>
<rdf:RDF xml:lang="{LANGCODE}"
	xmlns="http://purl.org/rss/1.0/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel rdf:about="{BOARD_URL}">
	<title>{BOARD_TITLE}</title>
	<link>{BOARD_URL}</link>
	<description>{BOARD_DESCRIPTION}</description>
	<dc:date>{BUILD_DATE_ISO8601}</dc:date>
	<dc:language>{LANGCODE}</dc:language>
	<items>
		<rdf:Seq>
<!-- BEGIN post_li -->
			<rdf:li rdf:resource="{post_li.POST_URL}"/>
<!-- END post_li -->
		</rdf:Seq>
	</items>
</channel>
<!-- BEGIN post_item -->
<item rdf:about="{post_item.POST_URL}">
	<title>{post_item.POST_SUBJECT}</title>
	<link>{post_item.POST_URL}</link>
	<dc:creator>{post_item.AUTHOR}</dc:creator>
	<dc:date>{post_item.POST_TIME_ISO8601}</dc:date>
	<description><![CDATA[{post_item.POST_DESCRIPTION}]]></description>
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
</rdf:RDF>
