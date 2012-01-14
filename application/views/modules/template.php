<?= doctype(); ?> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title>Wannagreen Platform</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link href="http://fonts.googleapis.com/css?family=Indie+Flower&v1" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/jquery/jquery.custom.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/common.css" />
	<link rel="stylesheet/less" type="text/css" href="<?= base_url() ?>css/style.less" />
	<!--<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/style.css" />-->
	
	<!-- dev -->
	<script type="text/javascript">var BASE_URL = '<?= base_url() ?>';</script>
	<script type="text/javascript" src="<?= base_url() ?>javascript/head.load.min.js"></script>
	<script type="text/javascript">
		head.js('<?= base_url() ?>javascript/less.min.js',
				'http://platform.twitter.com/anywhere.js?id=OwgO03dTn5ajVL5ldaQ&v=1.2',
				'<?= base_url() ?>javascript/jquery.min.js',
				'<?= base_url() ?>javascript/jquery-ui.min.js',
				'<?= base_url() ?>javascript/jquery.cookie.min.js',
				'<?= base_url() ?>javascript/jquery.simplemodal.1.4.1.min.js',
				'<?= base_url() ?>javascript/jquery.placeholder.min.js',
				'<?= base_url() ?>javascript/selectivizr-min.js',
				'<?= base_url() ?>javascript/scripts.js');
	</script>
	
	<!-- online
	<script type="text/javascript" src="<?= base_url() ?>javascript/head.load.min.js"></script>
	<script type="text/javascript">
		head.js('http://platform.twitter.com/anywhere.js?id=OwgO03dTn5ajVL5ldaQ&v=1.2',
				'//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js',
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js',
				'<?= base_url() ?>javascript/jquery.cookie.min.js',
				'<?= base_url() ?>javascript/jquery.simplemodal.1.4.1.min.js',
				'<?= base_url() ?>javascript/jquery.placeholder.min.js',
				'<?= base_url() ?>javascript/selectivizr-min.js',
				'<?= base_url() ?>javascript/scripts.js');
	</script> -->
	
	<link rel="icon" href="<?= base_url() ?>images/icons/group.png" type="image/png" />
</head>
<body>
	<div id="header">
		<div id="center">
			<div id="logo"><a href="<?= base_url() ?>">Wannagreen</a></div>
			<div id="recherche">
				<form action="" method="post">
					<input id="barre_recherche" placeholder="Recherche" type="text" />
					<input type="submit" value="GO"  class="button" />
				</form>
			</div>
			<div id="menu">
				<?php 
					if($user_connected)
						$this->load->view('header_connecte.php');
					else
						$this->load->view('header_connexion.php');
				?>
			</div>
		</div>
	</div>
	<div id="conteneur">
		<div id="contenu">
			<div id="gauche">
				<?php
				if($user_connected) {
					$this->load->view('blocks/groupes.php');
					$this->load->view('blocks/contribuer.php');
				}
				$this->load->view('blocks/consulter.php');
				?>
			</div>
			<div id="droite" class="block">
				<?php
					if(isset($module) && file_exists('application/views/modules/'.$module.'.php')):
						$this->load->view('modules/'.$module.'.php');
					else: ?>
						<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
							<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
								<h1>Module introuvable</h1>
							</div>
							<div id="publication_creer" class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
								<p class="warning">Il n'y a aucun module à charger, ou le module ne peut pas être chargé</p>
							</div>
						</div><?php
					endif;
				?>
			</div>
		</div>
		<div class="clear"></div>
		<div id="footer">
			<p>FJJMNR • &copy; PPD Plateforme Wannagreen • <a href="javascript:;" class="twitter" id="twitter_decouvrir">Faire connaître sur Twitter</a> • <a href="<?= base_url() ?>/lien/mentions_legales">Mentions légales</a> • <a href="#">Retour en haut</a></p>
		</div>
	</div>
</body>
</html>
