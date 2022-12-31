<?php
/**
 * Generating an RSS feed
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @package imblogging
 * @version $Id$
 */
/**
 * Include the module's header for all pages
 */
include_once 'header.php';
include_once ICMS_ROOT_PATH . '/header.php';

$clean_post_uid = isset($_GET['uid']) ? (int) $_GET['uid'] : FALSE;
$clean_cat_id = isset($_GET['cid']) ? (int) $_GET['cid'] : FALSE;

$imblogging_feed = new icms_feeds_Rss();

$imblogging_feed->title = $icmsConfig['sitename'] . ' - ' . icms::$module->getVar("name");
$imblogging_feed->url = ICMS_URL;
$imblogging_feed->description = htmlspecialchars($icmsConfig['slogan'], ENT_QUOTES);
$imblogging_feed->language = _LANGCODE;
$imblogging_feed->charset = _CHARSET;
$imblogging_feed->category = icms::$module->getVar("name");

$imblogging_post_handler = icms_getModuleHandler('post', $moddir, 'imblogging');
$postsArray = $imblogging_post_handler->getPosts(0, 10, $clean_post_uid, $clean_cat_id);

foreach ($postsArray as $postArray) {
	$imblogging_feed->feeds[] = array(
		'title' => $postArray['post_title'],
		'link' => str_replace('&', '&amp;', $postArray['itemUrl']),
		'description' => htmlspecialchars(str_replace('&', '&amp;', $postArray['post_lead']), ENT_QUOTES),
		'pubdate' => date('r', $postArray['post_published_date_int']),
		'guid' => str_replace('&', '&amp;', $postArray['itemUrl']),
		'author' => '',
		'category' => '');
}

$imblogging_feed->render();
