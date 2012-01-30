<h1>Mot de passe oubli&eacute;</h1>
<?php 
if($showEmailForm) {
echo form_open_multipart('utilisateur/sendPasswordMail').'<br/>'
     .form_label('Veuillez saisir votre email : ', 'email').'<br/>'
     .form_input('email', set_value('email', isset( $_POST['email']) ?  $_POST['email'] : ''), 'id="email" size="80" maxlength="64"').'<br/>'
     .form_submit('Envoyer', 'Envoyer', 'class="button"').'<br/>'
     .form_close();
}

if($showPasswordForm){
echo form_open_multipart('utilisateur/updatePassword').'<br/>'
     .form_label('Veuillez saisir un nouveau mot de passe : ', 'pwd').'<br/>'
     .form_input('pwd','', 'id="pwd" size="40" maxlength="64"').'<br/>'
     .form_submit('Envoyer', 'Envoyer', 'class="button"').'<br/>'
     .form_close();
}

if($showSendSuccess){
    echo 'mail ok';
}

if($showSendFail){
    echo 'mail ko';
}

if($updateError){
    echo "update fail";
}
?>
