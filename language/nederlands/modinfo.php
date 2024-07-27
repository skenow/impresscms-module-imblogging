<?php
/**
 * Dutch language constants related to module information
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @translation    McDonald
 *
 */
if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

define("_MI_IMBLOGGING_MD_NAME", "imBlogging");
define("_MI_IMBLOGGING_MD_DESC", "Eenvoudige Blog module voor ImpressCMS");

define("_MI_IMBLOGGING_POSTS", "Berichten");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "Groeps toegestaan om te posten");
define("_MI_IMBLOGGING_POSTERGRDSC", "Selecteer de groepen die nieuwe berichten mogen inzenden. Gelieve op te merken op dat een gebruiker die tot één van deze groepen behoort direct op de plaats zal kunnen posten. De module heeft momenteel geen modificatie optie.");
define("_MI_IMBLOGGING_LIMIT", "Berichten limiet");
define("_MI_IMBLOGGING_LIMITDSC", "Aantal berichten weer te geven aan gebruikers zijde.");
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
define("_MI_IMBLOGGING_POSTRECENT", "Recente berichten");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Geef meest recente berichten weer");
define("_MI_IMBLOGGING_POSTBYMONTH", "Berichten per maand");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Geef lijst van maanden weer waarin geen berichten zijn");
define("_MI_IMBLOGGING_POSTSPOTLIGHT", "Recent posts with spotlight");
define("_MI_IMBLOGGING_POSTSPOTLIGHTDSC", "Display most recent posts and spotlight the first post");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Alle berichten");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Notificaties gerelateerd aan alle berichten in de module");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Nieuwe bericht gepubliceerd");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Breng me op de hoogte wanneer een nieuw berichten wordt gepubliceerd.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Ontvang bericht wanneer een nieuwe bericht wordt gepubliceerd.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-bericht : Nieuw berichten gepubliceerd");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Voeg nieuw bericht toe");
