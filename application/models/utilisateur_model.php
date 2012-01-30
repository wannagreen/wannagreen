<?php
class Utilisateur_model extends CI_Model {
	
	var $id_utilisateur = null;
	var $email = null;
	var $password = null;
	var $nom = null;
	var $prenom = null;
	var $adresse = null;
	var $latitude = null;
	var $longitude = null;
	var $sexe = null;
	var $date_naissance = null;
	var $avatar = null;
	var $date_creation = null;
	var $date_maj = null;
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*function qui permet de créer dans la base un utilisateur depuis un objet instancié*/
	function create()
	{
		$this->date_creation = date_db_format();
		if($this->id_utilisateur != null || ($this->email == null || $this->password == null || $this->nom == null || $this->prenom == null	|| $this->date_creation == null))
			$result = FALSE;
		else {
			$result = $this->db->insert('utilisateur', $this);
			$this->id_utilisateur = $this->db->insert_id();
		}
		return $result;
	}
	
	/*function qui modifie l'utilisateur dans la base par l'objet instancié*/
	function update()
	{   
		$this->date_maj = date_db_format();
		if($this->id_utilisateur == null || $this->email == null || $this->nom == null || $this->prenom == null || $this->date_maj == null)
			return FALSE;
		else {
			$fields = array (
				'email' => $this->email,
				'nom' => $this->nom,
				'prenom' => $this->prenom,
				'date_maj' => $this->date_maj
			);
			if($this->password != null)	$fields['password'] = $this->password;
			if($this->adresse != null) $fields['adresse'] = $this->adresse;
			if($this->latitude != null) $fields['latitude'] = $this->latitude;
			if($this->longitude != null) $fields['longitude'] = $this->longitude;
			if($this->sexe != null)	$fields['sexe'] = $this->sexe;
			if($this->date_naissance != null) $fields['date_naissance'] = $this->date_naissance;
			if($this->avatar != null) $fields['avatar'] = $this->avatar;
			
			$this->db->where('id_utilisateur', $this->id_utilisateur);
			return $this->db->update('utilisateur', $fields);
		}
	}
	
	/*function qui supprime l'utilisateur par l'id*/
	function delete()
	{
		$this->db->delete('utilisateur', array('id_utilisateur' => $this->id_utilisateur));
	}	
	
	/*function qui retourne tous les utilisateurs enregistrés dans la base*/
	function get_all()
	{
		$this->db->order_by('nom','ASC');
		$this->db->order_by('prenom','ASC');
		$query = $this->db->get('utilisateur');
		return $query->result();
	}
	
	/*function qui renvoie true si l'email existe déjà dans la table utilisateur et faux s'il n'existe pas*/
	function email_exists()
	{
		if($this->email == null)
			return FALSE;
		else {
			/*
			autre méthode:
			$this->db->select('id');
			$this->db->from('utilisateur');
			$this->db->where('email', $this->email);
			$query = $this->db->get();
			
			ou encore:
			$this->db->select('id')->from('utilisateur')->where('email', $this->email);
			$query = $this->db->get();
			*/
			$query = $this->db->get_where('utilisateur', array('email' => $this->email));
			return $query->num_rows() > 0;
		}
	}
	
	/*function qui return true si la combinaison email password est correcte*/
	function valid_connection()
	{
error_log("$this->email == null || $this->password == null)", 3, "/Users/mourad/hyperEspace/wannagreen/php_error.log" );
		if($this->email == null || $this->password == null)
			return FALSE;
		else {
			$query = $this->db->get_where('utilisateur', array('email' => $this->email, 'password' => $this->password), 1);
			$result = $query->num_rows() == 1;
			if($result) {
				$this->id_utilisateur = $query->row()->id_utilisateur;
				$this->email = $query->row()->email;
				$this->password = $query->row()->password;
				$this->nom = $query->row()->nom;
				$this->prenom = $query->row()->prenom;
				$this->adresse = $query->row()->adresse;
				$this->sexe = $query->row()->sexe;
				$this->date_naissance = $query->row()->date_naissance;
				$this->avatar = $query->row()->avatar;
				$this->date_creation = $query->row()->date_creation;
				$this->date_maj = $query->row()->date_maj;
			}
error_log("res=".print_r($result,1), 3, "/Users/mourad/hyperEspace/wannagreen/php_error.log" );
			return $result;
		}
	}
	
	/*function qui renvoie les profils des membres qui sont membres/favoris, enregistrés/en attente du groupe*/
	function liste_membre_groupe($id_groupe, $type, $statut, $mode_map = FALSE)
	{
		$this->db->select('u.id_utilisateur, u.email, u.password, u.nom, u.prenom, u.adresse, u.latitude, u.longitude, u.sexe, u.date_naissance,u.avatar, u.date_creation, u.date_maj');
		$this->db->from('utilisateur u');
		$this->db->join('adhesion a','a.id_utilisateur=u.id_utilisateur');
		$this->db->where('a.id_groupe', $id_groupe);
		$this->db->where('a.type',$type);
		if($mode_map) {
			$this->db->where('u.adresse <>', 'null');
			$this->db->where('u.latitude <>', 'null');
			$this->db->where('u.longitude <>', 'null');
		}
		$this->db->where('a.statut' ,$statut);
		$this->db->order_by('u.nom','ASC');
		$this->db->order_by('u.prenom','ASC');
		return $this->db->get();
	}
	
	/*function qui renvoie true si l'utilisateur existe dans la base*/
	function utilisateur_exists()
	{
		$query = $this->db->get_where('utilisateur', array('id_utilisateur' => $this->id_utilisateur));
		return $query->num_rows() == 1;
	}
	
	/*function qui instancie l'objet par l'envoie de son id*/
	function get_by_id()
	{
		$query = $this->db->get_where('utilisateur', array('id_utilisateur' => $this->id_utilisateur));
		
		$this->email = $query->row()->email;
		$this->password = $query->row()->password;
		$this->nom = $query->row()->nom;
		$this->prenom = $query->row()->prenom;
		$this->adresse = $query->row()->adresse;
		$this->sexe = $query->row()->sexe;
		$this->date_naissance = $query->row()->date_naissance;
		$this->avatar = $query->row()->avatar;
		$this->date_creation = $query->row()->date_creation;
		$this->date_maj = $query->row()->date_maj;
	}
	
	/*function qui renvoie la liste des profils externes de l'utilisateur*/
	public function get_profil_externe($id_utilisateur) {
		$this->db->select('p.id_utilisateur, p.id_site_social, p.url, s.libelle, s.avatar');
		$this->db->from('profil_externe p');
		$this->db->join('site_social s', 's.id_site_social = p.id_site_social');
		$this->db->where("p.id_utilisateur = $id_utilisateur");
		$query=$this->db->get();
		return $query->result();
	}

	/*function qui instancie l'objet par l'envoie d'un id_tag*/
	function get_utilisateur_by_id_tag($id_tag)
	{
		$this->db->select ('u.id_utilisateur, u.email, u.password, u.nom, u.prenom, u.adresse, u.sexe, u.date_naissance,u.avatar, u.date_creation, u.date_maj');
		$this->db->from('utilisateur u');
		$this->db->where('tag_utilisateur t','t.id_utilisateur=u.id_utilisateur');
		$this->db->where('t.id_tag',$id_tag);
		$query = $this->db->get();
		$this->id_utilisateur = $query->row()->id_utilisateur;
		$this->email = $query->row()->email;
		$this->password = $query->row()->password;
		$this->nom = $query->row()->nom;
		$this->prenom = $query->row()->prenom;
		$this->ville = $query->row()->ville;
		$this->sexe = $query->row()->sexe;
		$this->date_naissance = $query->row()->date_naissance;
		$this->avatar = $query->row()->avatar;
		$this->date_creation = $query->row()->date_creation;
		$this->date_maj = $query->row()->date_maj;
	}
	
	/*function qui instancie l'objet par l'envoie d'un id_publication*/
	function get_utilisateur_by_id_publication($id_publication)
	{
		$this->db->select ('u.id_utilisateur, u.email, u.password, u.nom, u.prenom, u.adresse, u.sexe, u.date_naissance,u.avatar, u.date_creation, u.date_maj');
		$this->db->from('utilisateur u');
		$this->db->join('publication p','p.id_utilisateur=u.id_utilisateur');
		$this->db->where('p.id_publication',$id_publication);
		$query = $this->db->get();
		$this->id_utilisateur = $query->row()->id_utilisateur;
		$this->email = $query->row()->email;
		$this->password = $query->row()->password;
		$this->nom = $query->row()->nom;
		$this->prenom = $query->row()->prenom;
		$this->ville = $query->row()->ville;
		$this->sexe = $query->row()->sexe;
		$this->date_naissance = $query->row()->date_naissance;
		$this->avatar = $query->row()->avatar;
		$this->date_creation = $query->row()->date_creation;
		$this->date_maj = $query->row()->date_maj;
	}
	
	/*function qui instancie l'objet par l'envoie d'un id_commentaire*/
	function get_utilisateur_by_id_commentaire($id_commentaire)
	{
		$this->db->select('u.id_utilisateur, u.email, u.password, u.nom, u.prenom, u.adresse, u.sexe, u.date_naissance,u.avatar, u.date_creation, u.date_maj');
		$this->db->from('utilisateur u');
		$this->db->join('commentaire c','c.id_utilisateur=u.id_utilisateur');
		$this->db->where('c.id_commentaire',$id_commentaire);
		$this->db->limit(1);
		$query = $this->db->get();
		
		$this->id_utilisateur = $query->row()->id_utilisateur;
		$this->email = $query->row()->email;
		$this->password = $query->row()->password;
		$this->nom = $query->row()->nom;
		$this->prenom = $query->row()->prenom;
		$this->ville = $query->row()->ville;
		$this->sexe = $query->row()->sexe;
		$this->date_naissance = $query->row()->date_naissance;
		$this->avatar = $query->row()->avatar;
		$this->date_creation = $query->row()->date_creation;
		$this->date_maj = $query->row()->date_maj;
	}
	
	/*function qui retourne les 5 derniers utilisateurs créés*/
	function get_last($limit=5)
	{
		$this->db->select('id_utilisateur, nom, email, avatar, prenom, ville, sexe, date_naissance, date_creation, date_maj, password')
				 ->from('utilisateur')
				 ->order_by('date_creation','DESC')
				 ->order_by('nom','ASC')
				 ->order_by('prenom','ASC')
				 ->limit($limit);
		$query = $this->db->get();
		return $query->result();
   	}
	
	/*function qui retourne vrai si l'utilisateur est membre/favoris du groupe*/
	function deja_membre ($id_utilisateur, $id_groupe)
	{
		$this->db->select('a.id_groupe,a.id_utilisateur,a.type,a.statut,a.date_creation,a.date_maj')
				 ->from('adhesion a')
				 ->where('a.id_groupe' , $id_groupe)
				 ->where('a.id_utilisateur' , $id_utilisateur)
				 ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	
	/*function qui retourne l'administrateur du groupe*/
	function get_admin ($id_groupe)
	{
		$this->db->select('u.id_utilisateur, u.email, u.password, u.nom, u.prenom, u.adresse, u.sexe, u.date_naissance,u.avatar, u.date_creation, u.date_maj')
					->from('utilisateur u')
					->join('groupe g','g.id_utilisateur=u.id_utilisateur')
					->where('g.id_groupe', $id_groupe)
					->limit(1);
		$query=$this->db->get();
		return $query->row();
	}

	/**
	 * retourne le mot de passe associe a un email
	 * @email string 
	 * @return array
	 */
	function getPasswordByMail($_email){
		$this->db->select('password')
				 ->from('utilisateur')
				 ->where('email' , $_email)
				 ->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	function updatePassword($_pwd, $_email=NULL){
		if($_email===NULL)
                {
                    $_email=$this->email;
                }
		$this->db->where('email', $_email);
		return $this->db->update('utilisateur', array('password'=>$_pwd));
	}
}
