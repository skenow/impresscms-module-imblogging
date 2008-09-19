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
define("_MI_IMBLOGGING_MD_DESC", "ImpressCMS - einfaches Blog Modul");

define("_MI_IMBLOGGING_POSTS", "Beiträge");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "Gruppen die Beiträge schreiben dürfen");
define("_MI_IMBLOGGING_POSTERGRDSC", "Wählen Sie die Gruppen aus, denen erlaubt ist neue Beiträge zu schreiben. Beachten Sie welche Mitglieder in welche Gruppe sich befinden! Das Modul verfügt derzeit über keine Moderatorenfunktion.");
define("_MI_IMBLOGGING_LIMIT", "Beiträge anzeigen");
define("_MI_IMBLOGGING_LIMITDSC", "Anzahl der Beiträge die in der Webseite angezeigt werden sollen.");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Alle Beiträge");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Notifications related to all posts in the module");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Neuer Beitrag veröffentlicht");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Mich benachrichtigen, wenn ein neuer Beitrag veröffentlicht wurde");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Receive notification when any new post is published.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New post published");
?>