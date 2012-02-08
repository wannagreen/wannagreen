<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAqfqC32HRwVbFeWjw30oqdxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQL9EsNvZ0w9ufI1qxCJmTn8dL85g"></script>
<script type="text/javascript" src="<?= base_url() ?>javascript/google_maps.js"></script>

<div id="inscription" class="block ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">Inscription</div>
	<div class="block_content ui-tabs-panel ui-widget-content ui-corner-bottom">
		
			<?php if(isset($context)) echo $context; ?>
		<?= strlen(validation_errors()) ? '<div class="error">Tous les champs sont obligatoires</div>': ''; ?>
		
		<ul>
			<table>
		<?php
		echo form_open('utilisateur/creer_simple')
		."<tr><td>".form_label('Nom* :', 'nom')."</td></tr><tr><td>".form_input('nom', '', 'id="nom"')."<br /><br /></td></tr>"
		."<tr><td>".form_label('Prénom* :', 'prenom')."</td></tr><tr><td>".form_input('prenom', '', 'id="prenom"')."<br /><br /></td></tr>"
		."<tr><td>".form_label('E-mail* :', 'email')."</td></tr><tr><td>".form_input('email', '', 'id="email"')."<br /><br /></td></tr>"
		."<tr><td>".form_label('Mot de passe* :', 'password')."</td></tr><tr><td>".form_password('password', '', 'id="password"')."<br /><br /></td></tr>"
		."<tr><td>".form_label('Confirmation mot de passe* :', 'passwordconf')."</td></tr><tr><td>".form_password('passwordconf', '', 'id="passwordconf"')	."</td></tr>"
		."<tr><td><p>* Champs obligatoires</p></td></tr>"
		."<tr><td align='center'>".form_submit('create', 'Valider')."</td></tr>".form_close();
		
		?>
			</table>
			
			
			
		</ul>
	</div>
</div>