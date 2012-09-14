<?php
/**
* @package acp
*/
class acp_wysiwyg_info
{
    function module()
    {
        return array(
            'filename'    => 'acp_wysiwyg',
            'title'        => 'ACP_WYSIWYG',
            'version'    => '0.0.9',
            'modes'        => array(
            'default'        => array('title' => 'ACP_WYSIWYG', 'auth' => 'acl_a_user', 'cat' => array('ACP_WYSIWYG_CAT')),
            ),
        );
    }

    function install()
    {
    }

    function uninstall()
    {
    }
}  



?>
