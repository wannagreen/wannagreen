<!doctype html>
<html>
<head>
	<title>WannaGreen Project</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/common.css" />
</head>
<body>
<!-- Les vues avec le suffixe "_view" servent à faire des tests -->
<h1>Creer un compte sur WannaGreen</h1>
<?php if(isset($context)) echo $context; ?>
<div id="createuser">
<?php
echo form_open('utilisateur/creer_compte')
	.'<p>'
	.form_label('E-mail*', 'email')
	.form_input('email', '', 'id="email"')
	.'</p><p>'
	.form_label('Mot de passe*', 'password')
	.form_password('password', '', 'id="password"')
	.'</p><p>'
	.form_label('Confirmation mot de passe*', 'passwordconf')
	.form_password('passwordconf', '', 'id="passwordconf"')	
	.'</p><p>'
	.form_label('Nom*', 'nom')
	.form_input('nom', '', 'id="nom"')
	.'</p><p>'
	.form_label('Prénom*', 'prenom')
	.form_input('prenom', '', 'id="prenom"')
	.'</p><p>'
	.form_label('Adresse', 'adresse')
	.form_input('adresse', '', 'id="adresse"')
	.'</p><p>'
	.form_label('Sexe', 'sexe')
	.form_input('sexe', '', 'id="sexe"')
	.'</p><p>'
	.form_label('Date de naissance', 'date_naissance')
	.form_input('date_naissance', '', 'id="date_naissance"')
	.'</p><p>'
	.form_submit('create', 'Valider')
	.'</p>'
	.form_close();
?>
</div>
</body>
</html>
