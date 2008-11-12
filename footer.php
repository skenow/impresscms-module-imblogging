<?php
/**
* Footer page included at the end of each page on user side of the mdoule
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @package imblogging
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$xoopsTpl->assign("imblogging_adminpage", imblogging_getModuleAdminLink());
$xoopsTpl->assign("imblogging_is_admin", $imblogging_isAdmin);
$xoopsTpl->assign('imblogging_url', IMBLOGGING_URL);
$xoopsTpl->assign('imblogging_images_url', IMBLOGGING_IMAGES_URL);
$xoopsTpl->assign('imblogging_userCanSubmit', $imblogging_post_handler->userCanSubmit());

$xoTheme->addStylesheet(IMBLOGGING_URL . 'module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');

$xoopsTpl->assign("ref_smartfactory", "imBlogging is developed by The SmartFactory (http://smartfactory.ca), a division of INBOX International inc. (http://inboxinternational.com)");

include_once(ICMS_ROOT_PATH . '/footer.php');

?>