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

// check form and define var for form action
isset($_REQUEST['action'])?$action = $_REQUEST['action']:$action='';


//if submitting form, update database
if(isset($_POST['action'])) {
	switch ($action) {
		case "submit":
			lenny_edit($_POST);
			needreload();
			redirect_standard();
		break;
	}
}

//  to add right navigation menu enclose output in <div class="rnav"> </div>
echo '<div class="rnav">';
echo "menu items";
echo '</div>';

echo "Lenny Blacklist Mod<p>";
echo "Redirect all blacklisted calls to the SIP URI lenny@itslenny.com<p>";
echo "this page will be a placeholder until I figure out html hooks for the blacklist page";
$config = lenny_config();



?>

