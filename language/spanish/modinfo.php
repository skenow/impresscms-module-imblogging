<?php
/**
* * Spanish language constants used in admin section of the module (traducción por debianus)
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
define("_MI_IMBLOGGING_MD_DESC", "Módulo de creación de blogs para ImpressCMS");

define("_MI_IMBLOGGING_artículos", "Artículos");

define('_MI_IMBLOGGING_BETA', "Este módulo se proporciona como es y sin ninguna garantía. Es una versión BETA, lo que significa que todavía está en activo desarrollo y se proporciona <b>solo con la finalidad de testear</b>; le recomendamos  <b>encarecidamente</b> que no la use en un sitio web final.");

define('_MI_IMBLOGGING_RC', "Este módulo se proporciona como es y sin ninguna garantía. Este módulo es una 'Versión Candidata' y no debería ser usada en un sitio web final porque está todavía bajo desarrollo y si lo usa es bajo su propia responsabilidad, lo que significa que su autor no es responsable.");

define('_MI_IMBLOGGING_FINAL', "Este módulo se proporciona como es y sin ninguna garantía. Aunque no es una versión beta todavía está bajo desarrollo. Esta versión puede ser usada en un sitio web o entorno de producción pero bajo su propia responsabilidad; el autor no es responsable.");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "Grupos a los que se permite publicar artículos");
define("_MI_IMBLOGGING_POSTERGRDSC", "Seleccione los grupos de usuarios que pueden publicar nuevos artículos. Tenga en cuenta que un usuario perteneciente a cualquiera de los grupos que elija podrá publicar directamente en el sitio web y que el módulo actualmente no tiene la característica de moderar los envios..");
define("_MI_IMBLOGGING_LIMIT", "Límite de artículos");
define("_MI_IMBLOGGING_LIMITDSC", "Número de artículos que se mostrarán en el sitio.");

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "Artículos recientes");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Mostrar los artículos más recientes");
define("_MI_IMBLOGGING_POSTBYMONTH", "Artículos por mes");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Muestra la lista con los meses en los cuales se publicaron artículos");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Todos los artículos");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Notificaciones relacionadas con todos los artículos existentes en el módulo");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Nuevo artículo publicado");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Notificarme cuando un nuevo artículo es publicado");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Recibir notificación cuando cualquier nuevo artículo es publicado.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : Nuevo artículo publicado");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Añadir un nuevo artículo");
?>