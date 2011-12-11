<div id="groupe" class="block">
	<?php if((isset($tabs_mes_groupes) && count($tabs_mes_groupes) > 0) || (isset($tabs_mes_favoris) && count($tabs_mes_favoris) > 0) || (isset($tabs_mes_admin) && count($tabs_mes_admin) > 0)): ?>
	<div id="tabs">
		<ul>
		
			<?php if(isset($tabs_mes_groupes) && count($tabs_mes_groupes) > 0): ?>
				<li><a href="#tabs_mes_groupes">Groupes</a></li>
			<?php	endif; ?>								
			
			<?php if(isset($tabs_mes_favoris) && count($tabs_mes_favoris) > 0): ?>
				<li><a href="#tabs_mes_favoris">Favoris</a></li>
			<?php	endif; ?>
			
			<?php if(isset($tabs_mes_admin) && count($tabs_mes_admin) > 0): ?>
				<li><a href="#tabs_mes_admin">Admin</a></li>
			<?php	endif; ?>

		</ul>
		
		<?php if(isset($tabs_mes_groupes) && count($tabs_mes_groupes) > 0): ?>
		<div id="tabs_mes_groupes">
			<ul>
				<?php foreach($tabs_mes_groupes as $groupe): ?>
				<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
		
		<?php if(isset($tabs_mes_favoris) && count($tabs_mes_favoris) > 0): ?>
		<div id="tabs_mes_favoris">
			<ul>
				<?php foreach($tabs_mes_favoris as $groupe): ?>
				<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
		<?php if(isset($tabs_mes_admin) && count($tabs_mes_admin) > 0): ?>
		<div id="tabs_mes_admin">
			<ul>
				<?php foreach($tabs_mes_admin as $groupe): ?>
				<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe?>"><?= $groupe->nom ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
		
	</div>
	<?php endif; ?>
</div>