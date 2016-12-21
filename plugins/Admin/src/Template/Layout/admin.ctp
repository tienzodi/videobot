<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Website Administration</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<?php
			echo $this->Html->meta('icon');

			echo $this->Html->css(array('/admin/assets/admin/css/bootstrap.min', '/admin/assets/admin/css/bootstrap-theme.min', '/admin/assets/admin/css/styles', '/admin/fancybox/css/jquery.fancybox', '/admin/css/bootstrapValidator.min','/admin/strength_password/strength', '/admin/css/custom'));
			echo $this->Html->script(array('/admin/js/jquery-2.1.4.min', '/admin/assets/admin/js/bootstrap.min','/admin/assets/admin/js/main','/admin/fancybox/js/jquery.fancybox','/admin/fancybox/js/jquery.fancybox-media', '/admin/js/bootstrapValidator.min', '/admin/js/validator','/admin/js/tock.min.js','/admin/js/main.js','/admin/strength_password/strength'));
			$jsVars = array(
				'webroot' => $this->request->webroot."admin/",
			);
			echo $this->Html->scriptBlock('var window_app = ' . json_encode($jsVars) . ';'); 
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
            
			//echo $this->Js->writeBuffer();
		?>

		<!--HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
			<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		<?php if (!empty($current_user)) { ?>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo $this->request->webroot; ?>">Administration</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<?php
							foreach ($menu_items as $key => $menu_item) {
								if (empty($menu_item['allow_access']) || in_array($current_user['role'], $menu_item['allow_access']))
                                {
									echo '<li '. ($key == $active_menu_item ? 'class="active"' : '') .'><a href="'. $menu_item['link'] .'">'. $menu_item['text'] .'</a></li>';
                                }
							}
						?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'logout']) ?>">Log out</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>

		</div>
		<?php } ?>
		<div class="container">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
			<div class="alert alert-info" role="alert">
                This system is for the use of authorised users only. User activity may be monitored. Users of this system expressly consent to such monitoring and are advised that if such monitoring reveals possible criminal activity, security staff may provide the evidence of such monitoring to law enforcement officials.
                <br /><br />
                Dit systeem is uitsluitend voor gebruik door geautoriseerde personen. Gebruikersactiviteit kan onderwerp zijn van monitoring. Gebruikers van dit systeem geven middels het inloggen op dit systeem toestemming voor deze monitoring. Mocht monitoring uitwijzen dat er sprake zou kunnen zijn van illegale activiteiten of onrechtmatigheden dan is de security afdeling gerechtigd de gegevens in dit systeem ter beschikking te stellen aan de bevoegde instanties.
                <br /><br />
                Ce système est exclusivement réservé aux utilisateurs autorisés. L'activité des utilisateurs peut être surveillée. Les utilisateurs de ce système acceptent expressément cette surveillance et sont informés que si elle révèle une potentielle activité criminelle, le personnel chargé de la sécurité peut apporter la preuve de cette surveillance aux responsables de l'application de la loi
            </div>
		</div><!-- /.container -->
		<?php 
		  echo $this->element('logoutCountDown');
		?>
		
	<script>
	var SessionExprieIn = '<?php echo $SessionExprieIn ?>';
	</script>
	</body>
</html>