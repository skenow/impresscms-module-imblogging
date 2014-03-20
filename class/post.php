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

defined("ICMS_ROOT_PATH") || die("ICMS root path not defined");

$moddir = basename(dirname(dirname(__FILE__)));
/** include the common functions for this module */
include_once ICMS_MODULES_PATH . '/' . $moddir . '/include/common.php';

/**
 * Post status definitions
 */
define('IMBLOGGING_POST_STATUS_PUBLISHED', 1);
define('IMBLOGGING_POST_STATUS_PENDING', 2);
define('IMBLOGGING_POST_STATUS_DRAFT', 3);
define('IMBLOGGING_POST_STATUS_PRIVATE', 4);

/**
 *
 * @category	ICMS
 * @package		Module
 * @subpackage	imBlogging
 */
class ImbloggingPost extends icms_ipf_seo_Object {

	private	 $post_date_info = FALSE;
	private $poster_info = FALSE;
	public 	$updating_counter = FALSE;
	public	$categories = FALSE;

	/**
	 * Constructor
	 *
	 * @param object $handler ImbloggingPostHandler object
	 */
	public function __construct(& $handler) {

		parent::__construct($handler);

		$this->quickInitVar('post_id', XOBJ_DTYPE_INT, TRUE);
		/**
		 * @todo IPF needs to be able to know what to do with XOBJ_DTYPE_ARRAY, which it does not right now...
		 */
		$this->initNonPersistableVar('categories', XOBJ_DTYPE_INT, 'category', FALSE, FALSE, FALSE, TRUE);
		$this->quickInitVar('post_title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('post_content', XOBJ_DTYPE_TXTAREA, TRUE, FALSE, _CO_IMBLOGGING_POST_POST_CONTENT_DSC);
		$this->quickInitVar('post_published_date', XOBJ_DTYPE_LTIME);
		$this->quickInitVar('post_uid', XOBJ_DTYPE_INT);
		$this->quickInitVar('post_status', XOBJ_DTYPE_INT, FALSE, FALSE, FALSE, IMBLOGGING_POST_STATUS_PUBLISHED);
		$this->quickInitVar('post_cancomment', XOBJ_DTYPE_INT, FALSE, FALSE, FALSE, TRUE);
		$this->quickInitVar('post_comments', XOBJ_DTYPE_INT);

		$this->hideFieldFromForm('post_comments');

		$this->quickInitVar('post_notification_sent', XOBJ_DTYPE_INT);
		$this->hideFieldFromForm('post_notification_sent');

		$this->initCommonVar('counter', FALSE);
		$this->initCommonVar('dohtml', FALSE, TRUE);
		$this->initCommonVar('dobr', FALSE, FALSE);
		$this->initCommonVar('doimage', FALSE, TRUE);
		$this->initCommonVar('dosmiley', FALSE, TRUE);
		$this->initCommonVar('doxcode', FALSE, TRUE);

		$this->setControl('categories', array(
				'name'=>'categories',
				'module'=>'imtagging'
			));
		$this->setControl('post_content', 'dhtmltextarea');
		$this->setControl('post_uid', 'user');
		$this->setControl('post_status', array(
				'itemHandler' => 'post',
				'method' => 'getPost_statusArray',
				'module' => 'imblogging'
			));

		$this->setControl('post_cancomment', 'yesno');

		$this->initiateSEO();
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
		if ($format == 's' && in_array($key, array('post_uid', 'post_status', 'categories'))) {
			return call_user_func(array($this, $key));
		} elseif ($format == 'e' && in_array($key, array('categories'))) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * Load categories linked to this post
	 *
	 * @return void
	 */
	function loadCategories() {
		$imtagging_category_link_handler = icms_getModuleHandler('category_link', 'imtagging');
		$ret = $imtagging_category_link_handler->getCategoriesForObject($this->id(), $this->handler);
		$this->setVar('categories', $ret);
	}

	function categories() {
		$ret = $this->getVar('categories', 'n');
		$ret = $this->vars['categories']['value'];
		if (is_array($ret)) {
			return $ret;
		} else {
			(int) $ret > 0 ? array((int) $ret) : FALSE;
		}
	}

	/**
	 * Retrieving the name of the poster, linked to his profile
	 *
	 * @return str name of the poster
	 */
	function post_uid() {
		return icms_member_user_Handler::getUserLink($this->getVar('post_uid', 'e'));
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
	 * @return bool TRUE | FALSE
	 */
	function need_do_br() {
		global $icmsConfig;
		$imblogging_module = icms_getModuleInfo(basename(dirname(dirname(__FILE__))));
		$groups = icms::$user->getGroups();

		$editor_default = $icmsConfig['editor_default'];
		$gperm_handler = icms::handler('icms_member_groupperm');
		if (file_exists(ICMS_EDITOR_PATH . "/" . $editor_default . "/xoops_version.php") && $gperm_handler->checkRight('use_wysiwygeditor', $imblogging_module->getVar("mid"), $groups)) {
			return FALSE;
		} else {
			return TRUE;
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
	 * @param	string	$perm_name	specific permission to check
	 * @return bool TRUE if user can view this post, FALSE if not
	 */
	function accessGranted($perm_name = NULL) {
		global $imblogging_isAdmin;
		return $this->getVar('post_status', 'e') == IMBLOGGING_POST_STATUS_PUBLISHED
			|| $imblogging_isAdmin
			|| $this->getVar('post_uid', 'e') == icms::$user->getVar("uid");
	}

	/**
	 * Get the poster
	 *
	 * @param bool $link with link or not
	 * @return str poster name linked on his module poster page, or simply poster name
	 */
	function getPoster($link = FALSE) {
		if (!$this->poster_info) {
			$member_handler = icms::handler('icms_member');
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
				global $icmsConfig;
				$this->poster_info['uid'] = 0;
				$this->poster_info['uname'] = $icmsConfig['anonymous'];
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
			return '<a href="' . $this->getItemLink(TRUE) . '#comments_container">' . sprintf(_CO_IMBLOGGING_POST_COMMENTS_INFO, $post_comments) . '</a>';
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
		if ($slices[1]) {
			$ret = $slices[0] . ' <a href="' . $this->getItemLink(TRUE)
				. '" title="' . $this->getVar('post_title')
				. '">' . _MD_IMBLOGGING_KEEP_READING . '</a>';
		} else {
			$ret = $slices[0];
		}
		return $ret;
	}

	/**
	 * Get post year, month and day and assign value to proper var
	 *
	 * @return VOID
	 */
	function getPostDateInfo() {
		$post_date = $this->getVar('post_published_date', 'n');
		$this->post_date_info['year'] = formatTimestamp($post_date, 'Y');
		$this->post_date_info['month'] = Icms_getMonthNameById(formatTimestamp($post_date, 'n'));
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
	 * Check to see if the current user can edit or delete this post
	 *
	 * @return bool TRUE if he can, FALSE if not
	 */
	function userCanEditAndDelete() {
		global $imblogging_isAdmin;
		if (!is_object(icms::$user)) {
			return FALSE;
		}
		if ($imblogging_isAdmin) {
			return TRUE;
		}
		return $this->getVar('post_uid', 'e') == icms::$user->uid();
	}

	/**
	 * Sending the notification related to a post being published
	 *
	 * @return VOID
	 */
	function sendNotifPostPublished() {
		global $imbloggingModule;
		$module_id = $imbloggingModule->getVar('mid');
		$notification_handler = icms::handler('icms_data_notification');

		$tags['POST_TITLE'] = $this->getVar('post_title');
		$tags['POST_URL'] = $this->getItemLink(TRUE);

		$notification_handler->triggerEvent('global', 0, 'post_published', $tags, array(), $module_id);
	}

	/**
	 * Overridding IcmsPersistable::toArray() method to add a few info
	 *
	 * @return array of post info
	 */
	function toArray() {
		$ret = parent::toArray();
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
		$ret['editItemLink'] = $this->getEditItemLink(FALSE, TRUE, TRUE);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(FALSE, TRUE, TRUE);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		$ret['post_posterid'] = $this->getVar('post_uid','e');
		$ret['post_poster_link'] = $this->getPoster(FALSE);
		$ret['itemLink'] = '<a href="' . $this->getItemLink(TRUE)
				. '" title="' . $this->getVar('post_title')
				. '">' . $this->getVar('post_title') . '</a>';
		return $ret;
	}

	/**
	 * Retreive the object user side link
	 *
	 * @param bool $onlyUrl whether or not to return a simple URL or a full <a> link
	 * @return string user side link to the object
	 */
	public function getItemLink($onlyUrl = FALSE) {
		$link = parent::getItemLink($onlyUrl);
		$short_url = $this->getVar('short_url');
		if (!empty($short_url)) {
			$link .= "&amp;title=" . $short_url;
		}
		return $link;
	}
}

/**
 *
 * @category	ICMS
 * @package		Module
 * @subpackage	imBlogging
 */
class ImbloggingPostHandler extends icms_ipf_Handler {

	/**
	 * @var array of status
	 */
	var $_post_statusArray = array();

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		parent::__construct($db, 'post', 'post_id', 'post_title', 'post_content', basename(dirname(dirname(__FILE__))));
	}

	/**
	 * Retrieve the possible status of a post object
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
	 * @return icms_db_criteria_Compo $criteria
	 */
	function getPostsCriteria($start = 0, $limit = 0, $post_uid = FALSE, $cid = FALSE, $year = FALSE, $month = FALSE, $post_id = FALSE) {

		$criteria = new icms_db_criteria_Compo();
		if ($start) {
			$criteria->setStart($start);
		}
		if ($limit) {
			$criteria->setLimit((int) ($limit));
		}
		$criteria->setSort('post_published_date');
		$criteria->setOrder('DESC');

		if (!is_object(icms::$user) || (is_object(icms::$user) && !icms::$user->isAdmin())) {
			$criteria->add(new icms_db_criteria_Item('post_status', IMBLOGGING_POST_STATUS_PUBLISHED));
			$criteria->add(new icms_db_criteria_Item('post_published_date', time(), '<='));
		}
		if ($post_uid) {
			$criteria->add(new icms_db_criteria_Item('post_uid', $post_uid));
		}
		if ($cid) {
			$imtagging_category_link_handler = icms_getModuleHandler('category_link', 'imtagging');
			$categoryids = $imtagging_category_link_handler->getItemidsForCategory($cid, $this);
			$criteria->add(new icms_db_criteria_Item('post_id', '(' . implode(',', $categoryids) . ')', 'IN'));
		}
		if ($year && $month) {
			$criteriaYearMonth = new icms_db_criteria_Compo();
			$criteriaYearMonth->add(new icms_db_criteria_Item('MONTH(FROM_UNIXTIME(post_published_date))', $month));
			$criteriaYearMonth->add(new icms_db_criteria_Item('YEAR(FROM_UNIXTIME(post_published_date))', $year));
			$criteria->add($criteriaYearMonth);
		}
		if ($post_id) {
			$criteria->add(new icms_db_criteria_Item('post_id', $post_id));
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
		$ret = $this->getPosts(0, 0, FALSE, FALSE, FALSE, FALSE, $post_id);
		return isset($ret[$post_id]) ? $ret[$post_id] : FALSE;
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
	function getPosts($start = 0, $limit = 0, $post_uid = FALSE, $cid = FALSE, $year = FALSE, $month = FALSE, $post_id = FALSE) {
		$criteria = $this->getPostsCriteria($start, $limit, $post_uid, $cid, $year, $month, $post_id);
		$ret = $this->getObjects($criteria, TRUE, FALSE);

		// retrieve the ids of all Posts retrieved
		$postIds = $this->getIdsFromObjectsAsArray($ret);

		// retrieve categories linked to these postIds
		$imtagging_category_link_handler = icms_getModuleHandler('category_link', 'imtagging');
		$categoriesObj = $imtagging_category_link_handler->getCategoriesFromObjectIds($postIds, $this);

		// put the category info in each postObj
		foreach ($categoriesObj as $categoryObj) {
			if (isset($categoryObj->items['imblogging']['post']))
			foreach ($categoryObj->items['imblogging']['post'] as $postid) {
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
		$member_handler = icms::handler('icms_member');
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
	function getPostsCount($post_uid, $cid = FALSE, $year = FALSE, $month = FALSE) {
		$criteria = $this->getPostsCriteria(FALSE, FALSE, $post_uid, $cid, $year, $month);
		return $this->getCount($criteria);
	}

	/**
	 * Get a count of posts for each month/year
	 *
	 * @return	array	An array of year/month post counts
	 */
	function getPostsCountByMonth() {
		$sql = 'SELECT count(post_id) AS posts_count, MONTH(FROM_UNIXTIME(post_published_date)) AS posts_month, YEAR(FROM_UNIXTIME(post_published_date)) AS posts_year'
			. ' FROM ' . $this->table
			. ' WHERE post_published_date <= ' . time()
			. ' GROUP BY posts_year, posts_month'
			. ' HAVING posts_count > 0'
			. ' ORDER BY posts_year DESC, posts_month DESC';
		$postsByMonthArray = $this->query($sql, FALSE);
		$ret = array();
		$config_handler =& icms::handler('config');
		$icmsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);
		foreach ($postsByMonthArray as $postByMonth) {
			$postByMonthnr = $postByMonth['posts_month'];
			$postByYearname = $postByMonth['posts_year'];
			$postByYearnr = $postByMonth['posts_year'];
			if (defined('_CALENDAR_TYPE') && _CALENDAR_TYPE == "jalali" && $icmsConfig['use_ext_date'] == 1) {
				include_once ICMS_ROOT_PATH . '/language/' . $icmsConfig['language'] . '/calendar.php';
				$gyear = $postByYearname;
				$gmonth = $postByMonthnr;
				list($jyear, $jmonth, $jday) = gregorian_to_jalali($gyear, $gmonth, '1');
				$postByYearname = icms_conv_nr2local($jyear);
				$postByYearnr = $jyear;
				$postByMonthnr = $jmonth;

			}
			$postByMonth['posts_year_nr'] = $postByYearnr;
			$postByMonth['posts_month_nr'] = $postByMonthnr;
			$postByMonth['posts_month_name'] = Icms_getMonthNameById($postByMonthnr);
			$postByMonth['posts_year_name'] = $postByYearname;
			$ret[] = $postByMonth;
		}
		return $ret;
	}

	/**
	 * Get Posts requested by the global search feature
	 *
	 * @param array $queryarray array containing the searched keywords
	 * @param bool $andor whether the keywords should be searched with AND or OR
	 * @param int $limit maximum results returned
	 * @param int $offset where to start in the resulting dataset
	 * @param int $userid should we return posts by specific poster ?
	 * @return array array of posts
	 */
	function getPostsForSearch($queryarray, $andor, $limit, $offset, $userid) {
		$criteria = new icms_db_criteria_Compo();

		$criteria->setStart($offset);
		$criteria->setLimit($limit);

		if ($userid != 0) {
			$criteria->add(new icms_db_criteria_Item('post_uid', $userid));
		}
		if ($queryarray) {
			$criteriaKeywords = new icms_db_criteria_Compo();
			for ($i = 0; $i < count($queryarray); $i++) {
				$criteriaKeyword = new icms_db_criteria_Compo();
				$criteriaKeyword->add(new icms_db_criteria_Item('post_title', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeyword->add(new icms_db_criteria_Item('post_content', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeywords->add($criteriaKeyword, $andor);
				unset($criteriaKeyword);
			}
			$criteria->add($criteriaKeywords);
		}
		$criteria->add(new icms_db_criteria_Item('post_status', IMBLOGGING_POST_STATUS_PUBLISHED));
		$criteria->add(new icms_db_criteria_Item('post_published_date', time(), '<='));
		$criteria->setSort('post_published_date');
		$criteria->setOrder('DESC');
		return $this->getObjects($criteria, TRUE, FALSE);
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
			$this->insert($postObj, TRUE);
		}
	}

	/**
	 * Check if the current user can submit a new post or not
	 *
	 * @return bool TRUE if he can FALSE if not
	 */
	function userCanSubmit() {
		global $imblogging_isAdmin;
		$imbloggingModuleConfig = icms_getModuleConfig(basename(dirname(dirname(__FILE__))));

		if (!is_object(icms::$user)) {
			return FALSE;
		}
		if ($imblogging_isAdmin) {
			return TRUE;
		}
		$user_groups = icms::$user->getGroups();
		return count(array_intersect($imbloggingModuleConfig['poster_groups'], $user_groups)) > 0;
	}

	/**
	 * BeforeSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework before the object is inserted or updated.
	 *
	 * @param object $obj ImbloggingPost object
	 * @return TRUE
	 */
	function beforeSave(& $obj) {
		if ($obj->updating_counter) return TRUE;

		$obj->setVar('dobr', $obj->need_do_br());
		return TRUE;
	}

	/**
	 * AfterSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework after the object is inserted or updated
	 *
	 * @param object $obj ImbloggingPost object
	 * @return TRUE
	 */
	function afterSave(&$obj) {
		if ($obj->updating_counter)	return TRUE;

		// storing categories
		$imtagging_category_link_handler = icms_getModuleHandler('category_link', 'imtagging');
		$imtagging_category_link_handler->storeCategoriesForObject($obj);

		if (!$obj->getVar('post_notification_sent') && $obj->getVar('post_status', 'e') == IMBLOGGING_POST_STATUS_PUBLISHED) {
			$obj->sendNotifPostPublished();
			$obj->setVar('post_notification_sent', TRUE);
			// too late - the record has already been saved! Save again? Effective, but clumsy
			$this->insert($obj);
		}
		return TRUE;
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
		$this->query($sql, NULL, TRUE);
	}

	/**
	 * Build an array containing all the ids of an array of objects as array
	 *
	 * @param array $objectsAsArray array of IcmsPersistableObject
	 */
	function getIdsFromObjectsAsArray($objectsAsArray) {
		$ret = array();
		foreach ($objectsAsArray as $array) {
			$ret[] = $array[$this->keyName];
		}
		return $ret;
	}
}
