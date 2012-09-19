<?php
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'INSTALL_WYSIWYG_MOD'				=> 'تركيب المحرر المرئي',
	'INSTALL_WYSIWYG_MOD_CONFIRM'		=> 'هل أنت جاهز لتركيب المحرر المرئي ?',

	'REMOVE_BBCODE_ROW'				=> 'حذف حقول bbcode من phpbb_bbcodes.',
	'ADD_BBCODE_ROW'				=> 'إضافة حقول bbcode ل phpbb_bbcodes.',

	'WYSIWYG_BOOLEAN'					=> 'Custom Boolean',
	'WYSIWYG_MOD'						=> 'هاك المحرر المرئي',
	'ACP_WYSIWYG'						=> 'إعدادات عامة',
	// EXPRESSIONS
	'WYSIWYG_EXP'						=> 'عناصر القائمة الاضافية',
	'WYSIWYG_EXP_EXPLAIN'						=> 'Write every item in a separate <b>line</b> . you may use HTML but <b>without any quotes <u>single or double </u>.</b>',
	'WYSIWYG_EXPT'						=> 'عنوان القائمة الاضافية',
	'WYSIWYG_EXPT_EXPLAIN'						=> 'لا تكتب فى هذا الحقل إذا اردت تعطيل القائمة الإضافية',
	// DIRECTION
	'WYSIWYG_DIR' => 'اتجاه المحرر',
	'WYSIWYG_LTR' => 'من اليسار لليمين',
	'WYSIWYG_RTL' => 'من اليمين لليسار',
	// FONTS
	'WYSIWYG_FONTS'				=> 'الخطوط',
	'WYSIWYG_FONTS_EXPLAIN'				=> 'اكتب كل خط فى سطر منفصل .',
	'UNINSTALL_WYSIWYG_MOD'			=> 'حذف المحرر المرئي',
	'UNINSTALL_WYSIWYG_MOD_CONFIRM'	=> 'هل أنت جاهز لحذف المحرر المرئي ؟ كل الاعدادات من قاعدة البيانات سيتم حذفها !!',
	'UPDATE_WYSIWYG_MOD'				=> 'تحديث المحرر المرئي',
	'UPDATE_WYSIWYG_MOD_CONFIRM'		=> 'هل أنت جاهز لتحديث المحرر المرئي ?',
));

?>