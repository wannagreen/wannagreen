<?php

//include('delicious.php');
//$this->load->view('delicious');//Pour que ça marche avec CI

//session_start();
if(($this->session->userdata('nb_connection'))){
	$nb_connection = $this->session->userdata('nb_connection') + 1;
	$this->session->set_userdata('nb_connection', $nb_connection);
}
?>
<div id="module" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div class="block_header ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<h1>Publier lien</h1>
	</div>
	<?php if((isset($mesGroupesAdmin) && count($mesGroupesAdmin) > 0) || ((isset($mesGroupesMembres) && count($mesGroupesMembres) > 0))) { ?>
	<form method="post" action="<?=base_url()?>lien/traitement_lien">
		<?php if(isset($message)){ ?>
		<h3><?= $message ?></h3>
		<?php }
			if(isset($tentative_connection) && $tentative_connection=='oui' && $user_connected==TRUE){ ?>
		<h3>La connexion a échoué</h3>
			<?php }	?>
			<!--<a href="">Me connecter à Delicious</a>-->
		<?php if((isset($est_co) && $est_co == 'non') || (isset($tentative_connection) && $tentative_connection=='oui') && $user_connected == TRUE){ ?>
			<p><a href="#" id="toggle_delicious_connexion">Me connecter à Delicious</a></p>
			<div id="delicious_connexion" >
				<table>
					<tr>
						<td><label for="login">Login</label></td>
						<td><input name="login" id="login" type="text" /></td>
					</tr>
					<tr>
						<td><label for="password">Mot de passe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
						<td><input name="password" id="password" type="password" /></td>
						<td colspan="2" align="right"><input class="button" type="submit" value="Valider" role="button" /></td>
					</tr>
				</table>
				<br/><hr/><br/>
			</div>
		<?php }
		//if(isset($this->session->userdata('login_delicious')) && isset($this->session->userdata('mdp_delicious')){
		if(($this->session->userdata('login_delicious')) && ($this->session->userdata('mdp_delicious')) && $user_connected==TRUE){
		?>
		<p><a href="<?=base_url()?>lien/deconnexion">Déconnecter mon compte Delicious de Wannagreen</a></p></br>
		<?php }
		if ($user_connected==TRUE){
		?>
		<table>
			<tr>
				<td><label for="url"><strong>URL</strong></label></td>
				<td><input name="url" id="url" type="text" /></td>
			</tr>
			<tr>
				<td><label for="titre"><strong>Titre</strong></label></td>
				<td><input name="titre" id="titre" type="text" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><hr/></td><td><hr/></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><em>Gestion des tags</em></td>
				<?php if(($this->session->userdata('login_delicious')) && ($this->session->userdata('mdp_delicious')) && $user_connected==TRUE){ ?>
				<td><input class="button" value="Suggérer" role="button" id="bouton_suggerer_tags" /></td>
				
			<?php } ?>
			</tr>
			
			<?php if(($this->session->userdata('login_delicious')) && ($this->session->userdata('mdp_delicious')) && $user_connected==TRUE){ ?>
			<tr>
				<td><strong>Suggestions</strong></td>
				<td><div id="tags_suggeres"></div></td>
			</tr>
			<?php } ?>
			
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><strong>Mes tags Delicious</strong></td>
				<td>
				<?php
				if((isset($les_tags) || ($this->session->userdata('les_tags'))) && $user_connected == TRUE){
					$tab_tags = explode('/', $this->session->userdata('les_tags'));
					foreach($tab_tags as $tag){ 
						echo '<span class="tags-add">'.$tag.'</span> ';
					}
				}
				else { ?>
					Aucun tag suggéré.
				<?php
				}
				?>
				</td>
			</tr>
			<tr>
				<td><label for="tags"><strong>Tags choisis</strong></label></td>
				<td><input name="tags" id="tags" type="text" /></td>
				<td><i>*Séparez chaque tag par une virgule</i></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input name="valid_click" id="valid_click" type="hidden" /></td>
			</tr>
			<tr>
				<td>Publication privée</td>
				<td><input type="checkbox" name="prive" value="1"> <br></td>
			</tr>
			<tr>
				<td>Sélectionner un groupe</td>
				<td>
					<?php if(isset($mesGroupesAdmin) && count($mesGroupesAdmin) > 0): foreach($mesGroupesAdmin as $groupe): ?>
						<input type="checkbox" name="groupes[]" value="<?= $groupe->id_groupe ?>"> <?= $groupe->nom ?><br>
						<?php endforeach;					
						endif;
					?>
					<?php if(isset($mesGroupesMembres) && count($mesGroupesMembres) > 0): foreach($mesGroupesMembres as $groupe): ?>
						<input type="checkbox" name="groupes[]" value="<?= $groupe->id_groupe ?>"> <?= $groupe->nom ?><br>
						<?php endforeach;					
						endif;
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input class="button ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Valider" role="button" /></td>
			</tr>
		</table>
	<?php } ?>
	</form>
	<?php } 
	else { ?>
	<form method="post" action="">
	<h1>Vous devez être membre ou administrateur d'un groupe pour publier un lien.</h1>
	<?php } ?>
</div>