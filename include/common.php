<?php
/**
 * Common file of the module included on all pages of the module
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @version $Id$
 */
if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

if (!defined("IMBLOGGING_DIRNAME")) define("IMBLOGGING_DIRNAME", $modversion['dirname'] = basename(dirname(dirname(__FILE__))));
if (!defined("IMBLOGGING_URL")) define("IMBLOGGING_URL", ICMS_MODULES_URL . '/' . IMBLOGGING_DIRNAME . '/');
if (!defined("IMBLOGGING_ROOT_PATH")) define("IMBLOGGING_ROOT_PATH", ICMS_MODULES_PATH . '/' . IMBLOGGING_DIRNAME . '/');
if (!defined("IMBLOGGING_IMAGES_URL")) define("IMBLOGGING_IMAGES_URL", IMBLOGGING_URL . 'images/');
if (!defined("IMBLOGGING_ADMIN_URL")) define("IMBLOGGING_ADMIN_URL", IMBLOGGING_URL . 'admin/');

// Include the common language file of the module
icms_loadLanguageFile(IMBLOGGING_DIRNAME, 'common');

include_once IMBLOGGING_ROOT_PATH . "include/functions.php";

// Creating the module object to make it available throughout the module
$imbloggingModule = icms_getModuleInfo(IMBLOGGING_DIRNAME);
if (is_object($imbloggingModule)) {
	$imblogging_moduleName = $imbloggingModule->getVar('name');
}

// Find if the user is admin of the module and make this info available throughout the module
$imblogging_isAdmin = icms_userIsAdmin(IMBLOGGING_DIRNAME);

// Creating the module config array to make it available throughout the module
$imbloggingConfig = icms_getModuleConfig(IMBLOGGING_DIRNAME);

// including the post class
include_once IMBLOGGING_ROOT_PATH . 'class/post.php';

// creating the icmsPersistableRegistry to make it available throughout the module
$icmsPersistableRegistry = icms_ipf_registry_Handler::getInstance();
