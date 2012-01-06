<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAqfqC32HRwVbFeWjw30oqdxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQL9EsNvZ0w9ufI1qxCJmTn8dL85g"></script>
<script type="text/javascript" src="<?= base_url() ?>javascript/google_maps.js"></script>
<script src="<?= base_url() ?>javascript/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="<?= base_url() ?>javascript/tiny_mce/init.js" type="text/javascript"></script>
<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<h1>Publier article</h1>
	</div>
	<div id="article_creer" class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		<p>* Champs obligatoires</p>
		<?php if(isset($context)) echo $context; ?>
		<?= strlen(validation_errors()) ? '<div class="error"><p>Veuillez corriger les erreurs suivantes :</p><ul>'.validation_errors().'</ul></div>': ''; ?>
		<?php
		// Génération du formulaire d'inscription à partir des outils fournis par CI
		echo form_open_multipart(isset($publication) ? 'publication/modifier' : 'publication/creer');
		
		// Récupération de la liste des groupes dont l'utilisateur est membre ou admin
		$liste_groupes = '';
		if(isset($tabs_mes_admin) && count($tabs_mes_admin) > 0):
			foreach($tabs_mes_admin as $groupe):
				// Traitement spécifique à la modification : recherche des groupes où l'article a été publié pour pouvoir les cocher
				$checked = FALSE;
				if(isset($publication->liste_groupe_modif) && count($publication->liste_groupe_modif) > 0):
					foreach($publication->liste_groupe_modif as $groupe_modif):
						if($groupe_modif->id_groupe == $groupe->id_groupe):
							$checked = TRUE;
						endif;
					endforeach;
				endif;
				$liste_groupes .= form_checkbox('groupes[]', $groupe->id_groupe, $checked);
				$liste_groupes .= form_label($groupe->nom, $groupe->nom);
			endforeach;
		endif;
		
                if(isset($tabs_mes_groupes) && count($tabs_mes_groupes) > 0):
			foreach($tabs_mes_groupes as $groupe):
				// Traitement spécifique à la modification : recherche des groupes où l'article a été publié pour pouvoir les cocher
				$checked = FALSE;
				if(isset($publication->liste_groupe_modif) && count($publication->liste_groupe_modif) > 0):
					foreach($publication->liste_groupe_modif as $groupe_modif):
						if($groupe_modif->id_groupe == $groupe->id_groupe):
							$checked = TRUE;
						endif;
					endforeach;
				endif;
				$liste_groupes .= form_checkbox('groupes[]', $groupe->id_groupe, $checked);
				$liste_groupes .= form_label($groupe->nom, $groupe->nom);
			endforeach;
		endif;
		
                if(count($tabs_mes_groupes) == 0 && count($tabs_mes_admin) == 0) 
                {
                    $liste_groupes = "Vous n'êtes inscrit sur aucun groupe.";
                }
                
		$fields = array(
			array(
				'&nbsp;',
				form_hidden('id_publication', set_value('id_publication', isset($publication) ? $publication->id_publication : ''), 'id="id_publication"')
			),
			array(
				form_label('Titre *', 'titre'),
				form_input('titre', set_value('titre', isset($publication) ? $publication->titre : ''), 'id="titre" size="80" maxlength="64"')
			),
			array(
				'&nbsp;', '&nbsp;' // saut de ligne
			),
			array(
				form_label('Texte *&nbsp;', 'description'),
                                form_textarea('description', set_value('description', isset($publication) ? $publication->description : ''), 'id="description" cols="29" rows="4"')
			),
			array(
				'&nbsp;', '&nbsp;' // saut de ligne
			),
			array(
				form_label('Tags', 'tags'),
				form_input('tags', set_value('tags', isset($publication) ? $publication->tags : ''), 'id="tags" size="80" maxlength="64"').'<br />'
			),
			array(
				'&nbsp;', '&nbsp;' // saut de ligne
			),
			array(
				form_label('Privé', 'prive'),
				form_checkbox('prive', '1', (!isset($publication) || (isset($publication) && $publication->prive == 0)) ? FALSE : TRUE, set_checkbox('prive', '1'))
			),
			array(
				'&nbsp;', '&nbsp;' // saut de ligne
			),
                        
			array(
				'Où ?',
				$liste_groupes
			),
			array(
				'&nbsp;', '&nbsp;' // saut de ligne
			),
			array(
				'&nbsp;', '&nbsp;' // saut de ligne
			),
			array(
				'&nbsp;',
				form_submit('create', 'Valider', 'class="button"')
			)
		);
		echo $this->table->generate($fields).form_close();
		?>
	</div>
</div>
