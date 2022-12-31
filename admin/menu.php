<?php
/**
 * Configuring the amdin side menu for the module
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @version $Id$
 */
$adminmenu[] = array(
	'title' => _MI_IMBLOGGING_POSTS,
	'link' => "admin/post.php");

if (isset(icms::$module)) {
	$moddir = basename(dirname(dirname(__FILE__)));

	$headermenu[] = array(
		'title' => _PREFERENCES,
		'link' => '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . icms::$module->getVar('mid'));
	$headermenu[] = array(
		'title' => _CO_ICMS_GOTOMODULE,
		'link' => ICMS_MODULES_URL . '/' . $moddir);
	$headermenu[] = array(
		'title' => _CO_ICMS_UPDATE_MODULE,
		'link' => ICMS_MODULES_URL . '/system/admin.php?fct=modulesadmin&op=update&module=' . icms::$module->getVar('dirname'));
	$headermenu[] = array(
		'title' => _MODABOUT_ABOUT,
		'link' => ICMS_MODULES_URL . '/' . $moddir . '/admin/about.php');
}
