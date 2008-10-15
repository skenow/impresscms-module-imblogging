<?php
/**
* English language constants related to module information
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_IMBLOGGING_MD_NAME", "imBlogging");
define("_MI_IMBLOGGING_MD_DESC", "ImpressCMS Simple Blogging module");

define("_MI_IMBLOGGING_POSTS", "Posts");

define("_MI_IMBLOGGING_BETA", "This module comes as is, without any guarantees whatsoever. This module is BETA, meaning it is still under active development. This release is meant for <b>testing purposes only</b> and we <b>strongly</b> recommend that you do not use it on a live website or in a production environment.");
define("_MI_IMBLOGGING_FINAL", "This module comes as is, without any guarantees whatsoever. Although this module is not beta, it is still under active development. This release can be used in a live website or a production environment, but its use is under your own responsibility, which means the author is not responsible.");
define("_MI_IMBLOGGING_RC", "This module comes as is, without any guarantees whatsoever. This module is a Release Candidate and should not be used on a production web site. The module is still under active development and its use is under your own responsibility, which means the author is not responsible.");


// Configs
define("_MI_IMBLOGGING_POSTERGR", "Groups allowed to posts");
define("_MI_IMBLOGGING_POSTERGRDSC", "Select the groups which are allowed to create new posts. Please note that a user belonging to one of these groups will be able to post directly on the site. The module currently has no moderation feature.");
define("_MI_IMBLOGGING_LIMIT", "Posts limit");
define("_MI_IMBLOGGING_LIMITDSC", "Number of posts to display on user side.");

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "Recent posts");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Display most recent posts");
define("_MI_IMBLOGGING_POSTBYMONTH", "Posts by month");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Display list of months in which there were posts");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "All posts");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Notifications related to all posts in the module");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "New post published");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Notify me when a new post is published");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Receive notification when any new post is published.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New post published");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Add a new post");
?>