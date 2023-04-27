<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>
<script>
	function SubmitMenu(new_value, page_action) {
		document.getElementById("menu").value = new_value;
		document.getElementById("menu_hidden_form").action = page_action;
		document.getElementById('menu_hidden_form').submit();
	}
</script>

<form action="/menu.php" method="post" id="menu_hidden_form">
	<input type="hidden" id="menu" name="menu" value="">
</form>

<?php

if (isset($_POST['menu'])) {
	if (strlen($_POST['menu']) > 0) {
		$current_menu = $_POST['menu'];
	}
}

?>