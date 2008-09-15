<?php
/**
* Classes responsible for managing imBlogging post objects
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH."/kernel/icmspersistableseoobject.php";

/**
 * Post status definitions
 */
define ('IMBLOGGING_POST_STATUS_PUBLISHED', 1);
define ('IMBLOGGING_POST_STATUS_PENDING', 2);
define ('IMBLOGGING_POST_STATUS_DRAFT', 3);
define ('IMBLOGGING_POST_STATUS_PRIVATE', 4);

class ImbloggingPost extends IcmsPersistableSeoObject {

    function ImbloggingPost(&$handler) {
    	global $xoopsConfig;

    	$this->IcmsPersistableObject($handler);

        $this->quickInitVar('post_id', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('post_title', XOBJ_DTYPE_TXTBOX);
        $this->quickInitVar('post_content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('post_published_date', XOBJ_DTYPE_LTIME);
		$this->quickInitVar('post_uid', XOBJ_DTYPE_INT);
		$this->quickInitVar('post_status', XOBJ_DTYPE_INT, false, false, false, IMBLOGGING_POST_STATUS_PUBLISHED);
		$this->quickInitVar('post_cancomment', XOBJ_DTYPE_INT, false, false, false, true);

		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false, $xoopsConfig['editor_default'] == 'dhtmltextarea');
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('doxcode', false, true);

		$this->setControl('post_content', 'dhtmltextarea');
		$this->setControl('post_uid', 'user');
		$this->setControl('post_status', array(
											'itemHandler' => 'post',
											'method' => 'getPost_statusArray',
											 'module' => 'imblogging'));

		$this->setControl('post_cancomment', 'yesno');

		$this->IcmsPersistableSeoObject();
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('post_uid', 'post_status'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

    function post_uid() {
        return icms_getLinkedUnameFromId($this->getVar('post_uid', 'e'));
    }

    function post_status() {
        $ret = $this->getVar('post_status', 'e');
        $post_statusArray = $this->handler->getPost_statusArray();
        return $post_statusArray[$ret];
    }

    function getPosterLink() {
    	$member_handler = xoops_getHandler('member');
    	$poster_uid = $this->getVar('post_uid', 'e');
    	$userObj = $member_handler->getuser($poster_uid);

    	return '<a href="' . IMBLOGGING_URL . 'poster.php?uid=' . $poster_uid . '">' . $userObj->getVar('uname') . '</a>';
    }

    function getPostInfo() {
		$ret = sprintf(_CO_IMBLOGGING_POST_INFO, $this->getPosterLink(), $this->getVar('post_published_date'));
		return $ret;
    }

    function getPostLead() {
    	$ret = $this->getVar('post_content');
    	$slices = explode('[more]', $ret);
    	return $slices[0];
    }

    function toArray() {
		$ret = parent::toArray();
		$ret['post_info'] = $this->getPostInfo();
		$ret['post_lead'] = $this->getPostLead();
		return $ret;
    }

}
class ImbloggingPostHandler extends IcmsPersistableObjectHandler {

    var $_post_statusArray = array();

    function ImbloggingPostHandler($db) {
        $this->IcmsPersistableObjectHandler($db, 'post', 'post_id', 'post_title', '', 'imblogging');
    }

    function getPost_statusArray() {
	    if (!$this->_post_statusArray) {
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_PUBLISHED] = _CO_IMBLOGGING_POST_STATUS_PUBLISHED;
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_PENDING] = _CO_IMBLOGGING_POST_STATUS_PENDING;
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_DRAFT] = _CO_IMBLOGGING_POST_STATUS_DRAFT;
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_PRIVATE] = _CO_IMBLOGGING_POST_STATUS_PRIVATE;
	    }
	    return $this->_post_statusArray;
    }

    function getPosts() {
    	$criteria = new CriteriaCompo();
    	$criteria->setSort('post_published_date');
    	$criteria->setOrder('DESC');
    	$ret = $this->getObjects($criteria, true, false);
    	return $ret;
    }
}
?>