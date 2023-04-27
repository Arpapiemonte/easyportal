<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

if (isset($_SESSION['username'])) {
    if (strlen($_SESSION['username']) > 0) {
        error_log($_SESSION['username'] . " logged out", 0);
        unset($_SESSION['username']);
        unset($_SESSION['username2']);
        unset($_SESSION['home_page']);
        unset($_SESSION['page_not_allowed']);
        session_destroy();
    }
}
?>