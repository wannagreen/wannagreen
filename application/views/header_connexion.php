<div id="header_connexion">
	<?php
	echo form_open('utilisateur/connexion')
		.form_input('email', '', 'id="email" placeholder="Email"')
		.form_password('password', '', 'id="password" placeholder="Mot de passe"');
	?>
	<?php
	echo form_submit('create', 'Connexion', 'class="button"')
		.form_close();
	?>
	<a href="<?= base_url() ?>utilisateur/inscription">Pas encore inscrit ? S'inscrire</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="<?= base_url() ?>utilisateur/mot_de_passe_oublie">Mot de passe oubli&eacute;?</a>
</div>
