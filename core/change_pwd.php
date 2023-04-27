<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>

<br>
<div class="change-pwd-form">
	<form method="post"
		action="<?php echo $reverse_proxy . "/core/changepassword/" . env('CHANGE_PASSWORD_PROVIDER') ?>">
		<div class="form-group">
			<label for="current_password">Password corrente</label>
			<input type="password" class="form-control" placeholder="Password" name="current_password"
				id="current_password">
		</div>
		<div class="form-group">
			<label for="new_password">Nuova password</label>
			<input type="password" class="form-control" placeholder="Password" name="new_password" id="new_password">
		</div>
		<div class="form-group">
			<label for="verify_password">Verifica Nuova password</label>
			<input type="password" class="form-control" placeholder="Password" name="verify_password"
				id="verify_password">
		</div>
		<button type="submit" class="btn btn-primary">Cambia password</button>
	</form>
</div>
<br>