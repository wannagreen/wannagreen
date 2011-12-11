<?php
class delicious {

    function delicious($user,$pass) {
        $this->username = $user;
        $this->password = $pass;
        $this->response = array();
        $this->timeout  = 5;
    }
    
    function getURLs($tag = '',$results = 15) {
        $url = 'https://api.del.icio.us/v1/posts/all?results=' . urlencode($results) . '&tag=' . urlencode($tag);
        return $this->fetch($url);
    }
    // récupération des tags delicious de l'utilisateur
    function getTags() {
        $url = 'https://api.del.icio.us/v1/tags/get?';
        return $this->fetch($url);
    }
    // récupération des tags suggérés sur pour une url
    function suggestTags($link) {
        $url = 'https://api.del.icio.us/v1/posts/suggest?url=' . urlencode($link);
        return $this->fetch($url);
    }
    // ajout de l'url dans delicious
    function addURL($link,$description,$tags,$notes) {
        $url = 'https://api.del.icio.us/v1/posts/add?url=' . urlencode($link) . '&description=' . urlencode($description) . '&extended=' . urlencode($notes) . '&tags=' . urlencode($tags) . '&replace=no&shared=yes';
        return $this->fetch($url);
    }
    
    function fetch($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
		// Définit l'en-tête User-Agent (le navigateur) pour la requête HTTP.
        curl_setopt($ch, CURLOPT_USERAGENT, 'Wannagreen');
		// retourne le transfert sous forme de chaine
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // vérifie le certificat du site : false
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// Temps maximal d'exécution, exprimé en secondes, de la fonction curl_exec.
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        $output = curl_exec($ch);
        $this->response = curl_getinfo($ch);
        curl_close($ch);
        if((int)$this->response['http_code'] == 200) {
            return new SimpleXMLElement($output);
        }
        else {
            return false;
        }
    }

}
?>