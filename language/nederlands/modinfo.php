<?php
/**
* Dutch language constants related to module information
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @translation    McDonald
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_IMBLOGGING_MD_NAME", "imBlogging");
define("_MI_IMBLOGGING_MD_DESC", "Eenvoudige Blog module voor ImpressCMS");

define("_MI_IMBLOGGING_POSTS", "Berichten");

define('_MI_IMBLOGGING_BETA', "Deze module komt zoals hij is, zonder welke garantie dan ook. De status van deze module is BETA, wat betekent dat hij nog steeds in actieve ontwikkeling is. Deze uitgave is alleen bedoelt voor <b>test doeleinden</b> en wij raden u <b>ten zeerste</b> aan dat deze module niet wordt gebruikt binnen een actieve/online website of in een productieve omgeving.");
define('_MI_IMBLOGGING_FINAL', "Deze module komt zoals hij is, zonder welke garantie dan ook. Ondanks dat de status van deze module geen beta is, is de module nog steeds in actieve ontwikkeling. Deze uitgave kan worden gebruikt in een actieve/online website of in een productieve omgeving, maar het gebruik valt volledig onder uw eigen verantwoordelijkheid, dit betekent dat de auteur niet verantwoordelijk is.");
define('_MI_IMBLOGGING_RC', "Deze module komt zoals hij is, zonder welke garantie dan ook. Deze module is een uitgave kandidaat en dient niet te worden gebruikt in een actieve/online website of in een productieve omgeving. De module is nog steeds in actieve ontwikkeling en het gebruik ervan valt onder uw eigen verantwoordelijkheid, dit betekent dat de auteur niet verantwoordelijk is.");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "Groeps toegestaan om te posten");
define("_MI_IMBLOGGING_POSTERGRDSC", "Selecteer de groepen die nieuwe berichten mogen inzenden. Gelieve op te merken op dat een gebruiker die tot één van deze groepen behoort direct op de plaats zal kunnen posten. De module heeft momenteel geen modificatie optie.");
define("_MI_IMBLOGGING_LIMIT", "Berichten limiet");
define("_MI_IMBLOGGING_LIMITDSC", "Aantal berichten weer te geven aan gebruikers zijde.");

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "Recente berichten");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Geef meest recente berichten weer");
define("_MI_IMBLOGGING_POSTBYMONTH", "Berichten per maand");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Geef lijst van maanden weer waarin geen berichten zijn");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Alle berichten");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Notificaties gerelateerd aan alle berichten in de module");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Nieuwe bericht gepubliceerd");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Breng me op de hoogte wanneer een nieuw berichten wordt gepubliceerd.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Ontvang bericht wanneer een nieuwe bericht wordt gepubliceerd.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-bericht : Nieuw berichten gepubliceerd");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Voeg nieuw bericht toe");
?>