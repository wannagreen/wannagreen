<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<?php if($liste == 'publications_recentes') :?> 
			<h1>Publications récentes</h1>
		<?php endif;?> 
		<?php if($liste == 'mes_publications') :?> 
			<h1>Mes publications</h1>
		<?php endif;?> 
	</div>
	<div id="publication_liste" class="listing block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		<div class="description listing">
		<?php if(isset($liste_publications) && count($liste_publications) > 0) : foreach($liste_publications as $publication): ?>
			
			<?php if($liste == 'mes_publications' || $publication->id_utilisateur == $this->session->userdata('id_utilisateur')):
						if ($publication->type == 'article') :?> 
							<p><a href="<?= base_url() ?>publication/modification_publication/<?= $publication->id_publication?>" class="edit" id="clic_modif_article">Modifier</a></p>
				<?php endif; ?>
					<p><a href="<?= base_url() ?>publication/supprimer/<?=$liste ?>/<?= $publication->id_publication?>" class="delete" id="clic_suppr_publication">Supprimer</a></p>
			<?php endif;?> 
			
			<?php if($publication->visible) :?> 
			<div class="publication">
				Publié dans le(s) groupe(s) :				
				<?php foreach($publication->groupe as $groupe) : ?>
					<a href="<?= base_url() ?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a>
				<?php endforeach; ?>
				<br><br>
				<?php if(isset($publication->info) && count($publication->info) > 0) : foreach($publication->info as $info) : 
						if($info->libelle == 'titre') : ?>
						<h3 class="nom"><?= $info->contenu ?></h3>
						<?php endif;
						if($info->libelle == 'url') : ?>
						<a href=<?= $info->contenu ?> class="_blank"><?= $info->contenu ?></a>
						<?php endif;
						if($info->libelle == 'description') : ?>
						<p><?= html_entity_decode($info->contenu) ?></p>
						<?php endif;
						if($info->libelle == 'date') : ?>
						<p class="date"><?= time_to_str($info->contenu) ?> par <a href="<?= base_url() ?>utilisateur/profil/<?= $publication->id_utilisateur?>"><?= $publication->prenom.' '.$publication->nom ?></a></p>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php endif;?>
				<br>
				<?php
					if(count($publication->tags) > 0): ?>
						<strong>Tags : </strong>
					<?php foreach($publication->tags as $tag): ?>
						<span class="tag_link"><a href="<?= base_url().'tag/search/'.$tag->id_tag ?>"><?= $tag->libelle ?></a></span>
					<?php endforeach;
					endif; ?>
			</div>
			
			<?php endif;?> 
		<?php endforeach;
		else: ?>
			<p>Il n'y a aucune publication</p>
		<?php
		endif;
		?>
		</div>
	</div>
</div>
