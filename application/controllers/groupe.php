<?php
class Groupe extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->data = array();
		
		// Chargement des modèles
		$this->load->Model('Publication_model');
		$this->load->Model('Publication_info_model');
		$this->load->Model('Tag_model');
		$this->load->Model('Utilisateur_model');
		$this->load->Model('Commentaire_model');
		
		/* Gestion des groupes de l'utilisateur connecté */
		if($this->session->userdata('is_connected') === TRUE) {
			$this->data['user_connected'] = TRUE;
			$id_utilisateur = $this->session->userdata('id_utilisateur');
			$this->data = array_merge($this->data, $this->Groupe_model->listes_groupes($id_utilisateur));
			
			$user = new Utilisateur_model();
			$id_url = $this->uri->segment(3, null);
			if($id_url != null && intval($id_url) != 0) {
				$this->data['admin'] = $user->Utilisateur_model->get_admin($id_url);
				$this->data['is_admin'] = $this->data['admin']->id_utilisateur == $this->session->userdata('id_utilisateur');
			}
		}
		else
			$this->data['user_connected'] = FALSE;
	}
	
	public function index()
	{
		$this->data['module'] = 'groupe_liste';
		$this->load->view('template', $this->data);
	}
	
	/**
	 * Création d'un groupe : vérifie si le groupe n'est pas déjà créé à partir du nom saisie
	 * Si la création s'est passée correctement on envoie le nom du groupe dans la vue
	 * sinon message d'erreur
	 */
	public function creation()
	{	
		$this->data['module'] = 'groupe_creer';
		$this->load->view('template', $this->data);
	}
	
	/**
	 * Création d'un groupe en base
	 */
	public function creer()
	{
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->form_validation->set_rules('nom', 'Nom', 'trim|required|max_length[64]|htmlspecialchars');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|htmlspecialchars');
		
		if($this->form_validation->run() == TRUE) {
			$groupe = new Groupe_model();
			$groupe->nom = $this->input->post('nom');
			
			if(!$groupe->groupe_exists()) {
				if($this->session->userdata('is_connected') === TRUE) $groupe->id_utilisateur = $this->session->userdata('id_utilisateur');
				if($this->input->post('description') != '') $groupe->description = $this->input->post('description');			
				if($this->input->post('avatar') != '') $groupe->avatar = $this->input->post('avatar');		
				if($this->input->post('ferme') == '1') $groupe->ferme = 1;
				
				if($groupe->create()) {
					//***** Mohamed
					// gestion des tags
					if($this->input->post('tag') != ''){
						$tab_tags = explode(' ', $this->input->post('tag'));
						// parcours de chaque tag
						foreach($tab_tags as $tag_saisi){ 
							$tag = new Tag_model();
							$tag->libelle = $tag_saisi;
							//si le tag n'existe pas
							if($tag->tag_exist()==0){
								// insertion du tag
								if($tag->create_tag()){
									$id_tag = $tag->id_tag;
								}
							}
							//si le tag existe on recupère son id
							else {
								$query = $tag->get_id_tag();
								$id_tag = $query->id_tag;
							}
							//insertion dans la table tag_publication
							if($tag->add_tag_groupe($id_tag,$groupe->id_groupe,$this->session->userdata('id_utilisateur'))){
								$this->data['notice'] = 'Le groupe et les tags ont bien été créés';
								$this->data['notice_type'] = 'success';
							}
							else {
								$this->data['notice'] = 'Une erreur s\'est produite pendant le traitement, merci de rééssayer';
								$this->data['notice_type'] = 'error';
							}
						}
					}
					else {
						$this->data['notice'] = 'Le groupe a bien été créé';
						$this->data['notice_type'] = 'success';
					}
					//*****Fin Mohamed
					//OK avant traitement tag
					/*$this->data['notice'] = 'Le groupe a bien été créé';
					$this->data['notice_type'] = 'success';*/
				}
				else {
					$this->data['notice'] = 'Une erreur s\'est produite pendant le traitement, merci de rééssayer';
					$this->data['notice_type'] = 'error';
				}
			}
			else {
				$this->data['notice'] = 'Un groupe avec ce nom existe déjà';
				$this->data['notice_type'] = 'warning';
			}
			
								
			$this->data['context'] = $this->load->view('notice', $this->data, TRUE);
		}
		
		$this->data['module'] = isset($this->data['notice_type']) && $this->data['notice_type'] === 'success' ? 'groupe_confirmation' : 'groupe_creer';
		$this->load->view('template', $this->data);
	}
	
	/** 
	 * Affiche les derniers groupes créés
	 */
	public function liste_dernier()
	{
		$this->data['liste'] = $this->Groupe_model->get_last();
		$this->load->view('template', $this->data);
	}
	
	/**
	 * Affiche tous les groupes
	 */
	public function liste()
	{
		$this->load->helper('text');
		$this->data['groupes'] = $this->Groupe_model->get_all();
		$this->data['module'] = 'groupe_liste';
		
		foreach($this->data['groupes'] as $groupe):
			// JR_TO DO : même méthode que pour nb membres et favoris
			$groupe->nb_partenaires = count($this->Groupe_model->get_liste_partenaire($groupe->id_groupe));
			$groupe->nb_membres = $this->nb_membres($groupe->id_groupe);
			$groupe->nb_favoris = $this->nb_favoris($groupe->id_groupe);
		endforeach;
		
		$this->load->view('template', $this->data);
	}
	
	public function details($id_url = null) {		
		if($id_url != null) {
			$tag = new Tag_model();
			$groupe = new Groupe_model();
			$groupe->id_groupe = $id_url;
			$groupe->get_details();
			$this->data['groupe'] = $groupe; // On peut aussi mettre $groupe->get_details() ici

			// Récupère l'administrateur du groupe
			$this->load->Model('Utilisateur_model');
			$this->data['admin'] = $this->Utilisateur_model->get_admin($id_url);
			
			// Vérifie le lien entre l'utilisateur (si connecté) et le groupe
			$id_utilisateur = $this->session->userdata('id_utilisateur');
			if($id_utilisateur != null) {
				// Vérifie le type d'adhésion de l'utilisateur au groupe
				$this->data['adhesion'] = $this->Utilisateur_model->deja_membre($id_utilisateur, $id_url);
				
				// Récupère la liste des groupes dont l'utilisateur n'est pas l'admin et qui ne sont pas déjà dans les partenaires
				$this->data['liste_partenaires_possibles'] = $this->Groupe_model->liste_partenaire_possible($id_utilisateur, $id_url);
				
				$this->data['est_admin'] = FALSE;				
				// Vérifie si l'utilisateur connecté est l'admin du groupe
				if($id_utilisateur == $this->data['admin']->id_utilisateur) {
					$this->data['est_admin'] = TRUE;
					$this->data['liste_membres_attente'] = $this->Utilisateur_model->liste_membre_groupe($id_url, "membre", 0)->result();
				}
			}
			
			// Récupère le nombre de membres et de favoris du groupe
			$this->data['nb_membres'] = $this->nb_membres($id_url);
			$this->data['nb_favoris'] = $this->nb_favoris($id_url);
			
			// Récupère la liste des membres du groupe
			$this->data['liste_membres'] = $this->Utilisateur_model->liste_membre_groupe($id_url, "membre", 1)->result();
			
			// Récupère la liste des partenaires du groupe
			$this->data['liste_partenaires'] = $this->Groupe_model->get_liste_partenaire($id_url);
			
			$this->data['nb_partenaires'] = count($this->data['liste_partenaires']); // JR_TO DO : même méthode que pour nb membres et favoris
			
			//$this->data['nb_partenaires'] = $query->num_rows();
			
			// Liste des tags associés au groupe
			$this->data['liste_tags']= $tag->get_tag_groupe($id_url);
			
			
			/*** Publications ***/
			$publication = new Publication_model();
						
			if((isset($this->data['adhesion']) && $this->data['adhesion']) || (isset($this->data['est_admin']) && $this->data['est_admin'])) {
				$liste_publication = $publication->get_publication_by_id_groupe($id_url);
			}
			else {
				$liste_publication = $publication->get_publication_publique_by_id_groupe($id_url);
			}
	
			foreach ($liste_publication as $publication) {
				$publication->info = $this->Publication_info_model->get_by_id_publication($publication->id_publication);
				$publication->commentaires = $this->Commentaire_model->get_by_id_publication($publication->id_publication);
				$publication->tags = $this->Tag_model->get_tag_publication($publication->id_publication);
			}
			$this->data['liste_publications'] = $liste_publication;
			
			$this->data['liste_partenaires_demandes'] = $groupe->get_liste_partenaire($groupe->id_groupe, FALSE);
		}
		
		$this->data['module'] = 'groupe_details';
		$this->load->view('template', $this->data);
	}
	
	function liste_groupes_partenariat_possible()
	{
		$id_utilisateur = $this->session->userdata('id_utilisateur');
		$id_url = $this->uri->segment(3, null);
		
		$this->load->Model('Utilisateur_model');
		
		$this->data['liste_groupes_partenariat_possible'] = $this->Groupe_model->liste_partenaire_possible($id_utilisateur, $id_url);
		$this->data['id_utilisateur'] = $id_utilisateur;
		$this->data['id_url'] = $id_url;
		
		$this->load->view('modal/groupes_partenariat_possible.php', $this->data);
	}
	
	function get_xml_map()
	{
		$id_url = $this->uri->segment(3, null);
		$this->load->Model('Utilisateur_model');
		$data['liste_membres'] = $this->Utilisateur_model->liste_membre_groupe($id_url, 'membre', 1, TRUE)->result();
		
		$this->load->view('process/get_xml_map.php', $data);
	}
	
	/**
	 * Demander partenariat avec un groupe
	 */
	public function demander_partenariat(){
		
		//Attention, il faudra tester si l'utilisateur connecté est bien l'administrateur du groupe demandeur !
		//A réorganiser
		//are_Partenaire($idgroupe1,$idgroupe2)>0)
		$groupe = new Groupe_model();
		
		$idgroupedemandeur = $this->uri->segment(3, null);
		$groupe->id_groupe = $this->uri->segment(4, null);
		
		if($groupe->are_Partenaire($idgroupedemandeur) == 0){
			if($groupe->deja_demande_partenariat($idgroupedemandeur)==0){
				if($groupe->demande_partenariat($idgroupedemandeur)){
					$data['texte']="Demande de partenariat effectuée avec succès";
				}
				else {
					$data['texte']="Demande de partenariat échouée";
				}
			}
			else {
				$data['texte']="demande partenariat en cours";
			}
		}
		else {
			$data['texte']="Les groupes sont déjà partenaires";
		}
		//$this->load->view('groupe_view', $data);
		redirect('/groupe/details/'.$groupe->id_groupe);
	}
	
	/**
	 * Arrêter le partenariat avec un autre groupe ou refuser la demande de partenariat d'un autre groupe
	 */
	public function arreter_refuser_partenariat(){
		//Attention, il faudra tester si l'utilisateur connecté est bien l'administrateur du groupe demandeur !
		//A réorganiser
		$groupe = new Groupe_model();
		// identifiant du groupe visité
		$groupe->id_groupe = $this->uri->segment(3, null);
		$groupe->id_groupe_demande = $this->uri->segment(4, null);
		// 0 : refuser demande adhésion, 1 : quitter partenariat
		$action = 1;
		if($groupe->refuser_demande_partenariat_annuler_partenariat($action)){
			$data['texte'] = "Suppression réussie";
		}
		else {
			$data['texte'] = "Suppression échouée";
		}
		redirect('/groupe/details/'.$groupe->id_groupe);
	}
	
	/**
	 * Accepter la demande de partenariat d'un autre groupe
	 */
	public function accepter_partenariat(){

		$groupe = new Groupe_model();
		/*groupe->*/$id_groupe_demande = $this->uri->segment(3, null);
		$groupe->id_groupe = $this -> uri->segment(4, null);
		
		
		$groupe->statut = 1;  //Quoi ?				
		$groupe->validate_partenariat($id_groupe_demande);
		
		redirect('/groupe/details/'.$id_groupe_demande);
	}
	
	/**
	 * Recommander un groupe
	 */
	public function recommander(){
		$idgrouperecommandeur=1;
		$idgrouperecommande=2;
		if($this->Groupe_model->recommander($idgrouperecommandeur, $idgrouperecommande)){
			$this->data['texte']='groupe id '.$idgrouperecommandeur.' recommande le groupe id '.$idgrouperecommande.'.';
		}
		else {
			$this->data['texte']='insertion ne fonctionne pas';
		}
		$this->load->view('groupe_view', $this->data);
	}
	
	/**
	 * Notifications d'un groupe (connaitre les groupes désirant devenir partenaire)
	 */
	public function notifications_groupe_partenariat(){
		$idgroupe=2;
		$this->data['liste']=$this->Groupe_model->get_notification_groupe_partenariat($idgroupe);
		$this->load->view('groupe_view', $this->data);
	}
	
	/**
	 * Notifications d'un groupe (connaitre les utilisateurs désirant devenir membre)
	 */
	public function notifications_groupe_membre() {
		$idgroupe = 2;
		$this->data['liste'] = $this->Groupe_model->get_notification_groupe_membre($idgroupe);
		$this->load->view('groupe_view', $this->data);
	}
	
	/**
	 * Vérifier si un groupe est déjà partenaire avec un autre groupe
	 */
	public function sontPartenaires() {
		$idgroupe1 = 1;
		$idgroupe2 = 2;
		if($this->Groupe_model->are_Partenaire($idgroupe1,$idgroupe2)>0)
			$this->data['texte'] = 'Ces groupes sont déjà partenaires';
		else
			$this->data['texte'] = 'Ces groupes ne sont pas partenaires';
		$this->load->view('groupe_view', $this->data);
	}
	
	/**
	 * Vérifier si un utilisateur est déjà membre d'un groupe
	 */
	public function estMembre() {
		$idmembre = 1;
		$idgroupe = 1;
		if($this->Groupe_model->isMembre($idmembre,$idgroupe)>0)
			$this->data['texte'] = 'Cet utilisateur est déjà membre';
		else
			$this->data['texte'] = "Cet utilisateur n'est pas membre";
		$this->load->view('groupe_view', $this->data);
	}
	
	/**
	 * Nombre de membres d'un groupe
	 */
	public function nb_membres($id_groupe) {
		$this->load->Model('Utilisateur_model');
		return count($this->Utilisateur_model->liste_membre_groupe($id_groupe, 'membre', 1)->result());
	}
	
	/**
	 * Nombre d'utilisateurs ayant le groupe dans ses favoris
	 */
	public function nb_favoris($id_groupe) {
		$this->load->Model('Utilisateur_model');
		return count($this->Utilisateur_model->liste_membre_groupe($id_groupe, 'favoris', 1)->result());
	}
	
	/**
	 * Adhésion à un groupe
	 */
	public function adherer() {
	
		// Type d'adhésion
		$type_adhesion = $this->uri->segment(3, null);
	
		$groupe = new Groupe_model();
		$groupe->id_groupe = $this->uri->segment(4, null);
		$groupe->id_utilisateur = $this->session->userdata('id_utilisateur');
		
		if($type_adhesion == 'favoris') {
			$groupe->type = $type_adhesion; //favoris
			$groupe->statut = 1;
		}
		else {
			if($groupe->get_ferme() == 1) {
				$type_groupe=0; // fermé ==> statut en table adhésion = 0 (en attente)
			}
			else {
				$type_groupe=1; // libre ==> statut en table adhésion = 1
			}
			
			$groupe->type = $type_adhesion; //membre
			$groupe->statut = $type_groupe;		
		}
		
		$groupe->adherer();
		
		redirect('/groupe/details/'.$groupe->id_groupe);
	}
	
	/**
	 * Se détacher d'un groupe
	 */
	public function se_detacher(){
	
		$groupe = new Groupe_model();
		$groupe->id_groupe = $this->uri->segment(3, null);
		$groupe->id_utilisateur = $this->session->userdata('id_utilisateur');
		
		$groupe->se_detacher();
		
		redirect('/groupe/details/'.$groupe->id_groupe);
	}
	
	/**
	 * Accepter la demande d'adhésion à un groupe
	 */
	public function accepter_adhesion(){
				
		$groupe = new Groupe_model();
		$groupe->id_groupe = $this->uri->segment(3, null);
		
		$this->load->Model('Utilisateur_model');
		if($this->Utilisateur_model->get_admin($groupe->id_groupe)->id_utilisateur == $this->session->userdata('id_utilisateur')){	
			$groupe->id_utilisateur = $this->uri->segment(4, null);
			$groupe->type = "membre";
			$groupe->statut = 1;		
			$groupe->adherer();
		}
		redirect('/groupe/details/'.$groupe->id_groupe);
	}
	
	/**
	 * Refuser la demande d'adhésion à un groupe
	 */
	public function refuser_adhesion(){

		$groupe = new Groupe_model();
		$groupe->id_groupe = $this->uri->segment(3, null);
		$groupe->id_utilisateur = $this->uri->segment(4, null);		
		
		$groupe->se_detacher();
		
		redirect('/groupe/details/'.$groupe->id_groupe);
	}
	
	//Ajouter des tags à un groupe
	public function ajout_tag_groupe(){
		if($this->data['is_admin'] == TRUE) {
			$id_groupe = $this->uri->segment(3, null);
			//recupérér l'identifiant de l'utilisateur
			$id_utilisateur=$this->session->userdata('id_utilisateur');
			
			if($this->input->post('tags') != ''){
				//remplace les ";" par des ","
				$tags=Trim($this->input->post('tags'));
				$les_tags = str_replace(";",",",$tags);
				$tab_tags = explode(',', $les_tags);
				// parcours de chaque tag
				foreach($tab_tags as $tag_saisi){ 
					$tag = new Tag_model();
					$tag_saisi2=Trim($tag_saisi);
					$tag->libelle = $tag_saisi2;
					if($tag_saisi2 != ''){
						//si le tag n'existe pas
						if($tag->tag_exist()==0){
							// insertion du tag
							if($tag->create_tag()){
								$id_tag = $tag->id_tag;
							}
						}
						//si le tag existe on recupère son id
						else {
							$query = $tag->get_id_tag();
							$id_tag = $query->id_tag;
						}
						// on verifie si le groupe ne possède pas deja le tag
						$ok = FALSE;
						if($tag->groupe_possede_tag($id_groupe,$id_tag)==0) {
							if($tag->add_tag_groupe($id_tag,$id_groupe,$id_utilisateur)){
								//ajouter les tags de l'utilisateur dans la table tag_utilisateur
								$ok = TRUE;
							}
						}
					}
				}
			}
			if(isset($ok) && $ok) {
				$this->data['notice'] = 'les tags ont bien été ajoutés';
				$this->data['notice_type'] = 'success';
			}
			else {
				$this->data['notice'] = 'problème pour ajouter les tags';
				$this->data['notice_type'] = 'warning';
			}
			$this->load->view('notice', $this->data);
		}
		else {
			$this->data['notice'] = 'Vous n\'êtes pas administrateur de ce groupe';
			$this->data['notice_type'] = 'warning';
		}
	}
	
	public function supprimer_tag(){
		if($this->data['is_admin'] == TRUE) {
			$tag = new Tag_model();
			$id_groupe = $this->uri->segment(3, null);
			$id_tag = $this->input->post('id_tag');
			if($tag->supprimer_tag_groupe($id_tag, $id_groupe)){
				$this->data['notice'] = 'Le tag a bien été supprimé';
				$this->data['notice_type'] = 'success';
			}
			else {
				$this->data['notice'] = 'problème pour la suppression du tag';
				$this->data['notice_type'] = 'warning';
			}
			$this->load->view('notice', $this->data);
		}
	}
	

	public function ajouter_tag_publication(){
		$tag = new Tag_model();
		$id_utilisateur=$this->session->userdata('id_utilisateur');
		$id_publication = $this->uri->segment(4, null);
		
		if($this->input->post('tags') != ''){
			//remplace les ";" par des ","
			$tags=Trim($this->input->post('tags'));
			$les_tags = str_replace(";",",",$tags);
			$tab_tags = explode(',', $les_tags);
			// parcours de chaque tag
			foreach($tab_tags as $tag_saisi){ 
				$tag = new Tag_model();
				$tag_saisi2=Trim($tag_saisi);
				$tag->libelle = $tag_saisi2;
				if($tag_saisi2 != ''){
					//si le tag n'existe pas
					if($tag->tag_exist()==0){
						// insertion du tag
						if($tag->create_tag()){
							$id_tag = $tag->id_tag;
						}
					}
					//si le tag existe on recupère son id
					else {
						$query = $tag->get_id_tag();
						$id_tag = $query->id_tag;
					}
					// on verifie si la publication ne possède pas deja le tag
					$ok = FALSE;
					if($tag->publication_possede_tag($id_publication,$id_tag)==0) {
						if($tag->add_tag_publication($id_tag,$id_publication,$id_utilisateur)){
							//ajouter les tags de l'utilisateur dans la table tag_utilisateur
							$ok = TRUE;
						}
					}
				}
			}
		}
		if(isset($ok) && $ok) {
			$this->data['notice'] = 'les tags ont bien été ajoutés';
			$this->data['notice_type'] = 'success';
		}
		else {
			$this->data['notice'] = 'problème pour ajouter les tags';
			$this->data['notice_type'] = 'warning';
		}
		$this->load->view('notice', $this->data);
	}
	
	public function modifier_description_groupe() {
		$groupe = new Groupe_model();
		
		$id_groupe = $this->uri->segment(3, null);
		$description = $this->input->post('new_description');
		
		if($description != '' && $id_groupe != null && intval($id_groupe) != 0) {
			$groupe->id_groupe = $id_groupe;
			$groupe->description = $description;
			if($groupe->update_description()) {
				$this->output->set_status_header(200, 'Modification reussie !');
			}
			else {
				$this->output->set_status_header(500, 'Erreur de traitement !');
			}
		}
		else {
			$this->output->set_status_header(400, 'Erreur dans les parametres !');
		}
	}
	
}