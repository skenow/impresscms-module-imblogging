<?php
/**
 * Index page
 *
 * @copyright	http://smartfactory.ca The SmartFactory
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @package	imblogging
 * @version	$Id$
 */
/** Include the module's header for all pages */
include_once 'header.php';

$xoopsOption['template_main'] = 'imblogging_index.html';
/** Include the ICMS header file */
include_once ICMS_ROOT_PATH . '/header.php';

// At which record shall we start display
$clean_start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
$clean_post_uid = isset($_GET['uid']) ? (int) $_GET['uid'] : FALSE;
$clean_year = isset($_GET['y']) ? (int) $_GET['y'] : FALSE;
$clean_month = isset($_GET['m']) ? (int) $_GET['m'] : FALSE;
$clean_cid = isset($_GET['cid']) ? (int) $_GET['cid'] : FALSE;
$Basic_Check = defined ('_CALENDAR_TYPE') && _CALENDAR_TYPE == "jalali" && $icmsConfig['use_ext_date'] == 1;
if(!empty($_GET['y']) && !empty($_GET['m']) && $Basic_Check) {
	$jyear = $clean_year;
	$jmonth = $clean_month;
	list($gyear, $gmonth, $gday) = jalali_to_gregorian( $jyear, $jmonth, '1');
	$clean_year =  $gyear;
	$clean_month = $gmonth;

}

$imblogging_post_handler = icms_getModuleHandler('post');

$icmsTpl->assign('imblogging_posts', $imblogging_post_handler->getPosts($clean_start, $icmsModuleConfig['posts_limit'], $clean_post_uid, $clean_cid, $clean_year, $clean_month));
/**
 * Create Navbar
 */
include_once ICMS_ROOT_PATH . '/class/pagenav.php';
$posts_count = $imblogging_post_handler->getPostsCount($clean_post_uid, $clean_cid, $clean_year, $clean_month);
$extr_argArray = array();
$category_pathArray = array();

if ($clean_post_uid) {
	$imblogging_poster_link = icms_getLinkedUnameFromId($clean_post_uid);
	$icmsTpl->assign('imblogging_rss_url', IMBLOGGING_URL . 'rss.php?uid=' . $clean_post_uid);
	$icmsTpl->assign('imblogging_rss_info', _MD_IMBLOGGING_RSS_POSTER);
	$extr_arg = 'uid=' . $clean_post_uid;
} else {
	$icmsTpl->assign('imblogging_rss_url', IMBLOGGING_URL . 'rss.php');
	$icmsTpl->assign('imblogging_rss_info', _MD_IMBLOGGING_RSS_GLOBAL);
	$extr_arg = '';
}
if ($clean_post_uid) {
	$extr_argArray[] = 'uid=' . $clean_post_uid;
	$category_pathArray[] = sprintf(_CO_IMBLOGGING_POST_FROM_USER, icms_getLinkedUnameFromId($clean_post_uid));
}
if ($clean_cid) {
	$imtagging_category_handler = icms_getModuleHandler('category', 'imtagging');
	$category_name = $imtagging_category_handler->getCategoryName($clean_cid);
	$category_pathArray[] = $category_name;
	$extr_argArray[] = 'cid=' . $clean_cid;
}
$config_handler =& xoops_gethandler('config');
$icmsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);
if ($clean_year && $clean_month) {
	if($Basic_Check) {
		$gyear = $clean_year;
		$gmonth = $clean_month;
		$gday = 1;
		list($jyear, $jmonth, $jday) = gregorian_to_jalali( $gyear, $gmonth, $gday);
		$clean_year =  icms_conv_nr2local($jyear);
		$clean_month = $jmonth;

	}
	$category_pathArray[] = sprintf(_CO_IMBLOGGING_POST_FROM_MONTH, Icms_getMonthNameById($clean_month), $clean_year);
}

$extr_arg = count($extr_argArray) > 0 ? implode('&amp;', $extr_argArray) : '';

$pagenav = new XoopsPageNav($posts_count, $icmsModuleConfig['posts_limit'], $clean_start, 'start', $extr_arg);
$icmsTpl->assign('navbar', $pagenav->renderNav());

$icmsTpl->assign('imblogging_module_home', imblogging_getModuleName(TRUE, TRUE));

$category_path = count($category_pathArray) > 0 ? implode(' > ', $category_pathArray) : FALSE;
$icmsTpl->assign('imblogging_category_path', $category_path);

$icmsTpl->assign('imblogging_showSubmitLink', TRUE);

/** Include the module's footer */
include_once 'footer.php';
