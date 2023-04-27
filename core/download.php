<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

if (strlen($site_document_root) > 0 && strlen($request_without_reverse_proxy) > 0) {
	if (file_exists($site_document_root . $request_without_reverse_proxy)) {
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $request_without_reverse_proxy . '";');
		readfile($site_document_root . $request_without_reverse_proxy);
	} else {
		error_log("donwload FILE NOT EXISTS " . $site_document_root . $request_without_reverse_proxy);
		header("location: " . $reverse_proxy . $home_page);
	}
} else {
	error_log("donwload FILE NOT EXISTS " . $site_document_root . $request_without_reverse_proxy);
	header("location: " . $reverse_proxy . $home_page);
}
?>