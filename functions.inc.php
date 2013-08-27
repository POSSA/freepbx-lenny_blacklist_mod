<?php /* $Id */

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



//  check for settings and return
function lenny_config() {
	$sql = "SELECT * FROM lenny WHERE `id` = '1'";
	$results = sql($sql,"getAll",DB_FETCHMODE_ASSOC);
	return is_array($results)?$results:array();
}

// store settings
function lenny_edit($id,$post){
	global $db;

	$var1 = $db->escapeSimple($post['enable']);
	$var2 = $db->escapeSimple($post['record']);
	$var3 = $db->escapeSimple($post['destination']);

	$foo = lenny_config();
	
	// only trigger a reload if any changes are made that require a reload
	if ($foo[0]['enable']!=$var1 || $foo[0]['record']!=$var2 || $foo[0]['destination']!=$var3) {
		needreload();
	}

	$results = sql("
		UPDATE lenny 
		SET 
			enable = '$var1', 
			record = '$var2', 
			destination = '$var3'
		WHERE id = '$id'");
}

function lenny_hookGet_config($engine) {

	// This generates the dialplan
	global $ext;
	global $asterisk_conf;
	global $astman;
	switch($engine) {
		case "asterisk":
			$config = lenny_config();
			$context = "app-blacklist-check";
			$exten = "s";

			// need to splice dial plan differently if FreePBX version is earlier than 2.11
			$foo = version_compare (getversion(),"2.11.0");
			if ( $foo != -1) {
				// for FreePBX version 2.11 and higher
				if ($config[0]['enable']=='CHECKED' && $config[0]['record']=='CHECKED') {
					$ext->splice($context, $exten, "blacklisted", new ext_gosub('1', 's', 'sub-record-check', 'rg,s,always'),"",1);
					$ext->splice($context, $exten, "blacklisted", new ext_dial($config[0]['destination'],'60,rL,240000'),"",2);
					$ext->splice($context, $exten, "blacklisted", new ext_hangup,"",3);
				}
				else if ($config[0]['enable']=='CHECKED') {
					$ext->splice($context, $exten, "blacklisted", new ext_dial($config[0]['destination'],'60,rL,240000'),"",1);
					$ext->splice($context, $exten, "blacklisted", new ext_hangup,"",2);
				}
			}
			else {
				// for FreePBX versions < 2.11 splice function does not receive the offset variable so must test for both 
				// conditions of "enable block anonymous calls" and splice lenny dial plan in different place for each case.
				$bar = $astman->database_get("blacklist","blocked");
				if ($bar == 1) {
					$splice_position = 8;
				}
				else {
					$splice_position = 4;
				}
				if ($config[0]['enable']=='CHECKED' && $config[0]['record']=='CHECKED') {
					$ext->splice($context, $exten, $splice_position, new ext_gosub('1', 's', 'sub-record-check', 'rg,s,always'));
					$ext->splice($context, $exten, ($splice_position+1), new ext_dial($config[0]['destination'],'60,rL,240000'));
					$ext->splice($context, $exten, ($splice_position+2), new ext_hangup);
				}
				else if ($config[0]['enable']=='CHECKED') {
					$ext->splice($context, $exten, $splice_position, new ext_dial($config[0]['destination'],'60,rL,240000'));
					$ext->splice($context, $exten, ($splice_position+1), new ext_hangup);
				}

			}
		break;
	}
}

/*** *** *** reports of this code not working properly in FreePBX 2.10 *** ***
function lenny_hook_blacklist() {
        $lenconfig = lenny_config();

        //if submitting form, update database
        if(isset($_POST['submit'])) {
                lenny_edit(1,$_POST);
        }
        $html = "<table>";
        $html .= "<tr><td colspan='2'><h5><a href='#' class='info'>Lenny Blacklist Mod Config<span>This is used to modify the FreePBX blacklist module so that blacklisted callers are automatically redirected to SIP/lenny@sip.itslenny.com:5060 or another user specified destination.</span></a><hr></h5></td></tr>";
        $html .= "<tr>";
        $html .= "<td><a href='#' class='info'>Enable redirect<span>If this is disabled, the blacklist reverts to default behavior. Clicking this box certifies compliance with the Terms of Service of the receiving destination.</span></a></td>";
        $html .= "<td><input type='checkbox' name='enable' value='CHECKED' ".$lenconfig[0]['enable']."></td>";
        $html .= "</tr><tr>";
        $html .= "<td><a href='#' class='info'>Enable Recording<span>If enabled, the call is recorded locally</span></a></td>";
        $html .= "<td><input type='checkbox' name='record' value='CHECKED' ".$lenconfig[0]['record']."></td>";
        $html .= "</tr><tr>";
        $html .= "<td><a href='#' class='info'>Destination<span>SIP/URI destination to send blacklisted caller in the format SIP/xxx@domain.com:port</span></a></td>";
        $html .= "<td><input type='text' name='destination' size=40 value='".htmlspecialchars(isset($lenconfig[0]['destination']) ? $lenconfig[0]['destination'] : '')."' ></td>";
        $html .= "</tr></table>";

        return $html;
}
*** *** *** *** *** *** *** ***/
		
function lenny_vercheck() {
	$newver = false;
	if ( function_exists(lenny_xml2array)){
		$module_local = lenny_xml2array("modules/lenny/module.xml");
		$module_remote = lenny_xml2array("https://raw.github.com/POSSA/freepbx-lenny_blacklist_mod/master/module.xml");
		
		if ( $module_remote[module][version] > $module_local[module][version])
			{
			$newver = true;
			}
		return ($newver);
		}
	}

//Parse XML file into an array
function lenny_xml2array($url, $get_attributes = 1, $priority = 'tag')  {
	$contents = "";
	if (!function_exists('xml_parser_create'))
	{
		return array ();
	}
	$parser = xml_parser_create('');
	if(!($fp = @ fopen($url, 'rb')))
	{
		return array ();
	}
	while(!feof($fp))
	{
		$contents .= fread($fp, 8192);
	}
	fclose($fp);
	xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);
	if(!$xml_values)
	{
		return; //Hmm...
	}
	$xml_array = array ();
	$parents = array ();
	$opened_tags = array ();
	$arr = array ();
	$current = & $xml_array;
	$repeated_tag_index = array ();
	foreach ($xml_values as $data)
	{
		unset ($attributes, $value);
		extract($data);
		$result = array ();
		$attributes_data = array ();
		if (isset ($value))
		{
			if($priority == 'tag')
			{
				$result = $value;
			}
			else
			{
				$result['value'] = $value;
			}
		}
		if(isset($attributes) and $get_attributes)
		{
			foreach($attributes as $attr => $val)
			{
				if($priority == 'tag')
				{
					$attributes_data[$attr] = $val;
				}
				else
				{
					$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}
		}
		if ($type == "open")
		{
			$parent[$level -1] = & $current;
			if(!is_array($current) or (!in_array($tag, array_keys($current))))
			{
				$current[$tag] = $result;
				if($attributes_data)
				{
					$current[$tag . '_attr'] = $attributes_data;
				}
				$repeated_tag_index[$tag . '_' . $level] = 1;
				$current = & $current[$tag];
			}
			else
			{
				if (isset ($current[$tag][0]))
				{
					$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
					$repeated_tag_index[$tag . '_' . $level]++;
				}
				else
				{
					$current[$tag] = array($current[$tag],$result);
					$repeated_tag_index[$tag . '_' . $level] = 2;
					if(isset($current[$tag . '_attr']))
					{
						$current[$tag]['0_attr'] = $current[$tag . '_attr'];
						unset ($current[$tag . '_attr']);
					}
				}
				$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
				$current = & $current[$tag][$last_item_index];
			}
		}
		else if($type == "complete")
		{
			if(!isset ($current[$tag]))
			{
				$current[$tag] = $result;
				$repeated_tag_index[$tag . '_' . $level] = 1;
				if($priority == 'tag' and $attributes_data)
				{
					$current[$tag . '_attr'] = $attributes_data;
				}
			}
			else
			{
				if (isset ($current[$tag][0]) and is_array($current[$tag]))
				{
					$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
					if ($priority == 'tag' and $get_attributes and $attributes_data)
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
					}
					$repeated_tag_index[$tag . '_' . $level]++;
				}
				else
				{
					$current[$tag] = array($current[$tag],$result);
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' and $get_attributes)
					{
						if (isset ($current[$tag . '_attr']))
						{
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset ($current[$tag . '_attr']);
						}
						if ($attributes_data)
						{
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
					}
					$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
				}
			}
		}
		else if($type == 'close')
		{
			$current = & $parent[$level -1];
		}
	}
	return ($xml_array);
}