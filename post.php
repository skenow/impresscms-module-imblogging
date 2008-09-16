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

function editpost($post_id = 0)
{
	global $imblogging_post_handler, $xoopsTpl;

	$postObj = $imblogging_post_handler->get($post_id);

	if (!$postObj->isNew()){
		$sform = $postObj->getForm(_MD_IMBLOGGING_POST_EDIT, 'addpost');
		$sform->assign($xoopsTpl, 'imblogging_postform');
		$xoopsTpl->assign('imblogging_category_path', $postObj->getVar('post_title') . ' > ' . _EDIT);
	} else {
		$sform = $postObj->getForm(_MD_IMBLOGGING_POST_SUBMIT, 'addpost');
		$sform->assign($xoopsTpl, 'imblogging_postform');
		$xoopsTpl->assign('imblogging_category_path', _SUBMIT);
	}
}

include_once('header.php');

$xoopsOption['template_main'] = 'imblogging_post.html';
include_once(ICMS_ROOT_PATH . "/header.php");

$imblogging_post_handler = xoops_getModuleHandler('post');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0 ;

switch ($op) {
	case "mod":
		editpost($post_id);
		break;

	case "addpost":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($imblogging_post_handler);
		$controller->storeFromDefaultForm(_MD_IMBLOGGING_POST_CREATED, _MD_IMBLOGGING_POST_MODIFIED);
		break;

	default:
		$postObj = $imblogging_post_handler->get($post_id);
		if ($postObj && !$postObj->isNew() && $postObj->accessGranted()) {
			$xoopsTpl->assign('imblogging_post', $postObj->toArray());
			$xoopsTpl->assign('imblogging_category_path', $postObj->getVar('post_title'));
		} else {
			redirect_header(IMBLOGGING_URL, 3, _NOPERM);
		}

		if ($xoopsModuleConfig['com_rule'] && $postObj->getVar('post_cancomment')) {
			$xoopsTpl->assign('imblogging_post_comment', true);
			include_once ICMS_ROOT_PATH . "/include/comment_view.php";
		}
		break;
}
$xoopsTpl->assign('imblogging_module_home', imblogging_getModuleName(true, true));
include_once("footer.php");
?>