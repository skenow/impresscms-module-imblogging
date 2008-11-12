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
if($xoopsConfig['language'] == "persian" && defined('_EXT_DATE_FUNC') && $xoopsConfig['use_ext_date'] == 1 && _EXT_DATE_FUNC && file_exists(ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/ext/ext_date_function.php'))
{
		include_once ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/ext/ext_date_function.php';
		$gyear = $clean_year;
		$gmonth = $clean_month;
		$gday = 30;
		list($jyear, $jmonth, $jday) = jalali_to_gregorian( $gyear, $gmonth, $gday );
		$clean_year =  $jyear;
		$clean_month = $jmonth;

}

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
	$imblogging_poster_link = icms_getLinkedUnameFromId($clean_post_uid);
	$xoopsTpl->assign('imblogging_rss_url', IMBLOGGING_URL . 'rss.php?uid=' . $clean_post_uid);
	$xoopsTpl->assign('imblogging_rss_info', _MD_IMBLOGGING_RSS_POSTER);
	$extr_arg = 'uid=' . $clean_post_uid;
} else {
	$xoopsTpl->assign('imblogging_rss_url', IMBLOGGING_URL . 'rss.php');
	$xoopsTpl->assign('imblogging_rss_info', _MD_IMBLOGGING_RSS_GLOBAL);
	$extr_arg = '';
}
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
	$config_handler =& xoops_gethandler('config');
	$xoopsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);
if ($clean_year && $clean_month) {
if($xoopsConfig['language'] == "persian" && defined('_EXT_DATE_FUNC') && $xoopsConfig['use_ext_date'] == 1 && _EXT_DATE_FUNC && file_exists(ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/ext/ext_date_function.php'))
{
		include_once ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/ext/ext_date_function.php';
		$gyear = $clean_year;
		$gmonth = $clean_month;
		$gday = 1;
		list($jyear, $jmonth, $jday) = gregorian_to_jalali( $gyear, $gmonth, $gday );
		$clean_year =  Convertnumber2farsi($jyear);
		$clean_month = $jmonth;

}
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
