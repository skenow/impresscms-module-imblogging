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

if (isset($_GET['op'])) $op = $_GET['op'];

$xoopsTpl->assign('imblogging_posts', $imblogging_post_handler->getPosts());
$xoopsTpl->assign('imblogging_module_home', imblogging_getModuleName(true, true));

include_once("footer.php");
?>