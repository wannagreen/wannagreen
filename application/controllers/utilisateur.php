<?php
class Utilisateur extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper('form');
        
        $this->load->library('form_validation');
        $this->load->library('table');
        
        $this->load->Model('Utilisateur_model');
        $this->load->Model('Publication_model');
        $this->load->Model('Publication_info_model');
        $this->load->Model('Publication_groupe_model');
        $this->load->Model('Tag_model');
        
        $this->data = array();
        
        /* Gestion des groupes de l'utilisateur connecté */
        if($this->session->userdata('is_connected') === TRUE) {
            $this->data['user_connected'] = TRUE;
            $id_utilisateur = $this->session->userdata('id_utilisateur');
            $this->data = array_merge($this->data, $this->Groupe_model->listes_groupes($id_utilisateur));
        }
        else
            $this->data['user_connected'] = FALSE;
    }
    
    public function index()
    {
        redirect('utilisateur/inscription');
    }

    public function forgottenPAssword()
    {
         $this->_initForgottenPasswordVars();
         $this->data['showEmailForm']=TRUE;
         $this->data['module'] = 'forgottenPassword';
         $this->load->view('template', $this->data);
    }
    
    public function inscription()
    {
        $this->data['module'] = 'utilisateur_creer';
        $this->load->view('template', $this->data);
    }
    
    public function modification_profil()
    {
        $this->load->library('table');
        $utilisateur = new Utilisateur_model();
        $utilisateur->id_utilisateur = $this->session->userdata('id_utilisateur');
        $utilisateur->get_by_id();
        $this->data['utilisateur'] = $utilisateur;
        $this->data['module'] = 'utilisateur_creer';
        $this->load->view('template', $this->data);
    }
    
    public function modifier()
    {
        $this->load->library('upload');
        
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Mot de passe', 'matches[passwordconf]');
        $this->form_validation->set_rules('passwordconf', 'de confirmation du Mot de passe', 'matches[password]');
        $this->form_validation->set_rules('nom', 'Nom', 'trim|required|max_length[64]|htmlspecialchars');
        $this->form_validation->set_rules('prenom', 'Prénom', 'trim|required|max_length[64]|htmlspecialchars');
        $this->form_validation->set_rules('adresse', 'Adresse', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('sexe', 'Sexe', 'trim|alpha|max_length[1]');
        $this->form_validation->set_rules('date_naissance', 'Date de naissance', 'trim|max_length[10]|regex_match[/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/i]');
        
        $user = new Utilisateur_model();
        if($this->form_validation->run() == TRUE) {
            $user->email = $this->input->post('email');
            if($user->email == $this->session->userdata('email') || !$user->email_exists()) {
                $this->load->helper('mysecurity');
                $user->id_utilisateur = $this->session->userdata('id_utilisateur');
                $user->nom = $this->input->post('nom');
                $user->prenom = $this->input->post('prenom');
                if($this->input->post('password') != '') $user->password = crypt_password($this->input->post('password'));                
                if($this->input->post('sexe') != '') $user->sexe = $this->input->post('sexe');
                if($this->input->post('date_naissance') != '') $user->date_naissance = $this->input->post('date_naissance');
                
                // Gestion adresse
                $xmlGoogleMap = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=true&address=".$this->input->post('adresse');
                $document_xml = new DomDocument();
                $document_xml->load($xmlGoogleMap);
                $status = $document_xml->getElementsByTagName("status")->item(0);
                if ($status->nodeValue == "OK") {
                    $adresseGoogle = $document_xml->getElementsByTagName("formatted_address")->item(0)->nodeValue;
                    $latitude = $document_xml->getElementsByTagName("lat")->item(0)->nodeValue;
                    $longitude = $document_xml->getElementsByTagName("lng")->item(0)->nodeValue;
                    
                    $user->adresse = $adresseGoogle;
                    $user->latitude = $latitude;
                    $user->longitude = $longitude;
                }                
                
                // Gestion avatar
                if($this->upload->do_upload('avatar') || $this->upload->display_errors() == no_file_uploaded()) {
                    $avatar_ok = FALSE;
                    $updata = $this->upload->data();
                    
                    if(count($updata) && $updata['image_width'] >= 150 && $updata['image_width'] == $updata['image_height']) {
                        $this->load->library('image_lib');
                        $this->load->helper('myconfig');
                        
                        $this->image_lib->initialize(cfg_image_thumb($updata['file_name']));
                        if(!$this->image_lib->resize()) {
                            $this->data['notice'] = $this->image_lib->display_errors();
                            $this->data['notice_type'] = 'error';
                        }
                        $this->image_lib->clear();
                        $this->image_lib->initialize(cfg_image_regular($updata['file_name']));
                        if(!$this->image_lib->resize()) {
                            $this->data['notice'] = $this->image_lib->display_errors();
                            $this->data['notice_type'] = 'error';
                        }
                        
                        if(!isset($this->data['notice_type']) || $this->data['notice_type'] == 'error') {
                            $user->avatar = $updata['file_name'];
                            $avatar_ok = TRUE;
                        }
                    }
                    elseif(count($updata) && $this->upload->display_errors() != no_file_uploaded()) {
                        $this->data['notice'] = 'L\'avatar que vous importez doit avoir un format carré d\'au moins 150 pixels de côté';
                        $this->data['notice_type'] = 'error';
                    }
                    else
                        $avatar_ok = TRUE;
                    
                    /**
                     * Ici tout est ok (qu'il y ait un avatar de précisé ou non), on fait la création effective de l'utilisateur
                     */
                    if($avatar_ok) {
                        if($user->update()) {
                            $this->data['notice'] = 'Votre profil a bien été modifié';
                            $this->data['notice_type'] = 'success';
                            $new_sess_infos = array(
                                'email' => $user->email,
                                'nom' => $user->nom,
                                'prenom' => $user->prenom
                            );
                            $this->session->set_userdata($new_sess_infos);
                        }
                        else {
                            $this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
                            $this->data['notice_type'] = 'error';
                        }
                    }
                }
                else {
                    $this->data['notice'] = $this->upload->display_errors();
                    $this->data['notice_type'] = 'error';
                }
            }
            else {
                $this->data['notice'] = 'Cet email est déjà utilisé par un utilisateur';
                $this->data['notice_type'] = 'error';
            }    
            
            $this->data['context'] = $this->load->view('notice', $this->data, TRUE);
        }
        
        $this->data['utilisateur'] = $user;
        $this->data['module'] = isset($this->data['notice_type']) && $this->data['notice_type'] === 'success' ? 'utilisateur_confirmation' : 'utilisateur_creer';
        $this->load->view('template', $this->data);
    }
    
    public function creer()
    {
        $this->load->library('upload');
        
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required|matches[passwordconf]');
        $this->form_validation->set_rules('passwordconf', 'de confirmation du Mot de passe', 'required|matches[password]');
        $this->form_validation->set_rules('nom', 'Nom', 'trim|required|max_length[64]|htmlspecialchars');
        $this->form_validation->set_rules('prenom', 'Prénom', 'trim|required|max_length[64]|htmlspecialchars');
        $this->form_validation->set_rules('adresse', 'Adresse', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('sexe', 'Sexe', 'trim|alpha|max_length[1]');
        $this->form_validation->set_rules('date_naissance', 'Date de naissance', 'trim|max_length[10]|regex_match[/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/i]');
        
        if($this->form_validation->run()) {
            $user = new Utilisateur_model();
            $user->email = $this->input->post('email');
            if(!$user->email_exists()) {
                $this->load->helper('mysecurity');
                $user->nom = $this->input->post('nom');
                $user->prenom = $this->input->post('prenom');
                $user->password = crypt_password($this->input->post('password'));
                if($this->input->post('sexe') != '') $user->sexe = $this->input->post('sexe');
                if($this->input->post('date_naissance') != '') $user->date_naissance = $this->input->post('date_naissance');
                
                // Gestion adresse
                $xmlGoogleMap = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=true&address=".$this->input->post('adresse');
                $document_xml = new DomDocument();
                $document_xml->load($xmlGoogleMap);
                $status = $document_xml->getElementsByTagName("status")->item(0);
                if ($status->nodeValue == "OK") {
                    $adresseGoogle = $document_xml->getElementsByTagName("formatted_address")->item(0)->nodeValue;
                    $latitude = $document_xml->getElementsByTagName("lat")->item(0)->nodeValue;
                    $longitude = $document_xml->getElementsByTagName("lng")->item(0)->nodeValue;
                    
                    $user->adresse = $adresseGoogle;
                    $user->latitude = $latitude;
                    $user->longitude = $longitude;
                }
                
                // Gestion avatar
                if($this->upload->do_upload('avatar') || $this->upload->display_errors() == no_file_uploaded()) {
                    $avatar_ok = FALSE;
                    $updata = $this->upload->data();
                    
                    if(count($updata) && $updata['image_width'] >= 150 && $updata['image_width'] == $updata['image_height']) {
                        $this->load->library('image_lib');
                        $this->load->helper('myconfig');
                        
                        $this->image_lib->initialize(cfg_image_thumb($updata['file_name']));
                        if(!$this->image_lib->resize()) {
                            $this->data['notice'] = $this->image_lib->display_errors();
                            $this->data['notice_type'] = 'error';
                        }
                        $this->image_lib->clear();
                        $this->image_lib->initialize(cfg_image_regular($updata['file_name']));
                        if(!$this->image_lib->resize()) {
                            $this->data['notice'] = $this->image_lib->display_errors();
                            $this->data['notice_type'] = 'error';
                        }
                        
                        if(!isset($this->data['notice_type']) || $this->data['notice_type'] == 'error') {
                            $user->avatar = $updata['file_name'];
                            $avatar_ok = TRUE;
                        }
                    }
                    elseif(count($updata) && $this->upload->display_errors() != no_file_uploaded()) {
                        $this->data['notice'] = 'L\'avatar que vous importez doit avoir un format carré d\'au moins 150 pixels de côté';
                        $this->data['notice_type'] = 'error';
                    }
                    else
                        $avatar_ok = TRUE;
                    
                    /**
                     * Ici tout est ok (qu'il y ait un avatar de précisé ou non), on fait la création effective de l'utilisateur
                     */
                    if($avatar_ok) {
                        if($user->create()) {
                            $this->data['notice'] = 'Le compte a bien été créé, vous pouvez maintenant vous connecter';
                            $this->data['notice_type'] = 'success';
                        }
                        else {
                            $this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
                            $this->data['notice_type'] = 'error';
                        }
                    }
                }
                else {
                    $this->data['notice'] = $this->upload->display_errors();
                    $this->data['notice_type'] = 'error';
                }
            }
            else {
                $this->data['notice'] = 'Cet email est déjà utilisé par un utilisateur';
                $this->data['notice_type'] = 'error';
            }
            
            $this->data['context'] = $this->load->view('notice', $this->data, TRUE);
        }
        
        $this->data['module'] = isset($this->data['notice_type']) && $this->data['notice_type'] === 'success' ? 'utilisateur_confirmation' : 'utilisateur_creer';
        $this->load->view('template', $this->data);
    }
    
	/**
	* Block inscription simple sur l'accueil
	*/
	public function creer_simple()
    {
        $this->load->library('upload');
        
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required|matches[passwordconf]');
        $this->form_validation->set_rules('passwordconf', 'de confirmation du Mot de passe', 'required|matches[password]');
        $this->form_validation->set_rules('nom', 'Nom', 'trim|required|max_length[64]|htmlspecialchars');
        $this->form_validation->set_rules('prenom', 'Prénom', 'trim|required|max_length[64]|htmlspecialchars');
       
        
        if($this->form_validation->run()) {
            $user = new Utilisateur_model();
            $user->email = $this->input->post('email');
            if(!$user->email_exists()) {
                $this->load->helper('mysecurity');
                $user->nom = $this->input->post('nom');
                $user->prenom = $this->input->post('prenom');
                $user->password = crypt_password($this->input->post('password'));
				
				 if($user->create()) {
                            $this->data['notice'] = 'Le compte a bien été créé, vous pouvez maintenant vous connecter';
                            $this->data['notice_type'] = 'success';
                        }
                        else {
                            $this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
                            $this->data['notice_type'] = 'error';
                        }
						
				}else
				{
				$this->data['notice'] = 'Cet email est déjà utilisé par un utilisateur';
                $this->data['notice_type'] = 'error';
				}
        $this->data['context'] = $this->load->view('notice', $this->data, TRUE);
		}
		
		
		
		$this->data['liste'] = 'publications_recentes';
		$this->data['module'] = isset($this->data['notice_type']) && $this->data['notice_type'] === 'success' ? 'utilisateur_creer_simple' : 'utilisateur_creer_simple';
        $this->load->view('template', $this->data);
	}
	
    /**
     * Affiche tous les utilisateurs
     */
    public function liste()
    {
        $this->data['utilisateurs'] = $this->Utilisateur_model->get_all();
        $this->data['module'] = 'utilisateur_liste';
        $this->load->view('template', $this->data);
    }
    
    /**
     * Affiche le profil d'un utilisateur
     */
    public function profil()
    {        
        $utilisateur = new Utilisateur_model();
        $utilisateur->id_utilisateur = $this->uri->segment(3, null);
        
        if($utilisateur->id_utilisateur == null) {
            $this->data['notice'] = 'Cet utilisateur n\'existe pas';
            $this->data['notice_type'] = 'error';
            $this->data['context'] = $this->load->view('notice', $this->data);
        }
        else {
            $utilisateur->get_by_id();
            $this->data['utilisateur'] = $utilisateur;
            
            $this->data['utilisateur_groupes'] = $this->Groupe_model->liste_groupe_membres($utilisateur->id_utilisateur);
            $this->data['utilisateur_favoris'] = $this->Groupe_model->liste_groupe_favoris($utilisateur->id_utilisateur);
            $this->data['utilisateur_admin'] = $this->Groupe_model->liste_groupe_admin($utilisateur->id_utilisateur);
            
            $liste_profils_externes = $utilisateur->get_profil_externe($utilisateur->id_utilisateur);
            foreach($liste_profils_externes as $profil_externe) {
                switch($profil_externe->libelle) {
                    case 'Facebook':
                        $profil_externe->url = '<a class="_blank" href="http://www.facebook.com/'.$profil_externe->url.'">'.$profil_externe->url.'</a>';
                        break;
                    case 'Twitter':
                        $profil_externe->url = '@'.$profil_externe->url;
                        break;
                    case 'LinkedIn':
                        $profil_externe->url = '<a class="_blank" href="https://www.linkedin.com/e/fpf/'.$profil_externe->url.'">'.$profil_externe->url.'</a>';
                        break;
                    case 'Youtube':
                        $profil_externe->url = '<a class="_blank" href="http://www.youtube.com/user/'.$profil_externe->url.'">'.$profil_externe->url.'</a>';
                        break;
                    default:
                        break;
                }
            }
            $this->data['utilisateur_profils_externes'] = $liste_profils_externes;
            
            
            
            
            $publication = new Publication_model();
            $liste_publication = $publication->get_publication_by_id_utilisateur($utilisateur->id_utilisateur);
            
            foreach ($liste_publication as $publication) {
                $publication->info = $this->Publication_info_model->get_by_id_publication($publication->id_publication);
                $publication->tags = $this->Tag_model->get_tag_publication($publication->id_publication);
                $liste_groupe = $this->Publication_groupe_model->get_by_id_publication($publication->id_publication);
                $publication->groupe = $liste_groupe;
                
                $nb_publication_visible = 0;
                $publication->visible = FALSE;
                
                if(count($liste_groupe)>0)
                {
                  foreach ($liste_groupe as $groupe) {
                    // Vérifie le lien entre l'utilisateur (si connecté) et le groupe
                    $id_utilisateur = $this->session->userdata('id_utilisateur');
                    
                    if($publication->prive == 0 || $id_utilisateur == $publication->id_utilisateur) {
                        $nb_publication_visible += 1;
                    }
                    else {
                        if($id_utilisateur != null) {
                            // Récupère l'administrateur du groupe
                            $this->load->Model('Utilisateur_model');
                            $this->data['admin'] = $this->Utilisateur_model->get_admin($groupe->id_groupe);
                            $this->data['est_admin'] = FALSE;
                            // Vérifie si l'utilisateur connecté est l'admin du groupe
                            if($id_utilisateur == $this->data['admin']->id_utilisateur) {
                                $this->data['est_admin'] = TRUE;
                                $this->data['liste_membres_attente'] = $this->Utilisateur_model->liste_membre_groupe($groupe->id_groupe, "membre", 0)->result();
                            }
                            
                            // Vérifie le type d'adhésion de l'utilisateur au groupe
                            $this->data['adhesion'] = $this->Utilisateur_model->deja_membre($id_utilisateur, $groupe->id_groupe);
                            
                            if($this->data['adhesion'] || $this->data['est_admin']) {
                                $nb_publication_visible += 1;
                            }
                        }
                    }
                    if($nb_publication_visible >= 1)
                        $publication->visible = TRUE;
                    else
                        $publication->visible = FALSE;
                  }
                }
                
                /* Si pas membre s'un groupe */
                else {
                    
                    $id_utilisateur = $this->session->userdata('id_utilisateur');
                    
                    if($publication->prive == 0 || $id_utilisateur == $publication->id_utilisateur) {
                        $nb_publication_visible += 1;
                    }
                    
                    else {
                        
                        if($id_utilisateur != null) {
                            // Récupère l'administrateur du groupe
                            $this->load->Model('Utilisateur_model');
                            //$this->data['admin'] = $this->Utilisateur_model->get_admin($groupe->id_groupe);
                            $this->data['est_admin'] = FALSE;
                            // Vérifie si l'utilisateur connecté est l'admin du groupe
                            if($id_utilisateur == $this->data['admin']->id_utilisateur) {
                                $this->data['est_admin'] = TRUE;
                                $this->data['liste_membres_attente'] = $this->Utilisateur_model->liste_membre_groupe($groupe->id_groupe, "membre", 0)->result();
                            }
                            
                            // Vérifie le type d'adhésion de l'utilisateur au groupe
                            //$this->data['adhesion'] = $this->Utilisateur_model->deja_membre($id_utilisateur, $groupe->id_groupe);
                            
                            if($this->data['adhesion'] || $this->data['est_admin']) {
                                $nb_publication_visible += 1;
                            }
                        }
                    }
                    if($nb_publication_visible >= 1)
                        $publication->visible = TRUE;
                    else
                        $publication->visible = FALSE;
                }

}
            }
            $this->data['liste_publications'] = $liste_publication;
            
        //}
        
        $this->data['module'] = 'utilisateur_profil';
        $this->load->view('template', $this->data);
    }
    
    public function connexion()
    {
        $this->load->helper('mysecurity');
        
        $user = new Utilisateur_model();
        
        if(check_email($this->input->post('email')))
            $user->email = $this->input->post('email');
        else {
            $this->data['notice'] = 'L\'email que vous avez saisi est invalide';
            $this->data['notice_type'] = 'error';
        }
        $user->password = crypt_password($this->input->post('password'));
        
        if($user->valid_connection()) {
            // On créer la session pour l'utilisateur
            $sess_infos = array(
                'is_connected' => TRUE,
                'id_utilisateur' => $user->id_utilisateur,
                'email' => $user->email,
                'nom' => $user->nom,
                'prenom' => $user->prenom
            );
            $this->session->set_userdata($sess_infos);
                        redirect('accueil');
        }
                
                if($user->valid_connection()==false) {
            // On créer la session pour l'utilisateur
                    /*
            $this->data['notice'] = 'Les informations de connexion que vous avez saisies sont invalides';
            $this->data['notice_type'] = 'error';*/
                        //$this->load->view('modules/utilisateur_creer', $this->data);
                    /*$data['message'] = 'La connexion a échoué. Veuillez vérifier vos paramètres de connexion.';
                    
                    $data['erreur'] = true;*/
                    //$this->load->view('template', $data);
                  redirect(base_url()."publication/erreurconnexion_accueil_recente");
        }
               
        /*else
                {
                    $this->data['notice'] = 'L\'email que vous avez saisi est invalide';
                    $this->data['notice_type'] = 'error';
                } */
        /* Gestion des groupes de l'utilisateur connecté */
        if($this->session->userdata('is_connected') === TRUE) {
            $this->data['user_connected'] = TRUE;
            $id_utilisateur = $this->session->userdata('id_utilisateur');
            $this->data = array_merge($this->data, $this->Groupe_model->listes_groupes($id_utilisateur));
            redirect('accueil');
        }
        
        //redirect('accueil');
    }

    public function sendPasswordMail(){
        $this->_initForgottenPasswordVars();
        $this->data['showSendFail']=TRUE;
        $this->load->helper('email');
        if (isset ($_POST['email'])){
            if(valid_email($this->input->post('email'))){
                $this->load->library('encrypt');
                $user_model = new Utilisateur_model();
                $pwd = $user_model->getPasswordByMail($this->input->post('email'));
                if(!empty($pwd)){
                    $this->load->helper('language');
                    $this->load->library('email');
                    $this->email->from('admin@wannagreen.site88.net', 'Administrateur');
                    $this->email->to($this->input->post('email')); 
                    $userinfo = $pwd[0];
                    $this->lang->load('forgotten_password','francais');
                    $this->email->subject($this->lang->line('mail_subject'));
                    $url = base_url().'utilisateur/modifyPassword/'.$this->input->post('email').'/'.$userinfo->password;
                    $email_body = str_replace('***********MAIL_LINK***********',$url,$this->lang->line('mail_text'));
                    $this->email->message($email_body); 
                    $this->email->send();
                    $this->data['showSendSuccess']=TRUE;
                    $this->data['showSendFail']=FALSE;
                }
                else
                {
                    $this->data['showEmailForm']=TRUE;
                }
            }
        }
        
        $this->data['module'] = 'forgottenPassword';
        $this->load->view('template', $this->data);
    }
    
    public function modifyPassword($_oldemail, $_hashpwd){
        $this->_initForgottenPasswordVars();
        $this->data['showPasswordForm']=TRUE;
        $this->load->library('encrypt');
        $this->load->helper('cookie');
        $encrypted_string = $this->encrypt->encode($_oldemail.'|'.$_hashpwd);
        $cookie = array(
            'name'   => 'authData',
            'value'  => $encrypted_string,
            'expire' => '300',
            'domain' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : ''
        );
        $this->input->set_cookie($cookie);
        $this->data['hideEmailForm']=TRUE;
        $this->data['module'] = 'forgottenPassword';
        $this->load->view('template', $this->data);
    }
    
    public function updatePassword(){
        $this->_initForgottenPasswordVars();
        if($this->input->post('pwd')!=NULL){
            $this->load->library('encrypt');
            $this->load->helper('cookie');
            $decrypted_string = $this->encrypt->decode($this->input->cookie('authData'));
            $decrypted_string = explode('|',$decrypted_string);
            if(count($decrypted_string) == 2){
                $user_model = new Utilisateur_model();
                $user_model->password = $decrypted_string[1];
                $user_model->email = $decrypted_string[0];
                if($user_model->valid_connection()){
                     $this->load->helper('mysecurity');
                     $pwd = crypt_password($this->input->post('pwd'));
                     $user_model->updatePassword($pwd);
                     $sess_infos = array(
                         'is_connected' => TRUE,
                         'id_utilisateur' => $user_model->id_utilisateur,
                         'email' => $user_model->email,
                         'nom' => $user_model->nom,
                         'prenom' => $user_model->prenom
                     );
                     $this->session->set_userdata($sess_infos);
                     delete_cookie('authData');
                     redirect('accueil');
                }
            }
        }
        
        $this->data['showPasswordForm'] = TRUE;
        $this->data['updateError']=TRUE;
        $this->data['module'] = 'forgottenPassword';
        $this->load->view('template', $this->data);
    }
   
    private function _initForgottenPasswordVars(){
        $this->data['showEmailForm']=
        $this->data['showPasswordForm']=
        $this->data['showSendSuccess']=
        $this->data['showSendFail']=
        $this->data['updateError']=FALSE;
    }
     
    public function deconnexion()
    {
        $this->session->sess_destroy(); // Destruction de la session        
        redirect('/');
    } 
}
