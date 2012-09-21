<?php
/**
* New comment form
*
* This file holds the configuration information of this module
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

include_once 'header.php';
$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
	$imblogging_post_handler = icms_getModuleHandler('post');
	$postObj = $imblogging_post_handler->get($com_itemid);
	if ($postObj && !$postObj->isNew()) {
		//$com_replytext = _POSTEDBY.'&nbsp;<b>'.smartsection_getLinkedUnameFromId($itemObj->uid()) . '</b>&nbsp;'._DATE.'&nbsp;<b>'.$itemObj->dateSub().'</b><br /><br />'.$itemObj->summary();
		$com_replytext = 'test...';
		$bodytext = $postObj->getPostLead();
		if ($bodytext != '') {
			$com_replytext .= '<br /><br />'.$bodytext.'';
		}
		$com_replytitle = $postObj->getVar('post_title');
		include_once ICMS_ROOT_PATH .'/include/comment_new.php';
	}
}

?>