<?php

/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

// the load tab is done first because I have to link the menus with the tabs

// find tab group name based on request
$tab_group_name = "";
$index_tab = 1;
foreach ($tab_links as $tab_group => $array_of_tab) {
	foreach ($array_of_tab as $tab_text => $array_of_tab_link) {
		foreach ($array_of_tab_link as $tab_link) {
			$tab_link_final = "";
			if (!preg_match('/http:\/\/|https:\/\//i', $tab_link)) {
				$tab_link_final = $tab_link;
			} else {
				$tab_link_final = "javascript:openUrlBlank('" .
					$tab_link . "')";
			}
			if ($request_uri_without_reverse_proxy == $tab_link_final) {
				// save current tab based on request
				$tab_group_name = $tab_group;
			}
		}
	}
}

if (strlen($tab_group_name) > 0) {
	?>
	<ul class="nav nav-tabs nav-tabs-cards" id="card-simple" role="tablist" style="font-size: 16px;">
		<?php
		foreach ($tab_links as $tab_group => $array_of_tab) {
			if ($tab_group == $tab_group_name) {
				// only first link when tab click event happens
				// but tab_text only one equal to request in group
				foreach ($array_of_tab as $tab_text => $array_of_tab_link) {
					// don't display the menu item which is only used to connect the tabs to the menu item that calls them ( file.menu )
					// is set to load_tab.php
					if ($tab_text != "menu") {
						$tab_selected = "";
						$first_tab_link = "";
						foreach ($array_of_tab_link as $tab_link) {
							$tab_link_final = "";
							if (!preg_match('/http:\/\/|https:\/\//i', $tab_link)) {
								$tab_link_final = $tab_link;
							} else {
								$tab_link_final = "javascript:openUrlBlank('" .
									$tab_link . "')";
							}
							// this will be the link to the page when the tab is clicked
							if (strlen($first_tab_link) == 0) {
								$first_tab_link = $reverse_proxy . $tab_link_final;
							}
							if ($request_uri_without_reverse_proxy == $tab_link_final) {
								$tab_active = "active";
								$tab_selected = $tab_text;
							} else {
								$tab_active = "";
							}
						}
						if (strlen($tab_selected) > 0) {
							$tab_active = "active";
						} else {
							$tab_active = "";
						}
						?>
						<li class="nav-item">
							<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
								<a class="nav-link <?php echo $tab_active; ?>" id="<?php echo $first_tab_link; ?>" data-bs-toggle="tab"
									href="#card-simpletab<?php echo $index_tab; ?>" role="tab"
									aria-controls="card-simpletab<?php echo $index_tab; ?>" aria-selected="true">
							<?php }else{ ?>
								<a class="nav-link <?php echo $tab_active; ?>" id="<?php echo $first_tab_link; ?>" data-toggle="tab"
									href="#card-simpletab<?php echo $index_tab; ?>" role="tab"
									aria-controls="card-simpletab<?php echo $index_tab; ?>" aria-selected="true">
							<?php } ?>
								<?php echo $tab_text; ?>
							</a>
						</li>
						<?php
						$index_tab++;
					}
				}
			}
		}

		?>
		<li class="nav-item-filler"></li>
	</ul>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#card-simple a').on('click', function (e) {
				e.preventDefault()
				window.location.href = this.id;
			});
		});
	</script>
<?php
}
?>