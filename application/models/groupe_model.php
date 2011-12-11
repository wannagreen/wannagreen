<?php
class Groupe_model extends CI_Model {

	var $id_groupe = null;
	var $nom = null;
	var $description = null;
	var $avatar = null;
	var $ferme=null;
	var $poids_recommandation = null;
	var $date_creation = null;
	var $date_maj = null;
	var $id_utilisateur = null;
	
	/**
	 * Insert un groupe dans la base de données
	 */
	function create()
	{
		$this->date_creation = date_db_format();
		if($this->poids_recommandation == null) $this->poids_recommandation = 1;
		if($this->ferme == null) $this->ferme = 0;
		if($this->id_groupe != null || $this->nom == null || $this->description == null || $this->id_utilisateur == null) {
			$result = FALSE;
		}
		else {
			$result = $this->db->insert('groupe', $this);
			$this->id_groupe = $this->db->insert_id();
		}
		return $result;
	}

	/* Update de la description d'un groupe dans la base de données */
	function update_description()
	{   
		$this->date_maj = date_db_format();
		if($this->id_groupe == null || $this->description == null || $this->date_maj == null)
			return FALSE;
		else {
			$fields = array (
				'description' => $this->description,
				'date_maj' => $this->date_maj
			);
			
			$this->db->where('id_groupe', $this->id_groupe);
			return $this->db->update('groupe', $fields);
		}
	}
	
	/**
	 * Vérifie si un groupe existe à partir du nom saisit
	 */
	function groupe_exists() {
		if($this->nom == null)
			return FALSE;
		else {
			$query = $this->db->get_where('groupe', array('nom' => $this->nom), 1);
			return $query->num_rows() > 0;
		}
	}
	
	/**
	 * Vérifie si un groupe existe à partir de son id
	 */
	function groupe_exists_id() {
		if($this->id_groupe == null)
			return FALSE;
		else {
			$query = $this->db->get_where('groupe', array('id_groupe' => $this->id_groupe), 1);
			return $query->num_rows() > 0;
		}
	}	
	
	/**
	 * Affiche (par défaut) les cinq derniers groupes créés
	 */
	function get_last($limit=5){
		$this->db->select('id_groupe, nom, description, avatar, poids_recommandation, date_creation, id_utilisateur')
				 ->from('groupe')
				 ->order_by('date_creation','DESC')
				 ->limit($limit);
		$query=$this->db->get();
		return $query->result();
	}
	
	/**
	 * Affiche tous les groupes
	 */
	function get_all(){
		$this->db->select('id_groupe, nom, description, avatar, poids_recommandation, date_creation, id_utilisateur')
				 ->from('groupe')
				 ->order_by('nom','ASC');
		$query=$this->db->get();
		return $query->result();
	}
	
	function get_liste_partenaire($id, $confirmed = TRUE)
	{
		$and = $confirmed ? ' and statut = 1' : ' and statut = 0';
		$query = $this->db->query("
			(select p.id_groupe_demandeur id_groupe, g.nom, g.avatar from partenariat p, groupe g where p.id_groupe_demandeur = g.id_groupe and p.id_groupe_demande = $id $and)
			union
			(select p.id_groupe_demande id_groupe, g.nom, g.avatar from partenariat p, groupe g where p.id_groupe_demande = g.id_groupe and p.id_groupe_demandeur = $id $and)
		");
		
		return $query->result();
	}
	
	function liste_partenaire_possible($id_utilisateur, $id_groupe) {
		
		// Récupère la liste des groupes qui sont déjà partenaires avec le groupe sélectionné
		$result = $this->get_liste_partenaire($id_groupe, TRUE);
		$partenaires = array();
				
		// Récupère la liste des groupes qui ne sont pas encore partenaires avec le groupe sélectionné
		// Les groupes avec lesquels ont est pas encore partenaire et dont on est pas l'administrateur
		$this->db->select('id_groupe, nom, avatar');
		$this->db->from('groupe');
		$this->db->where('id_utilisateur =', $id_utilisateur);
		if(count($partenaires)) {
			$this->db->where_not_in('id_groupe', $partenaires);
		}
		$query=$this->db->get();
		return $query->result();
	}
	
	/*
	* Afficher les informations d'un groupe
	*/
	function get_details() {
		if($this->id_groupe == null || !$this->groupe_exists_id())
			return null;
		else {
			$query = $this->db->get_where('groupe', array('id_groupe' => $this->id_groupe), 1)->row();
			
			$this->nom = $query->nom;
			$this->description = $query->description;
			$this->avatar = $query->avatar;
			$this->poids_recommandation = $query->poids_recommandation;
			$this->date_creation = $query->date_creation;
			$this->date_maj = $query->date_maj;
			$this->id_utilisateur = $query->id_utilisateur;
			
			return $this;
		}
	}
	
	/*
	* Recommander un groupe
	*/
	function recommander($id_recommandeur){
		$date_creation = date_db_format();
		$data = array(
		   'id_groupe_recommandeur' => $id_recommandeur ,
		   'id_groupe_recommande' => $this->id_groupe ,
		   'date_creation' => $date_creation
		);
		// $result true : si ca marche, false sinon
		$result = $this->db->insert('recommandation', $data);
		return $result;
	}
	
	/*
	* Notifications d'un groupe (identifiant passé en paramètre)
	* Connaitre les groupes désirant devenir partenaire avec un groupe (dont l'identifiant est passé en paramètre)
	*/
	function get_notification_groupe_partenariat(){
		//$query=$this->db->get_where('partenariat', array('idgroupedemande' => $idgroupe, 'statut' => 0));
		
		$this->db->select('g.id_groupe, g.nom');
		$this->db->from('groupe g');
		$this->db->join('partenariat p', 'p.id_groupe_demandeur = g.id_groupe');
		$this->db->where('p.id_groupe_demande', $this->id_groupe);
		$this->db->where('p.statut', 0);
		$query=$this->db->get();
		
		return $query;
	}
	
	/*
	* Vérifier si un groupe est déjà partenaire avec un autre groupe
	*/
	function are_Partenaire($idgroupe2){
		$this->db->select('p.id_groupe_demande,p.id_groupe_demandeur');
		$this->db->from('partenariat p');
		//$this->db->where("(p.id_groupe_demandeur=$this->id_groupe AND p.id_groupe_demande=$idgroupe2 and p.statut=1) OR (p.id_groupe_demandeur=$idgroupe2 AND p.id_groupe_demande=$this->id_groupe and p.statut=1)");
		$this->db->where("(p.id_groupe_demandeur=$idgroupe2 AND p.id_groupe_demande=$this->id_groupe and p.statut=1) OR (p.id_groupe_demandeur=$this->id_groupe AND p.id_groupe_demande=$idgroupe2 and p.statut=1)");
		//$this->db->where('p.statut', 1);
		return $this->db->get()->num_rows > 0;
	}
	
	/*
	* Vérifie si la demande de partenariat est en cours
	* @param : identifiant de l'utilisateur, identifiant du groupe
	*/
	function deja_demande_partenariat($idgroupedemandeur){
		$this->db->select('p.id_groupe_demande,p.id_groupe_demandeur');
		$this->db->from('partenariat p');
		//$this->db->where("(p.id_groupe_demandeur=$this->id_groupe AND p.id_groupe_demande=$idgroupedemande and p.statut=0) OR (p.id_groupe_demandeur=$idgroupedemande AND p.id_groupe_demande=$this->id_groupe and p.statut=0)");
		$this->db->where("(p.id_groupe_demandeur=$idgroupedemandeur AND p.id_groupe_demande=$this->id_groupe and p.statut=0) OR (p.id_groupe_demandeur=$this->id_groupe AND p.id_groupe_demande=$idgroupedemandeur and p.statut=0)");
		//$this->db->where('p.statut', 1);
		return $this->db->get()->num_rows > 0;
	}
	
	/*
	* Vérifie si un utilisateur est déjà membre d'un groupe
	* @param : identifiant de l'utilisateur, identifiant du groupe
	*/
	function is_membre(){
		$statut=1;
		//$array = array('type' => 'membre', 'statut' => $statut, 'id_utilisateur' => $this->id_utilisateur, 'id_groupe' => $this->id_groupe);
		
		/*$this->db->select('id_groupe,id_utilisateur');
		$this->db->where($array); 
		$query=$this->db->get();*/
		$this->db->select('id_groupe,id_utilisateur');
		$this->db->from('adhesion');
		$this->db->where('type', 'membre');
		$this->db->where('statut', 1);
		$this->db->where('id_utilisateur', $this->id_utilisateur);
		$this->db->where('id_groupe', $this->id_groupe);
		return $this->db->get()->num_rows > 0;
	}
	
	/*
	* Vérifie si la demande de l'utilisateur est en cours pour un groupe
	* @param : identifiant de l'utilisateur, identifiant du groupe
	*/
	function deja_demande(){
		$this->db->select('id_groupe,id_utilisateur');
		$this->db->from('adhesion');
		$this->db->where('type', 'membre');
		$this->db->where('statut', 0);
		$this->db->where('id_utilisateur', $this->id_utilisateur);
		$this->db->where('id_groupe', $this->id_groupe);
		return $this->db->get()->num_rows > 0;
	}
	
	/*
	* Adhérer à un groupe
	* @param : identifiant de l'utilisateur, identifiant du groupe
	*/
	function adherer(){
		// Vérifie si un utilisateur adhère déjà à un groupe
		$this->db->select('id_groupe,id_utilisateur');
		$this->db->from('adhesion');
		$this->db->where('id_utilisateur', $this->id_utilisateur);
		$this->db->where('id_groupe', $this->id_groupe);
		
		$date=date_db_format();
		if($this->db->get()->num_rows > 0) {
			// Changement du statut au sein du groupe
			$data = array (
				'type' => $this->type,
				'statut' => $this->statut,
				'date_maj' => $date
			);
			return $this->db->update('adhesion', $data, array('id_utilisateur'=>$this->id_utilisateur, 'id_groupe'=>$this->id_groupe));
		}
		else {
			// Adhésion à un groupe
			$data = array(
				'id_groupe' => $this->id_groupe,
				'id_utilisateur' => $this->id_utilisateur,
				'type' => $this->type,
				'statut' => $this->statut,
				'date_creation' => $date
			);
			return $this->db->insert('adhesion', $data); 
		}
	}
	
	/*
	* Retourne le type de groupe (ouvert ou fermé : l'admin doit confirmer les demandes d'adhésion)
	* @param : identifiant du groupe
	*/
	function get_ferme() {
		$this->db->select('ferme');
		$this->db->from('groupe');
		$this->db->where('id_groupe', $this->id_groupe);
		$query=$this->db->get();
		return $query->row()->ferme;
	}
	
	/*
	* Se détacher d'un groupe (= quitter le groupe, = retirer de ses favoris, = annuler la demande d'adhésion)
	* @param : identifiant de l'utilisateur, identifiant du groupe
	*/
	function se_detacher(){
		$this->db->where('id_utilisateur', $this->id_utilisateur);
		$this->db->where('id_groupe', $this->id_groupe);
		return $this->db->delete('adhesion');
	}
	
	/*
	* modifier un groupe
	*/
	function update_group(){
		$data = array(
               'nom' => $this->nom,
               'description' => $this->description,
               'avatar' => $this->avatar,
			   'date_maj' => $this->date_maj
            );
		//$this->db->where('id_groupe', $groupe->id_groupe);*/
		//return $this->db->update('groupe', $this); 
		return $this->db->update('groupe', $data, "id_groupe = $this->id_groupe");
	}
		
	/*
	* Lister les groupes d'un utilisateur (ceux dont il est membre, sympathisant et administrateur)
	*/
	/*function liste_groupe_utilisateur($id_utilisateur){
		// problème n'affiche pas quand c'est admin (affiche favoris ou membre)
		$this->db->select('g.id_groupe,g.nom, g.id_utilisateur, a.type,a.statut');
		$this->db->from('groupe g');
		$this->db->join('adhesion a', 'a.id_groupe = g.id_groupe');
		$this->db->where("(g.id_utilisateur=$idutilisateur) OR (a.id_utilisateur=$idutilisateur and a.type='membre' and a.statut='1') OR (a.id_utilisateur=$idutilisateur and a.type='favoris' and a.statut='1')");
		$query=$this->db->get();
		return $query->result();
	}*/
	
	/**
	 * Liste des groupes d'un utilisateur dont il est administrateur
	 */
	function liste_groupe_admin($idutilisateur) {
		$query=$this->db->get_where('groupe', array('id_utilisateur' => $idutilisateur));
		//$query=$this->db->get();
		return $query->result();
	}
	
	function liste_groupe_membres($idutilisateur) {
		$this->db->select('g.id_groupe,g.nom, g.id_utilisateur,g.description, a.type,a.statut');
		$this->db->from('groupe g');
		$this->db->join('adhesion a', 'a.id_groupe = g.id_groupe');
		$this->db->where("(a.id_utilisateur=$idutilisateur and a.type='membre' and a.statut='1')");
		$query=$this->db->get();
		return $query->result();
	}
    
    /**
     * Liste des groupes d'un utilisateur dont il est favoris (sympathisant)
     */
    function liste_groupe_favoris($idutilisateur) {

        $this->db->select('g.id_groupe,g.nom, g.id_utilisateur,g.description, a.type,a.statut');
        $this->db->from('groupe g');
        $this->db->join('adhesion a', 'a.id_groupe = g.id_groupe');
        $this->db->where("(a.id_utilisateur=$idutilisateur and a.type='favoris' and a.statut='1')");
        $query=$this->db->get();
        return $query->result();
    }	
	
	/*
	* L'administrateur refuse une demande de partenariat action = 0)
	* L'administrateur quitte un partenariat (action = 1)
	*/
	function refuser_demande_partenariat_annuler_partenariat($groupe_demandeur){
		$this->db->where("((id_groupe_demande=$this->id_groupe and id_groupe_demandeur=$groupe_demandeur) or (id_groupe_demande=$groupe_demandeur and id_groupe_demandeur=$this->id_groupe))");
		return $this->db->delete('partenariat'); 
	}
	
	/**
	 * Demande de partenariat d'un groupe à un autre groupe
	 */
	function demande_partenariat($idgroupedemandeur){
		$date=date_db_format();
		$data = array(
		   'id_groupe_demandeur' => $idgroupedemandeur,
		   'id_groupe_demande' => $this->id_groupe,
		   'statut' => 0,
		   'date_creation' => $date);
		return $this->db->insert('partenariat', $data); 	
	}
	
	/**
	 * Valider une demande de partenariat
	 */
	function validate_partenariat($idgroupedemande){
		$data = array(
               'statut' => 1
            );
		return $this->db->update('partenariat', $data, "id_groupe_demandeur = $this->id_groupe and id_groupe_demande=$idgroupedemande and statut=0");
	}
	
	/**
	 * Retourne une array avec les indices et les infos permettant de constituer les onglets des groupes d'un membre
	 */
	function listes_groupes($id_utilisateur)
	{
		if($id_utilisateur != null) {
			$groupes = array();
			$groupes['tabs_mes_groupes'] = $this->liste_groupe_membres($id_utilisateur);
			$groupes['tabs_mes_favoris'] = $this->liste_groupe_favoris($id_utilisateur);
			$groupes['tabs_mes_admin'] = $this->liste_groupe_admin($id_utilisateur);
			return $groupes;
		}
		else
			return array();
	}
}