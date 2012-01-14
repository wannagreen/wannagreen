<?php
function newChaine( $chrs = "") {

		if( $chrs == "" ) $chrs = 8;



		$chaine = ""; 



		$list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		mt_srand((double)microtime()*1000000);

		$newstring="";



		while( strlen( $newstring )< $chrs ) {

			$newstring .= $list[mt_rand(0, strlen($list)-1)];

		}

		return $newstring;

	}
?>

<h1>Mot de passe oubli&eacute;</h1>
<p>
Veuillez saisir votre email:
	<form method = "POST">
		<input type = "text" name = "email" size="50"/>
		<br /><br />
		<input type = "submit" value = "Envoyer"/>
	</form>
</p>
<?php 
if (isset ($_POST['email'])){
ini_set("SMTP","server9.000webhost.com");
ini_set("sendmail_from","admin@wannagreen.site88.net");
// To
$to = $_POST['email'];
// Subject
$subject = 'Nouveau mot de passe';
// Message
$msg = newChaine();
// Function mail()
mail($to, $subject, $msg);
?>
<br /><br />
<div style="color:#005100; font-weight:bold">
<?php
echo "Votre mot de passe vient de vous &ecirc;tre envoy&eacute;. Il se peut qu'il soit dans votre courrier ind&eacute;sirable.";
?>
</div>
<?php
}
?>






