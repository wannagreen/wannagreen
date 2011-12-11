<?php
class Groupe_test extends CI_Controller {
	
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
		$groupe = new Groupe_model();
		// voir comment le récupérer (variable session ?)
		$idgrouperecommandeur=1;
		// groupe recommandé (id dans l'url)
		$groupe->id_groupe=$this->uri->segment(3, null);
		if($groupe->recommander($idgrouperecommandeur)){
			$data['texte']='groupe id '.$idgrouperecommandeur.' recommande le groupe id '.$groupe->id_groupe.'.';
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
		$groupe = new Groupe_model();
		$groupe->id_groupe=$this->uri->segment(3, null);
		$query=$groupe->get_notification_groupe_partenariat();
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="Il n'y a pas de notification pour ce groupe";
		}	
		
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Notifications d'un groupe (connaitre les utilisateurs désirant devenir membre)
	*/
	public function notifications_groupe_membre(){
		$groupe = new Groupe_model();
		$groupe->id_groupe=$this->uri->segment(3, null);
		$query=$groupe->get_notification_groupe_membre();
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="Il n'y a pas de notification (membres) pour ce groupe";
		}	
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Vérifier si un groupe est déjà partenaire avec un autre groupe
	*/
	public function sontPartenaires(){
		$groupe = new Groupe_model();
		$groupe->id_groupe=$this->uri->segment(3, null);
		// récupérer la variable en session ?
		$idgroupe2=3;
		if($groupe->are_Partenaire($idgroupe2)>0)
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
		$groupe = new Groupe_model();
		$groupe->id_groupe=$this->uri->segment(3, null);
		//$groupe->id_groupe=1;
		$groupe->id_utilisateur=1;
		if($groupe->isMembre()>0)
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
		$groupe = new Groupe_model();
		// récupérer la variable session ?
		$groupe->id_utilisateur=2;
		// identifiant du groupe visité
		$groupe->id_groupe=$this->uri->segment(3, null);
		if($groupe->isSympathisant()>0){
			$data['texte'] = "Vous etes deja membre de ce groupe";
		}
		else {
			if($groupe->demande_favori()){
				$data['texte'] = "Groupe ajouté à vos favoris";
			}
			else {
				$data['texte'] = "échec de l'ajout du groupe";
			}
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Utilisateur supprime un groupe de ses favoris
	*/
	public function Supprimer_groupe_favoris(){
		$groupe = new Groupe_model();
		// récupérer la variable session ?
		$groupe->id_utilisateur=2;
		// identifiant du groupe visité
		$groupe->id_groupe=$this->uri->segment(3, null);
		if($groupe->delete_lien_sympathisant()){
			$data['texte']="Suppression réussie";
		}
		else {
			$data['texte']="Suppression échouée";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Liste groupe d'un utilisateur (membre, sympathisant, admi)
	*/
	public function getMesGroupes(){
		$idutilisateur=1;
		$data['liste']=$this->Groupe_model->liste_groupe_utilisateur($idutilisateur);
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Liste des groupes dont il est administrateur
	*/
	public function mesGroupesAdmin(){
		$groupe = new Groupe_model();
		// récupérer la variable session ?
		$groupe->id_utilisateur=1;
		$query=$groupe->liste_groupe_admin();
		if($query->num_rows()>0){
			$data['liste']=$query->result();
		}
		else {
			$data['texte']="Vous êtes administrateur de 0 groupe.";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Liste des groupes dont il est membre
	*/
	public function mesGroupesMembresFavoris(){
		$idutilisateur=2;
		$data['liste']=$this->Groupe_model->liste_groupe_membres_favoris($idutilisateur);
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* L'administrateur refuse une demande d'adhésion à un groupe (action = 0)
	* L'utilisateur quitte un groupe dont il est membre (action = 1)
	*/
	public function refuserDemandeAdhesion(){
		$groupe = new Groupe_model();
		// récupérer la variable session ?
		$groupe->id_utilisateur=1;
		// identifiant du groupe visité
		$groupe->id_groupe=$this->uri->segment(3, null);
		// 0 : refuser demande adhésion, 1 : quitter groupe
		$action=0;
		if($groupe->refuser_demande_adhesion_quitter_groupe($action)){
			$data['texte']="Suppression réussie";
		}
		else {
			$data['texte']="Suppression échouée";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* L'administrateur refuse une demande de partenariat (action = 0)
	* L'administrateur quitte un partenariat (action = 1)
	*/
	public function RefuseQuitterPartenariat(){
		$groupe = new Groupe_model();
		// identifiant du groupe visité
		$groupe->id_groupe=$this->uri->segment(3, null);
		// 0 : refuser demande adhésion, 1 : quitter partenariat
		$action=1;
		if($groupe->refuser_demande_partenariat_annuler_partenariat($action)){
			$data['texte']="Suppression réussie";
		}
		else {
			$data['texte']="Suppression échouée";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* modifier un groupe
	*/
	public function ModifierGroupe(){
		$groupe = new Groupe_model();
		
		$groupe->id_groupe=$this->uri->segment(3, null);
		$groupe->nom = 'PPD robotique';
		$groupe->description = 'robot NAO';
		$groupe->avatar='monAvatar.png';
		$groupe->date_maj=date_db_format();
		
		if($groupe->update_group()){
			$data['texte']=$groupe->date_maj ;
		}
		else {
			$data['texte']="Modification échouée";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Demande d'adhésion un groupe
	*/
	public function demande_adhesion(){
		$groupe = new Groupe_model();
		$groupe->id_groupe=$this->uri->segment(3, null);
		$groupe->id_utilisateur=1;
		if($groupe->is_membre()==0){
			if($groupe->deja_demande()==0){
				if($groupe->demande_adhesion_groupe()){
					$data['texte']="Demande effectuée avec succès";
				}
			}
			else {
				$data['texte']="Demande en cours";
			}
		}
		else {
				$data['texte']="Vous êtes déjà membre de ce groupe";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Administrateur valide une demande d'adhésion
	*/
	public function valider_demande_adhesion(){
		$groupe = new Groupe_model();
		$groupe->id_groupe=$this->uri->segment(3, null);
		$groupe->id_utilisateur=1;
		
		if($groupe->validate_adhesion()){
			$data['texte']="Modification réussie" ;
		}
		else {
			$data['texte']="Modification échouée";
		}
		$this->load->view('groupe_view', $data);
	}
	
	/*
	* Demande de partenariat d'un groupe ($idgroupedemandeur2) vers un autre groupe ($groupe->id_groupe --> récupéré dans l'url)
	*/
	public function demande_partenariat(){
		//are_Partenaire($idgroupe1,$idgroupe2)>0)
		$groupe = new Groupe_model();
		$groupe->id_groupe=$this->uri->segment(3, null);
		$idgroupedemandeur=2;
		if($groupe->are_Partenaire($idgroupedemandeur)==0){
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
		$this->load->view('groupe_view', $data);
	}	
	
	/*
	* Administrateur valide une demande d'adhésion
	*/
	public function valider_demande_partenariat(){
		$groupe = new Groupe_model();
		// voir comment le récupérer
		$groupe->id_groupe=2;
		//id du demandé (récupérer avec la variable session)
		$idgroupedemande=3;
		
		if($groupe->validate_partenariat($idgroupedemande)){
			$data['texte']="Demande validée" ;
		}
		else {
			$data['texte']="Validation échouée";
		}
		$this->load->view('groupe_view', $data);
	}
	
	
}