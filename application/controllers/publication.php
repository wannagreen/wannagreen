<?php
class Publication extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('form');
		
		$this->load->library('form_validation');
		$this->load->library('table');
	
		$this->load->Model('Publication_model');
		$this->load->Model('Publication_info_model');
		$this->load->Model('Publication_groupe_model');
		$this->load->Model('Tag_model');
		$this->load->Model('Commentaire_model');
		
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
		redirect('/publication/creation_publication');
	}
	
	public function creation_publication()
	{
		$this->data['module'] = 'publication_creer';
		$this->load->view('template', $this->data);
	}
	
	public function modification_publication($id_url = null)
	{
		if($id_url != null) {
			$publication = new Publication_model();
			$publication->id_publication = $id_url;
			$publication->get_publication_by_id();
			
			$liste_info = $this->Publication_info_model->get_by_id_publication($publication->id_publication);
			foreach ($liste_info as $info) {
				if($info->libelle == 'titre') {
					$publication->titre = $info->contenu;
				}
				if($info->libelle == 'description') {
					$publication->description = $info->contenu;
				}
			}
			
			$publication->liste_groupe_modif = $this->Publication_groupe_model->get_by_id_publication($publication->id_publication);
			
			$publication->tags = '';
			$liste_tags = $this->Tag_model->get_tag_publication($publication->id_publication);
			
			// $publication->tags = implode(',', $liste_tags);
			
			foreach ($liste_tags as $tag) {
				$publication->tags .= $tag->libelle . ', ' ;
			}
			
			$this->data['publication'] = $publication;
		}
		$this->data['module'] = 'publication_creer';
		$this->load->view('template', $this->data);
	}
	
	public function creer()
	{
		// Récupération de l'id de l'utilisateur connecté
		if($this->session->userdata('is_connected') === TRUE) $id_utilisateur = $this->session->userdata('id_utilisateur');
		
		$this->load->library('upload');
		
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		// Contrôle des champs obligatoires
		$this->form_validation->set_rules('titre', 'Titre', 'trim|required|max_length[128]');
		$this->form_validation->set_rules('description', 'Description', 'trim|htmlspecialchars');
		
		if($this->form_validation->run()) {
			$publication = new Publication_model();
			$publication->id_utilisateur = $id_utilisateur;
			$publication->type = 'article';
			$this->input->post('prive') == '1' ? $publication->prive = 1 : $publication->prive = 0;
			
			if($publication->create()) {				
				/*** Insertions en table publication_info ***/
				// Récupération de l'id et de la date de la nouvelle publication
				$id_publication = $publication->id_publication;
				$date_creation = $publication->date_creation;
				
				// 1. Insertion du titre
				$publication_info = new Publication_info_model();
				$publication_info->id_publication = $id_publication;
				$publication_info->libelle = 'titre';
				$publication_info->contenu = $this->input->post('titre');
				if($publication_info->create()){
					// 2. Insertion de la description
					$publication_info = new Publication_info_model();
					$publication_info->id_publication = $id_publication;
					$publication_info->libelle = 'description';
					$publication_info->contenu = $this->input->post('description');
                                        
                                        /// OK
					if($publication_info->create()){
						// 3. Insertion de la date
						$publication_info = new Publication_info_model();
						$publication_info->id_publication = $id_publication;
						$publication_info->libelle = 'date';
						$publication_info->contenu = $date_creation;
						if($publication_info->create()){
                                                    
                                                        
							/*** AJOUT Insertion en table publication_groupe si existant***/
                                                        if($this->input->post('groupes')!=null)
                                                        {
                                                            foreach ($this->input->post('groupes') as $id_groupe) {
								$publication_groupe = new Publication_groupe_model();
								$publication_groupe->id_publication = $id_publication;
								$publication_groupe->id_groupe = $id_groupe;
								$publication_groupe->create();
                                                            }
                                                        }
                                                        /*** AJOUT Insertion en table publication_groupe si existant***/
                                                        /*else
                                                        {
                                                        
                                                            $publication_groupe = new Publication_groupe_model();
                                                            $publication_groupe->id_publication = $id_publication;
                                                            $publication_groupe->id_groupe = 0;
                                                            $publication_groupe->create();
                                                            
                                                        }*/
                                                                /*** Gestion des tags ***/
							if($this->input->post('tags')!=''){
								$tags=Trim($this->input->post('tags'));
								$les_tags = str_replace(";",",",$tags);
								$tab_tags = explode(',', $les_tags);
								// Parcours de chaque tag
								foreach($tab_tags as $tag_saisi){ 
									$tag = new Tag_model();
									$tag_saisi2=Trim($tag_saisi);
									$tag->libelle = $tag_saisi2;
									if($tag_saisi2 != ''){
										// Si le tag n'existe pas
										if($tag->tag_exist()==0){
											// Insertion du tag
											if($tag->create_tag()){
												$id_tag = $tag->id_tag;
											}
										}
										// Si le tag existe on recupère son id
										else {
											$query = $tag->get_id_tag();
											$id_tag = $query->id_tag;
										}
										// Insertion dans la table tag_publication
										if($tag->add_tag_publication($id_tag,$id_publication,$id_utilisateur)){
											
											// Insertion dans la table tag_utilisateur
											if($tag->user_possede_tag($id_utilisateur,$id_tag)==0){
												if($tag->add_tag_user($id_tag,$id_utilisateur)){
													
												}
												else {
													$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
													$this->data['notice_type'] = 'error';
												}
											}
										}
										else {
											$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
											$this->data['notice_type'] = 'error';
										}
									}
								}
							} // fin gestion des tags
							
                                                        else {
								$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
								$this->data['notice_type'] = 'error';
							}
							
						}
						else {
							$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
							$this->data['notice_type'] = 'error';
						}
					}
					else {
						$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
						$this->data['notice_type'] = 'error';
					}
				}
				else {
					$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
					$this->data['notice_type'] = 'error';
				}
				$this->data['notice'] = 'L\'article a bien été publié';
				$this->data['notice_type'] = 'success';
			}
			else {
				$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
				$this->data['notice_type'] = 'error';
			}
			$this->data['context'] = $this->load->view('notice', $this->data, TRUE);
		}
		
		$this->data['module'] = isset($this->data['notice_type']) && $this->data['notice_type'] === 'success' ? 'publication_confirmation' : 'publication_creer';
		$this->load->view('template', $this->data);
	}
	
	public function modifier()
	{
		$this->creer();
		$this->supprimer();
	}
	
	public function supprimer()
	{	
		$id_publication = $this->uri->segment(4, null);
		if($id_publication == '') {
			$suppression = TRUE;
			$id_publication = $this->input->post('id_publication');
		}
		
		$tag_publication = new Tag_model();
		$tag_publication->id_publication = $id_publication;
		if($tag_publication->delete_tag_publication()){
			$this->data['notice'] = 'L\'article a bien été publié';
			$this->data['notice_type'] = 'success';
		}
		else {
			$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
			$this->data['notice_type'] = 'error';
		}
					
		$publication_groupe = new Publication_groupe_model();
		$publication_groupe->id_publication = $id_publication;
		if($publication_groupe->delete_publication_groupe()){
			$this->data['notice'] = 'L\'article a bien été publié';
			$this->data['notice_type'] = 'success';
		}
		else {
			$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
			$this->data['notice_type'] = 'error';
		}
		
		
		$publication_info = new Publication_info_model();
		$publication_info->id_publication = $id_publication;
		if($publication_info->delete_publication_info()){
			$this->data['notice'] = 'L\'article a bien été publié';
			$this->data['notice_type'] = 'success';
		}
		else {
			$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
			$this->data['notice_type'] = 'error';
		}
		
		$publication = new Publication_model();
		$publication->id_publication = $id_publication;
		if($publication->delete_publication()){
			$this->data['notice'] = 'L\'article a bien été publié';
			$this->data['notice_type'] = 'success';
		}
		else {
			$this->data['notice'] = 'Une erreur de traitement s\'est produite, merci de rééssayer';
			$this->data['notice_type'] = 'error';
		}
		
		
		// Retour vers la page précédente
		$page_precedente = $this->uri->segment(3, null);
		if($page_precedente == 'mes_publications') {
			$this->mes_publications();
		}
		if($page_precedente == 'publications_recentes') {
			$this->recente();
		}
		if($page_precedente == 'groupe_details') {
			$id_groupe = $this->uri->segment(5, null);
			redirect('/groupe/details/'.$id_groupe);
		}
		if($page_precedente == 'utilisateur_profil') {
			$id_utilisateur = $this->uri->segment(5, null);
			redirect('/utilisateur/profil/'.$id_utilisateur);
		}
		
	}
	
	public function commenter()
	{
		$id_groupe = $this->uri->segment(3, null);
		
		$commentaire = new Commentaire_model();
	
		$commentaire->id_utilisateur = $this->session->userdata('id_utilisateur');
		$commentaire->id_publication = $this->uri->segment(4, null);
		$commentaire->contenu = $this->input->post('commentaire');
		
		$result = $commentaire->create();
		if($result){
			$c = $this->Commentaire_model->get_by_id($commentaire->id_commentaire);
			$comm = array();
			$comm['id_utilisateur'] = $c->id_utilisateur;
			$comm['nom'] = $c->nom;
			$comm['prenom'] = $c->prenom;
			$comm['id_publication'] = $c->id_publication;
			$comm['contenu'] = $c->contenu;
			$comm['date_creation'] = time_to_str($c->date_creation);
			die(json_encode($comm));
		}
	}
	
	public function recente() {
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
	
	public function mes_publications() {
		$id_utilisateur = $this->session->userdata('id_utilisateur');
	
		$publication = new Publication_model();
		$liste_publication = $publication->get_publication_by_id_utilisateur($id_utilisateur);
		
		foreach ($liste_publication as $publication) {
			$publication->info = $this->Publication_info_model->get_by_id_publication($publication->id_publication);
			$publication->groupe = $this->Publication_groupe_model->get_by_id_publication($publication->id_publication);
			$publication->tags = $this->Tag_model->get_tag_publication($publication->id_publication);
			$publication->visible = TRUE;
		}
		
		$this->data['liste_publications'] = $liste_publication;
		
		$this->data['liste'] = 'mes_publications';
		$this->data['module'] = 'publication_liste';
		$this->load->view('template', $this->data);
	}
	
}
