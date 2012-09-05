<?php
/**
*
* @package VC
* @version $Id$
* @copyright (c) 2006, 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (!class_exists('phpbb_default_captcha'))
{
	// we need the classic captcha code for tracking solutions and attempts
	include($phpbb_root_path . 'includes/captcha/plugins/captcha_abstract.' . $phpEx);
}

/**
* @package VC
*/
class phpbb_nikita_sp extends phpbb_default_captcha
{
    var $nikita_sp_image;
    var $nikita_sp_code;
    var $nikita_sp_answer;

	function phpbb_nikita_sp(){
		global $config;
		session_start();
		$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '';
		$this->nikita_sp_code = $_SESSION['captcha_keystring'];
		$this->nikita_sp_image = '<img src="'.$phpbb_root_path.'kcaptcha/index.php?'.session_name().'='.session_id().'" alt="KCaptcha by Nikita_Sp" />';
		$this->nikita_sp_answer = request_var('answer_field', '');
	}

	function init($type){
		global $config, $db, $user;
		$user->add_lang('captcha_nikita_sp');
		parent::init($type);
	}

	function &get_instance(){
		$instance =& new phpbb_nikita_sp();
		return $instance;
	}

	function check_kcaptcha(){
		global $user;
		if($this->nikita_sp_code == $this->nikita_sp_answer){
			$this->solved = true;
			return false;
		}else{
			return $user->lang['NIKITA_SP_INCORRECT'];
		}
	}

	function validate(){
		if (!parent::validate()){
			return false;
		}else{
			return $this->check_kcaptcha();
		}
	}



	function get_template(){
		global $config, $user, $template;

		if ($this->is_solved()){
			return false;
		}else{
			$explain = $user->lang(($this->type != CONFIRM_POST) ? 'CONFIRM_EXPLAIN' : 'POST_CONFIRM_EXPLAIN', '<a href="mailto:' . htmlspecialchars($config['board_contact']) . '">', '</a>');
			$template->assign_vars(array(
				'NIKITA_SP_IMAGE'		=> $this->nikita_sp_image,
				'NIKITA_SP_CODE'		=> $this->nikita_sp_code,
				'S_TYPE'				=> $this->type,
				'L_CONFIRM_EXPLAIN'		=> $explain,
			));
			return 'captcha_nikita_sp.html';
		}
	}

	/**
	*  Не важное
	*/
	function is_available(){
		global $config, $user;
		$user->add_lang('captcha_nikita_sp');
		return true;
	}

	function get_name(){
		return 'CAPTCHA_NIKITA_SP';
	}

	function get_class_name(){
		return 'phpbb_nikita_sp';
	}

	function get_demo_template($id){
		return $this->get_template();
	}

}

?>
