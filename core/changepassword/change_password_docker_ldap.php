<?php

/**
 * Copyright (C) 2019-2022 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$ldap_server = "ldap";

function ldap_change_password($username, $current_password, $new_password, $ldap_server)
{
    $ldap_user = "CN=$username,DC=ldap";
    $ldap_pass = $current_password;

    try {
        $ad = ldap_connect($ldap_server);
        ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
        $bound = @ldap_bind($ad, $ldap_user, $ldap_pass);

    } catch (Exception $e) {
        return false;
    }

    if (!$bound)
        return false;

    $entry["userPassword"] = "{SHA}" . base64_encode(pack("H*", sha1($new_password)));

    if (ldap_modify($ad, $ldap_user, $entry) === false) {
        return false;
    }

    return true;
}

$error = null;

if (!isset($_SESSION['username']) || strlen(trim($_SESSION['username'])) <= 0) {
    $error = 'Devi essere loggato per cambiare la password';
} else if (
    empty(trim($_POST['current_password']))
    || empty(trim($_POST['new_password']))
    || empty(trim($_POST['verify_password']))
) {
    $error = 'Devi compilare tutti i campi per modificare la password';
} else if ($_POST['new_password'] != $_POST['verify_password']) {
    $error = 'Le password inserite non coincidono';
} else if ($_POST['current_password'] == $_POST['new_password']) {
    $error = 'La nuova e la vecchia password non possono essere uguali';
}

if (is_null($error)) {
    $changed = ldap_change_password($_SESSION['username'], trim($_POST['current_password']), trim($_POST['new_password']), $ldap_server);

    if (!$changed) {
        $error = 'Si è verificato un errore nella modifica della password';
        return;
    }
}

if (!is_null($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error ?>
    </div>
<?php else: ?>
    <div class="alert alert-success" role="alert">
        Password cambiata con successo
    </div>
<?php endif; ?>