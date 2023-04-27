<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

// MENU CSV HEADER
// Nav text;Link home;Group Name NAV COLUMN(NAV1|NAV2|NAV3|NAV4)#HEADER;Menu text;Link;tag_search1#tag_search2#tag_search3

// navigation menu with only the nav title association => link (home page of the navigation menu)
function create_menu_nav($menu_navs_link, $file_menu_name, $current_menu, $log, $request, $reverse_proxy)
{

	if (isset($_SERVER['QUERY_STRING'])) {
		if (strlen($_SERVER['QUERY_STRING']) > 0) {
			$request = $request . "?" . $_SERVER['QUERY_STRING'];
		}
	}

	$result = array();
	if (file_exists($file_menu_name)) {
		$csv = array_map('str_getcsv', file($file_menu_name));
		$header = array_shift($csv);
		foreach ($csv as $row) {
			if ($row[0] != null) {
				if (strlen($row[0]) > 0) {
					$var = explode(';', $row[0]);
					if (sizeof($var) == env("MAX_CSV_ARRAY_SIZE")) {
						if (strlen($var[0]) > 0 && strlen($var[1]) > 0 && strlen($var[3]) > 0 && strlen($var[4]) > 0) {
							if (!array_key_exists($var[0], $menu_navs_link)) {
								if (count($menu_navs_link) < env("MAX_MENU_NAV")) {
									$menu_navs_link += array($var[0] => $var[1]);
									//$request = str_replace($reverse_proxy, "", $request);
									if ($log)
										error_log("request=" . $request . " var[1]=" . $var[1]);
									if ($request == $var[1])
										$current_menu = $var[0];
									if ($log)
										error_log("menu nav file_name=" . $file_menu_name .
											" request=" . $request . " link=" . $var[1] .
											" current_menu=" . $current_menu, 0);
								} else {
									error_log("create_menu_nav error " . $row[0] . " number of menu nav > " . env("MAX_MENU_NAV"), 0);
								}
							}
						} else {
							error_log("create_menu_nav error " . $row[0], 0);
						}
					} else {
						error_log("create_menu_nav error " . $row[0], 0);
					}
				}
			}
		}
	}
	$result[0] = $menu_navs_link;
	$result[1] = $current_menu;
	return $result;
}

// creates the menu nav array which would be the visible navigation menu items inside 
// each nav array is the group array created by init_menu_group
function init_menu_side($menu_navs, $file_menu_name)
{
	if (file_exists($file_menu_name)) {
		$csv = array_map('str_getcsv', file($file_menu_name));
		$header = array_shift($csv);
		foreach ($csv as $row) {
			if ($row[0] != null) {
				if (strlen($row[0]) > 0) {
					$var = explode(';', $row[0]);
					if (sizeof($var) == env("MAX_CSV_ARRAY_SIZE")) {
						if (strlen($var[0]) > 0 && strlen($var[1]) > 0 && strlen($var[3]) > 0 && strlen($var[4]) > 0) {
							if (!array_key_exists($var[0], $menu_navs)) {
								if (count($menu_navs) < env("MAX_MENU_NAV")) {
									$menu_navs += array($var[0] => array());
								} else {
									error_log("init_menu_side error " . $row[0] . " number of menu nav > " . env("MAX_MENU_NAV"), 0);
								}
							}
						} else {
							error_log("init_menu_side error " . $row[0], 0);
						}
					} else {
						error_log("init_menu_side error " . $row[0], 0);
					}
				}
			}
		}
	}
	return $menu_navs;
}

function get_nav_category($menu_navs, $key0, $search)
{
	$result = $search;
	if (sizeof($menu_navs) > 0) {
		$key_nav_arr = explode("#", $search);
		if (sizeof($key_nav_arr) == 2) {
			if (strlen($key_nav_arr[0]) > 0 && strlen($key_nav_arr[1]) > 0) {
				//echo $search." cerco ".$key_nav_arr[0]." in ".$key0." size=".sizeof($menu_navs[$key0])." \n<br>";
				foreach ($menu_navs[$key0] as $group_text => $menu_sides) {
					//echo "group_text=$group_text\n<br>";
					$menu_explode = explode("#", $group_text);
					if (sizeof($menu_explode) == 2) {
						if (strlen($menu_explode[0]) > 0 && strlen($menu_explode[1]) > 0) {
							//echo $menu_explode[0]." - ".$key_nav_arr[0]." <> ".$menu_explode[1]." - ".$key_nav_arr[1]." \n<br>";
							if ($menu_explode[0] == $key_nav_arr[0]) {
								//echo " return search ".$search." - group_text=". $group_text."\n<br>";
								$result = $group_text;
							}
						}
					}
				}
			}
		}
	}
	return $result;
}

function nav_category_title_differs($menu_navs, $key0, $search)
{
	$result = false;
	if (array_key_exists($key0, $menu_navs)) {
		$key_nav_arr = explode("#", $search);
		if (sizeof($key_nav_arr) == 2) {
			if (strlen($key_nav_arr[0]) > 0 && strlen($key_nav_arr[1]) > 0) {
				//echo $search." search ".$key_nav_arr[0]." in ".$key0." size=".sizeof($menu_navs[$key0])." \n<br>";
				foreach ($menu_navs[$key0] as $group_text => $menu_sides) {
					//echo "group_text=$group_text\n<br>";
					$menu_explode = explode("#", $group_text);
					if (sizeof($menu_explode) == 2) {
						if (strlen($menu_explode[0]) > 0 && strlen($menu_explode[1]) > 0) {
							//echo $menu_explode[0]." - ".$key_nav_arr[0]." <> ".$menu_explode[1]." - ".$key_nav_arr[1]." \n<br>";
							if ($menu_explode[0] == $key_nav_arr[0] && $menu_explode[1] != $key_nav_arr[1]) {
								// same menu nav
								//echo "found ".$key_nav_arr[0]." in ". $key0."\n<br>";
								$result = true;
							}
						}
					}
				}
			}
		}
	}
	return $result;
}

function nav_category_title_replace($menu_navs, $key0, $replace_with)
{
	if (array_key_exists($key0, $menu_navs)) {
		$key_nav_arr = explode("#", $replace_with);
		if (sizeof($key_nav_arr) == 2) {
			if (strlen($key_nav_arr[0]) > 0 && strlen($key_nav_arr[1]) > 0) {
				foreach ($menu_navs[$key0] as $group_text => $menu_sides) {
					$menu_explode = explode("#", $group_text);
					if (sizeof($menu_explode) == 2) {
						if (strlen($menu_explode[0]) > 0 && strlen($menu_explode[1]) > 0) {
							if ($menu_explode[0] == $key_nav_arr[0]) {
								error_log("sostituisco " . $group_text . " con " . $replace_with);
								unset($menu_navs[$key0][$group_text]);
								$menu_group_side = array($replace_with => array());
								$menu_navs[$key0] += $menu_group_side;
							}
						}
					}
				}
			}
		}
	}
	return $menu_navs;
}

// creates the array of groups within the navs based on the menu items NAV1 NAV2 NAV3 NAV4
// replace already used titles X SAME NAV if NAV1#NAV1 is read first
// the second entry of the same nav was NAV1#TRY the title of the megamenu nav
// would become NAV1#TRY and NAV1#NAV1 would be overwritten
function init_menu_group($menu_navs, $file_menu_name, $is_auth)
{
	if (file_exists($file_menu_name)) {
		$csv = array_map('str_getcsv', file($file_menu_name));
		$header = array_shift($csv);
		foreach ($csv as $row) {
			if ($row[0] != null) {
				if (strlen($row[0]) > 0) {
					$var = explode(';', $row[0]);
					if (sizeof($var) == env("MAX_CSV_ARRAY_SIZE")) {
						if (strlen($var[0]) > 0 && strlen($var[1]) > 0 && strlen($var[3]) > 0 && strlen($var[4]) > 0) {
							if (array_key_exists($var[0], $menu_navs)) {
								if (strlen($var[2]) == 0) {
									$group_name = "not_grouped";
									$menu_group_side = array($group_name => array());
									$menu_navs[$var[0]] += $menu_group_side;
								} else {
									if (nav_category_title_differs($menu_navs, $var[0], $var[2])) {
										// overwrite NAV*#TITLE
										$menu_navs = nav_category_title_replace($menu_navs, $var[0], $var[2]);
									} else {
										$group_name = $var[2];
										$menu_group_side = array($group_name => array());
										$menu_navs[$var[0]] += $menu_group_side;
									}
								}
							}
						} else {
							error_log("init_menu_group error " . $row[0], 0);
						}
					} else {
						error_log("init_menu_group error " . $row[0], 0);
					}
				}
			}
			/*
			echo "----------INIZIO init_menu_group ----".$file_menu_name."------\n<br>";
			foreach ($menu_navs as $nav_text=>$menu_groups){
			echo "MENU NAV ".$nav_text."<br>";
			foreach ($menu_groups as $group_text=>$menu_sides){
			echo "&emsp;MENU GROUP ".$group_text."<br>";
			foreach ($menu_sides as $side_text=>$side_link){
			echo "&emsp;&emsp;MENU SIDE ".$side_text."-".$side_link."<br>";
			}
			}
			}
			echo "----------FINE init_menu_group ------".$file_menu_name."----\n<br>";
			*/
		}
	}
	return $menu_navs;
}

// after initializing the array of navs and groups the links to the pages are placed
// if a NAV is overwritten, the link is still placed under the category NAV1 NAV2 NAV3 NAV4
// of the megamenu
function create_menu_side(
	$menu_navs,
	$file_menu_name,
	$check_current_menu,
	$current_menu,
	$current_group,
	$log,
	$request,
	$search,
	$reverse_proxy
)
{

	if (isset($_SERVER['QUERY_STRING'])) {
		if (strlen($_SERVER['QUERY_STRING']) > 0) {
			$request = $request . "?" . $_SERVER['QUERY_STRING'];
		}
	}
	if (file_exists($file_menu_name)) {
		$csv = array_map('str_getcsv', file($file_menu_name));
		$header = array_shift($csv);
		foreach ($csv as $row) {
			if ($row[0] != null) {
				if (strlen($row[0]) > 0) {
					$var = explode(';', $row[0]);
					if (sizeof($var) == env("MAX_CSV_ARRAY_SIZE")) {
						if (strlen($var[0]) > 0 && strlen($var[1]) > 0 && strlen($var[3]) > 0 && strlen($var[4]) > 0) {
							if (array_key_exists($var[0], $menu_navs)) {
								if (strlen($var[2]) == 0) {
									$key_group = "not_grouped";
								} else {
									$key_group = $var[2];
								}
								$menu_side_link = array($var[3] => $var[4]);
								$nav_category = get_nav_category($menu_navs, $var[0], $key_group);
								if (array_key_exists($nav_category, $menu_navs[$var[0]])) {
									$menu_navs[$var[0]][$nav_category] += $menu_side_link;
									if ($check_current_menu) {
										if ($log)
											error_log("menu side current_menu=" . $current_menu, 0);
										if (strlen($current_menu) == 0) {
											//$request = str_replace($reverse_proxy, "", $request);
											if ($request == $var[4])
												$current_menu = $var[0];
											if ($log)
												error_log("menu side request=" . $request . " link=" .
													$var[4] . " current_menu=" . $current_menu, 0);
										}
										if (strlen($current_group) == 0) {
											//$request = str_replace($reverse_proxy, "", $request);
											if ($request == $var[4])
												$current_group = $var[2];
											if ($log)
												error_log("menu side request=" . $request . " link=" .
													$var[4] . " current_group=" . $current_group, 0);
										}
									}
									// fill search array
									if ($log)
										error_log("var[5]=" . $var[5] . " var[4]=" . $var[4]);
									if (strlen($var[5]) > 0) {
										$search_explode = explode("#", $var[5]);
										if (count($search_explode) > 0) {
											$search_explode = array_map('strtoupper', $search_explode);
											if (array_key_exists($var[4], $search)) {
												$search[$var[4]] = $search_explode;
											} else {
												$search += array($var[4] => $search_explode);
											}
										}
									}
								} else {
									error_log("menu ignorato " . $row[0]);
								}
							}
						} else {
							error_log("create_menu_side error " . $row[0], 0);
						}
					} else {
						error_log("create_menu_side error " . $row[0], 0);
					}
				}
			}
		}
	}
	$result = array();
	$result[0] = $menu_navs;
	$result[1] = $current_menu;
	$result[2] = $current_group;
	$result[3] = $search;
	if ($log) {
		foreach ($search as $key => $array_of_values) {
			foreach ($array_of_values as $key2 => $value) {
				error_log("search key=" . $key . " value=" . $value);
			}
		}
	}
	return $result;
}

// function for encode array
function encode_array($array)
{
	$result = array();
	foreach ($array as $key => $user_name_array) {
		$result += array(base64_encode($key) => array());
	}
	foreach ($array as $key => $value) {
		foreach ($value as $value_key => $value_value) {
			$result[base64_encode($key)][] = base64_encode($value_value);
		}
	}
	return $result;
}

// function for decode array
function decode_array($array)
{
	$result = array();
	foreach ($array as $key => $user_name_array) {
		$result += array(base64_decode($key) => array());
	}
	foreach ($array as $key => $value) {
		foreach ($value as $value_key => $value_value) {
			$result[base64_decode($key)][] = base64_decode($value_value);
		}
	}
	return $result;
}

// get array of groups for username
function get_groups($login_username, $site_document_root, $config_file)
{
	$result = array();
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
		foreach ($group_users as $key_group => $user_name_array) {
			foreach ($user_name_array as $key_user => $user_name) {
				if ($login_username == $user_name) {
					$result[] = $key_group;
				}
			}
		}
	} else {
		error_log("get_groups search for .config in dir " . $site_document_root .
			" FILE NOT FOUND!");
	}
	return $result;
}

// get array of groups for username
function get_paths($login_username, $site_document_root, $log, $config_file)
{
	$result = array();
	if (isset($_SESSION['username'])) {
		if (strlen($_SESSION['username']) > 0) {
			$result[] = "/". env("AUTH_SITE") ."/common/";
			$groups = array();
			if ($log)
				error_log("get_paths search for .config in dir " . $site_document_root);
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

				foreach ($group_users as $key_group => $user_name_array) {
					foreach ($user_name_array as $key_user => $user_name) {
						if ($login_username == $user_name) {
							$groups[] = $key_group;
						}
					}
				}
				foreach ($groups as $key_group => $group_name) {
					foreach ($group_paths as $key_path => $array_path_name) {
						if ($group_name == $key_path || $group_name == "admins") {
							foreach ($array_path_name as $key => $value) {
								$result[] = $value;
							}
						}
					}
				}
			}
		}
	}
	return $result;
}

// is /". env("AUTH_SITE") ."/common allowed?
function is_auth_common($request, $log)
{
	$result = false;
	$dir_level = explode("/", $request);
	// $dir_level[0] always empty
	if ($log) {
		foreach ($dir_level as $key => $value) {
			error_log("is_path_allowed 1 dir_level " . $key . " " . $value);
		}
	}
	if (sizeof($dir_level) > 2) {
		if (strlen($dir_level[1]) > 0) {
			if (
				$dir_level[1] == 'auth' &&
				$dir_level[2] == 'common'
			) {
				$result = true;
			}
		}
	}
	return $result;
}

// is this file allowed or not?
function is_file_allowed($file_enabled, $log, $request_without_reverse_proxy)
{
	$ok = false;
	foreach ($file_enabled as $file => $file_name) {
		if ($request_without_reverse_proxy == $file_name) {
			if ($log)
				error_log("check file set ok=true", 0);
			$ok = true;
		}
	}
	return $ok;
}

// is this path allowed or not?
function is_path_allowed($request, $username, $log, $site_document_root, $dir_enabled, $reverse_proxy, $config_file)
{
	$result = false;
	$dir_level = explode("/", $request);
	// $dir_level[0] always empty
	if ($log) {
		foreach ($dir_level as $key => $value) {
			error_log("is_path_allowed 1 dir_level " . $key . " " . $value);
		}
	}
	if (sizeof($dir_level) > 2) {

		if (strlen($dir_level[1]) > 0) {

			if (in_array($dir_level[1], $dir_enabled)) {
				$result = true;
			} else {
				$allowed_paths = get_paths($username, $site_document_root, $log, $config_file);
				if ($log) {
					foreach ($allowed_paths as $key => $value) {
						error_log("is_path_allowed 2 allowed_paths " . $key . " " . $value);
					}
				}
				if (!empty($allowed_paths)) {
					foreach ($allowed_paths as $key => $path_name) {
						if ($log)
							error_log("is_path_allowed 3 request=" . $request . ",allowed_paths=" . $path_name, 0);
						$allowed_paths_exp = explode("/", $path_name);
						if (sizeof($allowed_paths_exp) > 2) {
							if (
								$dir_level[1] == $allowed_paths_exp[1] &&
								$dir_level[2] == $allowed_paths_exp[2]
							) {
								if ($log)
									error_log("is_path_allowed 4 allow_path dir set ok=true", 0);
								$result = true;
							}
						}
					}
				}
			}
		}
	}
	return $result;
}

// get if username is admin or not
function is_admin($login_username, $site_document_root, $config_file)
{
	$result = false;
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
		foreach ($group_users as $key_group => $user_name_array) {
			foreach ($user_name_array as $key_user => $user_name) {
				if ($login_username == $user_name) {
					if ($key_group == "admins") {
						$result = true;
					}
				}
			}
		}
	} else {
		if (!empty($login_username)) {
			// set user as admin to allow for initial setup, until the files are created
			$result = true;
		}
		error_log("is_admin search for .config in dir " . $site_document_root . "/" . $config_file . " FILE NOT FOUND!");
	}
	return $result;
}

// this function saves all the strings contained in the <IGNORE_REPLACE>...</IGNORE_REPLACE> tags
// replace them with NULL_occurrence_number, return an array containing the output e at zero index
// from index 1 all the strings contained within the IGNORE_REPLACE tags
function ignore_replace_to_array($output)
{
	$output_array = array();
	array_push($output_array, $output);
	$count = 1;
	$pattern = "/<IGNORE_REPLACE>(.*?)<\/IGNORE_REPLACE>/";
	if (preg_match_all($pattern, $output, $matches) > 0) {
		//error_log("ignore_replace_to_array called");
		foreach ($matches[1] as $customTag) {
			//error_log("customTag=$customTag");
			$output = str_replace($customTag, "NULL_" . $count, $output);
			array_push($output_array, $customTag);
			$count++;
		}
	}
	$output = str_replace("<IGNORE_REPLACE>", "", $output);
	$output = str_replace("</IGNORE_REPLACE>", "", $output);
	$output_array[0] = $output;
	return $output_array;
}

// this function replaces all NULL_occurrence_number occurrences with the original text
// stored in the array $output_array these strings are actually ignored by the replaces of reverse_proxy_replaces
function ignore_replace_from_array($output, $output_array)
{
	if (count($output_array) > 1) {
		for ($i = 1; $i < count($output_array); $i++) {
			//error_log("customTag=$output_array[$i]");
			$output = str_replace("NULL_" . $i, $output_array[$i], $output);
		}
	}
	return $output;
}

/*
* the purpose of this function is to feed pages with absolute links to the portal
* ignoring the url where the portal is substantially positioned in the web pages
* called from check.php absolute links like /css will be replaced by /easyportal/css
*/
function reverse_proxy_replaces($output, $reverse_proxy)
{
	// per css e js cerco "/css/ e "/js con il carattere doppio apice davanti al path
	//$output = str_replace("\"/css/", "\"".$reverse_proxy."/css/", $output);
	//$output = str_replace("\"/js/", "\"".$reverse_proxy."/js/", $output);
	$pos = strpos($output, $reverse_proxy);
	// Note our use of ===.  Simply == would not work as expected
	// because the position of 'a' was the 0th (first) character.
	//if ($pos === false) {
	//$output = preg_replace("/href(.*)=(.*)\/css\//",  "href=\"".$reverse_proxy."/css/", $output);
	$output = preg_replace("/href=\"\/css\//", "href=\"" . $reverse_proxy . "/css/", $output);
	//$output = preg_replace("/href(.*)=(.*)\/svg\//",  "href=\"".$reverse_proxy."/svg/", $output);
	$output = preg_replace("/href=\"\/svg\//", "href=\"" . $reverse_proxy . "/svg/", $output);
	//$output = preg_replace("/src(.*)=(.*)\/js\//", "src=\"".$reverse_proxy."/js/", $output);
	$output = preg_replace("/src=\"\/js\//", "src=\"" . $reverse_proxy . "/js/", $output);
	//$output = preg_replace("/action(.*)=(.*)\//", "action=\"".$reverse_proxy."/", $output);
	$output = str_replace("/logo.png", $reverse_proxy . "/logo.png", $output);
	$output = str_replace("/svg/", $reverse_proxy . "/svg/", $output);
	$output = str_replace("/". env("PUBLIC_SITE") ."/", $reverse_proxy . "/". env("PUBLIC_SITE") ."/", $output);
	$output = str_replace("/images/", $reverse_proxy . "/images/", $output);
	$output = str_replace("/core/login.php", $reverse_proxy . "/core/login.php", $output);
	$output = str_replace("/core/logout.php", $reverse_proxy . "/core/logout.php", $output);
	$output = str_replace("/core/home.php", $reverse_proxy . "/core/home.php", $output);
	$output = str_replace("/core/search_html.php", $reverse_proxy . "/core/search_html.php", $output);
	$output = str_replace("/core/user_group.php", $reverse_proxy . "/core/user_group.php", $output);
	$output = str_replace("/vendor/autoload.php", $reverse_proxy . "/vendor/autoload.php", $output);
	$output = str_replace("/". env("AUTH_SITE") ."/", $reverse_proxy . "/". env("AUTH_SITE") ."/", $output);
	//}
	return $output;
}

// maintains public variables
function require_ob(
	$filename,
	$reverse_proxy,
	$site_document_root,
	$menu_navs_link,
	$current_menu,
	$menu_navs,
	$enable_menu_side,
	$file_enabled,
	$dir_enabled,
	$current_group,
	$search,
	$home_page,
	$login_page,
	$username,
	$request_without_reverse_proxy,
	$mobile,
	$config_file,
	$environment,
	$log
)
{
	$output = "";
	if (file_exists($filename)) {
		ob_start();
		require $filename;
		$output = ob_get_contents();
		$output_array = ignore_replace_to_array($output);
		$output = reverse_proxy_replaces($output_array[0], $reverse_proxy);
		$output = ignore_replace_from_array($output, $output_array);
		ob_end_clean();
	} else {
		error_log("require_ob FILE NOT FOUND " . $filename);
	}
	return $output;
}

function encrypt($string, $site_document_root)
{
	$output = false;
	$string_data = file_get_contents($site_document_root . "/". env("AUTH_SITE") ."/encode_encrypt_params.config");
	$encrypt_params = unserialize($string_data);
	$encrypt_params = decode_array($encrypt_params);
	$encrypt_method = $encrypt_params["encrypt_method"][0];
	$secret_key = $encrypt_params["key"][0];
	$secret_iv = $encrypt_params["secret_iv"][0];
	$key = hash($encrypt_params["algo"][0], $secret_key);
	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash($encrypt_params["algo"][0], $secret_iv), 0, 16);
	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($output);
	return $output;
}

function decrypt($string, $site_document_root)
{
	$output = false;
	$string_data = file_get_contents($site_document_root . "/". env("AUTH_SITE") ."/encode_encrypt_params.config");
	$encrypt_params = unserialize($string_data);
	$encrypt_params = decode_array($encrypt_params);
	$encrypt_method = $encrypt_params["encrypt_method"][0];
	$secret_iv = $encrypt_params["secret_iv"][0];
	$secret_key = $encrypt_params["key"][0];
	$key = hash($encrypt_params["algo"][0], $secret_key);
	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash($encrypt_params["algo"][0], $secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

function get_db_user_pwd($db_name, $site_document_root)
{
	$config_file = $_SESSION['config_file'];
	$result = false;
	if (file_exists($site_document_root . "/" . $config_file . "/db.config")) {
		$dbs = file($site_document_root . "/" . $config_file . "/db.config", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($dbs as $db) {
			$conn_params = explode(";", decrypt($db, $site_document_root));
			if ($conn_params[0] == $db_name) {
				$result = "user=" . $conn_params[1] . " password=" . $conn_params[2];
			}
		}
	}
	return $result;
}

function get_db_pwd($db_name, $username, $site_document_root)
{
	$config_file = $_SESSION['config_file'];
	$result = false;
	if (file_exists($site_document_root . "/" . $config_file . "/db.config")) {
		$dbs = file($site_document_root . "/" . $config_file . "/db.config", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($dbs as $db) {
			$conn_params = explode(";", decrypt($db, $site_document_root));
			if ($conn_params[0] == $db_name && $conn_params[1] == $username) {
				$result = "user=" . $conn_params[1] . " password=" . $conn_params[2];
			}
		}
	}
	return $result;
}

function encrypt_pwd($username, $password)
{
	$encryption = "";

	if (strlen(trim($username)) > 0 && strlen(trim($password)) > 0) {
		// Store the cipher method
		$ciphering = "AES-128-CTR";

		// Use OpenSSl Encryption method
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;
		$iv_username = $username . $username;

		// Use openssl_encrypt() function to encrypt the data
		$encryption = openssl_encrypt(
			$password,
			$ciphering,
			$username . $username,
			$options,
			$iv_username
		);
	}

	return $encryption;
}

function decrypt_pwd($username, $password)
{
	$decryption = "";

	if (strlen(trim($username)) > 0 && strlen(trim($password)) > 0) {
		// Store the cipher method
		$ciphering = "AES-128-CTR";

		// Use OpenSSl Encryption method
		//$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;

		// Non-NULL Initialization Vector for decryption
		$decryption_iv = $username . $username;

		// Store the decryption key
		$decryption_key = $username . $username;

		// Use openssl_decrypt() function to decrypt the data
		$decryption = openssl_decrypt(
			$password,
			$ciphering,
			$decryption_key,
			$options,
			$decryption_iv
		);
	}

	return $decryption;
}

function set_page_not_allowed($request_without_reverse_proxy)
{
	$_SESSION['page_not_allowed'] = $request_without_reverse_proxy;
	if (isset($_SERVER['QUERY_STRING'])) {
		if (strlen($_SERVER['QUERY_STRING']) > 0) {
			$_SESSION['page_not_allowed'] = $_SESSION['page_not_allowed'] . "?" . $_SERVER['QUERY_STRING'];
		}
	}
}

function readEnvFile()
{
	$handle = fopen(__DIR__ . "/../.env", "r");
	if (!$handle)
		throw new Exception(".ENV - ERROR: could not open .env file");

	while (($line = fgets($handle)) !== false) {
		// Skip comment lines
		$first_char = substr($line, 0, 1);
		if ($first_char === '#' || $first_char === '/')
			continue;
		// Not a comment, put value in env
		putenv($line);
	}
	fclose($handle);
}

/**
 * @param string      $value
 * @param bool|string $default
 * @return bool|string
 */
function env($value, $default = false)
{
	$value = trim(getenv($value));

	if (is_null($value) || empty($value)) {
		return $default;
	}

	$value = preg_replace('~[\r\n]+~', '', $value);

	if ($value[0] == '\'' || $value[0] == '"') {
		$value = substr($value, 1, strlen($value) - 2);
	}
	if ($value === 'true' || $value === 'false') {
		$value = $value === 'true';
	}
	return $value;
}

?>