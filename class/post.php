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

if (!defined("ICMS_ROOT_PATH"))
	die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . "/kernel/icmspersistableseoobject.php";

/**
 * Post status definitions
 */
define('IMBLOGGING_POST_STATUS_PUBLISHED', 1);
define('IMBLOGGING_POST_STATUS_PENDING', 2);
define('IMBLOGGING_POST_STATUS_DRAFT', 3);
define('IMBLOGGING_POST_STATUS_PRIVATE', 4);

class ImbloggingPost extends IcmsPersistableSeoObject {

	private $post_date_info = false;
	public $updating_counter = false;

	/**
	 * Constructor
	 *
	 * @param object $handler ImbloggingPostHandler object
	 */
	public function __construct(& $handler) {
		global $xoopsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('post_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('post_title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('post_content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('post_published_date', XOBJ_DTYPE_LTIME);
		$this->quickInitVar('post_uid', XOBJ_DTYPE_INT);
		$this->quickInitVar('post_status', XOBJ_DTYPE_INT, false, false, false, IMBLOGGING_POST_STATUS_PUBLISHED);
		$this->quickInitVar('post_cancomment', XOBJ_DTYPE_INT, false, false, false, true);
		$this->quickInitVar('post_comments', XOBJ_DTYPE_INT);
		$this->hideFieldFromForm('post_comments');

		$this->quickInitVar('post_notification_sent', XOBJ_DTYPE_INT);
		$this->hideFieldFromForm('post_notification_sent');

		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false);
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('doxcode', false, true);

		$this->setControl('post_content', 'dhtmltextarea');
		$this->setControl('post_uid', 'user');
		$this->setControl('post_status', array (
			'itemHandler' => 'post',
			'method' => 'getPost_statusArray',
			'module' => 'imblogging'
		));

		$this->setControl('post_cancomment', 'yesno');

		$this->IcmsPersistableSeoObject();
	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array (
				'post_uid',
				'post_status'
			))) {
			return call_user_func(array (
				$this,
				$key
			));
		}
		return parent :: getVar($key, $format);
	}

	/**
	 * Retrieving the name of the poster, linked to his profile
	 *
	 * @return str name of the poster
	 */
	function post_uid() {
		return icms_getLinkedUnameFromId($this->getVar('post_uid', 'e'));
	}

	/**
	 * Retrieving the status of the post
	 *
	 * @param str status of the post
	 * @return mixed $post_statusArray[$ret] status of the post
	 */
	function post_status() {
		$ret = $this->getVar('post_status', 'e');
		$post_statusArray = $this->handler->getPost_statusArray();
		return $post_statusArray[$ret];
	}

	/**
	 * Returns the need to br
	 *
	* @return bool true | false
	 */
	function need_do_br() {
		global $xoopsConfig, $xoopsUser;

		$imblogging_module = icms_getModuleInfo('imblogging');
		$groups = $xoopsUser->getGroups();

		$editor_default = $xoopsConfig['editor_default'];
		$gperm_handler = xoops_getHandler('groupperm');
		if (file_exists(ICMS_EDITOR_PATH . "/" . $editor_default . "/xoops_version.php") && $gperm_handler->checkRight('use_wysiwygeditor', $imblogging_module->mid(), $groups)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Check is user has access to view this post
	 *
	 * User will be able to view the post if
	 *    - the status of the post is Published OR
	 *    - he is an admin OR
	 * 	  - he is the poster of this post
	 *
	 * @return bool true if user can view this post, false if not
	 */
	function accessGranted() {
		global $imblogging_isAdmin, $xoopsUser;
		return $this->getVar('post_status', 'e') == IMBLOGGING_POST_STATUS_PUBLISHED || $imblogging_isAdmin || $this->getVar('post_uid', 'e') == $xoopsUser->uid();
	}

	/**
	 * Get the poster
	 *
	 * @param bool $link with link or not
	 * @return str poster name linked on his module poster page, or simply poster name
	 */
	function getPoster($link = false) {
		$member_handler = xoops_getHandler('member');
		$poster_uid = $this->getVar('post_uid', 'e');
		$userObj = $member_handler->getuser($poster_uid);

		/**
		 * We need to make sure the poster is a valid user object. It is possible the user no longer
		 * exists if, for example, he was previously deleted. In that case, we will return Anonymous
		 */
		if (is_object($userObj)) {
			if ($link) {
				return '<a href="' . IMBLOGGING_URL . 'index.php?uid=' . $poster_uid . '">' . $userObj->getVar('uname') . '</a>';
			} else {
				return $userObj->getVar('uname');
			}
		} else {
			global $xoopsConfig;
			return $xoopsConfig['anonymous'];
		}
	}

	/**
	 * Retrieve post info (poster and date)
	 *
	 * @return str post info
	 */
	function getPostInfo() {
		$ret = sprintf(_CO_IMBLOGGING_POST_INFO, $this->getPoster(true), $this->getVar('post_published_date'));
		return $ret;
	}

	/**
	 * Retrieve post comment info (number of comments)
	 *
	 * @return str post comment info
	 */
	function getCommentsInfo() {
		$post_comments = $this->getVar('post_comments');
		if ($post_comments) {
			return '<a href="' . $this->getItemLink(true) . '#comments_container">' . sprintf(_CO_IMBLOGGING_POST_COMMENTS_INFO, $post_comments) . '</a>';
		} else {
			return _CO_IMBLOGGING_POST_NO_COMMENT;
		}
	}

	/**
	 * Retrieve the post content without [more] tag
	 *
	 * @return str post content
	 */
	function getPostContent() {
		$ret = $this->getVar('post_content');
		$ret = str_replace('<p>[more]</p>', '', $ret);
		$ret = str_replace('[more]', '', $ret);
		return $ret;
	}

	/**
	 * Retrieve post lead, which is everything before the [more] tag
	 *
	 * @return str post lead
	 */
	function getPostLead() {
		$ret = $this->getVar('post_content');
		$slices = explode('[more]', $ret);
		return $slices[0];
	}

	/**
	 * Get post year, month and day and assign value to proper var
	 *
	 * @return VOID
	 */
	function getPostDateInfo() {
		include_once(ICMS_ROOT_PATH . '/modules/imblogging/include/functions.php');
		$post_date = $this->getVar('post_published_date', 'n');
		$this->post_date_info['year'] = date('Y', $post_date);
		$this->post_date_info['month'] = imblogging_getMonthNameById(date('n', $post_date));
		$this->post_date_info['day'] = date('D', $post_date);
		$this->post_date_info['day_number'] = date('d', $post_date);
	}

	/**
	 * Get year of this post
	 *
	 * @return int year of this post
	 */
	function getPostYear() {
		if (!$this->post_date_info) {
			$this->getPostDateInfo();
		}
		return $this->post_date_info['year'];
	}

	/**
	 * Get month of this post
	 *
	 * @return str month of this post
	 */
	function getPostMonth() {
		if (!$this->post_date_info) {
			$this->getPostDateInfo();
		}
		return $this->post_date_info['month'];
	}

	/**
	 * Get day of this post
	 *
	 * @return str day of this post
	 */
	function getPostDay() {
		if (!$this->post_date_info) {
			$this->getPostDateInfo();
		}
		return $this->post_date_info['day'];
	}

	/**
	 * Get day number of this post
	 *
	 * @return str day number of this post
	 */
	function getPostDayNumber() {
		if (!$this->post_date_info) {
			$this->getPostDateInfo();
		}
		return $this->post_date_info['day_number'];
	}

	/**
	 * Update the counter field of the post object
	 *
	 * @todo add this in directly in the IPF
	 *
	 * @return VOID
	 */
	function updateCounter() {
		icms_debug('666');
		$this->updating_counter = true;
		$counter = $this->getVar('counter');
		$this->setVar('counter', $counter +1);
		$this->handler->insert($this, true);
	}

	/**
	 * Check to see wether the current user can edit or delete this post
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanEditAndDelete() {
		global $xoopsUser, $imblogging_isAdmin;
		if (!is_object($xoopsUser)) {
			return false;
		}
		if ($imblogging_isAdmin) {
			return true;
		}
		return $this->getVar('post_uid', 'e') == $xoopsUser->uid();
	}

	/**
	 * Sending the notification related to a post being published
	 *
	 * @return VOID
	 */
	function sendNotifPostPublished() {
		global $imbloggingModule;
		$module_id = $imbloggingModule->getVar('mid');
		$notification_handler = xoops_getHandler('notification');

		$tags['POST_TITLE'] = $this->getVar('post_title');
		$tags['POST_URL'] = $this->getItemLink(true);

		$notification_handler->triggerEvent('global', 0, 'post_published', $tags, array (), $module_id);
	}

	/**
	 * Overridding IcmsPersistable::toArray() method to add a few info
	 *
	 * @return array of post info
	 */
	function toArray() {
		$ret = parent :: toArray();
		$ret['post_info'] = $this->getPostInfo();
		$ret['post_lead'] = $this->getPostLead();
		$ret['post_year'] = $this->getPostYear();
		$ret['post_month'] = $this->getPostMonth();
		$ret['post_day'] = $this->getPostDay();
		$ret['post_day_number'] = $this->getPostDayNumber();
		$ret['post_comment_info'] = $this->getCommentsInfo();
		$ret['post_content'] = $this->getPostContent();
		$ret['editItemLink'] = $this->getEditItemLink(false, true, true);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(false, true, true);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		return $ret;
	}
}
class ImbloggingPostHandler extends IcmsPersistableObjectHandler {

	/**
	 * @var array of status
	 */
	var $_post_statusArray = array ();

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'post', 'post_id', 'post_title', 'post_content', 'imblogging');
	}

	/**
	 * Retreive the possible status of a post object
	 *
	 * @return array of status
	 */
	function getPost_statusArray() {
		if (!$this->_post_statusArray) {
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_PUBLISHED] = _CO_IMBLOGGING_POST_STATUS_PUBLISHED;
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_PENDING] = _CO_IMBLOGGING_POST_STATUS_PENDING;
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_DRAFT] = _CO_IMBLOGGING_POST_STATUS_DRAFT;
			$this->_post_statusArray[IMBLOGGING_POST_STATUS_PRIVATE] = _CO_IMBLOGGING_POST_STATUS_PRIVATE;
		}
		return $this->_post_statusArray;
	}

	/**
	 * Create the criteria that will be used by getPosts and getPostsCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of posts to return
	 * @param int $post_uid if specifid, only the post of this user will be returned
	 * @param int $year of posts to display
	 * @param int $month of posts to display
	 * @return CriteriaCompo $criteria
	 */
	function getPostsCriteria($start = 0, $limit = 0, $post_uid = false, $year = false, $month = false) {
		$criteria = new CriteriaCompo();
		if ($start) {
			$criteria->setStart($start);
		}
		if ($limit) {
			$criteria->setLimit(intval($limit));
		}
		$criteria->setSort('post_published_date');
		$criteria->setOrder('DESC');
		$criteria->add(new Criteria('post_status', IMBLOGGING_POST_STATUS_PUBLISHED));
		if ($post_uid) {
			$criteria->add(new Criteria('post_uid', $post_uid));
		}
		if ($year && $month) {
			$criteriaYearMonth = new CriteriaCompo();
			$criteriaYearMonth->add(new Criteria('MONTH(FROM_UNIXTIME(post_published_date))', $month));
			$criteriaYearMonth->add(new Criteria('YEAR(FROM_UNIXTIME(post_published_date))', $year));
			$criteria->add($criteriaYearMonth);
		}
		return $criteria;
	}

	/**
	 * Get posts as array, ordered by post_published_date DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max posts to display
	 * @param int $post_uid if specifid, only the post of this user will be returned
	 * @param int $year of posts to display
	 * @param int $month of posts to display
	 * @return array of posts
	 */
	function getPosts($start = 0, $limit = 0, $post_uid = false, $year = false, $month = false) {
		$criteria = $this->getPostsCriteria($start, $limit, $post_uid, $year, $month);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}

	/**
	 * Get a list of users
	 *
	 * @return array list of users
	 */
	function getPostersArray() {
		$member_handler = xoops_getHandler('member');
		return $member_handler->getUserList();
	}

	/**
	 * Get posts count
	 *
	 * @param int $post_uid if specifid, only the post of this user will be returned
	 * @return array of posts
	 * @param int $year of posts to display
	 * @param int $month of posts to display
	 */
	function getPostsCount($post_uid, $year = false, $month = false) {
		$criteria = $this->getPostsCriteria(false, false, $post_uid, $year, $month);
		return $this->getCount($criteria);
	}

	function getPostsCountByMonth() {
		$sql = 'SELECT count(post_id) AS posts_count, MONTH(FROM_UNIXTIME(post_published_date)) AS posts_month, YEAR(FROM_UNIXTIME(post_published_date)) AS posts_year ' .
		'FROM ' . $this->table . ' ' .
		'GROUP BY posts_year, posts_month ' .
		'HAVING posts_count > 0 ' .
		'ORDER BY posts_year DESC, posts_month DESC';
		$postsByMonthArray = $this->query($sql, false);
		$ret = array ();
		foreach ($postsByMonthArray as $postByMonth) {
			$postByMonth['posts_month_name'] = imblogging_getMonthNameById($postByMonth['posts_month']);
			$ret[] = $postByMonth;
		}
		return $ret;
	}

	/**
	 * Get Posts requested by the global search feature
	 *
	 * @param array $queryarray array containing the searched keywords
	 * @param bool $andor wether the keywords should be searched with AND or OR
	 * @param int $limit maximum results returned
	 * @param int $offset where to start in the resulting dataset
	 * @param int $userid should we return posts by specific poster ?
	 * @return array array of posts
	 */
	function getPostsForSearch($queryarray, $andor, $limit, $offset, $userid) {
		$criteria = new CriteriaCompo();

		if ($userid != 0) {
			$criteria->add(new Criteria('post_uid', $userid));
		}
		if ($queryarray) {
			$criteriaKeywords = new CriteriaCompo();
			for ($i = 0; $i < count($queryarray); $i++) {
				$criteriaKeyword = new CriteriaCompo();
				$criteriaKeyword->add(new Criteria('post_title', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeyword->add(new Criteria('post_content', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeywords->add($criteriaKeyword, $andor);
				unset ($criteriaKeyword);
			}
			$criteria->add($criteriaKeywords);
		}
		$criteria->add(new Criteria('post_status', IMBLOGGING_POST_STATUS_PUBLISHED));
		return $this->getObjects($criteria, true, false);
	}

	/**
	 * Update number of comments on a post
	 *
	 * This method is triggered by imblogging_com_update in include/functions.php which is
	 * called by ImpressCMS when updating comments
	 *
	 * @param int $post_id id of the post to update
	 * @param int $total_num total number of comments so far in this post
	 * @return VOID
	 */
	function updateComments($post_id, $total_num) {
		$postObj = $this->get($post_id);
		if ($postObj && !$postObj->isNew()) {
			$postObj->setVar('post_comments', $total_num);
			$this->insert($postObj, true);
		}
	}

	/**
	 * Check wether the current user can submit a new post or not
	 *
	 * @return bool true if he can false if not
	 */
	function userCanSubmit() {
		global $xoopsUser, $imblogging_isAdmin;
		$imbloggingModuleConfig = icms_getModuleConfig('imblogging');

		if (!is_object($xoopsUser)) {
			return false;
		}
		if ($imblogging_isAdmin) {
			return true;
		}
		$user_groups = $xoopsUser->getGroups();
		return count(array_intersect($imbloggingModuleConfig['poster_groups'], $user_groups)) > 0;
	}

	/**
	 * BeforeSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework before the object is inserted or updated.
	 *
	 * @param object $obj ImbloggingPost object
	 * @return true
	 */
	function beforeSave(& $obj) {
		if ($obj->updating_counter) return true;

		$obj->setVar('dobr', $obj->need_do_br());
		return true;
	}

	/**
	 * AfterSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework after the object is inserted or updated
	 *
	 * @param object $obj ImbloggingPost object
	 * @return true
	 */
	function afterSave(& $obj) {
		if ($obj->updating_counter)	return true;

		if (!$obj->getVar('post_notification_sent') && $obj->getVar('post_status', 'e') == IMBLOGGING_POST_STATUS_PUBLISHED) {
			$obj->sendNotifPostPublished();
			$obj->setVar('post_notification_sent', true);
			$this->insert($obj);
		}
		return true;
	}
}
?>