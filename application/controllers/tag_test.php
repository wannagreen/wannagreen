<?php
class Tag_test extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->Model('Tag_model');
	}
	
	public function index()
	{
		$this->load->view('tag_view');
	}
	
        /* A SUPPRIMER */
        public function search($id_tag = null)
        {
            /*
            $tag = new Tag_model();
            $publication = new Publication_model();
            $tag->get_id_tag();
            $liste_publications = $publication->get_publication_by_id_tag($id_tag);
            
            $this->data['liste_publications'] = $liste_publications;
		
            //$this->data['liste'] = 'publications_recentes';
            $this->data['module'] = 'publication_liste';
            $this->load->view('template', $this->data);
            */
            
            $publication = new Publication_model();
		$liste_publication = $publication->get_publication_recente();
		
		foreach ($liste_publication as $publication) {
			$publication->info = $this->Publication_info_model->get_by_id_publication($publication->id_publication);
			$publication->groupe = $this->Publication_groupe_model->get_by_id_publication($publication->id_publication);
			$publication->tags = $this->Tag_model->get_tag_publication($publication->id_publication);
			
			$nb_publication_visible = 0;
			$publication->visible = FALSE;
                        
                        //foreach ($publication->info as $info) {
			foreach ($publication->groupe as $groupe) {
				if($publication->prive == 0) {
					$nb_publication_visible += 1;
                                       // $publication->visible = TRUE;
				}
				else {
					// Vérifie le lien entre l'utilisateur (si connecté) et le groupe
					$id_utilisateur = $this->session->userdata('id_utilisateur');
					
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
                        
                        /*if(count($publication->groupe) < count($liste_publication))
                        {
                            if($publication->prive == 0) {
					$nb_publication_visible += 1;
                                        $publication->visible = TRUE;
				}
                        }*/
		}
		$this->data['liste_publications'] = $liste_publication;
		
		$this->data['liste'] = 'publications_recentes';
		$this->data['module'] = 'publication_liste';
		$this->load->view('template', $this->data);
        }
	/*
	* Creation/ajout d'un tag
	*/
	function creer_tag(){
		$tag = new Tag_model();
		$tag->libelle='co2';
		if($tag->tag_exist()==0){
			if($tag->create_tag()){
				$data['texte']="Tag ajouté";
			}
			else {
				$data['texte']="le tag n'a pas été ajouté - erreur.";
			}	
		}
		else {
			$data['texte']="Ce tag existe déjà";
		}	
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Affiche les tags d'un utilisateur
	*/
	public function tag_utilisateur(){
		$tag = new Tag_model();
		// récupérer la variable session ?
		$id_utilisateur=3;
		$query=$tag->get_tag_utilisateur($id_utilisateur);
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="L'utilisateur n'a pas de tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Affiche les tags d'une publication
	*/
	public function tag_publication(){
		$tag = new Tag_model();
		// récupérer la variable session ?
		$id_publication=3;
		$query=$tag->get_tag_publication($id_publication);
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="La publication n'a pas de tag";
		}
		$this->load->view('tag_view', $data);
	}
	
      
	/*
	* Affiche les tags pour une publication et un utilisateur
	*/
	public function tag_publication_utilisateur(){
		$tag = new Tag_model();
		// récupérer la variable session ?
		$id_publication=1;
		// récupérer identifiant de l'utilisateur en session
		$id_utilisateur=1;
		$query=$tag->get_tag_publication_utilisateur($id_publication,$id_utilisateur);
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="La publication n'a pas de tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Vérifie si un tag existe dans la table tag
	*/
	public function tag_existe(){
		$tag = new Tag_model();
		// récupérer texte saisi ?
		//$tag->libelle='gougoutte';
		if($tag->tag_exist()>0){
			$data['texte']="Ce tag existe";
		}
		else {
			$data['texte']="Ce tag n'existe pas";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Vérifie si un utilisateur posséde un tag donné
	*/
	public function utilisateur_possede_tag(){
		$tag = new Tag_model();
		$id_utilisateur=3;
		$id_tag=1;
		if($tag->user_possede_tag($id_utilisateur,$id_tag)>0){
			$data['texte']="Cet utilisateur possède ce tag";
		}
		else {
			$data['texte']="Cet utilisateur ne possède pas ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Vérifie si un groupe possède un tag donné
	*/
	public function groupe_possede_tag(){
		$tag = new Tag_model();
		$id_groupe=3;
		$id_tag=1;
		if($tag->groupe_possede_tag($id_groupe,$id_tag)>0){
			$data['texte']="Ce groupe possède ce tag";
		}
		else {
			$data['texte']="Ce groupe ne possède pas ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Vérifie si une publication possède un tag donné
	*/
	public function publication_possede_tag(){
		$tag = new Tag_model();
		$id_publication=1;
		$id_tag=1;
		if($tag->publication_possede_tag($id_publication,$id_tag)>0){
			$data['texte']="Ce groupe possède ce tag";
		}
		else {
			$data['texte']="Ce groupe ne possède pas ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Affiche les tags d'un groupe
	*/
	public function tag_groupe(){
		$tag = new Tag_model();
		// récupérer la variable session ?
		$id_groupe=3;
		$query=$tag->get_tag_groupe($id_groupe);
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="Le groupe n'a pas de tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Affiche les tags d'un groupe
	*/
	public function tag_groupe_admin(){
		$tag = new Tag_model();
		// récupérer la variable session ?
		$id_groupe=3;
		$query=$tag->get_tag_groupe_admin($id_groupe);
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="Le groupe n'a pas de tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	//Ajouter des tags à une publication
	public function ajout_tag_publication(){
		$tag = new Tag_model();
		$id_publication=2;
		$id_tag=3;
		//recupérer l'identifiant de l'utilisateur
		$id_utilisateur=1;
		if($tag->publication_possede_tag($id_publication,$id_tag)==0) {
			if($tag->add_tag_publication($id_tag,$id_publication,$id_utilisateur)){
				$data['texte']="le tag a été ajouté à la publication";
			}
			else {
				$data['texte']="erreur - le tag n'a pu être ajouté";
			}
		}
		else {
			$data['texte']="la publication possède déjà ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	//Ajouter des tags à un groupe
	public function ajout_tag_groupe(){
		$tag = new Tag_model();
		$id_groupe=1;
		$id_tag=7;
		//recupérer l'identifiant de l'utilisateur
		$id_utilisateur=1;
		if($tag->groupe_possede_tag($id_groupe,$id_tag)==0) {
			if($tag->add_tag_groupe($id_tag,$id_groupe,$id_utilisateur)){
				//ajouter les tags de l'utilisateur dans la table tag_utilisateur
				$data['texte']="le tag a été ajouté au groupe";
			}
			else {
				$data['texte']="erreur - le tag n'a pu être ajouté";
			}
		}
		else {
			$data['texte']="le groupe possède déjà ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	//Ajouter des tags à un utilisateur
	public function ajout_tag_utilisateur(){
		$tag = new Tag_model();
		$id_utilisateur=3;
		$id_tag=7;
		//recupérer l'identifiant de l'utilisateur
		$id_utilisateur=1;
		if($tag->user_possede_tag($id_utilisateur,$id_tag)==0) {
			if($tag->add_tag_user($id_tag,$id_utilisateur,$id_utilisateur)){
				$data['texte']="le tag a été ajouté à l'utilisateur";
			}
			else {
				$data['texte']="erreur - le tag n'a pu être ajouté";
			}
		}
		else {
			$data['texte']="l'utilisateur possède déjà ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	
}
?>