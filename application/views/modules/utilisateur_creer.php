<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAqfqC32HRwVbFeWjw30oqdxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQL9EsNvZ0w9ufI1qxCJmTn8dL85g"></script>
<script type="text/javascript" src="<?= base_url() ?>javascript/google_maps.js"></script>
<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<?php if($user_connected): ?>
			<h1>Modification du profil</h1>
		<?php else: ?>
			<h1>Création d'un compte utilisateur</h1>
		<?php endif; ?>
	</div>
	<div id="utilisateur_creer" class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		<?php if($user_connected): ?><p><em>Si vous ne souhaitez pas modifier votre mot de passe, laissez le champs vide</em></p><?php endif; ?>
		<p>* Champs obligatoires</p>
		<?php if(isset($context)) echo $context; ?>
		<?= strlen(validation_errors()) ? '<div class="error"><p>Veuillez corriger les erreurs suivantes :</p><ul>'.validation_errors().'</ul></div>': ''; ?>
		<?php
		// Génération du formulaire d'inscription à partir des outils fournis par CI
		echo form_open_multipart($user_connected ? 'utilisateur/modifier' : 'utilisateur/creer');
		$fields = array(
			array('',''),
			array(
				form_label('Email *', 'email'),
				form_input('email', set_value('email', $user_connected ? $utilisateur->email : ''), 'id="email" maxlength="128"')
			),
			array(
				form_label('Mot de passe *', 'password'),
				form_password('password', '', 'id="password"')
			),
			array(
				form_label('Confirmation mot de passe *', 'passwordconf'),
				form_password('passwordconf', '', 'id="passwordconf"')
			),
			array(
				form_label('Nom *', 'nom'),
				form_input('nom', set_value('nom', $user_connected ? $utilisateur->nom : ''), 'id="nom" maxlength="64"')
			),
			array(
				form_label('Prénom *', 'prenom'),
				form_input('prenom', set_value('prenom', $user_connected ? $utilisateur->prenom : ''), 'id="prenom" maxlength="64"')
			),
			array(
				form_label('Adresse', 'adresse'),
				form_input('adresse', set_value('adresse', $user_connected ? $utilisateur->adresse : ''), 'id="adresse" onKeyUp="showLocation(this.value); return false;"')
				.'<div id="autoCompletionResult"></div>'
			),
			array(
				form_label('Sexe'),
				form_label('M', 'sexeM').form_radio('sexe', 'M', FALSE, 'id="sexeM" '.set_radio('sexe', 'M', $user_connected && $utilisateur->sexe == 'M'))
			   .form_label('F', 'sexeF').form_radio('sexe', 'F', FALSE, 'id="sexeF" '.set_radio('sexe', 'F', $user_connected && $utilisateur->sexe == 'F'))
			),
			array(
				form_label('Date de naissance', 'date_naissance'),
				form_input('date_naissance', set_value('date_naissance', $user_connected ? $utilisateur->date_naissance : ''), 'id="date_naissance" maxlength="10"').'<span>&nbsp;&nbsp;jj/mm/aaaa</span>'
			),
			array(
				form_label('Avatar', 'avatar'),
				form_upload('avatar', set_value('avatar'), 'id="avatar" size="25"')
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
