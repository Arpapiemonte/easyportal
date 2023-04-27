<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$html = "<ul>";
if (isset($_POST['search'])) {
	if (strlen($_POST['search']) > 0) {
		if (isset($search)) {
			if (is_array($search)) {
				foreach ($search as $key => $array_of_values) {
					if (in_array(strtoupper($_POST['search']), $array_of_values)) {
						$html = $html . "<li><a href=\"" . $key . "\">" . $key . "</a></li>";
					}
				}
			}
		}
	}
}
$html = $html . "<ul>";

?>

<form action="/core/search_html.php" method="post">
	<input type="text" name="search">
	<input type="submit">
</form>
<?php echo $html; ?>