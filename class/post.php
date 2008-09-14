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

class ImbloggingPost extends IcmsPersistableObject {

    function ImbloggingPost(&$handler) {
    	$this->IcmsPersistableObject($handler);

        $this->quickInitVar('post_id', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('post_title', XOBJ_DTYPE_TXTBOX);
        $this->quickInitVar('post_content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('post_published_date', XOBJ_DTYPE_LTIME);
		$this->quickInitVar('post_uid', XOBJ_DTYPE_INT);
		$this->quickInitVar('post_status', XOBJ_DTYPE_INT);
		$this->quickInitVar('post_cancomment', XOBJ_DTYPE_INT);

		$this->initCommonVar('counter', false);
		$this->initCommonVar('meta_keywords');
		$this->initCommonVar('meta_description');
		$this->initCommonVar('short_url');

		$this->setControl('post_content', 'dhtmltextarea');
		$this->setControl('post_uid', 'user');
		$this->setControl('post_cancomment', 'yesno');
		$this->setControl('post_meta_keywords', 'textarea');
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
        return "post_status() metod to be done";
    }

}
class ImbloggingPostHandler extends IcmsPersistableObjectHandler {

    var $_post_statusArray = array();

    function ImbloggingPostHandler($db) {
        $this->IcmsPersistableObjectHandler($db, 'post', 'post_id', 'post_title', '', 'imblogging');
    }

    function getPost_statusArray() {
	    if (!$this->_post_statusArray) {
			//...
	    }
	    return $this->_post_statusArray;
    }
}
?>