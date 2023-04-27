<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

if (isset($_SESSION['username'])) {

	$user_session_groups = get_groups($_SESSION['username'], $site_document_root, $config_file);

	if (in_array("admins", $user_session_groups) || is_admin($_SESSION['username'], $site_document_root, $config_file)) {

		$users = [];
		$groups = [];
		$paths = [];
		// association between group and user each group is a key and contains array of user
		// this array is saved encoded as group_users.config
		$group_users = [];
		// association between group and path each group is a key and contains array of path
		// this array is saved encoded as group_paths.config
		$group_paths = [];

		// erase session data and reload from file
		$reset = false;
		// load rom file if session data are empty
		$load_from_file = false;

		// the local array are saved in session
		if (!isset($_SESSION['admin_i_groups']) && !isset($_SESSION['admin_i_users']) && !isset($_SESSION['admin_i_paths']) && !isset($_SESSION['admin_i_group_users']) && !isset($_SESSION['admin_i_group_paths'])) {
			$load_from_file = true;
		}

		if (isset($_GET['reset'])) {
			if (strlen(trim($_GET['reset'])) > 0) {
				if ($_GET['reset'] == "true") {
					unset($_SESSION['admin_i_groups']);
					unset($_SESSION['admin_i_users']);
					unset($_SESSION['admin_i_paths']);
					unset($_SESSION['admin_i_groups_users']);
					unset($_SESSION['admin_i_groups_paths']);
					$reset = true;
					$load_from_file = true;
				}
			}
		}

		if ($load_from_file) {
			if (
				file_exists($site_document_root . "/" . $config_file . "/group_users.config") &&
				file_exists($site_document_root . "/" . $config_file . "/group_paths.config")
			) {
				$string_data = file_get_contents($site_document_root . "/" . $config_file . "/group_users.config");
				$group_users = unserialize($string_data);
				$group_users = decode_array($group_users);
				$string_data = file_get_contents($site_document_root . "/" . $config_file . "/group_paths.config");
				$group_paths = unserialize($string_data);
				$group_paths = decode_array($group_paths);
				foreach ($group_users as $key => $user_name) {
					$groups[] = $key;
				}
				foreach ($group_users as $key_group => $user_name_array) {
					foreach ($user_name_array as $key_user => $user_name) {
						$users[] = $user_name;
					}
				}
				foreach ($group_paths as $key_group => $path_name_array) {
					foreach ($path_name_array as $key_user => $path_name) {
						$paths[] = $path_name;
					}
				}
				$users = array_unique($users);
				$groups = array_unique($groups);
				$paths = array_unique($paths);
			}
		}

		// get array from SESSION to REQUEST data
		if (!$reset) {
			if (isset($_SESSION['admin_i_groups'])) {
				if (is_array($_SESSION['admin_i_groups']) > 0) {
					$groups = $_SESSION['admin_i_groups'];
				}
			}
			if (isset($_SESSION['admin_i_users'])) {
				if (is_array($_SESSION['admin_i_users']) > 0) {
					$users = $_SESSION['admin_i_users'];
				}
			}
			if (isset($_SESSION['admin_i_paths'])) {
				if (is_array($_SESSION['admin_i_paths']) > 0) {
					$paths = $_SESSION['admin_i_paths'];
				}
			}
			if (isset($_SESSION['admin_i_groups_users'])) {
				if (is_array($_SESSION['admin_i_groups_users']) > 0) {
					$group_users = $_SESSION['admin_i_groups_users'];
				}
			}
			if (isset($_SESSION['admin_i_groups_paths'])) {
				if (is_array($_SESSION['admin_i_groups_paths']) > 0) {
					$group_paths = $_SESSION['admin_i_groups_paths'];
				}
			}
		}

		// remove user from group from GET parameter
		if (isset($_GET['remove_user_from_group']) && isset($_GET['username_to_remove'])) {
			if (strlen(trim($_GET['remove_user_from_group'])) > 0 && strlen(trim($_GET['username_to_remove'])) > 0) {
				if (array_key_exists($_GET['remove_user_from_group'], $group_users)) {
					if (in_array($_GET['username_to_remove'], $group_users[$_GET['remove_user_from_group']])) {
						// removes the user from the admins group if he is not the only user in the admins group
						if ($_GET['remove_user_from_group'] == "admins") {
							$new_group_user = array_diff($group_users[$_GET['remove_user_from_group']], [$_GET['username_to_remove']]);
							if (count($new_group_user) > 0)
								$group_users[$_GET['remove_user_from_group']] = array_diff($group_users[$_GET['remove_user_from_group']], [$_GET['username_to_remove']]);
						} else {
							$group_users[$_GET['remove_user_from_group']] = array_diff($group_users[$_GET['remove_user_from_group']], [$_GET['username_to_remove']]);
						}
					}
				}
			}
		}

		// remove path from group from GET parameter
		if (isset($_GET['remove_path_from_group']) && isset($_GET['path_to_remove'])) {
			if (strlen(trim($_GET['remove_path_from_group'])) > 0 && strlen(trim($_GET['path_to_remove'])) > 0) {
				$path_to_remove = $_GET['path_to_remove'];
				$path_to_remove = str_replace("<IGNORE_REPLACE>", "", $path_to_remove);
				$path_to_remove = str_replace("</IGNORE_REPLACE>", "", $path_to_remove);
				if (array_key_exists($_GET['remove_path_from_group'], $group_paths)) {
					if (in_array($path_to_remove, $group_paths[$_GET['remove_path_from_group']])) {
						$group_paths[$_GET['remove_path_from_group']] = array_diff($group_paths[$_GET['remove_path_from_group']], [$path_to_remove]);
					}
				}
			}
		}

		// delete group from path
		if (isset($_GET['remove_group_from_path'])) {
			if (strlen(trim($_GET['remove_group_from_path'])) > 0) {
				if (array_key_exists($_GET['remove_group_from_path'], $group_paths)) {
					if (count($group_paths[$_GET['remove_group_from_path']]) == 0) {
						unset($group_paths[$_GET['remove_group_from_path']]);
					}
				}
			}
		}

		// delete group
		if (isset($_GET['remove_group'])) {
			if (strlen(trim($_GET['remove_group'])) > 0) {
				if (!array_key_exists($_GET['remove_group'], $group_paths)) {
					if (in_array($_GET['remove_group'], $groups)) {
						if (array_key_exists($_GET['remove_group'], $group_users)) {
							if (count($group_users[$_GET['remove_group']]) == 0) {
								unset($group_users[$_GET['remove_group']]);
								foreach ($groups as $key => $group_name) {
									if ($group_name == $_GET['remove_group']) {
										$groups = array_diff($groups, [$group_name]);
									}
								}
							}
						}
					}
				}
			}
		}

		// insert new POSTED data
		if (isset($_POST['new_user'])) {
			if (strlen(trim($_POST['new_user'])) > 0) {
				if (!in_array($_POST['new_user'], $users))
					$users[] = $_POST['new_user'];
			}
		}

		if (isset($_POST['new_group'])) {
			if (strlen(trim($_POST['new_group'])) > 0) {
				if (!in_array($_POST['new_group'], $groups))
					$groups[] = $_POST['new_group'];
			}
		}

		if (isset($_POST['new_path'])) {
			if (strlen(trim($_POST['new_path'])) > 0) {
				if (!in_array($_POST['new_path'], $paths))
					$paths[] = $_POST['new_path'];
			}
		}

		if (isset($_POST['SelectGroup']) && isset($_POST['SelectUser'])) {
			if (strlen(trim($_POST['SelectGroup'])) > 0 && strlen(trim($_POST['SelectUser'])) > 0) {
				if (array_key_exists($_POST['SelectGroup'], $group_users)) {
					if (!in_array($_POST['SelectUser'], $group_users[$_POST['SelectGroup']])) {
						array_push($group_users[$_POST['SelectGroup']], $_POST['SelectUser']);
					}
				} else {
					$group_users += [$_POST['SelectGroup'] => []];
					array_push($group_users[$_POST['SelectGroup']], $_POST['SelectUser']);
				}
			}
		}

		if (isset($_POST['SelectGroupPath']) && isset($_POST['SelectPath'])) {
			if (strlen(trim($_POST['SelectGroupPath'])) > 0 && strlen(trim($_POST['SelectPath'])) > 0) {
				if (array_key_exists($_POST['SelectGroupPath'], $group_paths)) {
					if (!in_array($_POST['SelectPath'], $group_paths[$_POST['SelectGroupPath']])) {
						array_push($group_paths[$_POST['SelectGroupPath']], $_POST['SelectPath']);
					}
				} else {
					$group_paths += [$_POST['SelectGroupPath'] => []];
					array_push($group_paths[$_POST['SelectGroupPath']], $_POST['SelectPath']);
				}
			}
		}

		// save local dat in session data
		$_SESSION['admin_i_users'] = $users;
		$_SESSION['admin_i_groups'] = $groups;
		$_SESSION['admin_i_paths'] = $paths;
		$_SESSION['admin_i_groups_users'] = $group_users;
		$_SESSION['admin_i_groups_paths'] = $group_paths;

		if (isset($_GET['save'])) {
			if (strlen(trim($_GET['save'])) > 0) {
				if ($_GET['save'] == "true") {
					if (sizeof($group_users) > 0 && sizeof($group_paths) > 0) {
						$string_data = serialize(encode_array($group_users));
						file_put_contents($site_document_root . "/" . $config_file . "/group_users.config", $string_data);
						$string_data = serialize(encode_array($group_paths));
						file_put_contents($site_document_root . "/" . $config_file . "/group_paths.config", $string_data);
					}
				}
			}
		}

		// sort arrays
		sort($users);
		sort($groups);
		sort($paths);
		ksort($group_users);
		foreach ($group_users as $key => $user_name_array) {
			sort($user_name_array);
		}
		ksort($group_paths);
		foreach ($group_paths as $key => $path_name_array) {
			sort($path_name_array);
		}

		if ($log) {
			print_r($users);
			echo "<br>";

			print_r($groups);
			echo "<br>";

			print_r($paths);
			echo "<br>";

			print_r($group_users);
			echo "<br>";

			print_r($group_paths);
			echo "<br>";
		}

		// require html code
		require $site_document_root . "/core/user_group_html.php";

	}
} else {
	error_log("user_group.php resource NOT ALLOWED " . $site_document_root . $request_without_reverse_proxy);
	require $site_document_root . "/core/login.php";
}

?>