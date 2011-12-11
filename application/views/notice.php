<?php
/**
 * Si $errors est passée à cette vue, on l'affiche en tant que liste d'erreurs
 * Sinon, si $notice et $notice_type existent, on affiche un message contextuel à partir de leurs valeurs
 */

if(isset($notice) && isset($notice_type) && in_array($notice_type, array('success','error','warning','info')))
	echo '<div class="'.$notice_type.'">'.$notice.'</div>';

if(isset($errors) && is_array($errors) && isset($notice))
	echo '<div class="error"><p>'.$notice.'</p>'.get_printable_array($errors).'</div>';
