<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

foreach ($menu_navs as $nav_text => $menu_groups) {
	echo "MENU NAV " . $nav_text . "<br>";
	foreach ($menu_groups as $group_text => $menu_sides) {
		echo "&emsp;MENU GROUP " . $group_text . "<br>";
		foreach ($menu_sides as $side_text => $side_link) {
			echo "&emsp;&emsp;MENU SIDE " . $side_text . "-" . $side_link . "<br>";
		}
	}
}

?>