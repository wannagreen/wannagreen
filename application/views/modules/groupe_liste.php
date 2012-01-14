<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<h1>Tous les groupes</h1>
	</div>
	<div id="groupe_liste" class="listing block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
	<?php if(isset($groupes)): foreach($groupes as $groupe): ?>
		<div class="groupe">
			<p class="avatar"><img src="<?= $groupe->avatar != null ? img_upload_path().filename_to_thumb($groupe->avatar) : img_upload_path().filename_to_thumb('group_default.png') ?>" width="50px" height="50px" alt="Groupe <?= $groupe->nom ?>" /></p>
			<h3 class="nom"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a></h3>
			<p>
				<span class="nbpartenaires"><strong><?= $groupe->nb_partenaires ?></strong> <?= plural('groupe', $groupe->nb_partenaires) . plural(' partenaire', $groupe->nb_partenaires) ?></span>
				<span class="nbmembres"><strong><?= $groupe->nb_membres ?></strong> <?= plural('membre', $groupe->nb_membres) ?></span>
				<span class="nbfavoris"><strong><?= $groupe->nb_favoris ?></strong> <?= plural('favori', $groupe->nb_favoris) ?></span>
			</p>
			<p class="description"><?= character_limiter($groupe->description, 60) ?></p>
		</div>
	<?php
		endforeach;
		else:
			$this->load-view('notice', array('notice_type' => 'info', 'notice' => "Il n'y a aucun groupe à afficher"));
		endif;
	?>
	</div>
</div>
