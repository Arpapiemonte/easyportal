<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

// adjust the tab arrays based on what is allowed to see

foreach ($file_tabs as $key => $value) {
	$value_new = "/" . str_replace($site_document_root, "", $value);
	$ok = is_path_allowed($value_new, $username, $log, $site_document_root, $dir_enabled, $reverse_proxy, $config_file);
	if (!$ok)
		$file_tabs = array_diff($file_tabs, array($value));
}

// array of tabs
$tab_links = array();

// from csv files .tab to array tab_links
// tab_group => array of tabs => array of links
foreach ($file_tabs as $tab_file_name) {
	// load csv file tab
	if (file_exists($tab_file_name)) {
		$csv = array_map('str_getcsv', file($tab_file_name));
		$header = array_shift($csv);
		foreach ($csv as $row) {
			if ($row[0] != null) {
				if (strlen($row[0]) > 0) {
					$var = explode(';', $row[0]);
					if (sizeof($var) == 4) {
						if (strlen($var[0]) > 0 && strlen($var[1]) > 0 && strlen($var[2]) > 0 && strlen($var[3]) > 0) {
							if (array_key_exists($var[0], $tab_links)) {
								if (array_key_exists($var[1], $tab_links[$var[0]])) {
									$tab_links[$var[0]][$var[1]][] = $var[2];
								} else {
									$tab_links[$var[0]] += array($var[1] => array($var[2]));
								}
							} else {
								$tab_links += array($var[0] => array($var[1] => array($var[2])));
								// memorize the menu item file.menu
								$tab_links[$var[0]] += array('menu' => array($var[3]));
							}
						}
					}
				}
			}
		}
	}
}
?>