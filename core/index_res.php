<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$ok = false;

$file_name = $site_document_root . $request_without_reverse_proxy;

if (file_exists($file_name)) {

	$ext = pathinfo($file_name, PATHINFO_EXTENSION);
	if ($ext == "css") {
		$content_type = "text/css";
	} else if ($ext == "js") {
		$content_type = "text/javascript";
	} else if ($ext == "svg") {
		$content_type = "image/svg+xml";
	} else {
		$content_type = mime_content_type($file_name);
	}

	$fp = fopen($file_name, 'rb');

	header("Content-Type: " . $content_type);

	// dump the picture and stop the script
	if ($log) error_log("index_res request=" . $request_without_reverse_proxy . ", file_name= " . $file_name . " served", 0);
	fpassthru($fp);
} else {
	error_log("index_res request=" . $request_without_reverse_proxy . ", file_name= " . $file_name . " NOT EXISTS", 0);
	header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
}
?>