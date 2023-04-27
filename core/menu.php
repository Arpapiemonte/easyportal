<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

// I adjust the menu arrays based on what is allowed to see
foreach ($file_menus as $key => $value) {
	$value_new = "/" . str_replace($site_document_root, "", $value);
	$ok = is_path_allowed($value_new, $username, $log, $site_document_root, $dir_enabled, $reverse_proxy, $config_file);
	if (!$ok)
		$file_menus = array_diff($file_menus, [$value]);
}

foreach ($auth_menus as $key => $value) {
	$value_new = "/" . str_replace($site_document_root, "", $value);
	$ok = is_path_allowed($value_new, $username, $log, $site_document_root, $dir_enabled, $reverse_proxy, $config_file);
	if (!$ok)
		$auth_menus = array_diff($auth_menus, [$value]);
}

//$auth_menus[] = $site_document_root . "/auth_site/auth.menu";

// load tabs
require $site_document_root . "/core/load_tab.php";
// load navs
require $site_document_root . "/core/menu_nav.php";
// load menu side links
require $site_document_root . "/core/menu_side.php";

?>