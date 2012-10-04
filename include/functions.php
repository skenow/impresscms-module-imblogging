<?php
/**
 * Common functions used by the module
 *
 * @copyright	http://smartfactory.ca The SmartFactory
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @version	$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/** compatibility with ImpressCMS prior 1.2 alpha 1 */
if (!function_exists('Icms_getMonthNameById')) {
	/**
	 * Get month name by its ID
	 *
	 * @param int $month_id ID of the month
	 * @return string month name
	 */
	function Icms_getMonthNameById($month_id) {
		global $icmsConfig;
		icms_loadLanguageFile('core', 'calendar');
		$month_id = icms_conv_local2nr($month_id);
		if( $icmsConfig['use_ext_date'] == 1 && defined ('_CALENDAR_TYPE') && _CALENDAR_TYPE == "jalali"){
			switch($month_id) {
				case 1:
					return _CAL_FARVARDIN;
					break;
				case 2:
					return _CAL_ORDIBEHESHT;
					break;
				case 3:
					return _CAL_KHORDAD;
					break;
				case 4:
					return _CAL_TIR;
					break;
				case 5:
					return _CAL_MORDAD;
					break;
				case 6:
					return _CAL_SHAHRIVAR;
					break;
				case 7:
					return _CAL_MEHR;
					break;
				case 8:
					return _CAL_ABAN;
					break;
				case 9:
					return _CAL_AZAR;
					break;
				case 10:
					return _CAL_DEY;
					break;
				case 11:
					return _CAL_BAHMAN;
					break;
				case 12:
					return _CAL_ESFAND;
					break;
			}
		}else{
			switch($month_id) {
				case 1:
					return _CAL_JANUARY;
					break;
				case 2:
					return _CAL_FEBRUARY;
					break;
				case 3:
					return _CAL_MARCH;
					break;
				case 4:
					return _CAL_APRIL;
					break;
				case 5:
					return _CAL_MAY;
					break;
				case 6:
					return _CAL_JUNE;
					break;
				case 7:
					return _CAL_JULY;
					break;
				case 8:
					return _CAL_AUGUST;
					break;
				case 9:
					return _CAL_SEPTEMBER;
					break;
				case 10:
					return _CAL_OCTOBER;
					break;
				case 11:
					return _CAL_NOVEMBER;
					break;
				case 12:
					return _CAL_DECEMBER;
					break;
			}
		}
	}
}
/** compatibility with ImpressCMS prior 1.2 alpha 1 */
if (!function_exists('icms_conv_local2nr')) {
	/**
	 * Get a number value in other languages and transform it to English
	 *
	 * This function is exactly the opposite of icms_conv_nr2local();
	 * Please view the notes there for more information.
	 */
	function icms_conv_local2nr($string) {
		$basecheck = defined('_USE_LOCAL_NUM') && _USE_LOCAL_NUM;
		if ( $basecheck ){
			$string = str_replace(
			array(_LCL_NUM0, _LCL_NUM1, _LCL_NUM2, _LCL_NUM3, _LCL_NUM4, _LCL_NUM5, _LCL_NUM6, _LCL_NUM7, _LCL_NUM8, _LCL_NUM9),
			array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
			$string);
		}
		return $string;
	}
}
/** compatibility with ImpressCMS prior 1.2 alpha 1 */
if (!function_exists('icms_getModuleAdminLink')) {
	/**
	 * Get module admion link
	 *
	 * @param string $moduleName dirname of the moodule
	 * @return string URL of the admin side of the module
	 */
	function icms_getModuleAdminLink($moduleName=false) {
		global $icmsModule;
		if (!$moduleName && (isset ($icmsModule) && is_object($icmsModule))) {
			$moduleName = $icmsModule->getVar('dirname');
		}
		$ret = '';
		if ($moduleName) {
			$ret = "<a href='" . ICMS_URL . "/modules/$moduleName/admin/index.php'>" . _CO_ICMS_ADMIN_PAGE . "</a>";
		}
		return $ret;
	}
}

/**
 * Get module admin link
 *
 * @todo to be move in icms core
 *
 * @param string $moduleName dirname of the moodule
 * @return string URL of the admin side of the module
 */

function imblogging_getModuleAdminLink($moduleName='imblogging') {
	global $icmsModule;
	if (!$moduleName && (isset ($icmsModule) && is_object($icmsModule))) {
		$moduleName = $icmsModule->getVar('dirname');
	}
	$ret = '';
	if ($moduleName) {
		$ret = "<a href='" . ICMS_URL . "/modules/$moduleName/admin/index.php'>" ._MD_IMBLOGGING_ADMIN_PAGE . "</a>";
	}
	return $ret;
}

/**
 * @todo to be move in icms core
 */
function imblogging_getModuleName($withLink = true, $forBreadCrumb = false, $moduleName = false) {
	if (!$moduleName) {
		global $icmsModule;
		$moduleName = $icmsModule->getVar('dirname');
	}
	$icmsModule = icms_getModuleInfo($moduleName);
	$icmsModuleConfig = icms_getModuleConfig($moduleName);
	if (!isset ($icmsModule)) {
		return '';
	}

	if (!$withLink) {
		return $icmsModule->getVar('name');
	} else {
		/*	    $seoMode = smart_getModuleModeSEO($moduleName);
		 if ($seoMode == 'rewrite') {
		 $seoModuleName = smart_getModuleNameForSEO($moduleName);
		 $ret = XOOPS_URL . '/' . $seoModuleName . '/';
		 } elseif ($seoMode == 'pathinfo') {
		 $ret = XOOPS_URL . '/modules/' . $moduleName . '/seo.php/' . $seoModuleName . '/';
		 } else {
			$ret = XOOPS_URL . '/modules/' . $moduleName . '/';
			}
			*/
		$ret = ICMS_URL . '/modules/' . $moduleName . '/';
		return '<a href="' . $ret . '">' . $icmsModule->getVar('name') . '</a>';
	}
}

/**
 * Get URL of previous page
 *
 * @todo to be moved in ImpressCMS 1.2 core
 *
 * @param string $default default page if previous page is not found
 * @return string previous page URL
 */
function imblogging_getPreviousPage($default=false) {
	global $impresscms;
	if (isset($impresscms->urls['previouspage'])) {
		return $impresscms->urls['previouspage'];
	} elseif($default) {
		return $default;
	} else {
		return ICMS_URL;
	}
}

/**
 * Get month name by its ID
 *
 * @todo to be moved in ImpressCMS 1.2 core
 *
 * @param int $month_id ID of the month
 * @return string month name
 */
function imblogging_getMonthNameById($month_id) {
	return Icms_getMonthNameById($month_id);
}
