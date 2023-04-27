<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$ok = false;
$mobile = false;

// redirect to homepage
if ($request_without_reverse_proxy == "/")
	$request_without_reverse_proxy = $home_page;

// check file name
$ok = is_file_allowed($file_enabled, $log, $request_without_reverse_proxy);

// check directory
if (!$ok)
	$ok = is_path_allowed(
		$request_without_reverse_proxy,
		$username,
		$log,
		$site_document_root,
		$dir_enabled,
		$reverse_proxy,
		$config_file
	);

if ($ok) {
	if (file_exists($site_document_root . $request_without_reverse_proxy)) {
		if (is_file($site_document_root . $request_without_reverse_proxy)) {
			if (!$enable_menu_side) {
				?>
				<div class="tab-content" id="card-simpleContent">
				<?php
			}
			if (!$enable_menu_side) {
				// without tab
				// redirect log file to /core/txt2html.php
				$username_before = "";
				$username_after = "";
				$request_ext = pathinfo($request_without_reverse_proxy, PATHINFO_EXTENSION);
				if (isset($_SESSION['username'])) {
					if (strlen($_SESSION['username']) > 0) {
						$username_before = $_SESSION['username'];
					}
				}
				try {
					if ($request_ext == "log") {
						//require $site_document_root ."/core/txt2html.php";
						echo require_ob(
							$site_document_root . "/core/txt2html.php",
							$reverse_proxy,
							$site_document_root,
							$menu_navs_link,
							$current_menu,
							$menu_navs,
							$enable_menu_side,
							$file_enabled,
							$dir_enabled,
							$current_group,
							$search,
							$home_page,
							$login_page,
							$username,
							$request_without_reverse_proxy,
							$mobile,
							$config_file,
							$environment,
							$log
						);
					} else {
						if ($request_without_reverse_proxy == "/core/user_group.php" || in_array($request_without_reverse_proxy, $no_replace_pages)) {
							require $site_document_root . $request_without_reverse_proxy;
						} else {

							echo require_ob(
								$site_document_root . $request_without_reverse_proxy,
								$reverse_proxy,
								$site_document_root,
								$menu_navs_link,
								$current_menu,
								$menu_navs,
								$enable_menu_side,
								$file_enabled,
								$dir_enabled,
								$current_group,
								$search,
								$home_page,
								$login_page,
								$username,
								$request_without_reverse_proxy,
								$mobile,
								$config_file,
								$environment,
								$log
							);

						}
					}
				} catch (Exception $e) {
					error_log($e);
				}
				if (isset($_SESSION['username'])) {
					if (strlen($_SESSION['username']) > 0) {
						$username_after = $_SESSION['username'];
					}
				}
				if ($username_before != $username_after) {
					error_log("tentativo da #" . $username_before . "# a #" . $username_after . "#");
					if (strlen($username_after) > 0) {
						if (is_admin($username_after, $site_document_root, $config_file)) {
							if (strlen($username_before) == 0) {
								error_log("unsetto username");
								unset($_SESSION['username']);
							} else {
								error_log("rimetto a " . $username_before);
								$_SESSION['username'] = $username_before;
							}
						}
					}
				}
			} else {
				// with tab
				$index_tab = 1;
				?>
					<div class="tab-pane p-4 fade show active" id="card-simpletab<?php echo $index_tab; ?>" role="tabpanel"
						aria-labelledby="card-simple<?php echo $index_tab; ?>-tab">
						<?php
						// redirect log file to /core/txt2html.php
						$username_before = "";
						$username_after = "";
						$request_ext = pathinfo($request_without_reverse_proxy, PATHINFO_EXTENSION);
						if (isset($_SESSION['username'])) {
							if (strlen($_SESSION['username']) > 0) {
								$username_before = $_SESSION['username'];
							}
						}
						try {
							if ($request_ext == "log") {
								echo require_ob(
									$site_document_root . "/core/txt2html.php",
									$reverse_proxy,
									$site_document_root,
									$menu_navs_link,
									$current_menu,
									$menu_navs,
									$enable_menu_side,
									$file_enabled,
									$dir_enabled,
									$current_group,
									$search,
									$home_page,
									$login_page,
									$username,
									$request_without_reverse_proxy,
									$mobile,
									$config_file,
									$environment
								);
							} else {
								if ($request_without_reverse_proxy == "/core/user_group.php" || in_array($request_without_reverse_proxy, $no_replace_pages)) {
									require $site_document_root . $request_without_reverse_proxy;
								} else {
									echo require_ob(
										$site_document_root . $request_without_reverse_proxy,
										$reverse_proxy,
										$site_document_root,
										$menu_navs_link,
										$current_menu,
										$menu_navs,
										$enable_menu_side,
										$file_enabled,
										$dir_enabled,
										$current_group,
										$search,
										$home_page,
										$login_page,
										$username,
										$request_without_reverse_proxy,
										$mobile,
										$config_file,
										$environment,
										$log
									);
								}
							}
						} catch (Exception $e) {
							error_log($e);
						}
						if (isset($_SESSION['username'])) {
							if (strlen($_SESSION['username']) > 0) {
								$username_after = $_SESSION['username'];
							}
						}
						if ($username_before != $username_after) {
							error_log("tentativo da #" . $username_before . "# a #" . $username_after . "#");
							if (strlen($username_after) > 0) {
								if (is_admin($username_after, $site_document_root, $config_file)) {
									if (strlen($username_before) == 0) {
										error_log("unsetto username");
										unset($_SESSION['username']);
									} else {
										error_log("rimetto a " . $username_before);
										$_SESSION['username'] = $username_before;
									}
								}
							}
						}
						?>
					</div>
				<?php
			}

			if (!$enable_menu_side) {
				?>
				</div> <!-- check.php tab-content -->
			<?php
			}
		}
	} else {
		error_log("check file request NOT FOUND " . $site_document_root . $request_without_reverse_proxy);
		echo "NOT FOUND!";
	}
} else {
	// the requested page is not allowed and therefore is stored to be redirected after login
	set_page_not_allowed($request_without_reverse_proxy);
	error_log("check NOT ALLOWED " . $site_document_root . $request_without_reverse_proxy);
	require $site_document_root . "/core/login.php";
}
?>