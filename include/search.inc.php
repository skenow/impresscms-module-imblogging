<?php
/**
 * imBlogging search function
 *
 * This file holds the configuration information of this module
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @version $Id$
 */
if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function imblogging_search($queryarray, $andor, $limit, $offset, $userid) {
	$moddir = basename(dirname(dirname(__FILE__)));
	$imblogging_post_handler = icms_getModuleHandler('post', $moddir, 'imblogging');
	$postsArray = $imblogging_post_handler->getPostsForSearch($queryarray, $andor, $limit, $offset, $userid);

	$ret = array();

	foreach ($postsArray as $postArray) {
		$item['image'] = "images/post.png";
		$item['link'] = str_replace(IMBLOGGING_URL, '', $postArray['itemUrl']);
		$item['title'] = $postArray['post_title'];
		$item['time'] = strtotime($postArray['post_published_date']);
		$item['uid'] = $postArray['post_posterid'];
		$ret[] = $item;
		unset($item);
	}
	return $ret;
}
