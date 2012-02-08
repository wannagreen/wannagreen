<div id="header_connecte">
	<ul>
		<li><a href="<?=base_url();?>accueil">Accueil</a></li>
		<li><a href="<?=base_url();?>utilisateur/profil/<?= $this->session->userdata('id_utilisateur')?>"><?= $this->session->userdata('prenom').' '.$this->session->userdata('nom'); ?></a></li>
		<li><a href="<?=base_url();?>utilisateur/deconnexion">Déconnexion</a></li>
	</ul>
</div>
