<div id="module">
	<?php if(isset($groupe)): ?>
		<div id="groupe_details" class="profil_details">
			<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<h2 class="nom"><?= $groupe->nom ?></h2>
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/infos">Infos</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/partenaires">Partenaires</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/members">Membres</a></li>
					<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/publications">Publications</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/carte">Carte</a></li>
					<?php
					if(isset($est_admin) && $est_admin) : ?>
						<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/administration">Administration</a></li>
					<?php
					endif;?>
				</ul>
				
			</div>
                    
                        <div id="tabs-4">
                            
					<div class="description listing">
                                            
                                            <?php 
                                            $nbPub = count($liste_publications);
                                            
                                            if($nbPub == '0' || $nbPub == '1')
                                            {
                                                ?>
                                                 <span class="nbpublications"><strong><?= $nbPub ?></strong> <?= plural('publication', $nbPub) ?></span><br /><br/>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <span class="nbpublications"><strong><?= $nbPub = count($liste_publications) ?></strong> <?= plural('publication', $nbPub) ?></span><br /><br/>
                                                <?php
                                            }
                                            ?>
                                                
                                                
                                                  <?php if(isset($liste_publications) && count($liste_publications) > 0) : foreach($liste_publications as $publication): ?>
							
                                                    <div class="publication">
								<?php if($publication->id_utilisateur == $this->session->userdata('id_utilisateur')):
										if ($publication->type == 'article') :?> 
											<a href="<?= base_url() ?>publication/modification_publication/<?= $publication->id_publication?>" class="edit" id="clic_modif_article">Modifier</a>
									<?php endif; ?>
									<a href="<?= base_url() ?>publication/supprimer/groupe_details/<?= $publication->id_publication?>/<?= $groupe->id_groupe?>" class="delete" id="clic_suppr_publication">Supprimer</a>
								<?php endif;?> 
								
                                                    <?php if(isset($publication->info) && count($publication->info) > 0) : 
                                    
                                                    foreach($publication->info as $info) : 
                                    
                                                        if($info->libelle == 'titre') : ?>
                                                        <h3 class="titre"><?= $info->contenu ?></h3>
                                                        <?php endif;?>
					
                                                    <?php endforeach;     
                                    
                                                    foreach($publication->info as $info) : 
                                    
                                                        if($info->libelle == 'url') : ?>
                                                        <a href=<?= $info->contenu ?> class="_blank"><?= $info->contenu ?></a>
                                                        <?php endif;?>
					
                                                    <?php endforeach;
					
                                                    foreach($publication->info as $info) : 
                                    
                                                        if($info->libelle == 'description') : ?>
                                                        <?= $info->contenu ?></p>
                                                        <?php endif;?>
					
                                                    <?php endforeach;
                                                
                                                    foreach($publication->info as $info) : 
                                    
                                                        if($info->libelle == 'date') : ?>
                                                        <p class="date"><?= time_to_str($info->contenu) ?> par <a href="<?= base_url() ?>utilisateur/profil/<?= $publication->id_utilisateur?>"><?= $publication->prenom.' '.$publication->nom ?></a></p>
                                                        <?php endif; ?>
					
                                                    <?php endforeach; ?> 
                                                
                                                
                                                    <?php endif;?>
                                                        
                                                        <br/>
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
							<p>Il n'y a aucune publication dans ce groupe.</p>
						<?php endif; ?>
					</div>				
				</div>
                    
		</div>
	<?php
		else:
			$this->load-view('notice', array('notice_type' => 'warning', 'notice' => "Le groupe demandé n'existe pas"));
		endif;
	?>
</div>

	
