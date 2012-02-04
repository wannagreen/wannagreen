<div id="module">
	<?php if(isset($groupe)): ?>
		<div id="groupe_details" class="profil_details">
			<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<h2 class="nom"><?= $groupe->nom ?></h2>
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/infos">Infos</a></li>
					<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/partenaires">Partenaires</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/members">Membres</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/publications">Publications</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/carte">Carte</a></li>
					<?php
					if(isset($est_admin) && $est_admin) : ?>
						<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/administration">Administration</a></li>
					<?php
					endif;?>
				</ul>
				
			</div>
                    
                    <div id="tabs-2">
					<div id="partenaire_liste" class="description listing">
                                        
                                            <span class="nbpartenaires"><strong><?= $nb_partenaires ?></strong> <?= plural('groupe', $nb_partenaires).' '.plural('partenaire', $nb_partenaires) ?></span><br /><br/>
                                           
                                        </div>
                        
					<?php if(isset($liste_partenaires) && count($liste_partenaires) > 0):
							foreach($liste_partenaires as $partenaire): ?>
						<div class="groupe">
							<p class="avatar"><img src="<?= $partenaire->avatar != null ? img_upload_path().filename_to_thumb($partenaire->avatar) : img_upload_path().filename_to_thumb('group_default.png') ?>" width="50px" height="50px" /></p>
							<h3 class="nom"><a href="<?= base_url() ?>groupe/details/<?= $partenaire->id_groupe?>"><?= $partenaire->nom ?></a></h3><?php if(isset($est_admin) && $est_admin) : ?><a href="<?=base_url();?>groupe/arreter_refuser_partenariat/<?= $groupe->id_groupe?>/<?= $partenaire->id_groupe?>">Arrêter partenariat</a><?php endif; ?>
						</div>
					<?php
							endforeach;
						else: ?>
							<p>Ce groupe n'a pas de partenaires.</p>
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

	
