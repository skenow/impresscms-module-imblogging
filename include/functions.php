<?php
/**
* Common functions used by the module
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**
 * Get module admion link
 *
 * @todo to be move in icms core
 *
 * @param string $moduleName dirname of the moodule
 * @return string URL of the admin side of the module
 */

function imblogging_getModuleAdminLink($moduleName='imblogging') {
	global $xoopsModule;
	if (!$moduleName && (isset ($xoopsModule) && is_object($xoopsModule))) {
		$moduleName = $xoopsModule->getVar('dirname');
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
		global $xoopsModule;
		$moduleName = $xoopsModule->getVar('dirname');
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
	icms_loadLanguageFile('core', 'calendar');
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

/**
* Return a linked username or full name for a specific $userid
*
* @todo this function is fixing a ucwords bug in icms_getLinkedUnameFromId so we will update this in icms 1.2
*
* @param integer $userid uid of the related user
* @param bool $name true to return the fullname, false to use the username; if true and the user does not have fullname, username will be used instead
* @param array $users array already containing XoopsUser objects in which case we will save a query
* @param bool $withContact true if we want contact details to be added in the value returned (PM and email links)
* @return string name of user with a link on his profile
*/
function imblogging_getLinkedUnameFromId($userid, $name = false, $users = array (), $withContact = false)
{
	if(!is_numeric($userid)) {return $userid;}
	$userid = intval($userid);
	if($userid > 0)
	{
		if($users == array())
		{
			//fetching users
			$member_handler = & xoops_gethandler('member');
			$user = & $member_handler->getUser($userid);
		}
		else
		{
			if(!isset($users[$userid])) {return $GLOBALS['xoopsConfig']['anonymous'];}
			$user = & $users[$userid];
		}
		if(is_object($user))
		{
			$ts = & MyTextSanitizer::getInstance();
			$username = $user->getVar('uname');
			$fullname = '';
			$fullname2 = $user->getVar('name');
			if(($name) && !empty($fullname2)) {$fullname = $user->getVar('name');}
			if(!empty ($fullname)) {$linkeduser = "$fullname [<a href='".ICMS_URL."/userinfo.php?uid=".$userid."'>".$ts->htmlSpecialChars($username)."</a>]";}
			else {$linkeduser = "<a href='".ICMS_URL."/userinfo.php?uid=".$userid."'>".$ts->htmlSpecialChars($username)."</a>";}
			// add contact info : email + PM
			if($withContact)
			{
				$linkeduser .= '<a href="mailto:'.$user->getVar('email').'"><img style="vertical-align: middle;" src="'.ICMS_URL.'/images/icons/email.gif'.'" alt="'._US_SEND_MAIL.'" title="'._US_SEND_MAIL.'"/></a>';
				$js = "javascript:openWithSelfMain('".ICMS_URL.'/pmlite.php?send2=1&to_userid='.$userid."', 'pmlite',450,370);";
				$linkeduser .= '<a href="'.$js.'"><img style="vertical-align: middle;" src="'.ICMS_URL.'/images/icons/pm.gif'.'" alt="'._US_SEND_PM.'" title="'._US_SEND_PM.'"/></a>';
			}
			return $linkeduser;
		}
	}
	return $GLOBALS['xoopsConfig']['anonymous'];
}
?>