<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<h1>Tous les utilisateurs</h1>
	</div>
	<div id="utilisateur_liste" class="listing block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
	<?php if(isset($context)) echo $context; ?>
	<?php if(isset($utilisateurs)): foreach($utilisateurs as $utilisateur): ?>
		<div class="utilisateur">
			<p class="avatar"><img src="<?= $utilisateur->avatar != null ? img_upload_path().filename_to_thumb($utilisateur->avatar) : img_upload_path().filename_to_thumb('user_default.png') ?>" width="50px" height="50px" alt="Utilisateur <?= $utilisateur->prenom.' '.$utilisateur->nom ?>" /></p>
			<h3 class="nom"><a href="<?= base_url() ?>utilisateur/profil/<?= $utilisateur->id_utilisateur?>"><?= $utilisateur->prenom.' '.$utilisateur->nom ?></a></h3>
			<p class="date">Inscrit(e) le <?= time_to_str($utilisateur->date_creation) ?></p>
		</div>
	<?php endforeach;
		else:
			$this->load-view('notice', array('notice_type' => 'info', 'notice' => "Il n'y a aucun utilisateur à afficher"));
		endif;
	?>
	</div>
</div>
