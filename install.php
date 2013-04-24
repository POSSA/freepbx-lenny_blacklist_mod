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

// maybe not elegant but purging table from any previous install
$sql = "DROP TABLE IF EXISTS lenny";
$check = $db->query($sql);
if (DB::IsError($check))
{
	die_freepbx( "Can not delete table: " . $check->getMessage() .  "\n");
}


// create a new table
$sql = "CREATE TABLE IF NOT EXISTS lenny (
	id INTEGER NOT NULL PRIMARY KEY $autoincrement,
	enable VARCHAR(10) NOT NULL,
	record VARCHAR(10) NOT NULL,
	destination VARCHAR(100) NOT NULL	
);";
$check = $db->query($sql);
if (DB::IsError($check)) {
        die_freepbx( "Can not create table: " . $check->getMessage() .  "\n");
}

// populate new table with default values
$sql = "INSERT INTO lenny (id, enable, record, destination) VALUES (1 , 'CHECKED',  'CHECKED', 'lenny@itslenny.com')";
$check = $db->query($sql);
if (DB::IsError($check)) {
        die_freepbx( "Can not insert default values: " . $check->getMessage() .  "\n");
}
?>
