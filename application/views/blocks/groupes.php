
	<?php if((isset($tabs_mes_groupes) && count($tabs_mes_groupes) > 0) || (isset($tabs_mes_favoris) && count($tabs_mes_favoris) > 0) || (isset($tabs_mes_admin) && count($tabs_mes_admin) > 0)): ?>
	
	<div id="groupe" class="block ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div id="tabs">
		<ul>
                        <!-- Pour l'affichage des groupes -->
			<?php if(isset($tabs_mes_groupes) && count($tabs_mes_groupes) > 0): ?>
				<li><a class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">Groupes</a></li>
			<?php	endif; ?>								
			
                       <?php if(isset($tabs_mes_groupes) && count($tabs_mes_groupes) > 0): ?>
                        <div id="tabs_mes_groupes">
                            <ul>
				<?php foreach($tabs_mes_groupes as $groupe): ?>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>/infos"><?= $groupe->nom ?></a></li>
				<?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>         
                                
                                
                        <!-- Pour l'affichage des favoris -->        
			<?php if(isset($tabs_mes_favoris) && count($tabs_mes_favoris) > 0): ?>
				<li><a class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">Favoris</a></li>
			<?php	endif; ?>
                                
			<?php if(isset($tabs_mes_favoris) && count($tabs_mes_favoris) > 0): ?>
                        <div id="tabs_mes_favoris">
                            <ul>
				<?php foreach($tabs_mes_favoris as $groupe): ?>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>/infos"><?= $groupe->nom ?></a></li>
				<?php endforeach; ?>
                            </ul>
                        </div>
                                
                        
                        <!-- Pour l'affichage des admins -->
                        <?php endif; ?>
			<?php if(isset($tabs_mes_admin) && count($tabs_mes_admin) > 0): ?>
				<li><a style='color:#007B7B' class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">Admin</a></li>
			<?php endif; ?>
                                
                        <?php if(isset($tabs_mes_admin) && count($tabs_mes_admin) > 0): ?>
                        <div id="tabs_mes_admin">
                            <ul>
                                <?php foreach($tabs_mes_admin as $groupe): ?>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>/infos"><?= $groupe->nom ?></a></li>
				<?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                </ul>
		
	</div>
</div>
	<?php endif; ?>
