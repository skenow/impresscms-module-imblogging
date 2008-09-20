<?php
/**
* Recent posts block file
*
* This file holds the functions needed for the recent posts block
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function imblogging_post_recent_show($options)
{
	include_once(ICMS_ROOT_PATH . '/modules/imblogging/include/common.php');
	$imblogging_post_handler = xoops_getModuleHandler('post', 'imblogging');
	$block['posts'] = $imblogging_post_handler->getPosts();

	return $block;
}

function imblogging_post_recent_edit($options)
{
	include_once(XOOPS_ROOT_PATH."/modules/smartsection/include/functions.php");

	$form .= '
	<table>
		<tr>
			<td style="vertical-align: top; width: 150px;">' . _MB_SSECTION_SELECTCAT . '</td>';
	$form .= '<td>';
	$form .= smartsection_createCategorySelect($options[0]) . '</td>';

    $form .= "<tr><td>" . _MB_SSECTION_ORDER . "</td>";
    $form .= "<td><select name='options[]'>";

    $form .= "<option value='datesub'";
    if ($options[1] == "datesub") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_SSECTION_DATE . "</option>";

    $form .= "<option value='counter'";
    if ($options[1] == "counter") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_SSECTION_HITS . "</option>";

    $form .= "<option value='weight'";
    if ($options[1] == "weight") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_SSECTION_WEIGHT . "</option>";

    $form .= "</select></td>";

    $form .= "</tr><tr><td>" . _MB_SSECTION_DISP . "</td><td><input type='text' name='options[]' value='" . $options[2] . "' />&nbsp;" . _MB_SSECTION_ITEMS . "</td></tr>";
    $form .= "<tr><td>" . _MB_SSECTION_CHARS . "</td><td><input type='text' name='options[]' value='" . $options[3] . "' />&nbsp;chars</td></tr>";
	$form .= "</table>";
    return $form;
	}

?>