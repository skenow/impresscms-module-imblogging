<?php
/**
* Persian language constants related to module information
*
* @copyright	http://smartfactory.ca The SmartFactory
* @copyright	http://www.impresscms.ir Official ImpressCMS support site for Persians
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_IMBLOGGING_MD_NAME", "وبلاگ");
define("_MI_IMBLOGGING_MD_DESC", "ماژولی برای وبلاگ نویسی در ایمپرس سی‌ام‌اس");

define("_MI_IMBLOGGING_POSTS", "نوشته‌ها");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "گروه‌های مجاز به نویسندگی");
define("_MI_IMBLOGGING_POSTERGRDSC", "گروه‌های مجاز به نوشتن مطالب را تعیین کنید. این ماژول در حال حاضر قابلیت مدیریتی ندارد.");
define("_MI_IMBLOGGING_LIMIT", "محدودیت پیام‌ها");
define("_MI_IMBLOGGING_LIMITDSC", "تعداد پیام‌های نمایان در قسمت کاربری.");

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "آخرین نوشته‌ها");
define("_MI_IMBLOGGING_POSTRECENTDSC", "نمایش آخرین پیام‌های ارسالی");
define("_MI_IMBLOGGING_POSTBYMONTH", "نوشته‌های ماهانه");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "نمایش فهرست ماه‌هایی که در آن‌ها پیامی نوشته و ارسال شده است");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "تمام پیام‌ها");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "آگاه سازی‌های متعلق به تمام مطالب این ماژول");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "پیام جدیدی منتشر شد");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "هرگاه مطلب جدید نوشته شد، مرا باخبر کن");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "هر مطلب جدیدی منتشر شد، مرا با خبر کن.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} آگاه سازی خودکار : نوشته‌ی جدیدی منتشر شد");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "پیام جدیدی را بنویسید");
?>