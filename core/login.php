<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>
<br>
<div id="login-form">
	<form method="post" action="<?php echo $reverse_proxy . $login_page ?>">
		<div class="form-group">
			<label for="username">User Name</label>
			<input type="text" class="form-control" placeholder="User Name" name="username" id="username">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" placeholder="Password" name="password" id="password">
		</div>
		<button type="submit" class="btn btn-primary">Login</button>
	</form>
</div>
<br>