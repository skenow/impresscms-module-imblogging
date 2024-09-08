<?php
/**
 * Index page
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @package imblogging
 *
 */
/**
 * Include the module's header for all pages
 */
include_once 'header.php';

$xoopsOption['template_main'] = 'imblogging_index.html';
/**
 * Include the ICMS header file
 */
include_once ICMS_ROOT_PATH . '/header.php';

// At which record shall we start display
$clean_start = isset($_GET['start']) ? (int) $_GET['start'] : 0;
$clean_post_uid = isset($_GET['uid']) ? (int) $_GET['uid'] : false;
$clean_year = isset($_GET['y']) ? (int) $_GET['y'] : false;
$clean_month = isset($_GET['m']) ? (int) $_GET['m'] : false;
$clean_cid = isset($_GET['cid']) ? (int) $_GET['cid'] : false;
$Basic_Check = defined('_CALENDAR_TYPE') && _CALENDAR_TYPE == "jalali" && $icmsConfig['use_ext_date'] == 1;
if (!empty($_GET['y']) && !empty($_GET['m']) && $Basic_Check) {
	$jyear = $clean_year;
	$jmonth = $clean_month;
	list($gyear, $gmonth, $gday) = jalali_to_gregorian($jyear, $jmonth, '1');
	$clean_year = $gyear;
	$clean_month = $gmonth;
}

$imblogging_post_handler = icms_getModuleHandler('post', $moddir, 'imblogging');

$icmsTpl->assign('imblogging_posts', $imblogging_post_handler->getPosts($clean_start, icms::$module->config['posts_limit'], $clean_post_uid, $clean_cid, $clean_year, $clean_month));
/**
 * Create Navbar
 */
$posts_count = $imblogging_post_handler->getPostsCount($clean_post_uid, $clean_cid, $clean_year, $clean_month);

// Initialize some variables
$category_meta_description = $category_meta_keywords = '';
$extr_argArray = array();
$category_pathArray = array();
$title_combined = array();

if ($clean_post_uid) {
	$imb_user_handler = new icms_member_user_Handler(icms::$xoopsDB);
	$author = $imb_user_handler->get($clean_post_uid);
	
	$imblogging_poster_link = icms_member_user_Handler::getUserLink($clean_post_uid);
	$extr_arg = 'uid=' . $clean_post_uid;
	$rss_url .= '?' . $extr_arg;
	$rss_info = _MD_IMBLOGGING_RSS_POSTER;

	$extr_argArray[] = $extr_arg;
	// removed code duplication here - icms_member_user_Handler::getUserLink($clean_post_uid) was called a 2nd time
	$category_pathArray[] = sprintf(_CO_IMBLOGGING_POST_FROM_USER, $imblogging_poster_link);
	
	// get information to use for meta properties when filtered by author
	$author_name = $author->getVar('uname');
	$title_combined['author'] = sprintf(_CO_IMBLOGGING_POST_FROM_USER, $author_name);
	// what can be used for meta description when showing posts by an author? Their bio (extra info) from their profile?
	$author_bio = $author->getVar('bio');
	
} else {
	$rss_info = _MD_IMBLOGGING_RSS_GLOBAL;
	$extr_arg = '';
}

$icmsTpl->assign('imblogging_rss_url', $rss_url);
$icmsTpl->assign('imblogging_rss_info', $rss_info);

if ($clean_cid) {
	$imtagging_category_handler = icms_getModuleHandler('category', $moddir, 'imtagging');
	$category_name = $imtagging_category_handler->getCategoryName($clean_cid);
	$category_pathArray[] = $category_name;
	$extr_argArray[] = 'cid=' . $clean_cid;
	
	// category meta information
	$categoryObj = $imtagging_category_handler->get($clean_cid);
	$category_meta_description = $categoryObj->getVar('meta_description');
	$category_meta_keywords = $categoryObj->getVar('meta_keywords');
	$title_combined['category'] = $category_name;
}
if ($clean_year && $clean_month) {
	if ($Basic_Check) {
		$gyear = $clean_year;
		$gmonth = $clean_month;
		$gday = 1;
		list($jyear, $jmonth, $jday) = gregorian_to_jalali($gyear, $gmonth, $gday);
		$clean_year = icms_conv_nr2local($jyear);
		$clean_month = $jmonth;
	}
	$category_pathArray[] = sprintf(_CO_IMBLOGGING_POST_FROM_MONTH, icms_getMonthNameById($clean_month), $clean_year);
	$title_combined['date'] = sprintf(_CO_IMBLOGGING_POST_FROM_MONTH, icms_getMonthNameById($clean_month), $clean_year);
}

$extr_arg = count($extr_argArray) > 0 ? implode('&amp;', $extr_argArray) : '';

$pagenav = new icms_view_PageNav($posts_count, icms::$module->config['posts_limit'], $clean_start, 'start', $extr_arg);
$icmsTpl->assign('navbar', $pagenav->renderNav());

$icmsTpl->assign('imblogging_module_home', icms_getModuleName(true, true));

$category_path = count($category_pathArray) > 0 ? implode(' > ', $category_pathArray) : false;
$icmsTpl->assign('imblogging_category_path', $category_path);

$icmsTpl->assign('imblogging_showSubmitLink', true);

/**
 *  Generating meta information for this page
 *  This page will generate several 'pages'
 *  As the number of posts increases, there will be multiple pages
 *  The page can also be filtered by category (imTagging) - which has its own meta properties
 *  And, you can get a list of posts by an author
 *  We need to account for all these to provide unique page titles for them all (at a minimum)
 */

// category and author do contribute to the page title without any further logic
$page_title = icms_getModuleName(false);

// some combination of the module, category, author, and page #
// if empty - icms_ipf_Metagen->createMetaKeywords() uses the title and description
if (!empty($category_meta_keywords)) {
	$page_keywords = $category_meta_keywords;
} else {
	$page_keywords = icms::$module->config['module_meta_keywords'];
}

// some combination of the module, category, author, and page #
// by default, if nothing is provided for this, icms_ipf_Metagen->setDescription() will use the module's meta description
if (!empty($category_meta_description)) {
	$page_description = $category_meta_description;
} elseif (!empty($author_bio)) {
	$page_description = $author_bio;
} else {
	$page_description = icms::$module->config['module_meta_description'];
}

// icms_ipf_Metagen takes 4 arguments - the last is 'categoryPath'
$page_category_path = $category_path;

// Generating meta information for this page
$icms_metagen = new icms_ipf_Metagen($page_title, $page_keywords, $page_description, $page_category_path);
$icms_metagen->createMetaTags();

/**
 * Include the module's footer
 */
include_once 'footer.php';
