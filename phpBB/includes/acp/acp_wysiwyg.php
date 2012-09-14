<?php
if (!defined('IN_PHPBB'))
{
    exit;
}
/**
* @package acp
*/
class acp_wysiwyg
{
	var $u_action;
	var $tpl_path;
	var $page_title;
	
	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang('mods/wysiwyg');
		$this->tpl_name = 'acp_wysiwyg';
		$this->page_title = 'ACP_WYSIWYG';
		
		if (isset($_POST['submit']))
		{
			set_config('bbcode_image_show', (int) request_var('bbcode_image_show_radio', 1));
			set_config('wysiwyg_show', (int) request_var('wysiwyg_show_radio', 1));
			set_config('link_p', (int) request_var('link_p_radio', 1));
			set_config('wysiwyg_bg', request_var('wysiwyg_bg',''));
			set_config('EXP', utf8_normalize_nfc(request_var('EXP','',true)));
			set_config('wdir', request_var('wdir',''));
			set_config('wysiwyg_color', request_var('wysiwyg_color',''));
			set_config('wysiwyg_fonts', request_var('wysiwyg_fonts',''));
			set_config('wysiwyg_exp', utf8_normalize_nfc(request_var('wysiwyg_exp','',true)));  
			add_log('admin', 'LOG_WYSIWYG_UPDATED');
			trigger_error($user->lang['LOG_WYSIWYG_UPDATED'] . adm_back_link($this->u_action));
		}

		$template->assign_vars(array(
			'S_BBCODE_IMAGE_SHOW'	=> ( $config['bbcode_image_show'] == 1 ) ? true:false,
			'S_BBCODE_IMAGE_DSHOW'		=> ( $config['bbcode_image_show'] == 0 ) ? true:false,
			'S_LINK_P'		=> ( $config['link_p'] == 1 ) ? true:false,
			'S_LINK_PD'		=> ( $config['link_p'] == 0 ) ? true:false,
			'S_WYSIWYG_BG'	=> $config['wysiwyg_bg'] ,
			'S_WYSIWYG_COLOR'	=> $config['wysiwyg_color'],
			'S_WYSIWYG_FONTS'	=> $config['wysiwyg_fonts'],
			'S_WYSIWYG_EXP'    => utf8_normalize_nfc($config['wysiwyg_exp']),  
			'S_EXP'	=> utf8_normalize_nfc($config['EXP']),
			'S_WYSIWYG_SHOW'	=> ($config['wysiwyg_show'] == 1 ) ? true:false,
			'S_WYSIWYG_DSHOW'	=> ($config['wysiwyg_show'] == 0 ) ? true:false,
			'S_E_DIR'	=> $config['wdir'],
		));
	}

}	
?>