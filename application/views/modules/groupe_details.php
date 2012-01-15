<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAKmeySfm8d1vUqvFQXc4G4BTI5KWAflYCsY9uh3NdOQum1dxXxBRdphD8px44o9tSgIXMAbhiHHof9A"></script>
<!--online:
<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAKmeySfm8d1vUqvFQXc4G4BS7GLwa-Cv_qXqb8x7KTdicjA1TxxRvMqHnbyHAS6Eg-D09G4U9alVWOw"></script>
-->
<script type="text/javascript" src="<?= base_url() ?>javascript/gmap_render.js"></script>
<div id="module">
	<?php if(isset($groupe)): ?>
		<div id="groupe_details" class="profil_details">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Infos</a></li>
					<li><a href="#tabs-2">Partenaires</a></li>
					<li><a href="#tabs-3">Membres</a></li>
					<li><a href="#tabs-4">Publications</a></li>
					<li><a href="#tabs-5">Carte</a></li>
					<?php
					if(isset($est_admin) && $est_admin) : ?>
						<li><a href="#tabs-6">Administration</a></li>
					<?php
					endif;?>
				</ul>
				
				<?php
				if($user_connected) : ?>
					<div id="barre_interaction">
						<ul>
						<?php if(isset($adhesion) && $adhesion != null) :
								if($adhesion->type == "favoris"): ?>
									<li><a href="<?=base_url();?>groupe/se_detacher/<?= $groupe->id_groupe ?>">Retirer des favoris</a></li>
									<li><a href="<?=base_url();?>groupe/adherer/membre/<?= $groupe->id_groupe ?>">Rejoindre le groupe</a></li>
								<?php
								else : ?>
									<li><a href="<?=base_url();?>groupe/adherer/favoris/<?= $groupe->id_groupe ?>">Ajouter aux favoris</a></li>
									<?php if($adhesion->statut == 1) : ?>
										<li><a href="<?=base_url();?>groupe/se_detacher/<?= $groupe->id_groupe ?>">Quitter le groupe</a></li>
									<?php else : ?>
										<li><a href="<?=base_url();?>groupe/se_detacher/<?= $groupe->id_groupe ?>">Annuler la demande d'adhésion</a></li>
									<?php endif;
								endif;
							else : 
								if(isset($est_admin) && !$est_admin) :
									// l'utilisateur n'est ni l'admin, ni membre, ni sympathisant ?>
									<li><a href="<?=base_url();?>groupe/adherer/favoris/<?= $groupe->id_groupe ?>">Ajouter aux favoris</a></li>
									<li><a href="<?=base_url();?>groupe/adherer/membre/<?= $groupe->id_groupe ?>">Rejoindre le groupe</a></li>
								<?php
								endif;
							endif; ?>
							<?php if(!isset($est_admin) || !$est_admin) { ?>
							<li><a href="<?=base_url();?>groupe/liste_groupes_partenariat_possible/<?= $groupe->id_groupe ?>" class="openmodal">Devenir partenaire</a></li>
							<?php } ?>
						</ul>
					</div>
					<div class="clear"></div>
				<?php endif;?>
				
				<div class="block_content ui-tabs-panel ui-widget-content">
					<p class="avatar"><img src="<?= $groupe->avatar != null ? img_upload_path().$groupe->avatar : img_upload_path().'group_default.png' ?>" width="150px" height="150px" alt="Groupe <?= $groupe->nom ?>" /></p>
					<h2 class="nom"><?= $groupe->nom ?></h2>
					<p class="date">Groupe créé le <?= time_to_str($groupe->date_creation) ?> par <?= isset($admin) ? '<a href="'.base_url().'utilisateur/profil/'.$admin->id_utilisateur.'">'.$admin->prenom.' '.$admin->nom.'</a>' : "un administrateur"; ?></p>
					<p>
						<span class="nbpartenaires"><strong><?= $nb_partenaires ?></strong> <?= plural('groupe', $nb_partenaires).' '.plural('partenaire', $nb_partenaires) ?></span><br />
						<span class="nbmembres"><strong><?= $nb_membres ?></strong> <?= plural('membre', $nb_membres) ?></span><br />
						<span class="nbfavoris"><strong><?= $nb_favoris ?></strong> <?= plural('sélection', $nb_favoris) ?> comme favoris</span><br />
					</p>
					<p>
						<?php if($liste_tags->num_rows()>0): ?>
							<?php foreach($liste_tags->result() as $tag): ?>
							<span class="tag_link"><a href="<?= base_url().'tag/search/'.$tag->id_tag ?>"><?= $tag->libelle ?></a>
							<?php if(isset($est_admin) && $est_admin == TRUE) { ?>
								<img src="<?=base_url()?>images/icons/delete.png" alt="supprimer" title="supprimer" rel="<?=$tag->id_tag?>" />
							<?php } ?>
							</span>
							<?php endforeach; ?>
						<?php endif; ?>
                    </p>
					<?php if(isset($est_admin) && $est_admin == TRUE): ?>
						<p><a href="#" id="toggle_ajout_tag">Ajouter des tags</a></p>
						<div id="ajout_tag" >
							<form method="post" action="<?= base_url() ?>groupe/ajout_tag_groupe/<?= $groupe->id_groupe?>">
								<table>
									<tr>
										<td><label for="tag">Tags&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
										<td><input name="tags" id="tags" type="text" /></td>
										<td colspan="2" align="right"><input class="button" type="submit" value="Valider" role="button" id="valider_tags" /></td>
										<td><i>*Séparez chaque tag par une virgule</i></td>
									</tr>
								</table>
							</form>
						</div>
					<?php endif; ?>
					<div id="tags_result"></div>
				</div>
				
				<div id="tabs-1">
					<div class="description">
						<?php if(isset($est_admin) && $est_admin): ?>
							<input type="hidden" id="id_groupe_modif" value="<?= $groupe->id_groupe ?>" />
							<p><a href="#" class="edit" id="clic_modif_description">Modifier</a></p>
						<?php endif; ?>
						<br />
						<p id="groupe_description"><?= $groupe->description ?></p>
					</div>
				</div>
				
				<div id="tabs-2">
					<div id="partenaire_liste" class="description listing">
					<?php if(isset($liste_partenaires) && count($liste_partenaires) > 0):
							foreach($liste_partenaires as $partenaire): ?>
						<div class="groupe">
							<p class="avatar"><img src="<?= $partenaire->avatar != null ? img_upload_path().filename_to_thumb($partenaire->avatar) : img_upload_path().filename_to_thumb('group_default.png') ?>" width="50px" height="50px" /></p>
							<h3 class="nom"><a href="<?= base_url() ?>groupe/details/<?= $partenaire->id_groupe?>"><?= $partenaire->nom ?></a></h3><?php if(isset($est_admin) && $est_admin) : ?><a href="<?=base_url();?>groupe/arreter_refuser_partenariat/<?= $groupe->id_groupe?>/<?= $partenaire->id_groupe?>">Arrêter partenariat</a><?php endif; ?>
						</div>
					<?php
							endforeach;
						else: ?>
							<p>Ce groupe n'a pas de partenaires</p>
						<?php
						endif;
					?>
					</div>
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
				
				<div id="tabs-4">
					<div class="description listing">
						<?php if(isset($liste_publications) && count($liste_publications) > 0) : foreach($liste_publications as $publication): ?>
							<div class="publication">
								<?php if($publication->id_utilisateur == $this->session->userdata('id_utilisateur')):
										if ($publication->type == 'article') :?> 
											<p><a href="<?= base_url() ?>publication/modification_publication/<?= $publication->id_publication?>" class="edit" id="clic_modif_article">Modifier</a></p>
									<?php endif; ?>
									<p><a href="<?= base_url() ?>publication/supprimer/groupe_details/<?= $publication->id_publication?>/<?= $groupe->id_groupe?>" class="delete" id="clic_suppr_publication">Supprimer</a></p>
								<?php endif;?> 
								
								<?php if(isset($publication->info) && count($publication->info) > 0) : foreach($publication->info as $info) : 
										if($info->libelle == 'titre') : ?>
										<h3 class="titre <?= $publication->id_utilisateur == $this->session->userdata('id_utilisateur') ? 'decaltop' : '' ?>"><?= $info->contenu ?></h3>
										<?php endif;
										if($info->libelle == 'url') : ?>
										<a href=<?= $info->contenu ?> class="_blank"><?= $info->contenu ?></a>
										<?php endif;
										if($info->libelle == 'description') : ?>
										<p><?= ($info->contenu) ?></p>
										<?php endif;
										if($info->libelle == 'date') : ?>
										<p class="date"><?= time_to_str($info->contenu) ?> par <a href="<?= base_url() ?>utilisateur/profil/<?= $publication->id_utilisateur?>"><?= $publication->prenom.' '.$publication->nom ?></a></p>
										<?php endif; ?>
									<?php endforeach; ?>
									<?php endif;?>
								<br>
								<?php if(count($publication->tags) > 0): ?>
									<strong>Tags : </strong>
									<?php foreach($publication->tags as $tag): ?>
										<span class="tag_link"><a href="<?= base_url().'tag/search/'.$tag->id_tag ?>"><?= $tag->libelle ?></a></span>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php if($publication->id_utilisateur == $this->session->userdata('id_utilisateur')): ?>
								<p><a href="#"  class="toggle_ajout_tag">Ajouter des tags</a></p>
								<div class="ajout_tag" >
									<form method="post" action="<?= base_url() ?>groupe/ajouter_tag_publication/<?= $groupe->id_groupe ?>/<?= $publication->id_publication ?>">
										<table>
											<tr>
												<td><label>Tags&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
												<td><input name="tags_publication" class="tags_publication" type="text" /></td>
												<td colspan="2" align="right"><input class="button valider_tags_pub" type="submit" value="Valider" role="button" /></td>
												<td><i>*Séparez chaque tag par une virgule</i></td>
											</tr>
										</table>
										<div class="tags_result"></div>
									</form>
								</div>
								<?php endif; ?>
								
								<div class="liste_comm">
									<?php if(isset($publication->commentaires) && count($publication->commentaires) > 0): ?>
										<br />
										<strong>Commentaires : </strong>
										<?php foreach($publication->commentaires as $comm): ?>
											<div class="commentaire">
												<p><?= $comm->contenu ?></p>
												<p class="date"><?= time_to_str($comm->date_creation) ?> par <a href="<?= base_url() ?>utilisateur/profil/<?= $comm->id_utilisateur?>"><?= $comm->prenom.' '.$comm->nom ?></a></p>
											</div>
										<?php endforeach; ?>
									<?php endif;?>
								</div>
								<div class="ajout_comm">
									<form method="post" action="<?= base_url() ?>publication/commenter/<?= $groupe->id_groupe ?>/<?= $info->id_publication ?>">
										<br />
										<textarea name="commentaire" class="commentaire_text" cols="80" rows="2" placeholder="Commenter cet article"></textarea>
										<br /><br />
										<input class="button valider_comm" type="submit" value="Commenter" />
										<div class="comm_result"></div>
									</form>
								</div>
							</div>
						<?php endforeach; ?>
						<?php else: ?>
							<p>Il n'y a aucune publication dans ce groupe</p>
						<?php endif; ?>
					</div>				
				</div>
				
				<div id="tabs-5">
					<div id="map_container" class="description">
						<div id="map"></div>
					</div>
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
		</div>
	<?php
		else:
			$this->load-view('notice', array('notice_type' => 'warning', 'notice' => "Le groupe demandé n'existe pas"));
		endif;
	?>
</div>

	