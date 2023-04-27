<?php

/**
 * Copyright (C) 2019-2022 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$ldap_server = "ldap";

function docker_ldap_login($username, $password, $ldap_server)
{
	$ldap_user = "CN=$username,DC=ldap";
	$ldap_pass = $password;

	try {
		$ad = ldap_connect($ldap_server);
		ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
		$bound = @ldap_bind($ad, $ldap_user, $ldap_pass);

	} catch (Exception $e) {
		return false;
	}

	return $bound;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
	if (!docker_ldap_login($_POST['username'], $_POST['password'], $ldap_server)) {
		$error_login = true;
		error_log("login_docker_ldap " . $_POST['username'] . " in on dc " . $ldap_server . " failed!");
		return;
	}

	$error_login = false;
	$_SESSION['username'] = $_POST['username'];
	error_log("login_docker_ldap " . $_POST['username'] . " logged in on dc " . $ldap_server);
	$my_path_allowed = get_paths($_POST['username'], $site_document_root, $log, $config_file);
	if (count($my_path_allowed) >= 2)
		$_SESSION['home_page'] = $my_path_allowed[1] . "home.php";
	else
		$_SESSION['home_page'] = $my_path_allowed[0] . "home.php";
}