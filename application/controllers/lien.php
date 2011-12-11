<?php
class Lien extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('form');
		//modele publication
		$this->load->Model('Publication_model');
		//modele publication_info
		$this->load->Model('Publication_info_model');
		//modele publication_groupe
		$this->load->Model('Publication_groupe_model');
		// modele tag
		$this->load->Model('Tag_model');
		// fichier delicious
		$this->load->view('delicious');//Pour que ça marche avec CI
		$this->data = array();
		
		if($this->session->userdata('is_connected') === TRUE) {
			$this->data['user_connected'] = TRUE;
			$id_utilisateur = $this->session->userdata('id_utilisateur');
			$this->data = array_merge($this->data, $this->Groupe_model->listes_groupes($id_utilisateur));
		}
		else
			$this->data['user_connected'] = FALSE;
		
	}
	
	public function traitement_lien(){
		  /* Gestion des groupes de l'utilisateur connecté */
		if($this->session->userdata('is_connected') === TRUE) {
			$id_utilisateur = $this->session->userdata('id_utilisateur');
			$this->data['mesGroupesMembres'] = $this->Groupe_model->liste_groupe_membres($id_utilisateur);
			$this->data['mesGroupesAdmin'] = $this->Groupe_model->liste_groupe_admin($id_utilisateur);
		}
		if($this->session->userdata('is_connected') === TRUE) {
			//if(!isset($_SESSION['login_delicious']) || !isset($_SESSION['mdp_delicious'])){
			if(!($this->session->userdata('login_delicious')) || !($this->session->userdata('mdp_delicious'))){
				if(($this->input->post('login')!=null) && ($this->input->post('password')!=null) && ($this->input->post('password') != '' || $this->input->post('login') != '')){
					$username = $this->input->post('login');
					$password = $this->input->post('password');
				
					//Test connexion
					$delicious = new delicious($username,$password);
					if($delicious->getTags() === false){
						$this->data['tentative_connection'] = 'oui';
					}
					else {
						$this->data['tentative_connection'] = 'non';
						$this->data['est_co'] = 'oui';
						
						$this->session->set_userdata('login_delicious', $this->input->post('login'));
						$this->session->set_userdata('mdp_delicious', $this->input->post('password'));
						$this->session->set_userdata('nb_connection', 1);
					}
				}
				else {
					$this->data['est_co'] = 'non';
				}
			}
			
			if(
				   $this->input->post('url') != null
				&& $this->input->post('url') != ''
				&& $this->input->post('titre') != null
				&& $this->input->post('titre') != ''
				&& $this->input->post('tags') != null
				&& $this->session->userdata('login_delicious')
				&& $this->session->userdata('mdp_delicious')
			){
				$username = $this->session->userdata('login_delicious');
				$password = $this->session->userdata('mdp_delicious');
				$delicious = new delicious($username,$password);
					// ajout de l'url dans wannagreen
					$addurl  = $delicious->addUrl($this->input->post('url'),$this->input->post('titre'),$this->input->post('tags'),$this->input->post('url'));
					// ajout dans la base
					$publication = new Publication_model();
					$publication->type = 'lien';
					if(($this->input->post('prive')!='') && ($this->input->post('prive')==1)){
						$publication->prive = 1;
					}
					else $publication->prive = 0;
					$publication->id_utilisateur = $id_utilisateur;
					if($publication->create ()){
						$id_publication = $publication->id_publication;
						$date_creation = $publication->date_creation;
						
						// 1. Insertion du titre
						$publication_info = new Publication_info_model();
						$publication_info->id_publication = $id_publication;
						$publication_info->libelle = 'titre';
						$publication_info->contenu = $this->input->post('titre');
						
						
						if($publication_info->create()){
							$publication_info = new Publication_info_model();
							$publication_info->libelle = 'url';
							$url=$this->input->post('url');
							if(substr($url, 0, 7) == 'http://'){
							}
							else $url='http://'.$url;
							$publication_info->contenu = $url;
							$publication_info->id_publication = $id_publication;
							
							$id_publication_info = $publication_info->id_publication_info;
							
							if($publication_info->create()){
								// 3. Insertion de la date
								$publication_info = new Publication_info_model();
								$publication_info->id_publication = $id_publication;
								$publication_info->libelle = 'date';
								$publication_info->contenu = $date_creation;
								if($publication_info->create()){
									//gestion des tags
									if($this->input->post('tags')!=''){
										
										foreach ($this->input->post('groupes') as $id_groupe) {
											$publication_groupe = new Publication_groupe_model();
											$publication_groupe->id_publication = $id_publication;
											$publication_groupe->id_groupe = $id_groupe;
											$publication_groupe->create();
										}
										$tags=Trim($this->input->post('tags'));
										$les_tags = str_replace(";",",",$tags);
										$tab_tags = explode(',', $les_tags);
										//$tab_tags = explode(' ', $this->input->post('tags'));
										foreach($tab_tags as $tag_saisi){ 
											$tag = new Tag_model();
											$tag_saisi2=Trim($tag_saisi);
											$tag->libelle = $tag_saisi2;
											if($tag_saisi2 != ''){
												//si le tag n'existe pas
												if($tag->tag_exist() == 0){
													// insertion du tag
													if($tag->create_tag()){
														$id_tag=$tag->id_tag;
													}
												}
												//si le tag existe on recupère son id
												else {
													$query=$tag->get_id_tag();
													//$data['liste']=$query->result();
													$id_tag=$query->id_tag;
												}
												//insertion dans la table tag_publication
												if($tag->add_tag_publication($id_tag,$id_publication,$id_utilisateur)){
													if($tag->user_possede_tag($id_utilisateur,$id_tag)==0){
															if($tag->add_tag_user($id_tag,$id_utilisateur)){
																
															}
													}
													$this->data['message']="le tag a été ajouté à la publication";
												}
												else {
													$this->data['message']="erreur - le tag n'a pu être ajouté";
												}
											}
										}
									}
								}
							}
						}
						else {
							$id_publication_info = 'insertion publication_info échouée';
						}
					//si insertion ne fonctionne pas
					//else $id_utilisateur = print_r($publication, true).'<br />'.$this->db->last_query();
					}
					
				else if($this->input->post('tags') == ''){
					$addurl  = $delicious->addUrl($this->input->post('url'),$this->input->post('titre'),'',$this->input->post('url'));
					// ajout dans la base
					$publication = new Publication_model();
					$publication->type = 'lien';
					if(($this->input->post('prive')!='') && ($this->input->post('prive')==1)){
						$publication->prive = 1;
					}
					else $publication->prive = 0;
					$publication->id_utilisateur = $id_utilisateur;
					if($publication->create ()){
						$id_publication = $publication->id_publication;
						$date_creation = $publication->date_creation;
						/*$publication_groupe = new Publication_groupe_model();
							
						$publication_groupe->id_groupe = $this->input->post('groupe');
						$publication_groupe->id_publication = $id_publication;
						if($publication_groupe->create()){
							$this->data['message'] = "Insertion publication_groupe OK";
						}
						else{
							$this->data['message'] = "Insertion publication_groupe en erreur";
						}*/
						
						$publication_info = new Publication_info_model();
						// 1. Insertion du titre
						$publication_info = new Publication_info_model();
						$publication_info->id_publication = $id_publication;
						$publication_info->libelle = 'titre';
						$publication_info->contenu = $this->input->post('titre');
						
						if($publication_info->create()){
							//URL
							$publication_info = new Publication_info_model();
							$publication_info->libelle = 'url';
							$url=$this->input->post('url');
							if(substr($url, 0, 7) == 'http://'){
							}
							else $url='http://'.$url;
							$publication_info->contenu = $url;
							$publication_info->id_publication = $id_publication;
							
							$id_publication_info = $publication_info->id_publication_info;
							
							if($publication_info->create()){
								// 3. Insertion de la date
								$publication_info = new Publication_info_model();
								$publication_info->id_publication = $id_publication;
								$publication_info->libelle = 'date';
								$publication_info->contenu = $date_creation;
								if($publication_info->create()){
								
									foreach ($this->input->post('groupes') as $id_groupe) {
											$publication_groupe = new Publication_groupe_model();
											$publication_groupe->id_publication = $id_publication;
											$publication_groupe->id_groupe = $id_groupe;
											$publication_groupe->create();
									}
									
									//gestion des tags
									if($this->input->post('tags')!=''){
										
										$tags=Trim($this->input->post('tags'));
										$les_tags = str_replace(";",",",$tags);
										$tab_tags = explode(',', $les_tags);
										foreach($tab_tags as $tag_saisi){ 
											$tag = new Tag_model();
											$tag_saisi2=Trim($tag_saisi);
											$tag->libelle = $tag_saisi2;
											if($tag_saisi2 != ''){
												//si le tag n'existe pas
												if($tag->tag_exist() == 0){
													// insertion du tag
													if($tag->create_tag()){
														$id_tag=$tag->id_tag;
													}
												}
												//si le tag existe on recupère son id
												else {
													$query=$tag->get_id_tag();
													//$data['liste']=$query->result();
													$id_tag=$query->id_tag;
												}
												//insertion dans la table tag_publication
												if($tag->add_tag_publication($id_tag,$id_publication,$id_utilisateur)){
													if($tag->user_possede_tag($id_utilisateur,$id_tag)==0){
															if($tag->add_tag_user($id_tag,$id_utilisateur)){
																
															}
													}
													$this->data['message']="le tag a été ajouté à la publication";
												}
												else {
													$this->data['message']="erreur - le tag n'a pu être ajouté";
												}
											}
										}
									}
								}
							}
						}
						else {
							$id_publication_info = 'insertion publication_info échouée';
						}
					}
					//si insertion ne fonctionne pas
					else $id_utilisateur = print_r($publication, true).'<br />'.$this->db->last_query();
				}
				if($addurl === false) {
				}
				else {
					$this->data['message'] = 'URL ajoutée avec succès';
				}
			}
			else {
				//var_dump($this->session->userdata('nb_connection'));
				//print_r($this->session->userdata);
				if($this->input->post('url') == '' || $this->input->post('titre') == '')
					$this->data['message'] = 'Saisissez une URL et un titre';
			}//si le mec ne se connecte pas et fait une erreur on aura pas le message
			
			if($this->input->post('url')!=null && $this->input->post('url')!='' && $this->input->post('titre')!=null && ($this->input->post('tags')!=null) && !($this->session->userdata('login_delicious')) && !($this->session->userdata('mdp_delicious'))){
				// ajout seulement dans la base, pas d'ajout dans delicious
				$this->data['message'] = 'URL ajoutée avec succès (sans etre connecté)';
				$publication = new Publication_model();
				$publication->type = 'lien';
				if(($this->input->post('prive')!='') && ($this->input->post('prive')==1)){
					$publication->prive = 1;
				}
				else $publication->prive = 0;
				$publication->id_utilisateur = $id_utilisateur;
				if($publication->create ()){
					$id_publication = $publication->id_publication;
					$date_creation = $publication->date_creation;
					/*$publication_groupe = new Publication_groupe_model();
						
					$publication_groupe->id_groupe = $this->input->post('groupe');
					$publication_groupe->id_publication = $id_publication;
					if($publication_groupe->create()){
						$this->data['message'] = "Insertion publication_groupe OK";
					}
					else{
						$this->data['message'] = "Insertion publication_groupe en erreur";
					}*/
					
					// 1. Insertion du titre
					$publication_info = new Publication_info_model();
					$publication_info->id_publication = $id_publication;
					$publication_info->libelle = 'titre';
					$publication_info->contenu = $this->input->post('titre');
					
					if($publication_info->create()){
						// URL
						$publication_info = new Publication_info_model();
						$publication_info->libelle = 'url';
						$url=$this->input->post('url');
							if((substr($url, 0, 7) == 'http://') || (substr($url, 0, 7) == 'https://')){
							}
						else $url='http://'.$url;
						$publication_info->contenu = $url;
						$publication_info->id_publication = $id_publication;
						$id_publication_info = $publication_info->id_publication_info;
						
						if($publication_info->create()){
							// 3. Insertion de la date
							$publication_info = new Publication_info_model();
							$publication_info->id_publication = $id_publication;
							$publication_info->libelle = 'date';
							$publication_info->contenu = $date_creation;
							if($publication_info->create()){
							
								foreach ($this->input->post('groupes') as $id_groupe) {
											$publication_groupe = new Publication_groupe_model();
											$publication_groupe->id_publication = $id_publication;
											$publication_groupe->id_groupe = $id_groupe;
											$publication_groupe->create();
								}
								
								//gestion des tags
								if($this->input->post('tags')!=''){
									$tags=Trim($this->input->post('tags'));
									$les_tags = str_replace(";",",",$tags);
									$tab_tags = explode(',', $les_tags);
									foreach($tab_tags as $tag_saisi){ 
										$tag = new Tag_model();
										$tag_saisi2=Trim($tag_saisi);
										$tag->libelle = $tag_saisi2;
										if($tag_saisi2 != ''){
											//si le tag n'existe pas
											if($tag->tag_exist() == 0){
												// insertion du tag
												if($tag->create_tag()){
													$id_tag=$tag->id_tag;
												}
											}
											//si le tag existe on recupère son id
											else {
												$query=$tag->get_id_tag();
												//$data['liste']=$query->result();
												$id_tag=$query->id_tag;
											}
											//insertion dans la table tag_publication
											if($tag->add_tag_publication($id_tag,$id_publication,$id_utilisateur)){
												if($tag->user_possede_tag($id_utilisateur,$id_tag)==0){
														if($tag->add_tag_user($id_tag,$id_utilisateur)){
															
														}
												}
												$this->data['message']="le tag a été ajouté à la publication";
											}
											else {
												$this->data['message']="erreur - le tag n'a pu être ajouté";
											}
										}
									}
								}
							}
						}
					}
					else {
						$id_publication_info='insertion publication_info échouée';
					}
				}
				//si insertion ne fonctionne pas
				else $id_utilisateur = print_r($publication, true).'<br />'.$this->db->last_query();
				
				//$this->data['message'] = $id_utilisateur .' '. $id_publication_info;
				
			}
			
			if(($this->session->userdata('login_delicious')) && ($this->session->userdata('mdp_delicious'))){
				$username = $this->session->userdata('login_delicious');
				$password = $this->session->userdata('mdp_delicious');			
				// Get Tags
				$delicious = new delicious($username,$password);
				// on récupère les tags du compte
				$gettags  = $delicious->getTags();
				if($gettags === false) {
					
				}
				else {
					$this->data['les_tags']='';
					foreach($gettags->tag as $tag) {
						$this->data['les_tags']=$this->data['les_tags'].'/'.$tag->attributes()->tag;
					}
					$this->session->set_userdata('les_tags', $this->data['les_tags']);
				}
			}
		}
		else {
				$this->data['message']="Merci de vous connecter";
		}	
		$this->data['module'] = 'formulaire';
		$this->load->view('template', $this->data);

	}
	
	public function suggerer_tags(){ 
		if(($this->session->userdata('login_delicious')) && ($this->session->userdata('mdp_delicious'))){
			$username = $this->session->userdata('login_delicious');
			$password = $this->session->userdata('mdp_delicious');
			$delicious = new delicious($username,$password);
			// récupération des tags suggérés pour l'URL
			$suggest = $delicious->suggestTags($this->input->post('url'));
			
			if($suggest !== false) {
				//parcours des tags suggérés et ajout dans un tableau
				if(count($suggest->popular)>0) {
					foreach($suggest->popular as $tag) {
						$tags[] = $tag;
					}
				}
				//parcours des tags recommandés et ajout dans un tableau
				if(count($suggest->recommended)>0) {
					foreach($suggest->recommended as $tag) {
						$tags[] = $tag;
					}
				}
				if(count($suggest->recommended)>0) {
					$tags = array_unique($tags);
					foreach($tags as $tag){
						echo '<span class="tags-add">'.$tag.'</span> ';
					}
				}
				if(count($suggest->recommended)<=0 && count($suggest->popular)<=0){
					echo 'Aucun tag suggéré';
				}
			}
			else {
				echo 'Aucun tag suggéré';
			}
			
		}
	}
	
	public function index()
	{
		$id_utilisateur = $this->session->userdata('id_utilisateur');
		
		if($this->session->userdata('is_connected') === TRUE) {
			if(!($this->session->userdata('login_delicious')) || !($this->session->userdata('mdp_delicious'))){
			if($this->input->post('login')!=null && $this->input->post('password')!=null && ($this->input->post('password') != '' || $this->input->post('login') != '')){
				$username = $this->input->post('login');
				$password = $this->input->post('password');
				//Test connexion
				$delicious = new delicious($username,$password);
				if($delicious->getTags() === false){
					$this->data['tentative_connection'] = 'oui';
				}
				else {
					$this->data['tentative_connection'] = 'non';
					$this->data['est_co'] = 'oui';
					$this->session->set_userdata('login_delicious', $this->input->post('login'));
					$this->session->set_userdata('mdp_delicious', $this->input->post('password'));
					$this->session->set_userdata('nb_connection', 0);
					}
				}
				else {
					$this->data['est_co'] = 'non';
				}
			}
		}
		else {
				$this->data['message']="Merci de vous connecter";
		}
		$groupe = new Groupe_model();
		$this->data['mesGroupesMembres']=$groupe->liste_groupe_membres($id_utilisateur);
		$this->data['mesGroupesAdmin']=$groupe->liste_groupe_admin($id_utilisateur);
		$this->data['module'] = 'formulaire';
		$this->load->view('template', $this->data);
	}
	
	public function deconnexion(){
		$this->session->unset_userdata('login_delicious');
		$this->session->unset_userdata('mdp_delicious');
		$this->session->unset_userdata('nb_connection');
		$this->session->unset_userdata('les_tags');
		redirect('lien/index'); 
	}
	
	
	
}