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
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/imblogging/include/functions.php');

/**
 * Post status definitions
 */
define('IMBLOGGING_POST_STATUS_PUBLISHED', 1);
define('IMBLOGGING_POST_STATUS_PENDING', 2);
define('IMBLOGGING_POST_STATUS_DRAFT', 3);
define('IMBLOGGING_POST_STATUS_PRIVATE', 4);

class ImbloggingPost extends IcmsPersistableSeoObject {

	private	$post_date_info = false;
	private $poster_info = false;
	public 	$updating_counter = false;
	public	$categories = false;

	/**
	 * Constructor
	 *
	 * @param object $handler ImbloggingPostHandler object
	 */
	public function __construct(& $handler) {
		global $xoopsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('post_id', XOBJ_DTYPE_INT, true);
		/**
		 * @todo IPF needs to be able to know what to do with XOBJ_DTYPE_ARRAY, which it does not right now...
		 */
		$this->initNonPersistableVar('categories', XOBJ_DTYPE_INT, 'category', false, false, false, true);
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
		$this->initCommonVar('dobr');
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('doxcode', false, true);

		$this->setControl('categories', array(
				'name'=>'categories',
				'module'=>'imtagging'
			));
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
		if ($format == 's' && in_array($key, array ('post_uid',	'post_status', 'categories'))) {
			return call_user_func(array ($this,	$key));
		} elseif($format == 'e' && in_array($key, array ('categories'))) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}

	/**
	 * Load categories linked to this post
	 *
	 * @return void
	 */
	function loadCategories() {
		$imtagging_category_link_handler = xoops_getModuleHandler('category_link', 'imtagging');
		$ret = $imtagging_category_link_handler->getCategoriesForObject($this->id(), $this->handler);
		$this->setVar('categories', $ret);
	}

	function categories() {
		$ret = $this->getVar('categories', 'n');
		$ret = $this->vars['categories']['value'];
		if (is_array($ret)) {
			return $ret;
		} else {
			(int)$ret > 0 ? array((int)$ret) : false;
		}
	}

	/**
	 * Retrieving the name of the poster, linked to his profile
	 *
	 * @return str name of the poster
	 */
	function post_uid() {
		return imblogging_getLinkedUnameFromId($this->getVar('post_uid', 'e'));
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
		if (!$this->poster_info) {
			$member_handler = xoops_getHandler('member');
			$poster_uid = $this->getVar('post_uid', 'e');
			$userObj = $member_handler->getuser($poster_uid);

			/**
			 * We need to make sure the poster is a valid user object. It is possible the user no longer
			 * exists if, for example, he was previously deleted. In that case, we will return Anonymous
			 */
			if (is_object($userObj)) {
				$this->poster_info['uid'] = $poster_uid;
				$this->poster_info['uname'] = $userObj->getVar('uname');
				$this->poster_info['link'] = '<a href="' . IMBLOGGING_URL . 'index.php?uid=' . $this->poster_info['uid'] . '">' . $this->poster_info['uname'] . '</a>';
			} else {
				global $xoopsConfig;
				$this->poster_info['uid'] = 0;
				$this->poster_info['uname'] = $xoopsConfig['anonymous'];
			}
		}
		if ($link && $this->poster_info['uid']) {
			return $this->poster_info['link'];
		} else {
			return $this->poster_info['uname'];
		}
	}

	/**
	 * Retrieve post info (poster and date)
	 *
	 * @return str post info
	 */
	function getPostInfo() {
		$status = $this->getVar('post_status', 'e');
		switch ($status) {
			case IMBLOGGING_POST_STATUS_PENDING:
				$ret = _CO_IMBLOGGING_POST_PENDING;
			break;

			case IMBLOGGING_POST_STATUS_DRAFT:
				$ret = _CO_IMBLOGGING_POST_DRAFT;
			break;

			case IMBLOGGING_POST_STATUS_PRIVATE:
				$ret = _CO_IMBLOGGING_POST_PRIVATE;
			break;

			default:
				$ret = _CO_IMBLOGGING_POST_PUBLISHED;
			break;
		}
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
	global $xoopsConfig;
		$post_date = $this->getVar('post_published_date', 'n');
		$this->post_date_info['year'] = formatTimestamp($post_date, 'Y');
		$this->post_date_info['month'] = imblogging_getMonthNameById(formatTimestamp($post_date, 'n'));
		$this->post_date_info['month_short'] = formatTimestamp($post_date, 'month');
		$this->post_date_info['day'] = formatTimestamp($post_date, 'D');
		$this->post_date_info['day_number'] = formatTimestamp($post_date, 'daynumber');
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
	 * Get month short name of this post
	 *
	 * @return str month of this post
	 */
	function getPostMonthShort() {
		if (!$this->post_date_info) {
			$this->getPostDateInfo();
		}
		return $this->post_date_info['month_short'];
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
		$ret['post_published_date_int'] = $this->getVar('post_published_date', 'e');
		$ret['post_year'] = $this->getPostYear();
		$ret['post_month'] = $this->getPostMonth();
		$ret['post_month_short'] = $this->getPostMonthShort();
		$ret['post_day'] = $this->getPostDay();
		$ret['post_day_number'] = $this->getPostDayNumber();
		$ret['post_comment_info'] = $this->getCommentsInfo();
		$ret['post_content'] = $this->getPostContent();
		$ret['editItemLink'] = $this->getEditItemLink(false, true, true);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(false, true, true);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		$ret['post_posterid'] = $this->getVar('post_uid','e');
		$ret['post_poster_link'] = $this->getPoster(true);
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
	 * @param int $cid if specifid, only the post related to this category will be returned
	 * @param int $year of posts to display
	 * @param int $month of posts to display
	 * @param int $post_id ID of a single post to retrieve
	 * @return CriteriaCompo $criteria
	 */
	function getPostsCriteria($start = 0, $limit = 0, $post_uid = false, $cid = false, $year = false, $month = false, $post_id = false) {
		global $xoopsUser;

		$criteria = new CriteriaCompo();
		if ($start) {
			$criteria->setStart($start);
		}
		if ($limit) {
			$criteria->setLimit(intval($limit));
		}
		$criteria->setSort('post_published_date');
		$criteria->setOrder('DESC');

		if (!isset($xoopsUser) || (isset($xoopsUser)) && !$xoopsUser->isAdmin()) {
			$criteria->add(new Criteria('post_status', IMBLOGGING_POST_STATUS_PUBLISHED));
		}
		if ($post_uid) {
			$criteria->add(new Criteria('post_uid', $post_uid));
		}
		if ($cid) {
			$imtagging_category_link_handler = xoops_getModuleHandler('category_link', 'imtagging');
			$categoryids = $imtagging_category_link_handler->getItemidsForCategory($cid, $this);
			$criteria->add(new Criteria('post_id', '(' . implode(',', $categoryids) . ')', 'IN'));
		}
		if ($year && $month) {
			$criteriaYearMonth = new CriteriaCompo();
			$criteriaYearMonth->add(new Criteria('MONTH(FROM_UNIXTIME(post_published_date))', $month));
			$criteriaYearMonth->add(new Criteria('YEAR(FROM_UNIXTIME(post_published_date))', $year));
			$criteria->add($criteriaYearMonth);
		}
		if ($post_id) {
			$criteria->add(new Criteria('post_id', $post_id));
		}
		return $criteria;
	}

	/**
	 * Get single post object
	 *
	 * @param int $post_id
	 * @return object ImbloggingPost object
	 */
	function getPost($post_id) {
		$ret = $this->getPosts(0, 0, false, false, false, false, $post_id);
		return isset($ret[$post_id]) ? $ret[$post_id] : false;
	}

	/**
	 * Get posts as array, ordered by post_published_date DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max posts to display
	 * @param int $post_uid if specifid, only the post of this user will be returned
	 * @param int $cid if specifid, only the post related to this category will be returned
	 * @param int $year of posts to display
	 * @param int $month of posts to display
	 * @param int $post_id ID of a single post to retrieve
	 * @return array of posts
	 */
	function getPosts($start = 0, $limit = 0, $post_uid = false, $cid=false, $year = false, $month = false, $post_id = false) {
		$criteria = $this->getPostsCriteria($start, $limit, $post_uid, $cid, $year, $month, $post_id);
		$ret = $this->getObjects($criteria, true, false);

		// retrieve the ids of all Posts retrieved
		$postIds = $this->getIdsFromObjectsAsArray($ret);

		// retrieve categories linked to these postIds
		$imtagging_category_link_handler = xoops_getModuleHandler('category_link', 'imtagging');
		$categoriesObj = $imtagging_category_link_handler->getCategoriesFromObjectIds($postIds, $this);

		// put the category info in each postObj
		foreach($categoriesObj as $categoryObj) {
			if (isset($categoryObj->items['imblogging']['post']))
			foreach($categoryObj->items['imblogging']['post'] as $postid) {
				$ret[$postid]['categories'][] = array(
						'id' => $categoryObj->id(),
						'title' => $categoryObj->getVar('category_title')
				);
			}
		}
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
	 * @param int $cid if specifid, only the post related to this category will be returned
	 * @return array of posts
	 * @param int $year of posts to display
	 * @param int $month of posts to display
	 */
	function getPostsCount($post_uid, $cid = false, $year = false, $month = false) {
		$criteria = $this->getPostsCriteria(false, false, $post_uid, $cid, $year, $month);
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
	$config_handler =& xoops_gethandler('config');
	$xoopsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);
		foreach ($postsByMonthArray as $postByMonth) {
			$postByMonthnr = $postByMonth['posts_month'];
			$postByYearname = $postByMonth['posts_year'];
			$postByYearnr = $postByMonth['posts_year'];
		if($xoopsConfig['language'] == "persian" && $xoopsConfig['use_ext_date'] == 1)
{
		include_once ICMS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php';
		$gyear = $postByYearname;
		$gmonth = $postByMonthnr;
		$gday = 1;
		list($jyear, $jmonth, $jday) = gregorian_to_jalali( $gyear, $gmonth, $gday );
		$postByYearname =  icms_conv_nr2local($jyear);
		$postByYearnr =  $jyear;
		$postByMonthnr = $jmonth;

}
			$postByMonth['posts_year_nr'] = $postByYearnr;
			$postByMonth['posts_month_nr'] = $postByMonthnr;
			$postByMonth['posts_month_name'] = imblogging_getMonthNameById($postByMonthnr);
			$postByMonth['posts_year_name'] = $postByYearname;
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

		$criteria->setStart($offset);
		$criteria->setLimit($limit);

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

		// storing categories
		$imtagging_category_link_handler = xoops_getModuleHandler('category_link', 'imtagging');
		$imtagging_category_link_handler->storeCategoriesForObject($obj);

		if (!$obj->getVar('post_notification_sent') && $obj->getVar('post_status', 'e') == IMBLOGGING_POST_STATUS_PUBLISHED) {
			$obj->sendNotifPostPublished();
			$obj->setVar('post_notification_sent', true);
			$this->insert($obj);
		}
		return true;
	}

	/**
	 * Update the counter field of the post object
	 *
	 * @todo add this in directly in the IPF
	 * @param int $post_id
	 *
	 * @return VOID
	 */
	function updateCounter($id) {
		$sql = 'UPDATE ' . $this->table . ' SET counter = counter + 1 WHERE ' . $this->keyName . ' = ' . $id;
		$this->query($sql, null, true);
	}
}
?>
