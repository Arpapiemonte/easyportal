<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 *
 *
 * This is the first file that receives all the portal redirects directly
 *
 * url malformed     -> $home_page, necessary redirect and then http header manipulation
 * index_wrapper.php -> logout.php, necessary redirect and then http header manipulation
 * 
 * index_wrapper.php -> $login_page, login.php refers to the page that authenticates depending on whether it is a page
 *     already requested $_SESSION['page_not_allowed']) or not it is necessary to redirect and therefore http header manipulation
 *   index_wrapper.php -> (follow login) redirect to $_SESSION['page_not_allowed'] or
 *   index_wrapper.php -> (follow login) redirect to home_page if user is logged
 * 
 * if request is not a resource file or is not a file to download or is not a excluded page
 *   go to index.php check will be performed by check.php (first require index.php)
 * 
 * index_wrapper.php -> index_res.php, binary resources based on $resources_extension or redirect to login in case of unauthorized page
 * index_wrapper.php -> download.php, based on $download_extension needed redirect and then http header manipulation
 * index_wrapper.php -> pages excluded from the redirect to the portal $excluded_pages if they have to return image files for example
 * index_wrapper.php -> index.php (second require index.php) for everything else
*/

require $_SERVER["DOCUMENT_ROOT"] . '/core/functions.php';

readEnvFile();

// load configuration
$log = env("LOG", false);

error_log("index_wrapper.php:-----START " . $_SERVER['REQUEST_URI'] . " " . $_SERVER['REDIRECT_URL']);

$resources_extension = [
	"gif",
	"png",
	"css",
	"js",
	"json",
	"jpg",
	"map",
	"svg",
	"xml",
	"xsl",
	"ico",
	"pdf",
	"tiff",
	"tif",
	"txt",
	"ttf",
	"woff",
	"woff2",
	"gz",
	"mp4",
	"ogg",
	"mp3"
];

// file extension for download
$download_extension = ["csv", "gz"];

// CONFIGURATION FOR PHP FILE PATH AND REVERSE_PROXY
$environment = env('ENV');
// LOAD FILE FOR AUTHENTICATION
$config_file = env('ENV');

// $reverse_proxy = "/api"; # set if use proxy_pass from nginx empty if there is no proxy_pass from nginx
$reverse_proxy = env('BASE_URL');
$reverse_proxy_filesystem = "/var/www/html/";
$site_document_root = $reverse_proxy_filesystem;
$set_site = env("SITE_TYPE");

// AUTHENTICATION CONFIGURATION
$login_page = "/core/login/" . env("AUTH_PROVIDER");

$content = env('PAGE_CONTENT', null) == null ? null : '/core/content/' . env('PAGE_CONTENT');
$login_provider = env('AUTH_PROVIDER', null) == null ? null : '/core/login/' . env('AUTH_PROVIDER');
$change_pwd_provider = env('CHANGE_PASSWORD_PROVIDER', null) == null ? null : '/core/changepassword/' . env('CHANGE_PASSWORD_PROVIDER');
$change_pwd_page = is_null($change_pwd_provider) ? null : "/core/change_pwd.php";

$news_provider = empty(env('NEWS_PROVIDER', "")) ? null : '/core/news/' . env('NEWS_PROVIDER');

$logo_file = env('ORG_LOGO', null);
$logo_file = $logo_file[0] == "/" ? $logo_file : "/$logo_file";
$env_enabled_files = explode(",", env('ENABLED_FILES', ''));
$enable_menu_side = env('MENU_SIDE', false);

if ($set_site == "intranet") {
	$home_page = "/". env("PUBLIC_SITE") ."/home.php";
	$dir_enabled = [env("PUBLIC_SITE"), "css", "svg", "js", "assets", "resources", "fonts", "images", "bootstrap-italia"];
	$file_enabled = [
		$home_page,
		"/core/login.php",
		$login_page,
		"/core/logout.php",
		"/core/user_group.php",
		"/core/user_group_html.php",
		"/core/search_html.php",
		"/favicon.ico",
		$content,
		$login_provider,
		$change_pwd_provider,
		$change_pwd_page, ...$env_enabled_files,
		$logo_file,
		$news_provider
	];

} else if ($set_site == "extranet") {
	$home_page = "/". env("AUTH_SITE") ."/common/home.php";
	$dir_enabled = ["css", "svg", "js", "assets", "fonts", "bootstrap-italia"];
	$file_enabled = [
		"/core/login.php",
		$login_page,
		"/core/logout.php",
		"/core/search_html.php",
		"/favicon.ico",
		"/core/login_ldap_virtcsi7.php",
		"/core/user_group.php",
		$content,
		$login_provider,
		$change_pwd_provider,
		$change_pwd_page, ...$env_enabled_files,
		$logo_file,
		$news_provider
	];
}

$home_page_orig = $home_page;

session_start();
if (isset($_SESSION["home_page"]))
	$home_page = $_SESSION["home_page"];

if (isset($_SESSION["page_not_allowed"])){
	if ($home_page_orig == $_SESSION["page_not_allowed"]){
		// extranet configuration
		unset($_SESSION['page_not_allowed']);
	}
}

if ($log) {
	error_log("index_wrapper.php: home_page=" . $home_page);
	if (isset($_SESSION["page_not_allowed"]))
		error_log("index_wrapper.php: page_not_allowed=" . $_SESSION["page_not_allowed"]);
	error_log("index_wrapper.php: REQUEST_URI=" . $_SERVER['REQUEST_URI']);
	error_log("index_wrapper.php: REDIRECT_URL=" . $_SERVER['REDIRECT_URL']);
}

$request_uri_without_reverse_proxy = str_replace($reverse_proxy, "", $_SERVER['REQUEST_URI']);
$request_without_reverse_proxy = str_replace($reverse_proxy, "", !empty($_SERVER['REDIRECT_URL'])
	? $_SERVER['REDIRECT_URL'] : $home_page);

// load json configuration
if (!file_exists($site_document_root . $config_file . ".json"))
	return;
$json = file_get_contents($site_document_root . $config_file . ".json");
$json = json_decode($json, true);
if (empty($json))
	return;

foreach ($json as $category => $values) {
	foreach ($values as $key => $value) {
		$exploded = explode("/", $value);
		$invalid_element = isset($exploded[1]) && ($exploded[1] == "core" || $exploded[1] == $config_file);
		if ($invalid_element)
			continue;
		if ($category == "excluded_pages") {
			$excluded_pages[] = $value;
		} else if ($category == "no_replace_pages") {
			$no_replace_pages[] = $value;
		} else if ($category == "file_menus") {
			$file_menus[] = $_SERVER["DOCUMENT_ROOT"] . $value;
		} else if ($category == "auth_menus") {
			$auth_menus[] = $_SERVER["DOCUMENT_ROOT"] . $value;
		} else if ($category == "file_tabs") {
			$file_tabs[] = $_SERVER["DOCUMENT_ROOT"] . $value;
		} else if ($category == "footer_links") {
			$footer_links[$key] = $value;
		} else if ($category == "social_links") {
			$social_links[$key] = $value;
		} else if ($category == "extends_dirs") {
			array_push($dir_enabled, $value);
		} else if ($category == "extends_files") {
			array_push($file_enabled, $value);
		}
	}
}
// set username
$_SESSION['config_file'] = $config_file; // if we don't use the session we have to change all the files that call the functions
// of connection to the db
$username = "";
if (isset($_SESSION['username'])) {
	if (strlen($_SESSION['username']) > 0) {
		$username = $_SESSION['username'];
	}
}

$pos = strpos($request_without_reverse_proxy, "/");

$path_parts = pathinfo($request_without_reverse_proxy);

if ($log) {
	error_log("index_wrapper.php: path_parts dirname " . $path_parts['dirname']);
	error_log("index_wrapper.php: path_parts basename " . $path_parts['basename']);
	if (isset($path_parts['extension']))
		error_log("index_wrapper.php: path_parts extension " . $path_parts['extension']);
	error_log("index_wrapper.php: path_parts filename " . $path_parts['filename']);
}

if (
	strlen(trim($path_parts['basename'])) > 0 && strlen(trim($path_parts['filename'])) > 0 &&
	!isset($path_parts['extension'])
) {
	$request_without_reverse_proxy = $request_without_reverse_proxy . "index.html";
	error_log("index_wrapper.php: search for index.html in " . $request_without_reverse_proxy );
}

if ($pos === false) {
	error_log("index_wrapper.php: malformed url redirect to " . $home_page);
	header("location: " . $reverse_proxy . $home_page);
	return;
} else {
	if ($request_without_reverse_proxy == "/core/logout.php") {
		require $site_document_root . "/core/logout.php";
		$home_page = $home_page_orig;
		header("location: " . $reverse_proxy . $home_page);
		return;
	} else if ($request_without_reverse_proxy == $login_page) {
		require $site_document_root . $login_page;

		$redirect_location = $reverse_proxy . "/core/login.php";
		if (isset($_SESSION['page_not_allowed'])) {
			$redirect_location = $reverse_proxy . $_SESSION['page_not_allowed'];
			unset($_SESSION['page_not_allowed']);
		} else if (isset($_SESSION['username'])) {
			error_log("index_wrapper.php: redirect to home");
			$redirect_location = $reverse_proxy . $_SESSION['home_page'];
		}
		header("location: " . $redirect_location);
		return;
	}

	// request is a resource, file or excluded page
	$request_ext = pathinfo($request_without_reverse_proxy, PATHINFO_EXTENSION);

	$needs_permission = in_array($request_ext, $resources_extension)
		|| in_array($request_ext, $download_extension)
		|| in_array($request_without_reverse_proxy, $excluded_pages);

	// if request is not a resource file or is not a file to download or is not a exclude page
	// go to index.php check wil be performed by check.php
	if (!$needs_permission) {
		error_log("index_wrapper.php: require index 1");
		require $site_document_root . "/core/index.php";
		return;
	}

	// check file or directory
	$allowed = is_file_allowed($file_enabled, $log, $request_without_reverse_proxy)
		|| is_path_allowed($request_without_reverse_proxy, $username, $log, $site_document_root, $dir_enabled, $reverse_proxy, $config_file);

	//  error_log("index_wrapper.php: ======================================");
	//  error_log(is_path_allowed($request_without_reverse_proxy, $username, $log, $site_document_root, $dir_enabled, $reverse_proxy, $config_file));
	//  error_log($request_without_reverse_proxy);
	//  error_log($username);
	//  error_log($log);
	//  error_log($site_document_root);
	//  error_log($dir_enabled);
	//  error_log($reverse_proxy);
	//  error_log($config_file);
	//  error_log("index_wrapper.php: ======================================");

	if (!$allowed) {
		set_page_not_allowed($request_without_reverse_proxy);
		error_log("index_wrapper.php: resource NOT ALLOWED " . $site_document_root . $request_without_reverse_proxy);
		header("location: " . $reverse_proxy . "/core/login.php");
		return;
	}
	// get file extension
	$request_ext = pathinfo($request_without_reverse_proxy, PATHINFO_EXTENSION);
	if (in_array($request_ext, $resources_extension) && $allowed) {
		if ($log) error_log("index_wrapper.php: require index_res");
		require $site_document_root . "/core/index_res.php";
	} else if (in_array($request_ext, $download_extension) && $allowed) {
		if ($log) error_log("index_wrapper.php: require download");
		require $site_document_root . "/core/download.php";
	} else if (in_array($request_without_reverse_proxy, $excluded_pages) && $allowed) {
		error_log("index_wrapper.php: excluded pages require " . $site_document_root . $request_without_reverse_proxy);
		require $site_document_root . $request_without_reverse_proxy;
	} else {
		error_log("index_wrapper.php: require index 2");
		require $site_document_root . "/core/index.php";
	}
}

?>