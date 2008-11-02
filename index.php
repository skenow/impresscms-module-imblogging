<?php
/**
* Index page
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @package imblogging
* @version		$Id$
*/
/** Include the module's header for all pages */
include_once 'header.php';

$xoopsOption['template_main'] = 'imblogging_index.html';
/** Include the ICMS header file */
include_once ICMS_ROOT_PATH . '/header.php';

// At which record shall we start display
$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$clean_post_uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
$clean_year = isset($_GET['y']) ? intval($_GET['y']) : false;
$clean_month = isset($_GET['m']) ? intval($_GET['m']) : false;
$clean_cid = isset($_GET['cid']) ? intval($_GET['cid']) : false;

$imblogging_post_handler = xoops_getModuleHandler('post');

$xoopsTpl->assign('imblogging_posts', $imblogging_post_handler->getPosts($clean_start, $xoopsModuleConfig['posts_limit'], $clean_post_uid, $clean_cid, $clean_year, $clean_month));
/**
 * Create Navbar
 */
include_once ICMS_ROOT_PATH . '/class/pagenav.php';
$posts_count = $imblogging_post_handler->getPostsCount($clean_post_uid, $clean_cid, $clean_year, $clean_month);
$extr_argArray = array();
$category_pathArray = array();

if ($clean_post_uid) {
	$extr_argArray[] = 'uid=' . $clean_post_uid;
	$category_pathArray[] = sprintf(_CO_IMBLOGGING_POST_FROM_USER, icms_getLinkedUnameFromId($clean_post_uid));
}
if ($clean_cid) {
	$imtagging_category_handler = xoops_getModuleHandler('category', 'imtagging');
	$category_name = $imtagging_category_handler->getCategoryName($clean_cid);
	$category_pathArray[] = $category_name;
	$extr_argArray[] = 'cid=' . $clean_cid;
}
if ($clean_year && $clean_month) {
	$category_pathArray[] = sprintf(_CO_IMBLOGGING_POST_FROM_MONTH, imblogging_getMonthNameById($clean_month), $clean_year);
}

$extr_arg = count($extr_argArray) > 0 ? implode('&amp;', $extr_argArray) : '';

$pagenav = new XoopsPageNav($posts_count, $xoopsModuleConfig['posts_limit'], $clean_start, 'start', $extr_arg);
$xoopsTpl->assign('navbar', $pagenav->renderNav());

$xoopsTpl->assign('imblogging_module_home', imblogging_getModuleName(true, true));

$category_path = count($category_pathArray) > 0 ? implode(' > ', $category_pathArray) : false;
$xoopsTpl->assign('imblogging_category_path', $category_path);

$xoopsTpl->assign('imblogging_showSubmitLink', true);

/** Include the module's footer */
include_once 'footer.php';
?>