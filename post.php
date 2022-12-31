<?php

/**
 * Post page
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @package imblogging
 * @version $Id$
 */

/**
 * Edit a Blog Post
 *
 * @param object $postObj ImblogginPost object to be edited
 */
function editpost($postObj) {
	global $imblogging_post_handler, $xoTheme, $icmsTpl;

	$postObj->setControl('categories', array(
		'name' => 'categories',
		'module' => 'imtagging',
		'userside' => TRUE));

	if (!icms::$user->isAdmin()) {
		$postObj->hideFieldFromForm(array(
			'post_published_date',
			'post_uid',
			'meta_keywords',
			'meta_description',
			'short_url'));
	}
	if (!$postObj->isNew()) {
		if (!$postObj->userCanEditAndDelete()) {
			redirect_header($postObj->getItemLink(TRUE), 3, _NOPERM);
		}
		$postObj->loadCategories();
		if (!icms::$user->isAdmin()) {
			$postObj->hideFieldFromForm(array(
				'post_published_date',
				'post_uid',
				'meta_keywords',
				'meta_description',
				'short_url'));
		}
		$sform = $postObj->getSecureForm(_MD_IMBLOGGING_POST_EDIT, 'addpost');
		$sform->assign($icmsTpl, 'imblogging_postform');
		$icmsTpl->assign('imblogging_category_path', $postObj->getVar('post_title') . ' > ' . _EDIT);
	} else {
		if (!$imblogging_post_handler->userCanSubmit()) {
			redirect_header(IMBLOGGING_URL, 3, _NOPERM);
		}
		$postObj->setVar('post_uid', icms::$user->getVar("uid"));
		$postObj->setVar('post_published_date', time());
		if (!icms::$user->isAdmin()) {
			$postObj->hideFieldFromForm(array(
				'post_published_date',
				'post_uid',
				'meta_keywords',
				'meta_description',
				'short_url'));
		}
		$sform = $postObj->getSecureForm(_MD_IMBLOGGING_POST_SUBMIT, 'addpost');
		$sform->assign($icmsTpl, 'imblogging_postform');
		$icmsTpl->assign('imblogging_category_path', _SUBMIT);
	}

	$xoTheme->addStylesheet(ICMS_MODULES_URL . '/imtagging/module' . ((defined("_ADM_USE_RTL") && _ADM_USE_RTL) ? '_rtl' : '') . '.css');
}

include_once 'header.php';

$xoopsOption['template_main'] = 'imblogging_post.html';
include_once ICMS_ROOT_PATH . '/header.php';

$imblogging_post_handler = icms_getModuleHandler('post', $moddir, 'imblogging');

/* Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/* Again, use a naming convention that indicates the source of the content of the variable */
$clean_post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;

/*
 * Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array(
	'mod',
	'addpost',
	'del',
	'');

/* Only proceed if the supplied operation is a valid operation */
if (in_array($clean_op, $valid_op, TRUE)) {
	switch ($clean_op) {
		case "mod":
			$postObj = $imblogging_post_handler->get($clean_post_id);
			if ($clean_post_id > 0 && $postObj->isNew()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
			}
			editpost($postObj);
			break;

		case "addpost":
			if (!icms::$security->check()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_IMBLOGGING_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
			}
			$controller = new icms_ipf_Controller($imblogging_post_handler);
			/* need to flush the template option to prevent error on redirect */
			$xoopsOption['template_main'] = NULL;
			$controller->storeFromDefaultForm(_MD_IMBLOGGING_POST_CREATED, _MD_IMBLOGGING_POST_MODIFIED);
			break;

		case "del":
			$postObj = $imblogging_post_handler->get($clean_post_id);
			if (!$postObj->userCanEditAndDelete()) {
				redirect_header($postObj->getItemLink(TRUE), 3, _NOPERM);
			}
			if (isset($_POST['confirm'])) {
				if (!icms::$security->check()) {
					redirect_header($impresscms->urls['previouspage'], 3, _MD_IMBLOGGING_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
				}
			}
			$controller = new icms_ipf_Controller($imblogging_post_handler);
			/* need to flush the template option to prevent error on redirect */
			$xoopsOption['template_main'] = NULL;
			$controller->handleObjectDeletionFromUserSide();
			$icmsTpl->assign('imblogging_category_path', $postObj->getVar('post_title') . ' > ' . _DELETE);

			break;

		default:
			$postArray = $imblogging_post_handler->getPost($clean_post_id);
			$imblogging_post_handler->updateCounter($clean_post_id);
			$icmsTpl->assign('imblogging_post', $postArray);
			$icmsTpl->assign('imblogging_category_path', $postArray['post_title']);

			$icmsTpl->assign('imblogging_showSubmitLink', TRUE);
			$icmsTpl->assign('imblogging_rss_url', IMBLOGGING_URL . 'rss.php');
			$icmsTpl->assign('imblogging_rss_info', _MD_IMBLOGGING_RSS_GLOBAL);

			if ($icmsModuleConfig['com_rule'] && $postArray['post_cancomment']) {
				$icmsTpl->assign('imblogging_post_comment', TRUE);
				include_once ICMS_ROOT_PATH . '/include/comment_view.php';
			}
			/* Generating meta information for this page */
			$icms_metagen = new icms_ipf_Metagen($postArray['post_title'], $postArray['meta_keywords'], $postArray['meta_description']);
			$icms_metagen->createMetaTags();

			break;
	}
}
$icmsTpl->assign('imblogging_module_home', icms_getModuleName(TRUE, TRUE));

include_once 'footer.php';
