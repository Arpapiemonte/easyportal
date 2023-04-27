<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

echo '<table border="1">';
$file = fopen($site_document_root . $request_without_reverse_proxy, "r") or die("Unable to open file!");
while (!feof($file)) {
	$data = fgets($file);
	echo "<tr><td>" . str_replace('NON LO USA', '</td><td>', $data) . '</td></tr>';
}
echo '</table>';
fclose($file);
?>