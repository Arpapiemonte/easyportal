<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

$log = false;
//$log = true;

if ($enable_menu_side) {

	// create html side menu
	foreach ($menu_navs as $nav_text => $menu_groups) {
		if ($nav_text == $current_menu) {
			$group_active = "active";
			$group_show = "show";
			$group_expanded = "true";
		} else {
			$group_active = "";
			$group_show = "";
			$group_expanded = "false";
		}
		$my_nav_text = str_replace(" ", "_", $nav_text);
		?>
		<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
			<a class="list-item large medium right-icon <?php echo $group_active; ?>" 
				href="#collapse_<?php echo $my_nav_text; ?>"
				data-bs-toggle="collapse" aria-expanded="<?php echo $group_expanded; ?>" 
				aria-controls="collapse_<?php echo $my_nav_text; ?>" data-focus-mouse="false">
				<span>
					<?php echo $nav_text; ?>
		<?php }else{ ?>
			<a class="list-item large medium right-icon <?php echo $group_active; ?>" 
				href="#collapse_<?php echo $my_nav_text; ?>"
				data-toggle="collapse" aria-expanded="<?php echo $group_expanded; ?>" 
				aria-controls="collapse_<?php echo $my_nav_text; ?>" data-focus-mouse="false">
				<span>
					<?php echo $nav_text; ?>
		<?php } ?>
				</span>
				<svg class="icon icon-sm icon-primary right" aria-hidden="true">
					<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
						<use xlink:href="<?php echo $reverse_proxy; ?>/svg/sprites.svg#it-expand"></use>
					<?php }else{ ?>
						<use xlink:href="<?php echo $reverse_proxy; ?>/svg/sprite.svg#it-expand"></use>
					<?php } ?>
				</svg>
			</a>
		<?php
		foreach ($menu_groups as $group_text => $menu_sides) {
			//ksort($menu_sides);			
			?>
			<li>
				
				<ul class="link-sublist collapse <?php echo $group_show; ?>" 
					id="collapse_<?php echo $my_nav_text; ?>">
					<?php
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
						?>
						<li>
							<a class="list-item" href="<?php echo $side_link_final; ?>">
								<span>
									<?php echo $side_text; ?>
								</span>
							</a>
						</li>
					<?php
					}
					?>
				</ul>
			</li>
		<?php
		}
	}
}
?>