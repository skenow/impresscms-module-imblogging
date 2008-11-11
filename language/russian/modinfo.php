<?php
/**
* Russian language constants related to module information
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$ Russian translation. Charset: utf-8 (without BOM)
*/

if (!defined("ICMS_ROOT_PATH")) die("Не определен корневой путь к ICMS");

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_IMBLOGGING_MD_NAME", "imBlogging");
define("_MI_IMBLOGGING_MD_DESC", "Простой модуль блогов для ImpressCMS");

define("_MI_IMBLOGGING_POSTS", "Сообщения");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "Разрешенные группы");
define("_MI_IMBLOGGING_POSTERGRDSC", "Выбор групп, которым разрешено создавать сообщения. Пожалуйста, обратите внимание, что пользователь, принадлежащий к одной из этих групп, может посылать на сайт сообщения напрямую. В настоящее время модуль не имеет свойства модерации.");
define("_MI_IMBLOGGING_LIMIT", "Ограничения сообщений");
define("_MI_IMBLOGGING_LIMITDSC", "Кол-во сообщений для показа пользователям.");

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "Новейшие сообщения");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Показать самые новые сообщения");
define("_MI_IMBLOGGING_POSTBYMONTH", "Сообщения по месяцам");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Показать список месяцев, в которых были сообщения");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Все сообщения");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Извещения, связанные со всеми сообщениями в модуле");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Новое сообщение опубликовано");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Известить меня, когда опубликовано новое сообщение");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Получить извещение, когда опубликовано новое сообщение.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} автоизвещение : Опубликовано новое сообщение");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Добавить сообщение");
?>