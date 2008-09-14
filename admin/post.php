<?php
/**
* Admin page to manage posts
*
* List, add, edit and delete post objects
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/
function editpost($showmenu = false, $post_id = 0, $parentid =0)
{
	global $imblogging_post_handler, $xoopsModule;

	$postObj = $imblogging_post_handler->get($post_id);

	$post_listid = isset($_GET['post_listid']) ? intval($_GET['post_listid']) : 0;
	$postObj->setVar('post_listid', $post_listid);

	if (!$postObj->isNew()){

		if ($showmenu) {
			$xoopsModule->displayAdminMenu(0, _AM_IMBLOGGING_POSTS . " > " . _CO_ICMS_EDITING);
		}
		$sform = $postObj->getForm(_AM_IMBLOGGING_POST_EDIT, 'addpost');
		$sform->display();
	} else {
		if ($showmenu) {
			$xoopsModule->displayAdminMenu(0, _AM_IMBLOGGING_POSTS . " > " . _CO_ICMS_CREATINGNEW);
		}
		$sform = $postObj->getForm(_AM_IMBLOGGING_POST_CREATE, 'addpost');
		$sform->display();
	}
}

include_once("admin_header.php");

$imblogging_post_handler = xoops_getModuleHandler('post');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		xoops_cp_header();

		editpost(true, $post_id);
		break;
	case "addpost":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($imblogging_post_handler);
		$controller->storeFromDefaultForm(_AM_IMBLOGGING_POST_CREATED, _AM_IMBLOGGING_POST_MODIFIED);

		break;

	case "del":
	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($imblogging_post_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :
		$postObj = $imblogging_post_handler->get($post_id);

		smart_xoops_cp_header();
		smart_adminMenu(1, _AM_IMBLOGGING_POST_VIEW . ' > ' . $postObj->getVar('post_title'));

		smart_collapsableBar('postview', $postObj->getVar('post_title') . $postObj->getEditLogLink(), _AM_IMBLOGGING_POST_VIEW_DSC);

		$postObj->displaySingleObject();

		smart_close_collapsable('postview');

		smart_collapsableBar('postview_posts', _AM_IMBLOGGING_POSTS, _AM_IMBLOGGING_POSTS_IN_POST_DSC);

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('post_id', $post_id));

		$objectTable = new SmartObjectTable($imblogging_post_handler, $criteria);
		$objectTable->addColumn(new SmartObjectColumn('post_date', 'left', 150));
		$objectTable->addColumn(new SmartObjectColumn('post_message'));
		$objectTable->addColumn(new SmartObjectColumn('post_uid', 'left', 150));

		$objectTable->addIntroButton('addpost', 'post.php?op=mod&post_id=' . $post_id, _AM_IMBLOGGING_POST_CREATE);

		$objectTable->render();

		smart_close_collapsable('postview_posts');

		break;

	default:

		xoops_cp_header();

		$xoopsModule->displayAdminMenu(0, _AM_IMBLOGGING_POSTS);

		//smart_collapsableBar('createdposts', _AM_IMBLOGGING_POSTS, _AM_IMBLOGGING_POSTS_DSC);

		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
		$objectTable = new IcmsPersistableTable($imblogging_post_handler);
		$objectTable->addColumn(new IcmsPersistableColumn('post_title', 'left'));
		$objectTable->addColumn(new IcmsPersistableColumn('post_published_date', 'left', 150));
		$objectTable->addColumn(new IcmsPersistableColumn('post_uid', 'left', 150));

		$objectTable->addIntroButton('addpost', 'post.php?op=mod', _AM_IMBLOGGING_POST_CREATE);

		$objectTable->addQuickSearch(array('post_name', 'post_description_small'));

		$objectTable->render();

		//smart_close_collapsable('createdposts');

		break;
}

xoops_cp_footer();

?>