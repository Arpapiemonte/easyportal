<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

if (env("LOG", false))
	error_log("menu side start with current_menu=" . $current_menu, 0);

// load link home, side menu and href and associate it with nav
// recreate menu_navs associated with =>array()

$menu_array = [];
$menu_side_link = [];
$menu_navs = [];
$current_group = "";
$search = [];

// search menu file in array
foreach ($file_menus as $file_menu => $file_menu_name) {
	if (file_exists($file_menu_name)) {
		$menu_navs = init_menu_side($menu_navs, $file_menu_name);
		$menu_navs = init_menu_group($menu_navs, $file_menu_name, false);
	}
}

// check if user is logged
$login = false;
if (isset($_SESSION['username'])) {
	if (strlen($_SESSION['username']) > 0) {
		$login = true;
	}
}

if ($login) {
	foreach ($auth_menus as $file_menu => $file_menu_name) {
		if (file_exists($file_menu_name)) {
			$menu_navs = init_menu_side($menu_navs, $file_menu_name);
			$menu_navs = init_menu_group($menu_navs, $file_menu_name, true);
		}
	}
}

//ksort($menu_navs);

foreach ($file_menus as $file_menu => $file_menu_name) {
	if (file_exists($file_menu_name)) {
		$menu_array = create_menu_side(
			$menu_navs,
			$file_menu_name,
			true,
			$current_menu,
			$current_group,
			$log,
			$request_without_reverse_proxy,
			$search,
			$reverse_proxy
		);
		$menu_navs = $menu_array[0];
		$current_menu = $menu_array[1];
		$current_group = $menu_array[2];
		$search = $menu_array[3];
	}
}

if ($login) {
	foreach ($auth_menus as $file_menu => $file_menu_name) {
		if (file_exists($file_menu_name)) {
			$menu_array = create_menu_side(
				$menu_navs,
				$file_menu_name,
				true,
				$current_menu,
				$current_group,
				$log,
				$request_without_reverse_proxy,
				$search,
				$reverse_proxy
			);
			$menu_navs = $menu_array[0];
			$current_menu = $menu_array[1];
			$current_group = $menu_array[2];
			$search = $menu_array[3];
		}
	}
}

// if current_menu still empty than search on path of nav menu home directory
foreach ($menu_navs as $nav_text => $menu_groups) {
	foreach ($menu_groups as $group_text => $menu_sides) {
		foreach ($menu_sides as $side_text => $side_link) {
			if (strlen($current_menu) == 0) {
				$my_request = $request_without_reverse_proxy;

				// remove basename from side_link and request_without_reverse_proxy
				$path_parts = pathinfo($side_link);

				if ($log) error_log("START side_link " . $side_link . " my_request " . $my_request);
				if ($log) error_log("path_parts dirname " . $path_parts['dirname']);
				$basename = $path_parts['basename'];
				if ($log) error_log("path_parts basename " . $basename);
				if (isset($path_parts['extension'])) {
					if ($log) error_log("path_parts extension " . $path_parts['extension'] . " remove " . $basename . " from " . $side_link);
					// remove basename
					$side_link = str_replace($basename, "", $side_link);
					if ($log) error_log("side_link " . $side_link);
				}
				if ($log) error_log("path_parts filename " . $path_parts['filename']);

				$path_parts = pathinfo($my_request);
				$basename = $path_parts['basename'];
				if (isset($path_parts['extension'])) {
					if ($log) error_log("path_parts extension " . $path_parts['extension'] . " remove " . $basename . " from " . $request_without_reverse_proxy);
					// remove basename
					$my_request = str_replace($basename, "", $my_request);
					if ($log) error_log("my_request " . $my_request);
				}

				// compare only they have the same size
				if ($my_request != null) {
					if (count(explode("/", $side_link)) === count(explode("/", $my_request))) {
						$pos = strpos($my_request, $side_link);
						if ($log) error_log("cerco " . $side_link . " in " . $my_request);
						if ($pos !== false) {
							if ($log) error_log("stringa trovata!");
							if ($pos >= 0) {
								if ($log) error_log("menu_nav.php ------ set current_menu " . $my_request . " " . $side_link);
								$current_menu = $nav_text;
							} else {
								if ($log) error_log("pos " . $pos);
							}
						} else {
							if ($log) error_log("stringa NON trovata!");
						}
					} else {
						if ($log) error_log("different size!");
					}
				} else {
					if ($log) error_log("my_request is null");
				}
			}
		}
	}
}

// if the current_menu hasn't been set yet look for it in the tabs
if (strlen($current_menu) == 0) {
	foreach ($tab_links as $tab_group => $array_of_tab) {
		foreach ($array_of_tab as $tab_text => $array_of_tab_link) {
			foreach ($array_of_tab_link as $tab_link) {
				if ($tab_text == 'menu')
					$menu_text = $tab_link;
				$tab_link_final = "";
				if (!preg_match('/http:\/\/|https:\/\//i', $tab_link)) {
					$tab_link_final = $tab_link;
				} else {
					$tab_link_final = "javascript:openUrlBlank('" . $tab_link . "')";
				}

				$request = $request_uri_without_reverse_proxy;

				if ($request == $tab_link_final) {
					$current_menu = $menu_text;
				}
			}
		}
	}
}

?>