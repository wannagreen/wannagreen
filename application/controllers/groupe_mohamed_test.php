<?php
class Groupe_mohamed_test extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->Model('Groupe_model');
	}
	
	public function index()
	{
		$this->load->view('groupe_view');
	}
	
	/*
	* Création d'un groupe : vérifie si le groupe n'est pas déjà créé à partir du nom saisie
	* Si la création s'est passée correctement on envoie le nom du groupe dans la vue
	* sinon message d'erreur
	*/
	public function creation_groupe()
	{
		$groupe = new Groupe_model();
		
		//$groupe->idgroupe = null;
		$groupe->nom = 'PPD Bangui';
		$groupe->description = 'serveur de partage';
		$groupe->idutilisateur = 1;
		if(!$groupe->groupe_exist()){
			if($groupe->create()){
				$data['texte']=$groupe->nom;
			}
			else {
				$data['texte']='erreur de creation du groupe';
			}
		}
		else
			$data['texte']='Ce groupe existe déjà !';
		
		$this->load->view('groupe_view', $data);
	}
	
	/* 
	* Affiche les derniers groupes créés
	*/
	public function liste_dernier_groupe()
	{
		$data['liste']=$this->Groupe_model->get_last();
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Affiche tous les groupes
	*/
	public function liste_groupe()
	{
		$data['liste']=$this->Groupe_model->get_all();
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Affiche la liste des partenaires d'un groupe (dont l'identifiant
	* est passé en paramètre)
	*/
	public function liste_partenaire()
	{
		$idgroupe=$this->uri->segment(3, null);
		if($idgroupe==null){
			$data['texte']='pas de parteaire';
		}
		else {
			$data['liste']=$this->Groupe_model->get_liste_partenaire($idgroupe);
			
		}
		$this->load->view('groupe_view', $data);
	}
	
	public function information_groupe(){
		$idgroupe=$this->uri->segment(3, null);
		if($idgroupe==null){
			$data['texte']='pas de groupe';
		}
		else {
			$data['liste']=$this->Groupe_model->get_info_groupe($idgroupe);
			
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Recommander un groupe
	*/
	public function recommander(){
		$idgrouperecommandeur=1;
		$idgrouperecommande=2;
		if($this->Groupe_model->recommander($idgrouperecommandeur, $idgrouperecommande)){
			$data['texte']='groupe id '.$idgrouperecommandeur.' recommande le groupe id '.$idgrouperecommande.'.';
		}
		else {
			$data['texte']='insertion ne fonctionne pas';
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Notifications d'un groupe (connaitre les groupes désirant devenir partenaire)
	*/
	public function notifications_groupe_partenariat(){
		$idgroupe=2;
		$data['liste']=$this->Groupe_model->get_notification_groupe_partenariat($idgroupe);
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Notifications d'un groupe (connaitre les utilisateurs désirant devenir membre)
	*/
	public function notifications_groupe_membre(){
		$idgroupe=2;
		$data['liste']=$this->Groupe_model->get_notification_groupe_membre($idgroupe);
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Vérifier si un groupe est déjà partenaire avec un autre groupe
	*/
	public function sontPartenaires(){
		$idgroupe1=1;
		$idgroupe2=2;
		if($this->Groupe_model->are_Partenaire($idgroupe1,$idgroupe2)>0)
		{
			$data['texte']="Ces groupes sont déjà partenaires";
		}
		else {
			$data['texte']="Ces groupes ne sont pas partenaires";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Vérifier si un utilisateur est déjà membre d'un groupe
	*/
	public function estMembre(){
		$idmembre=1;
		$idgroupe=1;
		if($this->Groupe_model->isMembre($idmembre,$idgroupe)>0)
		{
			$data['texte']="Cet utilisateur est déjà membre";
		}
		else {
			$data['texte']="Cet utilisateur n'est pas membre.";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Utilisateur ajoute un groupe à ses favoris
	*/
	public function demande_favori(){
		$idutilisateur=2;
		$idgroupe=2;
		if($this->Groupe_model->isSympathisant($idgroupe,$idutilisateur)>0){
			$data['texte'] = "Vous etes deja membre de ce groupe";
		}
		else {
			if($this->Groupe_model->demande_favori($idgroupe, $idutilisateur)){
				$data['texte'] = "Groupe ajouté à vos favoris";
			}
			else {
				$data['texte'] = "échec de l'ajout du groupe";
			}
		}
		$this->load->view('groupe_view', $data);
	}
	
	public function Supprimer_groupe_favoris(){
		$idgroupe=2;
		$idutilisateur=2;
		if($this->Groupe_model->delete_lien_sympathisant($idgroupe,$idutilisateur)>0){
			$data['texte']="Suppression réussie";
		}
		else {
			$data['texte']="Suppression échouée";
		}
		
		$this->load->view('groupe_view', $data);
	}
	
}