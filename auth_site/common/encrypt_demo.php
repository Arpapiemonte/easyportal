<?php

/**
 * Copyright (C) 2019-2022 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>
<?php
// ### per connessione con db e SSH
echo "encrypt myhost;myuser;mypwd INIZIO" . encrypt("myhost;myuser;mypwd", $site_document_root) . "FINE<br>\n";

echo "db.config";
print("<br>");
$dbs = file($site_document_root . "/" . $config_file . "/db.config", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($dbs as $db){
    $conn_params = explode(";",decrypt($db, $site_document_root));
    print_r($conn_params);
    print("<br>");
}

// ### per i DB
echo "connessione myhost db " . get_db_user_pwd("myhost",$site_document_root);
print("<br>");
// ### per SSH
echo "connessione myhost SSH " . get_db_pwd("myhost","myuser",$site_document_root);
print("<br>");

?>
