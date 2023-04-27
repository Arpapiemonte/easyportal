<?php
/**
 * Copyright (C) 2019-2023 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */
?>
<!doctype html>

<html lang="it">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="EasyPortal based on bootstrap-italia" />
	<meta name="author" content="Dipartimento rischi naturali e ambientali" />
	<!-- last Interne Explorer compatibility -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php echo env('TITLE', '') ?></title>
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo $reverse_proxy ?>/css/bootstrap-italia.min.css" />
	<link rel="stylesheet" href="<?php echo $reverse_proxy ?>/css/height.css">
	<!-- Favicons -->
	<link rel="icon" href="<?php echo $reverse_proxy ?>/bootstrap-italia/favicon.ico" />
	<link rel="icon" href="<?php echo $reverse_proxy ?>/bootstrap-italia/docs/assets/img/favicons/favicon-32x32.png"
		sizes="32x32" type="image/png" />
	<link rel="icon" href="<?php echo $reverse_proxy ?>/bootstrap-italia/docs/assets/img/favicons/favicon-16x16.png"
		sizes="16x16" type="image/png" />
	<link rel="mask-icon"
		href="<?php echo $reverse_proxy ?>/bootstrap-italia/docs/assets/img/favicons/safari-pinned-tab.svg"
		color="#0066CC" />
	<link rel="apple-touch-icon"
		href="<?php echo $reverse_proxy ?>/bootstrap-italia/docs/assets/img/favicons/apple-touch-icon.png" />
	<!--<link rel="manifest" href="<?php echo $reverse_proxy ?>/bootstrap-italia/site.webmanifest" />-->
	<meta name="msapplication-config" content="<?php echo $reverse_proxy ?>/bootstrap-italia/browserconfig.xml" />
	<meta name="theme-color" content="#0066CC" />
	<!--
	<script type="text/javascript">
		var _paq = window._paq = window._paq || [];
		/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function() {
				var u="https://ingestion.webanalytics.italia.it/";
				_paq.push(['setTrackerUrl', u+'matomo.php']);
				_paq.push(['setSiteId', 'kjpRNRPp1y']);
				var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
				g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
		})();
	</script>
	-->
</head>

<body>
	<script src="<?php echo $reverse_proxy ?>/js/jquery.min.js"></script>
	<script src="<?php echo $reverse_proxy ?>/js/popper.min.js"></script>
	<!-- tabulator requirements for IE11 -->
	<script src="<?php echo $reverse_proxy ?>/js/polyfill.min.js"></script>
	<script type="module" src="<?php echo $reverse_proxy ?>/js/fetch-3.6.1/fetch.js"></script>
	<!-- javascript per aprire link esterni -->
	<script type="text/javascript">
		function openUrlBlank(url) {
			window.open(url, "_blank");
		}

		function open_popup(url) {
			window.open(url, "_blank", "width=800, height=580");
		}
	</script>
	<header class="it-header-wrapper">
		<!--<div class="it-header-slim-wrapper theme-light">-->
		<div class="it-header-slim-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="it-header-slim-wrapper-content">
							<a class="d-none d-lg-block navbar-brand" href="#">
								<?php echo env('ORG_NAME', '') ?>
							</a>
							<span class="nav-mobile">
								<nav aria-label="Navigazione secondaria">
									<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
										<a class="it-opener d-lg-none" data-bs-toggle="collapse" href="#menu-principale"
											role="button" aria-expanded="false" aria-controls="menu1">
									<?php }else{ ?>
										<a class="it-opener d-lg-none" data-toggle="collapse" href="#menu-principale"
											role="button" aria-expanded="false" aria-controls="menu1">
									<?php } ?>
											<span><?php echo env('ORG_DEPARTMENT', '') ?></span>
											<svg class="icon">
												<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
													<use xlink:href="<?php echo $reverse_proxy ?>/svg/sprites.svg#it-expand"></use>
												<?php }else{ ?>
													<use xlink:href="<?php echo $reverse_proxy ?>/svg/sprite.svg#it-expand"></use>
												<?php } ?>
											</svg>
										</a>
									<?php
									$is_admin = is_admin($username, $site_document_root, $config_file);
									if (!empty(env("NEWS_PROVIDER")) || $is_admin) {
										?>
										<div class="link-list-wrapper collapse" id="menu-principale">
											<ul class="link-list">
												<?php
												if (!empty(env("NEWS_PROVIDER"))) {
													?>
													<li><a class="list-item"
															href="<?php echo $reverse_proxy ?>/core/news/<?php echo env("NEWS_PROVIDER", "") ?>">Novità</a>
													</li>
												<?php
												}
												if ($is_admin) {
													?>
													<li><a class="list-item"
															href="<?php echo $reverse_proxy ?>/core/user_group.php">Admin</a>
													</li>
												<?php
												}
												?>
											</ul>
										</div>
									<?php
									}
									?>
								</nav>
							</span>
							<div class="header-slim-right-zone">
								<div class="it-access-top-wrapper">
									<?php
									if (strlen($username) > 0) {
										?>
										<a href="<?php echo $reverse_proxy ?>/core/logout.php"
											class="btn btn-primary btn-sm" type="button">Logout <?php echo $_SESSION['username']; ?></a>
										<?php
										if (!empty(env("CHANGE_PASSWORD_PROVIDER", null))) {
											?>
											<a href="<?php echo $reverse_proxy ?>/core/change_pwd.php"
												class="btn btn-primary btn-sm" type="button">Cambia password</a>
										<?php
										}
									} else {
										?>
										<a href="<?php echo $reverse_proxy ?>/core/login.php" class="btn btn-primary btn-sm"
											type="button">Accedi</a>
									<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- START OF HEADER WITH LOGO SOCIAL AND SEARCH -->
		<div class="it-nav-wrapper">
			<!--<div class="it-header-center-wrapper theme-light" style="height:80px;">-->
			<div class="it-header-center-wrapper" style="height:80px;">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="it-header-center-content-wrapper">
								<div class="it-brand-wrapper">
									<!-- cliccando sul logo si torna alla home page -->
									<a href=<?php echo $reverse_proxy . "/" ?>>
										<img src="<?php echo $reverse_proxy . '/' . env('ORG_LOGO') ?>" width="100px">
										&nbsp;&nbsp;
										<div class="it-brand-text">
											<div class="it-brand-title">
												<?php echo env('ORG_DEPARTMENT', '') ?>
											</div>
											<div class="it-brand-tagline d-none d-md-block"></div>
										</div>
									</a>
								</div>
								<div class="it-right-zone">
									<?php if (!empty($social_links)): ?>
										<div class="it-socials d-none d-md-flex">
											<span>Seguici su</span>
											<ul>
												<?php foreach ($social_links as $social_name => $link): ?>
													<?php if (!empty($social_name) && !empty($link)): ?>
														<li>
															<a href="<?php echo $link ?>" aria-label="<?php echo $social_name ?>"
																target="_blank">
																<svg class="icon">
																	<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
																		<use xlink:href="<?php echo $reverse_proxy ?>/svg/sprites.svg#it-<?php echo $social_name ?>"></use>
																	<?php }else{ ?>
																		<use xlink:href="<?php echo $reverse_proxy ?>/svg/sprite.svg#it-<?php echo $social_name ?>"></use>
																	<?php } ?>
																</svg>
															</a>
														</li>
													<?php endif; ?>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endif; ?>
									<div class="it-search-wrapper">
										<span class="d-none d-md-block">Cerca</span>
										<a class="search-link rounded-icon" aria-label="Cerca nel sito"
											href="<?php echo $reverse_proxy ?>/core/search_html.php">
											<svg class="icon">
												<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
													<use xlink:href="<?php echo $reverse_proxy ?>/svg/sprites.svg#it-search"></use>
												<?php }else{ ?>
													<use xlink:href="<?php echo $reverse_proxy ?>/svg/sprite.svg#it-search"></use>
												<?php } ?>
											</svg>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--<div class="it-header-navbar-wrapper theme-light-desk">-->
			<div class="it-header-navbar-wrapper">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<nav class="navbar navbar-expand-lg has-megamenu" aria-label="Navigazione principale">
								<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
									<button class="custom-navbar-toggler" type="button" aria-controls="navC1"
										aria-expanded="false" aria-label="Mostra/Nascondi la navigazione"
										data-bs-toggle="navbarcollapsible" data-bs-target="#navC1">
								<?php }else{ ?>
									<button class="custom-navbar-toggler" type="button" aria-controls="navC1"
										aria-expanded="false" aria-label="Mostra/Nascondi la navigazione"
										data-toggle="navbarcollapsible" data-target="#navC1">
								<?php } ?>
									<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
										<svg class="icon">
											<use xlink:href="<?php echo $reverse_proxy; ?>/svg/sprites.svg#it-burger"></use>
										</svg>
									<?php }else{ ?>
										<svg class="icon">
											<use xlink:href="<?php echo $reverse_proxy; ?>/svg/sprite.svg#it-burger"></use>
										</svg>
									<?php } ?>
								</button>
								<div class="navbar-collapsable" id="navC1" style="display: none">
									<div class="overlay" style="display: none"></div>
									<div class="close-div">
										<button class="btn close-menu" type="button">
											<span class="visually-hidden">Nascondi la navigazione</span>
											<svg class="icon">
											<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
												<use href="<?php echo $reverse_proxy ?>/svg/sprites.svg#it-close-big">
												</use>
											<?php }else{ ?>
												<use href="<?php echo $reverse_proxy ?>/svg/sprite.svg#it-close-big">
												</use>
											<?php } ?>
											</svg>
										</button>
									</div>
									<div class="menu-wrapper">
										<ul class="navbar-nav">
											<?php
											// load menu
											require $site_document_root . '/core/menu.php';
											// render menu
											require $site_document_root . "/core/menu_html_nav.php";
											$min_height = "style=\"min-height:100%;\"";
											?>
										</ul>
									</div>
								</div>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
	
	<?php
		if ($enable_menu_side) {
	?>
	<div class="row flex-xl-nowrap" <?php echo $min_height; ?>>
	<?php
		}
	?>
		<?php
		if ($enable_menu_side) {
		?>
		<!-- sidebar -->
		<div class="col-12 col-md-3 col-xl-2 bd-sidebar">
			<div class="sidebar-wrapper">
				<div class="sidebar-linklist-wrapper">
					<div class="link-list-wrapper">
						<ul class="link-list">
							<?php require $site_document_root . "/core/menu_html_side.php"; ?>
						</ul>
					</div> 
				</div>
			</div>
		</div>
		<?php
			}
		?>
		
		<?php
		if ($enable_menu_side) {
		?>
		<!-- main -->
		<div class="col-12 col-md-9 col-xl-8 py-md-3 px-md-3 db-content">
		<?php
			}else{
		?>
		<div class="container my-4" <?php echo $min_height; ?>>
		<?php
			}
		?>
		<!--<div class="col-12 col-lg-12">-->
			<!-- container index.php without menu_side -->
			<?php
			// render tab
			require $site_document_root . '/core/tab_header.php';
			// unless it's the user management page, in this case I'm not interested in performing replaces on the url links/images/etc
			//if ( $request_without_reverse_proxy == "/core/user_group.php" || in_array($request_without_reverse_proxy,$no_replace_pages) ){
			// exclude reverse_proxy replaces
			require $site_document_root . '/core/check.php';
			//}else{
			// load the content of the requested page via request
			/*
			echo require_ob($site_document_root . '/core/check.php', $reverse_proxy,$site_document_root,
			$menu_navs_link,$current_menu,$menu_navs,$enable_menu_side, $file_enabled,
			$dir_enabled,$current_group,$search,$home_page,$login_page,$username,
			$request_without_reverse_proxy,$mobile,$config_file);
			*/
			//}
			
			if (!empty(env('PAGE_CONTENT'))) {
				require $site_document_root . '/core/content/' . env('PAGE_CONTENT');
			}
			?>
		</div>
	</div>

	<footer class="it-footer">
		<div class="it-footer-small-prints clearfix">
			<div class="container">
				<h3 class="visually-hidden">Sezione Link Utili</h3>
				<?php if (!empty($footer_links)): ?>
					<ul class="it-footer-small-prints-list list-inline mb-0 d-flex flex-column flex-md-row">
						<?php foreach ($footer_links as $title => $link): ?>
							<?php if (!empty($link) && !empty($title)): ?>
								<li class="list-inline-item">
									<a href="<?php echo $link; ?>" title="<?php echo $title; ?>">
										<?php echo $title; ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</footer>
	<script>
		window.__PUBLIC_PATH__ = '<?php echo $reverse_proxy ?>/fonts'
	</script>
	<script src="<?php echo $reverse_proxy ?>/js/bootstrap-italia.bundle.min.js"></script>
	<?php if (env('BOOTSTRAP_ITALIA_VERSION')==2) { ?>
		<script>
			bootstrap.loadFonts('/bootstrap-italia/dist/fonts')
		</script>
	<?php } ?>

</body>

</html>