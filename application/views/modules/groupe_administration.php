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
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/members">Membres</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/publications">Publications</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/carte">Carte</a></li>
					<?php
					if(isset($est_admin) && $est_admin) : ?>
						<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/administration">Administration</a></li>
					<?php
					endif;?>
				</ul>
				
			</div>
                    
                   <?php if(isset($est_admin) && $est_admin) : ?>			
					
                    <div id="tabs-6">			
                        <div class="description listing">
                            
                            <h3 class="libelle">Demande(s) d'adhésion en attente :</h3>		
                                    <?php if(isset($liste_membres_attente) && count($liste_membres_attente) > 0): foreach($liste_membres_attente as $membre_attente): ?>
								<div class="utilisateur">
									<p class="avatar"><img src="<?= $membre_attente->avatar != null ? img_upload_path().filename_to_thumb($membre_attente->avatar) : img_upload_path().filename_to_thumb('user_default.png') ?>" alt="Membre <?= $membre_attente->prenom.' '.$membre_attente->nom ?>" /></p>
									<h3 class="nom"><a href="<?= base_url() ?>utilisateur/profil/<?= $membre_attente->id_utilisateur?>"><?= $membre_attente->prenom.' '.$membre_attente->nom ?></a></h3>
									<ul>
										<li><a href="<?=base_url();?>groupe/accepter_adhesion/<?= $groupe->id_groupe?>/<?= $membre_attente->id_utilisateur?>">Accepter</a></li>
										<li><a href="<?=base_url();?>groupe/refuser_adhesion/<?= $groupe->id_groupe?>/<?= $membre_attente->id_utilisateur?>">Refuser</a></li>
									</ul>
								</div>
							<?php endforeach;
								else: ?>
									<p>Aucune</p>
								<?php
								endif;?>
			</div>
			
                        <div id="partenaire_possible_liste" class="description listing">
				<h3 class="libelle">Demande(s) de partenariat en attente :</h3>
				<?php if(isset($liste_partenaires_demandes) && count($liste_partenaires_demandes) > 0):
						foreach($liste_partenaires_demandes as $partenaire_demande): ?>
						
                                                <div class="groupe">
							<h3 class="nom"><?= $partenaire_demande->id_groupe?> <?= $partenaire_demande->nom?></h3>
							<div id="barre_interaction">
								<ul>
									<li><a href="<?=base_url();?>groupe/accepter_partenariat/<?= $groupe->id_groupe?>/<?= $partenaire_demande->id_groupe?>">Accepter</a></li>
									<li><a href="<?=base_url();?>groupe/arreter_refuser_partenariat/<?= $groupe->id_groupe?>/<?= $partenaire_demande->id_groupe?>">Refuser</a></li>
								</ul>
							</div>
						</div>
                                                <?php
						endforeach;
							
                                                else: ?>
							<p>Aucune</p>
						<?php
						endif; ?>
			</div>
						
		</div>
		<?php
		
                endif;?>
                    
	</div>
	<?php
		else:
			$this->load-view('notice', array('notice_type' => 'warning', 'notice' => "Le groupe demandé n'existe pas"));
		endif;
	?>
</div>

	
