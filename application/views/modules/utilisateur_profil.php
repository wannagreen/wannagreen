<div id="module">
	<?php if(isset($utilisateur)): ?>
		<div id="utilisateur_profil" class="profil_details">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Groupes</a></li>
					<li><a href="#tabs-2">Publications</a></li>
				</ul>
				<div class="block_content ui-tabs-panel ui-widget-content">
					<p class="avatar"><img src="<?= $utilisateur->avatar != null ? img_upload_path().$utilisateur->avatar : img_upload_path().'user_default.png' ?>" width="150px" height="150px" alt="Utilisateur <?= $utilisateur->prenom.' '.$utilisateur->nom ?>" /></p>
					<h2 class="nom"><?= $utilisateur->prenom.' '.$utilisateur->nom ?></h2>
					<p class="date">Inscrit(e) le <?= time_to_str($utilisateur->date_creation) ?></p>
					<p><?= $utilisateur->adresse != '' ? 'Adresse : '.$utilisateur->adresse : ''; ?></p>
					<p><?= $utilisateur->sexe != '' ? 'Sexe : '.$utilisateur->sexe : ''; ?></p>
					<p><?= $utilisateur->date_naissance != '' ? 'Né(e) le '.$utilisateur->date_naissance : ''; ?></p>
					<br />
					<?php if(isset($utilisateur_profils_externes) && count($utilisateur_profils_externes) > 0): ?>
						<ul>
							<?php foreach($utilisateur_profils_externes as $profil_externe): ?>
								<li><p class="<?= strtolower($profil_externe->libelle) ?> social_icon" style="background: url(../../images/social/<?= $profil_externe->avatar ?>) no-repeat left center;"><?= $profil_externe->url ?></p></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div id="tabs-1">
					<?php if((isset($utilisateur_groupes) && count($utilisateur_groupes) == 0) && (isset($utilisateur_admin) && count($utilisateur_admin) == 0)): ?>
						<p><?= $utilisateur->prenom . ' ' . $utilisateur->nom?> n'est membre d'aucun groupe</p>
					<?php else: ?>
						
						<?php if(isset($utilisateur_admin) && count($utilisateur_admin) > 0): ?>
							<ul>
								<h3>Administrateur de :</h3>
								<?php foreach($utilisateur_admin as $groupe): ?>
									<li><a class="admin" href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						
						<?php if(isset($utilisateur_groupes) && count($utilisateur_groupes) > 0): ?>
							<ul>
								<h3>Membre de :</h3>
								<?php foreach($utilisateur_groupes as $groupe): ?>
									<li><a class="membre" href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						
					<?php endif; ?>
				</div>
				<div id="tabs-2">
					<div class="description listing">
					<?php if(isset($liste_publications) && count($liste_publications) > 0) : foreach($liste_publications as $publication): ?>
						
						<?php if($publication->visible) :?> 
                                            
						<div class="publication">
                                                    
						<?php if($publication->id_utilisateur == $this->session->userdata('id_utilisateur')):
									if ($publication->type == 'article') :?> 
									<a href="<?= base_url() ?>publication/modification_publication/<?= $publication->id_publication?>" class="edit" id="clic_modif_article">Modifier</a>
                                                                        <?php endif; ?>
                                                                        <a href="<?= base_url() ?>publication/supprimer/utilisateur_profil/<?= $publication->id_publication?>/<?= $utilisateur->id_utilisateur?>" class="delete" id="clic_suppr_publication">Supprimer</a>
						<?php endif;?> 
							
                                                        <br/><br/>
                                                        <?php
                                                        if(count($publication->groupe) > 0)
                                                        {
                                                            ?>Publié dans le(s) groupe(s) : <?php
                                                             foreach($publication->groupe as $groupe) : ?>
                                                             <a href="<?= base_url() ?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a>
                                                            <?php endforeach; ?>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            Publié dans aucun groupe.
                                                            <?php
                                                        }
                                                        ?>
							             
							<br/>
                                                        
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
                                                            
							<br />
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
						<p><?= $utilisateur->prenom . ' ' . $utilisateur->nom?> n'a fait aucune publication</p>
					<?php
					endif;
					?>
					</div>
				</div>
			</div>
		</div>
	<?php
	else:
		$this->load-view('notice', array('notice_type' => 'warning', 'notice' => "L'utilisateur demandé n'existe pas"));
	endif;
	?>
</div>
