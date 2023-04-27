<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$user_session_groups = get_groups($_SESSION['username'], $site_document_root, $config_file);
$url_action = $reverse_proxy . "/core/user_group.php";
if (in_array("admins", $user_session_groups) || is_admin($_SESSION['username'], $site_document_root, $config_file)) {
	?>
	<form method="post" action="<?php echo $url_action; ?>">
		<label for="psw"><b>Add User</b></label>
		<input type="text" name="new_user">
		<input class="btn btn-primary" type="submit" value="Add User">
	</form>
	<br>
	<form method="post" action="<?php echo $url_action; ?>">
		<label for="psw"><b>Add Group</b></label>
		<input type="text" name="new_group">
		<input class="btn btn-primary" type="submit" value="Add Group">
	</form>
	<br>
	<form method="post" action="<?php echo $url_action; ?>">
		<label for="psw"><b>Add Path</b></label>
		<input type="text" name="new_path">
		<input class="btn btn-primary" type="submit" value="Add Path">
	</form>
	<br>
	<form name="SelectGroupForm" method="post" action="<?php echo $url_action; ?>">
		<select name="SelectGroup" title="SelectGroup">
			<option value=""></option>
			<?php
			foreach ($groups as $key => $groupname) {
				?>
				<option value="<?php echo $groupname; ?>">
					<?php echo $groupname; ?>
				</option>
			<?php
			}
			?>
		</select>
		<select name="SelectUser" title="SelectUser">
			<option value=""></option>
			<?php
			foreach ($users as $key => $username) {
				?>
				<option value="<?php echo $username; ?>">
					<?php echo $username; ?>
				</option>
			<?php
			}
			?>
		</select>
		<button onclick="javascipt:document.getElementById('SelectGroupForm').submit();" class="btn btn-info">Add User
			to Group
		</button>
	</form>
	<br>
	<form name="SelectGroupPath" method="post" action="<?php echo $url_action; ?>">
		<select name="SelectGroupPath" title="SelectGroupPath">
			<option value=""></option>
			<?php
			foreach ($groups as $key => $groupname) {
				?>
				<option value="<?php echo $groupname; ?>">
					<?php echo $groupname; ?>
				</option>
			<?php
			}
			?>
		</select>
		<select name="SelectPath" title="SelectPath">
			<option value=""></option>
			<?php
			foreach ($paths as $key => $pathname) {
				?>
				<option value="<?php echo $pathname; ?>">
					<?php echo $pathname; ?>
				</option>
			<?php
			}
			?>
		</select>
		<button onclick="javascipt:document.getElementById('SelectGroupPath').submit();" class="btn btn-info">Add Path
			to Group
		</button>
	</form>
	<br>
	<table class="table table-sm">
		<tbody>
			<?php
			foreach ($group_users as $group_name => $array_of_users) {
				?>
				<tr>
					<th scope="row"><a href="<?php echo $url_action; ?>?remove_group=<?php echo $group_name; ?>">
							<?php echo $group_name; ?>
					</th>
					<?php
					foreach ($array_of_users as $key => $user_name) {
						?>
						<td>
							<a
								href="<?php echo $url_action; ?>?remove_user_from_group=<?php echo $group_name; ?>&username_to_remove=<?php echo $user_name; ?>">
								<?php echo $user_name; ?>
							</a>
						</td>
					<?php
					}
					?>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<br>
	<table class="table table-sm">
		<tbody>
			<?php
			foreach ($group_paths as $group_name => $array_of_paths) {
				?>
				<tr>
					<th scope="row"><a href="<?php echo $url_action; ?>?remove_group_from_path=<?php echo $group_name; ?>">
							<?php echo $group_name; ?>
						</a>
					</th>
					<?php
					foreach ($array_of_paths as $key => $path_name) {
						?>
						<td>
							<a
								href="<?php echo $url_action; ?>?remove_path_from_group=<?php echo $group_name; ?>&path_to_remove=<?php echo "<IGNORE_REPLACE>" . $path_name . "</IGNORE_REPLACE>"; ?>">
								<?php echo $path_name; ?>
							</a>
						</td>
					<?php
					}
					?>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>

	<a href="<?php echo $url_action; ?>?reset=true" class="btn btn-info" role="button">Reset</a>
	<a href="<?php echo $url_action; ?>?save=true" class="btn btn-info" role="button">Save</a>

<?php
}
?>