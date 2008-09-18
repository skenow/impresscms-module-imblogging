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



/**
 * Edit a Blog Post
 *
 * @param int $post_id Postid to be edited
*/
function editpost($post_id = 0)
{
	global $imblogging_post_handler, $xoopsModule, $icmsAdminTpl;

	$postObj = $imblogging_post_handler->get($post_id);

	if (!$postObj->isNew()){

		$xoopsModule->displayAdminMenu(0, _AM_IMBLOGGING_POSTS . " > " . _CO_ICMS_EDITING);
		$sform = $postObj->getForm(_AM_IMBLOGGING_POST_EDIT, 'addpost');
		$sform->assign($icmsAdminTpl);

	} else {
		$xoopsModule->displayAdminMenu(0, _AM_IMBLOGGING_POSTS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $postObj->getForm(_AM_IMBLOGGING_POST_CREATE, 'addpost');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:imblogging_admin_post.html');
}

include_once("admin_header.php");

$imblogging_post_handler = xoops_getModuleHandler('post');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addpost','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
  	case "mod":
  	case "changedField":

  		xoops_cp_header();

  		editpost($clean_post_id);
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
  		$postObj = $imblogging_post_handler->get($clean_post_id);

  		smart_xoops_cp_header();
  		smart_adminMenu(1, _AM_IMBLOGGING_POST_VIEW . ' > ' . $postObj->getVar('post_title'));

  		smart_collapsableBar('postview', $postObj->getVar('post_title') . $postObj->getEditItemLink(), _AM_IMBLOGGING_POST_VIEW_DSC);

  		$postObj->displaySingleObject();

  		smart_close_collapsable('postview');

  		smart_collapsableBar('postview_posts', _AM_IMBLOGGING_POSTS, _AM_IMBLOGGING_POSTS_IN_POST_DSC);

  		$criteria = new CriteriaCompo();
  		$criteria->add(new Criteria('post_id', $clean_post_id));

  		$objectTable = new SmartObjectTable($imblogging_post_handler, $criteria);
  		$objectTable->addColumn(new SmartObjectColumn('post_date', 'left', 150));
  		$objectTable->addColumn(new SmartObjectColumn('post_message'));
  		$objectTable->addColumn(new SmartObjectColumn('post_uid', 'left', 150));

  		$objectTable->addIntroButton('addpost', 'post.php?op=mod&post_id=' . $clean_post_id, _AM_IMBLOGGING_POST_CREATE);

  		$objectTable->render();

  		smart_close_collapsable('postview_posts');

  		break;

  	default:

  		xoops_cp_header();

  		$xoopsModule->displayAdminMenu(0, _AM_IMBLOGGING_POSTS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($imblogging_post_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn('post_title', 'left'));
  		$objectTable->addColumn(new IcmsPersistableColumn('post_published_date', 'center', 150));
  		$objectTable->addColumn(new IcmsPersistableColumn('post_uid', 'center', 150));
  		$objectTable->addColumn(new IcmsPersistableColumn('post_status', 'center', 150));

  		$objectTable->addIntroButton('addpost', 'post.php?op=mod', _AM_IMBLOGGING_POST_CREATE);
  		$objectTable->addQuickSearch(array('post_title', 'post_content'));
  		$icmsAdminTpl->assign('imblogging_post_table', $objectTable->fetch());

  		$icmsAdminTpl->display('db:imblogging_admin_post.html');
  		break;
  }
  xoops_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>