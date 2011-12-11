<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<?php if($user_connected): ?>
			<h1>Modification du profil</h1>
		<?php else: ?>
			<h1>Création d'un compte utilisateur</h1>
		<?php endif; ?>
	</div>
	<div id="utilisateur_confirmation" class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		<?php
			if(isset($context)) echo $context;
			else $this->load-view('notice', array('notice_type' => 'info', 'notice' => 'Aucun message à afficher'));
		?>
	</div>
</div>
