<?php 

if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
//This file is part of FreePBX.
//
//    This is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 2 of the License, or
//    (at your option) any later version.
//
//    This module is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    see <http://www.gnu.org/licenses/>.
//

// check to see if user has automatic updates enabled in FreePBX settings
$cm =& cronmanager::create($db);
$online_updates = $cm->updates_enabled() ? true : false;

// check dev site to see if new version of module is available
if ($online_updates && $foo = lenny_vercheck()) {
	print "<br>A <b>new version of this module is available</b> from the <a target='_blank' href='http://pbxossa.org'>PBX Open Source Software Alliance</a><br>";
}

$module_local = lenny_xml2array("modules/lenny/module.xml");


// check form and define var for form action
isset($_REQUEST['action'])?$action = $_REQUEST['action']:$action='';

//if submitting form, update database
if(isset($_POST['submit'])) {
		lenny_edit(1,$_POST);
//		needreload();
		redirect_standard();
	
	}


//  to add right navigation menu enclose output in <div class="rnav"> </div>
/* echo '<div class="rnav">';
echo "menu items";
echo '</div>';
*/
$config = lenny_config();

?>

<h2>Lenny Blacklist Mod</h2>

<form autocomplete="off" name="edit" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
<table>
		<tr>			
			<td colspan="2">			
			    <?php echo _('This module is used to modify the standard FreePBX blacklist so that banned callers are redirected to a user specified SIP/URI.'); ?>
			</td>			
		</tr>
</table>
<?php

if (function_exists(lenny_hook_blacklist)) {
	echo $foo=lenny_hook_blacklist();
} else {

        $lenconfig = lenny_config();

        //if submitting form, update database
        if(isset($_POST['submit'])) {
                lenny_edit(1,$_POST);
        }
        $html = "<table>";
        $html .= "<tr><td colspan='2'><h5><a href='#' class='info'>Lenny Blacklist Mod Config<span>This is used to modify the FreePBX blacklist module so that blacklisted callers are automatically redirected to user specified SIP/URI or any other user specified destination.</span></a><hr></h5></td></tr>";
        $html .= "<tr>";
        $html .= "<td><a href='#' class='info'>Enable redirect<span>If this is disabled, the blacklist reverts to default behavior. Clicking this box certifies compliance with the Terms of Service of the receiving destination.</span></a></td>";
        $html .= "<td><input type='checkbox' name='enable' value='CHECKED' ".$lenconfig[0]['enable']."></td>";
        $html .= "</tr><tr>";
        $html .= "<td><a href='#' class='info'>Enable Recording<span>If enabled, the call is recorded locally, accessible from the CDR and the User ARI</span></a></td>";
        $html .= "<td><input type='checkbox' name='record' value='CHECKED' ".$lenconfig[0]['record']."></td>";
        $html .= "</tr><tr>";
        $html .= "<td><a href='#' class='info'>Destination<span>SIP/URI destination to send blacklisted caller in the format \"SIP/xxx@domain.com:port\". Alternatively, this can be in the format \"local/456@from-internal\" (without quotes)</span></a></td>";
        $html .= "<td><input type='text' name='destination' size=40 value='".htmlspecialchars(isset($lenconfig[0]['destination']) ? $lenconfig[0]['destination'] : '')."' ></td>";
        $html .= "</tr></table>";
	echo $html;
}
?>
<table>
	<tr>
		<td colspan="2"><br><h6><input name="submit" type="submit" value="<?php echo _("Submit Changes")?>" ></h6></td>
	</tr>
</table>
</form>
<center><br>

<?php
echo '<p align="center" style="font-size:11px;">This module is maintained by the developer community at the <a target="_blank" href="http://pbxossa.org">PBX Open Source Software Alliance</a>. Support, documentation and current versions are available at the module <a target="_blank" href="https://github.com/POSSA/freepbx-lenny_blacklist_mod">dev site</a>.';
echo '<p align="center" style="font-size:11px;">Lenny Blacklist Mod version: '.$module_local[module][version].'</center>';
?>
