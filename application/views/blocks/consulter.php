<div id="consulter" class="block ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">Consulter</div>
	<div class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		<ul>
			<li><a href="<?=base_url();?>groupe/liste">Tous les groupes</a></li>
			<li><a href="<?=base_url();?>utilisateur/liste">Tous les utilisateurs</a></li>
			<li><a href="<?=base_url();?>publication/recente">Publications récentes</a></li>
			<?php if($user_connected): ?>
				<li><a href="<?=base_url();?>publication/mes_publications">Mes publications</a></li>
			<?php endif;?>
		</ul>
	</div>
</div>