<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAKmeySfm8d1vUqvFQXc4G4BTI5KWAflYCsY9uh3NdOQum1dxXxBRdphD8px44o9tSgIXMAbhiHHof9A"></script>
<!--online:
<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAKmeySfm8d1vUqvFQXc4G4BS7GLwa-Cv_qXqb8x7KTdicjA1TxxRvMqHnbyHAS6Eg-D09G4U9alVWOw"></script>
-->
<script type="text/javascript" src="<?= base_url() ?>javascript/gmap_render.js"></script>
<div id="module">
	<?php if(isset($groupe)): ?>
		<div id="groupe_details" class="profil_details">
			<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<h2 class="nom"><?= $groupe->nom ?></h2>
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/infos">Infos</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/partenaires">Partenaires</a></li>
					<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/members">Membres</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/publications">Publications</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/carte">Carte</a></li>
					<?php
					if(isset($est_admin) && $est_admin) : ?>
						<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/administration">Administration</a></li>
					<?php
					endif;?>
				</ul>
				
			</div>
                    
                    <div id="tabs-3">
					<div id="membre_liste" class="description listing">
					<?php if(isset($liste_membres) && count($liste_membres) > 0): foreach($liste_membres as $membre): ?>
						<div class="utilisateur">
							<p class="avatar"><img src="<?= $membre->avatar != null ? img_upload_path().filename_to_thumb($membre->avatar) : img_upload_path().filename_to_thumb('user_default.png') ?>" alt="Membre <?= $membre->prenom.' '.$membre->nom ?>" /></p>
							<h3 class="nom"><a href="<?= base_url() ?>utilisateur/profil/<?= $membre->id_utilisateur?>"><?= $membre->prenom.' '.$membre->nom ?></a></h3>
						</div>
					<?php endforeach;
						else: ?>
							<p>Il n'y a aucun membre dans ce groupe</p>
						<?php
						endif;
					?>
					</div>
                    </div>
                    
		</div>
	<?php
		else:
			$this->load-view('notice', array('notice_type' => 'warning', 'notice' => "Le groupe demandé n'existe pas"));
		endif;
	?>
</div>

	
