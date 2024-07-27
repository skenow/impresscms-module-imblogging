<?php
/**
 * Persian language constants related to module information
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @copyright http://www.impresscms.ir Official ImpressCMS support site for Persians
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 *
 */
if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

define("_MI_IMBLOGGING_MD_NAME", "وبلاگ");
define("_MI_IMBLOGGING_MD_DESC", "ماژولی برای وبلاگ نویسی در ایمپرس سی‌ام‌اس");

define("_MI_IMBLOGGING_POSTS", "نوشته‌ها");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "گروه‌های مجاز به نویسندگی");
define("_MI_IMBLOGGING_POSTERGRDSC", "گروه‌های مجاز به نوشتن مطالب را تعیین کنید. این ماژول در حال حاضر قابلیت مدیریتی ندارد.");
define("_MI_IMBLOGGING_LIMIT", "محدودیت پیام‌ها");
define("_MI_IMBLOGGING_LIMITDSC", "تعداد پیام‌های نمایان در قسمت کاربری.");
define('_MI_IMBLOGGING_DEF_VIEW_PERM', 'Default View Permissions');
define('_MI_IMBLOGGING_DEF_VIEW_PERM_DSC', 'By default, Who can read posts. Individual posts can be adjusted');

// For IPF Metagen
define('_MI_IMBLOGGING_MODNAME_BREADCRUMB', 'Include Module Name in Breadcrumb and Title');
define('_MI_IMBLOGGING_MODNAME_BREADCRUMB_DSC', 'Do you want the module name included as part of the paget title?');
define('_MI_IMBLOGGING_METADESC', "Module's Meta Description");
define('_MI_IMBLOGGING_METADESC_DSC', 'The meta description to be used for the main page of the module');
define('_MI_IMBLOGGING_KEYWORDS', 'Default keywords for the module');
define('_MI_IMBLOGGING_KEYWORDS_DSC', 'The meta keywords to be used for the main page of the module');

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "آخرین نوشته‌ها");
define("_MI_IMBLOGGING_POSTRECENTDSC", "نمایش آخرین پیام‌های ارسالی");
define("_MI_IMBLOGGING_POSTBYMONTH", "نوشته‌های ماهانه");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Display list of months in which there were posts");
define("_MI_IMBLOGGING_POSTSPOTLIGHT", "Recent posts with spotlight");
define("_MI_IMBLOGGING_POSTSPOTLIGHTDSC", "Display most recent posts and spotlight the first post");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "تمام پیام‌ها");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "آگاه سازی‌های متعلق به تمام مطالب این ماژول");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "پیام جدیدی منتشر شد");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "هرگاه مطلب جدید نوشته شد، مرا باخبر کن");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "هر مطلب جدیدی منتشر شد، مرا با خبر کن.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} آگاه سازی خودکار : نوشته‌ی جدیدی منتشر شد");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "پیام جدیدی را بنویسید");
