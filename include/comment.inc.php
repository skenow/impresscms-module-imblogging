<?php

/**
 * Comment include file
 *
 * File holding functions used by the module to hook with the comment system of ImpressCMS
 *
 * @copyright http://smartfactory.ca The SmartFactory
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since 1.0
 * @author marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * @version $Id$
 */
function imblogging_com_update($item_id, $total_num) {
	$imblogging_post_handler = icms_getModuleHandler('post', $moddir, 'imblogging');
	$imblogging_post_handler->updateComments($item_id, $total_num);
}

function imblogging_com_approve(&$comment) {
	// notification mail here
}
