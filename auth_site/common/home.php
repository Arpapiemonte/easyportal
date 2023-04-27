<?php

/**
 * Copyright (C) 2019-2022 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>
<?php
echo "auth common page";

$my_path_allowed = get_paths($_SESSION['username'], $site_document_root,false,$config_file);
if ($log) print_r($my_path_allowed);
?>
