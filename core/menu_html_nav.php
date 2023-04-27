<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

foreach ($menu_navs_link as $menu_nav => $menu_side) {

	// menu configuration file third field ;NAV4#NAV4; [0] => HEADING, [1] => li html source
	$li_source = array(
		"NAV1" => array("", ""),
		"NAV2" => array("", ""),
		"NAV3" => array("", ""),
		"NAV4" => array("", "")
	);

	// if $current_menu is undefined then set current_menu at the first menu_nav
	if (strlen($current_menu) == 0) {
		$current_menu = $menu_nav;
	}

	// is menu $menu_nav group NAV or default side?
	$link_on_nav = false;
	foreach ($menu_navs as $nav_text => $menu_groups) {
		if ($menu_nav == $nav_text) {
			ksort($menu_groups);
			foreach ($menu_groups as $group_text => $menu_sides) {
				//ksort($menu_sides);
				if (strlen($group_text) > 0) {
					$explode_group_text = explode("#", $group_text);
					if (count($explode_group_text) == 2) {
						if (
							$explode_group_text[0] == "NAV1" || $explode_group_text[0] == "NAV2" ||
							$explode_group_text[0] == "NAV3" || $explode_group_text[0] == "NAV4"
						) {
							if ($log)
								error_log("menu_html searching for group_text " . $explode_group_text[0] . " find with menu_nav=" . $menu_nav);
							$link_on_nav = true;
							foreach ($menu_sides as $side_text => $side_link) {
								//$pos = strpos($side_link, "http://");
								$side_link_final = "";
								if (!preg_match('/http:\/\/|https:\/\//i', $side_link)) {
									//if ($pos === false) {
									$side_link_final = $reverse_proxy . $side_link;
								} else {
									$side_link_final = "javascript:openUrlBlank('" .
										$side_link . "')";
								}
								$li_source[$explode_group_text[0]][0] = $explode_group_text[1];
								$li_source[$explode_group_text[0]][1] =
									$li_source[$explode_group_text[0]][1] .
									"<li>\n" .
									"\t<a class=\"dropdown-item list-item\" href=\"" . $side_link_final . "\">\n" .
									"\t\t<span>" . $side_text . "</span>\n" .
									"\t</a>\n" .
									"</li>\n";
							}
						}
					}
				}
			}
		}
	}

	// design of nav menus with navs not exploded
	if ($link_on_nav) {
		// menu on nav
		if ($menu_nav == $current_menu) {
			//$create_menu_side = false;
			$nav_active = "active";
		} else {
			$nav_active = "";
		}
		if ($log)
			error_log("menu_html create link_on_nav with menu_nav=" . $menu_nav);
		$nav_show = "";
		//$nav_show="show";
		$nav_megamenu = "megamenu";
		//$nav_megamenu="";
		$nav_expanden = "false";
		//$nav_expanden="true";
		$nav_dropdown_show = "";
		//$nav_dropdown_show="show";
		?>

		<li class="nav-item dropdown <?php echo $nav_megamenu; ?> 		<?php echo $nav_show; ?>">
			<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
				<a class="nav-link dropdown-toggle <?php echo $nav_active; ?>" href="#" data-bs-toggle="dropdown"
					aria-expanded="<?php echo $nav_expanden; ?>" id="mainNavDropdownC1">
			<?php }else{ ?>
				<a class="nav-link dropdown-toggle <?php echo $nav_active; ?>" href="#" data-toggle="dropdown"
					aria-expanded="<?php echo $nav_expanden; ?>" id="mainNavDropdownC1">
			<?php } ?>
				<span>
					<?php echo $menu_nav; ?>
				</span>
				<svg class="icon icon-xs">
					<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
						<use xlink:href="<?php echo $reverse_proxy; ?>/svg/sprites.svg#it-expand"></use>
					<?php }else{ ?>
						<use xlink:href="<?php echo $reverse_proxy; ?>/svg/sprite.svg#it-expand"></use>
					<?php } ?>
				</svg>
			</a>
			<div class="dropdown-menu <?php echo $nav_dropdown_show; ?>" role="region" aria-labelledby="mainNavDropdownC1">
				<div class="row">
					<?php
					foreach ($li_source as $li_source_key => $li_source_value) {
						if (strlen($li_source_value[1]) > 0) {
							?>
							<div class="col-12 col-lg-4">
								<div class="link-list-wrapper">
									<div class="link-list-heading">
										<?php echo $li_source_value[0]; ?>
									</div>
									<ul class="link-list">
										<?php echo $li_source_value[1]; ?>
									</ul>
								</div>
							</div>
						<?php
						}
					}
					?>
				</div>
			</div>
		</li>
	<?php
	} else {
		if ($menu_nav == $current_menu) {
			?>
			<li class="nav-item active">
				<a class="nav-link active" href="<?php echo $reverse_proxy . $menu_side; ?>">
					<span>
						<?php echo $menu_nav; ?>
					</span>
					<!--<span class="sr-only">current</span>-->
				</a>
			</li>
		<?php
		} else {
			?>
			<li class="nav-item ">
				<a class="nav-link " href="<?php echo $reverse_proxy . $menu_side; ?>">
					<span>
						<?php echo $menu_nav; ?>
					</span>
				</a>
			</li>
		<?php
		}
	}
}

?>