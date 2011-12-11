<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<h1>Créer groupe</h1>
	</div>
	<div id="groupe_creer" class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
	<?php if(isset($context)) echo $context; ?>
	<?= strlen(validation_errors()) ? '<div class="error"><p>Veuillez corriger les erreurs suivantes :</p><ul>'.validation_errors().'</ul></div>': '';  ?>
	<?php
	echo form_open('groupe/creer')
		.'<table><tr><td>'
		.form_label('Nom*', 'nom').'</td><td>'
		.form_input('nom', set_value('nom'), 'id="nom" maxlength="64"')
		.'</td></tr><tr><td>'
		.form_label('Description*', 'description').'</td><td>'
		.form_textarea(array('name'=>'description', 'value'=>set_value('description'), 'id'=>'description', 'cols'=>'29', 'rows'=>'4'))		.'</td></tr><tr><td>'
		.'</td></tr><tr><td>'
		.form_label('Avatar', 'avatar').'</td><td>'
		.form_input('avatar', set_value('avatar'), 'id="avatar"')
		.'</td></tr><tr><td>'
		.form_label('Adhésions fermées', 'ferme').'</td><td>'
		.form_checkbox('ferme', '1', FALSE, 'id="ferme" '.set_checkbox('ferme', '1'))
		.'</td></tr><tr><td>'		
		.form_label('Tag', 'tag').'</td><td>'
		.form_input('tag', '', 'id="tag"')
		.'</td></tr><tr><td>&nbsp;</td><td>'
		.form_submit('create', 'Valider', 'class="button"')
		.'</td></tr></table>'
		.form_close();
	?>
	</div>
</div>
