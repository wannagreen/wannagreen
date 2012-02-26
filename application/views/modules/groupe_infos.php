<div id="module">
	<?php if(isset($groupe)): ?>
		<div id="groupe_details" class="profil_details">
			<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<h2 class="nom"><?= $groupe->nom ?></h2>
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/infos">Infos</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/partenaires">Partenaires (<?php echo $nb_partenaires; ?>)</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/members">Membres (<?php echo $nb_membres; ?>)</a></li> 
					
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/publications">Publications (<?php echo $nbPub; ?>)</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/carte">Carte</a></li>
					<?php
					if(isset($est_admin) && $est_admin) : ?>
						<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/administration">Administration</a></li>
					<?php
					endif;?>
				</ul>
				
			</div>
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
                            
                                <h3>Description du groupe : </h3>
                                
				<?php if(isset($est_admin) && $est_admin): ?>
					<input type="hidden" id="id_groupe_modif" value="<?= $groupe->id_groupe ?>" />
					<p><a href="#" class="edit" id="clic_modif_description">Modifier</a></p>
				<?php endif; ?>
				<br />
                               
				<p id="groupe_description"><?= $groupe->description ?></p>
			</div>
		</div>
                    
		</div>
	<?php
		else:
			$this->load->view('notice', array('notice_type' => 'warning', 'notice' => "Le groupe demandé n'existe pas"));
		endif;
	?>
</div>

	
