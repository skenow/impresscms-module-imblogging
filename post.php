<?php
/**
* Post page
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

function edititem($item_itemid = 0)
{
	global $ilog_item_handler, $ilog_list_handler, $xoopsTpl;

	$itemObj = $ilog_item_handler->get($item_itemid);

	if (!$itemObj->isNew()){
		$sform = $itemObj->getForm(_MD_ILOG_ITEM_EDIT, 'additem');
		$sform->assign($xoopsTpl, 'ilog_item');
		$xoopsTpl->assign('categoryPath', _MD_ILOG_ITEM_EDIT);
	} else {
		$item_listid = isset($_GET['item_listid']) ? intval($_GET['item_listid']) : 0;
		$listObj = $ilog_list_handler->get($item_listid);

		$itemObj->setVar('item_listid', $item_listid);
		$itemObj->hideFieldFromForm(array('item_date', 'item_uid'));

		$sform = $itemObj->getForm(_MD_ILOG_ITEM_CREATE, 'additem');
		$sform->assign($xoopsTpl, 'ilog_item');
		$xoopsTpl->assign('categoryPath', _MD_ILOG_ITEM_CREATE);
	}
}

include_once('header.php');

$xoopsOption['template_main'] = 'ilog_item.html';
include_once(XOOPS_ROOT_PATH . "/header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$ilog_item_handler = xoops_getModuleHandler('item');
$ilog_log_handler = xoops_getModuleHandler('log');
$ilog_list_handler = xoops_getModuleHandler('list');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$item_itemid = isset($_GET['item_itemid']) ? intval($_GET['item_itemid']) : 0 ;

if (!$op && $item_itemid > 0) {
	$op = 'view';
}

switch ($op) {
	case "mod":
	case "changedField":

		ilog_checkPermission('item_add', 'list.php', _CO_ILOG_ITEM_ADD_NOPERM);
		edititem($item_itemid);
		$xoopsTpl->assign('module_home', smart_getModuleName(true, true));
		break;

	case "additem":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($ilog_item_handler);
		$controller->storeFromDefaultForm(_MD_ILOG_ITEM_CREATED, _MD_ILOG_ITEM_MODIFIED);

		break;

	case "del":
		ilog_checkPermission('item_delete', 'list.php', _CO_ILOG_ITEM_DELETE_NOPERM);
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($ilog_item_handler);
		$controller->handleObjectDeletionFromUserSide();
		$xoopsTpl->assign('module_home', smart_getModuleName(true, true));
		$xoopsTpl->assign('categoryPath', _MD_ILOG_ITEM_DELETE);
		break;

	case "view" :
		$itemObj = $ilog_item_handler->get($item_itemid);

		$view_actions_col = array();
		if (ilog_checkPermission('item_add')) {
			$view_actions_col[] = 'edit';
		}
		if (ilog_checkPermission('item_delete')) {
			$view_actions_col[] = 'delete';
		}
		$xoopsTpl->assign('ilog_item_view', $itemObj->displaySingleObject(true, true, $view_actions_col, false));

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('log_itemid', $item_itemid));

		$table_actions_col = array();
		if (ilog_checkPermission('log_add')) {
			$table_actions_col[] = 'edit';
		}
		if (ilog_checkPermission('log_delete')) {
			$table_actions_col[] = 'delete';
		}

		$objectTable = new SmartObjectTable($ilog_log_handler, $criteria, $table_actions_col);
		$objectTable->isForUserSide();

		$objectTable->addColumn(new SmartObjectColumn('log_date', 'left', 150));
		$objectTable->addColumn(new SmartObjectColumn('log_message'));
		$objectTable->addColumn(new SmartObjectColumn('log_uid', 'left', 150));

		if (ilog_checkPermission('log_add')) {
			$objectTable->addIntroButton('addlog', 'log.php?op=mod&log_itemid=' . $item_itemid, _MD_ILOG_LOG_CREATE);
		}

		$xoopsTpl->assign('ilog_item_logs', $objectTable->fetch());

		$xoopsTpl->assign('module_home', smart_getModuleName(true, true));

		$xoopsTpl->assign('categoryPath', $itemObj->getVar('item_listid') . ' > ' . $itemObj->getVar('item_title'));

		break;

	default:
		$table_actions_col = array();

		$objectTable = new SmartObjectTable($ilog_item_handler, false, $table_actions_col);
		$objectTable->isForUserSide();

		$objectTable->addColumn(new SmartObjectColumn('item_name', 'left'));
		$objectTable->addColumn(new SmartObjectColumn('item_city', 'left', 150));
		$objectTable->addColumn(new SmartObjectColumn('item_phone', 'center', 150));

		$xoopsTpl->assign('ilog_items', $objectTable->fetch());
		$xoopsTpl->assign('module_home', smart_getModuleName(false, true));

		break;
}

include_once("footer.php");
?>