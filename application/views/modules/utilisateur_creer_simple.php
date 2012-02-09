<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
<h1>Publications récentes </h1>
	</div>
		<div id="publication_liste" class="listing block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		<div class="description listing">
		<?php 
			$publication = new Publication_model();
			$liste_publication = $publication->get_publication_recente();
		
			foreach ($liste_publication as $publication) { 
			$publication->info = $this->Publication_info_model->get_by_id_publication($publication->id_publication);
			$publication->groupe = $this->Publication_groupe_model->get_by_id_publication($publication->id_publication);
			$publication->tags = $this->Tag_model->get_tag_publication($publication->id_publication);
			
			$nb_publication_visible = 0;
			$publication->visible = FALSE;
                        
			foreach ($publication->groupe as $groupe) {
				if($publication->prive == 0) {
					$nb_publication_visible += 1;    
				}
				
				if($nb_publication_visible >= 1)
					$publication->visible = TRUE;
				else
					$publication->visible = FALSE;
			}
                               
		}
		?>
		<?php
		if(isset($liste_publication) && count($liste_publication) > 0) : 
		foreach($liste_publication as $publication): 
		?>
			
           <div class="publication">
				<?php 	
                                if(count($publication->groupe) > 0)
                                {
                                    ?><p> Publié dans le(s) groupe(s) : <?php
                                    foreach($publication->groupe as $groupe) : ?>
								<a href="<?= base_url() ?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a>
								<?php endforeach; ?>
                                    </p><?php
                                }
                                else
                                {
                                    ?>
                                    <p>Publié dans aucun groupe.</p>
                                    <?php
                                }
                                ?>
								
                        <br/>   
						
                        <?php if(isset($publication->info) && count($publication->info) > 0) : 
                                    
                        foreach($publication->info as $info) : 
							if($info->libelle == 'titre') : ?>
							<h3 class="nom"><?= $info->contenu ?></h3>
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
                                                
						<?php
						if(count($publication->tags) > 0): ?>
							<strong>Tags : </strong>
						<?php foreach($publication->tags as $tag): ?>
							<span class="tag_link"><a href="<?= base_url().'publication/tag_search/'.$tag->id_tag ?>"><?= $tag->libelle ?></a></span>
						<?php endforeach;
						endif; ?>
			
                </div>

           

		<?php endforeach;
		else: ?>
			<p>Il n'y a aucune publication</p>
		<?php endif;?>
	
	
			
			
		</div>
		</div>
	
	
</div>
