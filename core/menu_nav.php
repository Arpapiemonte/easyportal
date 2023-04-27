<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$login = false;
if (isset($_SESSION['username'])) {
	if (strlen($_SESSION['username']) > 0) {
		$login = true;
	}
}

$menu_navs_link = [];
$current_menu = "";

// search menu file in array
foreach ($file_menus as $file_menu => $file_menu_name) {
	if (file_exists($file_menu_name)) {
		$result_array = create_menu_nav(
			$menu_navs_link,
			$file_menu_name,
			$current_menu,
			$log,
			$request_without_reverse_proxy,
			$reverse_proxy
		);
		$menu_navs_link = $result_array[0];
		$current_menu = $result_array[1];
	}
}

if ($login) {
	foreach ($auth_menus as $file_menu => $file_menu_name) {

		if (file_exists($file_menu_name)) {
			$result_array = create_menu_nav(
				$menu_navs_link,
				$file_menu_name,
				$current_menu,
				$log,
				$request_without_reverse_proxy,
				$reverse_proxy
			);
			$menu_navs_link = $result_array[0];
			$current_menu = $result_array[1];
		}
	}
}

if ($log)
	error_log("menu nav exit with current_menu=" . $current_menu, 0);

?>