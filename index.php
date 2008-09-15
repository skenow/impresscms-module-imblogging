<?php
/**
* Index page
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

include_once('header.php');

$xoopsOption['template_main'] = 'imblogging_index.html';
include_once(ICMS_ROOT_PATH . "/header.php");

$imblogging_post_handler = xoops_getModuleHandler('post');

$post_uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;

$xoopsTpl->assign('imblogging_posts', $imblogging_post_handler->getPosts($post_uid));
$xoopsTpl->assign('imblogging_module_home', imblogging_getModuleName(true, true));

if ($post_uid) {
	$xoopsTpl->assign('imblogging_category_path', sprintf(_CO_IMBLOGGING_POST_FROM_USER, icms_getLinkedUnameFromId($post_uid)));
}

include_once("footer.php");
?>