<?php
/**
 * English language constants related to module information
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 *
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

// For IPF Metagen
define('_MI_IMBLOGGING_MODNAME_BREADCRUMB', 'Include Module Name in Breadcrumb and Title');
define('_MI_IMBLOGGING_MODNAME_BREADCRUMB_DSC', 'Do you want the module name included as part of the paget title?');
define('_MI_IMBLOGGING_METADESC', "Module's Meta Description");
define('_MI_IMBLOGGING_METADESC_DSC', 'The meta description to be used for the main page of the module');
define('_MI_IMBLOGGING_KEYWORDS', 'Default keywords for the module');
define('_MI_IMBLOGGING_KEYWORDS_DSC', 'The meta keywords to be used for the main page of the module');

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "Neue Beiträge");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Zeigt die neuesten Beiträge an");
define("_MI_IMBLOGGING_POSTBYMONTH", "Beitrag nach Monat");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Zeigt eine Monatsliste in denen es Beiträge gab");
define("_MI_IMBLOGGING_POSTSPOTLIGHT", "Neueste Einträge im Rampenlicht");
define("_MI_IMBLOGGING_POSTSPOTLIGHTDSC", "Anzeigen der neuesten Beiträge im Rampenlicht nach den neusten sortiert.");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Alle Beiträge");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Benachrichtigungen im Zusammenhang mit allen Beiträgen");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Neuer Beitrag veröffentlicht");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Mich benachrichtigen, wenn ein neuer Beitrag veröffentlicht wurde");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Benachrichtigung erhalten, wenn ein Beitrag veröffentlicht wurde.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : Neuer Beitrag veröffentlicht");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Beitrag hinzufügen");
