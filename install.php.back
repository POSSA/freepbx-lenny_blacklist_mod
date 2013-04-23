<?php

//  This is a module template replace all text between <<    >> with appropriate values
//  the word <<module>> is used in place of the actual module short name

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

// Check FreePBX db engine
if($amp_conf["AMPDBENGINE"] != "mysql")  {
//  Could be used to throw a warning on install
	}


//  boilerplate for defining a new feature code
$fcc = new featurecode('<<module>>', '<<featurecode_name>>');
$fcc->setDescription('Type description');
$fcc->setDefault('<<featurecode digits>>');
$fcc->setProvideDest();
$fcc->update();
unset($fcc);

// boilerplate for creating a table
$sql = "CREATE TABLE IF NOT EXISTS <<tablename>> (
	<<column1>> INTEGER NOT NULL PRIMARY KEY $autoincrement,
	<<column2>> NOT NULL,
	
);";
$check = $db->query($sql);
if (DB::IsError($check)) {
        die_freepbx( "Can not create <<tablename>>` table: " . $check->getMessage() .  "\n");
}

// boilerplate to add a column to a table
$sql = "SELECT <<column3>> FROM <<tablename>>";
$check = $db->getRow($sql, DB_FETCHMODE_ASSOC);  //$check will error is the query is invalid
if (DB::IsError($check)) {
	// add new field
	$sql = "ALTER TABLE <<tablename>> ADD <<column3>> INTEGER NOT NULL DEFAULT 0;";
	$result = $db->query($sql);
	if(DB::IsError($result)) {
		die_freepbx($result->getMessage());
	}
}


?>
