<?php
/**
 * File containing onUpdate and onInstall functions for the module
 *
 * This file is included by the core in order to trigger onInstall or onUpdate functions when needed.
 * Of course, onUpdate function will be triggered when the module is updated, and onInstall when
 * the module is originally installed. The name of this file needs to be defined in the
 * icms_version.php
 *
 * <code>
 * $modversion['onInstall'] = "include/onupdate.inc.php";
 * $modversion['onUpdate'] = "include/onupdate.inc.php";
 * </code>
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 *        
 */
if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// this needs to be the latest db version - and match the dirname
define(strtoupper(basename(dirname(__DIR__))) . '_DB_VERSION', 1);

/*
 * it is possible to define custom functions which will be call when the module is updating at the
 * correct time in update incrementation. Simply define a function named <dirname_db_upgrade_db_version>
 */
/*
 * function imblogging_db_upgrade_1() {
 * }
 * function imblogging_db_upgrade_2() {
 * }
 */

/**
 * This can be based on the module name (not the module directory)
 *
 * @param object $module
 */
function icms_module_update_imblogging($module) {
	/* need to add permissions to all the old posts now that we can add permissions */
	/* First, figure out if this needs to be done */
	$mid = $module->getVar('mid');

	/* use an array, in case there are multiple permissions to add, or more to add later */
	$permissions = array ('post_view');
	$gperm_handler = icms::handler('icms_member_groupperm');

	// Getting the groups that have read permission to this module
	$groups = $gperm_handler->getGroupIds('module_read', $mid);

	/*
	 * We can also set a default in icms_version.php
	 * See icms_ipf_form_Base->createPermissionControls for the methodology
	 * The module defaults only apply to new posts. Individual posts can have separate permissions.
	 */

	// Getting the config item for default view permission
	$config_handler = icms::handler('icms_config');
	$def_perm_config = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $mid));
	$def_perm_config->add(new icms_db_criteria_Item('conf_name', 'def_perm_post_view'));
	$config = $config_handler->getConfigs($def_perm_config);

	// This will update the default permissions every time the module is updated. Default permissions will match groups able to view the module
	$config[0]->setVar('conf_value', array_values($groups));
	$config_handler->insertConfig($config[0]);

	// Applying permissions to posts
	foreach ($permissions as $permname) {
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('gperm_modid', $mid));
		$criteria->add(new icms_db_criteria_Item('gperm_name', $permname));
 
		if (!$gperm_handler->getCount($criteria)) {
			$post_handler = icms_getModuleHandler('post', $module->getVar('dirname'), 'imblogging');
			$posts = $post_handler->getPosts();

			foreach ($groups as $group) {
				foreach ($posts as $post) {
					$gperm_handler->addRight($permname, $post['post_id'], $group, $mid);
				}
			}
		}
	}

	/*
	 * Using the IcmsDatabaseUpdater to automatically manage the database upgrade dynamically
	 * according to the class defined in the module
	 */
	$icmsDatabaseUpdater = icms_db_legacy_Factory::getDatabaseUpdater();
	$icmsDatabaseUpdater->moduleUpgrade($module);
	return true;
}

/**
 * This can be based on the module name (not the module directory)
 *
 * @param object $module
 */
function icms_module_install_imblogging($module) {
	return true;
}
