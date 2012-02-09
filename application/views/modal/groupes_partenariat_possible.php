<div>
	<ul>
	<?php foreach($liste_groupes_partenariat_possible as $groupe_partenariat_possible){ ?>
            <li><a href="<?=base_url();?>groupe/demander_partenariat/<?= $groupe_partenariat_possible->id_groupe ?>/<?= $id_url ?>"><?php echo $groupe_partenariat_possible->nom; ?></a></li>
	<?php } ?>
	</ul>
</div>