<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAx6DY7atQ-9PsB73zS1oFdxSXH2gz5Fee6Jq32UitLAFOha4qWxQjBVN1Lr6_btF1U5FO2FwaUGiUug" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url() ?>javascript/gmap_render.js"></script>
<div id="module">
	<?php if(isset($groupe)): ?>
		<div id="groupe_details" class="profil_details">
			<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<h2 class="nom"><?= $groupe->nom ?></h2>
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/infos">Infos</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/partenaires">Partenaires (<?php echo $nb_partenaires; ?>)</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/members">Membres (<?php echo $nb_membres; ?>)</a></li>
					<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/publications">Publications (<?php echo $nbPub; ?>)</a></li>
					<li><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/carte">Carte</a></li>
					<?php
					if(isset($est_admin) && $est_admin) : ?>
						<li class="ui-state-default ui-corner-top ui-state"><a href="<?=base_url();?>groupe/details/<?= $groupe->id_groupe ?>/administration">Administration</a></li>
					<?php
					endif;?>
				</ul>
				
			</div>
                    
                   <div id="tabs-5">
			<div id="map_container" class="description">
				<div id="map"></div>
			</div>
		   </div>
	<?php
		else:
			$this->load->view('notice', array('notice_type' => 'warning', 'notice' => "Le groupe demandé n'existe pas"));
		endif;
	?>
</div>

	
