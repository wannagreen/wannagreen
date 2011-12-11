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
	
	/*
	* Creation/ajout d'un tag
	*/
	function creer_tag(){
		$tag = new Tag_model();
		$tag->libelle='co2';
		if($tag->tag_exist()==0){
			if($tag->create_tag()){
				$data['texte']="Tag ajout�";
			}
			else {
				$data['texte']="le tag n'a pas �t� ajout� - erreur.";
			}	
		}
		else {
			$data['texte']="Ce tag existe d�j�";
		}	
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Affiche les tags d'un utilisateur
	*/
	public function tag_utilisateur(){
		$tag = new Tag_model();
		// r�cup�rer la variable session ?
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
		// r�cup�rer la variable session ?
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
		// r�cup�rer la variable session ?
		$id_publication=1;
		// r�cup�rer identifiant de l'utilisateur en session
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
	* V�rifie si un tag existe dans la table tag
	*/
	public function tag_existe(){
		$tag = new Tag_model();
		// r�cup�rer texte saisi ?
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
	* V�rifie si un utilisateur poss�de un tag donn�
	*/
	public function utilisateur_possede_tag(){
		$tag = new Tag_model();
		$id_utilisateur=3;
		$id_tag=1;
		if($tag->user_possede_tag($id_utilisateur,$id_tag)>0){
			$data['texte']="Cet utilisateur poss�de ce tag";
		}
		else {
			$data['texte']="Cet utilisateur ne poss�de pas ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* V�rifie si un groupe poss�de un tag donn�
	*/
	public function groupe_possede_tag(){
		$tag = new Tag_model();
		$id_groupe=3;
		$id_tag=1;
		if($tag->groupe_possede_tag($id_groupe,$id_tag)>0){
			$data['texte']="Ce groupe poss�de ce tag";
		}
		else {
			$data['texte']="Ce groupe ne poss�de pas ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* V�rifie si une publication poss�de un tag donn�
	*/
	public function publication_possede_tag(){
		$tag = new Tag_model();
		$id_publication=1;
		$id_tag=1;
		if($tag->publication_possede_tag($id_publication,$id_tag)>0){
			$data['texte']="Ce groupe poss�de ce tag";
		}
		else {
			$data['texte']="Ce groupe ne poss�de pas ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	/*
	* Affiche les tags d'un groupe
	*/
	public function tag_groupe(){
		$tag = new Tag_model();
		// r�cup�rer la variable session ?
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
		// r�cup�rer la variable session ?
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
	
	//Ajouter des tags � une publication
	public function ajout_tag_publication(){
		$tag = new Tag_model();
		$id_publication=2;
		$id_tag=3;
		//recup�r�r l'identifiant de l'utilisateur
		$id_utilisateur=1;
		if($tag->publication_possede_tag($id_publication,$id_tag)==0) {
			if($tag->add_tag_publication($id_tag,$id_publication,$id_utilisateur)){
				$data['texte']="le tag a �t� ajout� � la publication";
			}
			else {
				$data['texte']="erreur - le tag n'a pu �tre ajout�";
			}
		}
		else {
			$data['texte']="la publication poss�de d�j� ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	//Ajouter des tags � un groupe
	public function ajout_tag_groupe(){
		$tag = new Tag_model();
		$id_groupe=1;
		$id_tag=7;
		//recup�r�r l'identifiant de l'utilisateur
		$id_utilisateur=1;
		if($tag->groupe_possede_tag($id_groupe,$id_tag)==0) {
			if($tag->add_tag_groupe($id_tag,$id_groupe,$id_utilisateur)){
				//ajouter les tags de l'utilisateur dans la table tag_utilisateur
				$data['texte']="le tag a �t� ajout� au groupe";
			}
			else {
				$data['texte']="erreur - le tag n'a pu �tre ajout�";
			}
		}
		else {
			$data['texte']="le groupe poss�de d�j� ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	//Ajouter des tags � un utilisateur
	public function ajout_tag_utilisateur(){
		$tag = new Tag_model();
		$id_utilisateur=3;
		$id_tag=7;
		//recup�r�r l'identifiant de l'utilisateur
		$id_utilisateur=1;
		if($tag->user_possede_tag($id_utilisateur,$id_tag)==0) {
			if($tag->add_tag_user($id_tag,$id_utilisateur,$id_utilisateur)){
				$data['texte']="le tag a �t� ajout� � l'utilisateur";
			}
			else {
				$data['texte']="erreur - le tag n'a pu �tre ajout�";
			}
		}
		else {
			$data['texte']="l'utilisateur poss�de d�j� ce tag";
		}
		$this->load->view('tag_view', $data);
	}
	
	
}
?>