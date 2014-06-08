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

global $icmsModule;
define("_MI_IMBLOGGING_MD_NAME", "Blog");
define("_MI_IMBLOGGING_MD_DESC", "ImpressCMS - einfaches Blog Modul");

define("_MI_IMBLOGGING_POSTS", "Beiträge");


// Configs
define("_MI_IMBLOGGING_POSTERGR", "Gruppen die Beiträge schreiben dürfen");
define("_MI_IMBLOGGING_POSTERGRDSC", "Wählen Sie die Gruppen aus, denen erlaubt ist neue Beiträge zu schreiben. Beachten Sie welche Mitglieder in welche Gruppe sich befinden! Das Modul verfügt derzeit über keine Moderatorenfunktion.");
define("_MI_IMBLOGGING_LIMIT", "Beiträge anzeigen");
define("_MI_IMBLOGGING_LIMITDSC", "Anzahl der Beiträge die in der Webseite angezeigt werden sollen.");

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "Neue Beiträge");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Zeigt die neuesten Beiträge an");
define("_MI_IMBLOGGING_POSTBYMONTH", "Beitrag nach Monat");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Zeigt eine Monatsliste in denen es Beiträge gab");
define("_MI_IMBLOGGING_POSTSPOTLIGHT", "Recent posts with spotlight");
define("_MI_IMBLOGGING_POSTSPOTLIGHTDSC", "Display most recent posts and spotlight the first post");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Alle Beiträge");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Benachrichtigungen im Zusammenhang mit allen Beiträgen");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Neuer Beitrag veröffentlicht");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Mich benachrichtigen, wenn ein neuer Beitrag veröffentlicht wurde");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Benachrichtigung erhalten, wenn ein Beitrag veröffentlicht wurde.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : Neuer Beitrag veröffentlicht");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Beitrag hinzufügen");
