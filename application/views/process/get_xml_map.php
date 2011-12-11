<?php
$dom = new DOMDocument('1.0');
$node = $dom->createElement('utilisateurs');
$parnode = $dom->appendChild($node); 

header('Content-type: text/xml');

foreach($liste_membres as $membre){
	$node = $dom->createElement('utilisateur');
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute('email',$membre->email);
	$newnode->setAttribute('avatar',$membre->avatar);
	$newnode->setAttribute('nom',$membre->nom);
	$newnode->setAttribute('prenom',$membre->prenom);
	$newnode->setAttribute('adresse', $membre->adresse);
	$newnode->setAttribute('latitude', $membre->latitude);
	$newnode->setAttribute('longitude', $membre->longitude);
}

echo $dom->saveXML();
