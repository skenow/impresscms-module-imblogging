<?php
/**
*  Portuguese language constants related to module information
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
* @translation        GibaPhp - http://br.impresscms.org 
*/

if (!defined("ICMS_ROOT_PATH")) die("O caminho para o raiz do site não foi definido");

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_IMBLOGGING_MD_NAME", "imBlogging");
define("_MI_IMBLOGGING_MD_DESC", "Módulo simples de Blog para o ImpressCMS");

define("_MI_IMBLOGGING_POSTS", "Blogs");

// Configs
define("_MI_IMBLOGGING_POSTERGR", "Grupos permitidos para enviar Blogs");
define("_MI_IMBLOGGING_POSTERGRDSC", "Selecione os grupos que têm permissão para criar novos Blogs. Observe que um usuário que pertença a um destes grupos será capaz de enviar diretamente um Blog para o site. O módulo atualmente ainda não tem nenhum recurso moderação.");
define("_MI_IMBLOGGING_LIMIT", "Limite de Blogs");
define("_MI_IMBLOGGING_LIMITDSC", "Número de Blogs visualizados na área de usuário.");

// Blocks
define("_MI_IMBLOGGING_POSTRECENT", "Blogs Recentes");
define("_MI_IMBLOGGING_POSTRECENTDSC", "Mostrar Blogs Recentes");
define("_MI_IMBLOGGING_POSTBYMONTH", "Blogs por Mês");
define("_MI_IMBLOGGING_POSTBYMONTHDSC", "Mostrar lista dos meses dos Blogs recebidos");

// Notifications
define("_MI_IMBLOGGING_GLOBAL_NOTIFY", "Geral");
define("_MI_IMBLOGGING_GLOBAL_NOTIFY_DSC", "Notificações sobre todos os Blogs do módulo");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY", "Novo Blog publicado");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_CAP", "Avise-me quando um novo Blog for publicado");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_DSC", "Receber notificações quando algum novo Blog for publicado.");
define("_MI_IMBLOGGING_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} Aviso-Automático : Novo Blog publicado");

// Submit button
define("_MI_IMBLOGGING_POST_ADD", "Incluir novo Blog");
?>