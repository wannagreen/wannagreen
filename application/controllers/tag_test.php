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
	* Vérifie si un utilisateur possède un tag donné
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
		//recupérér l'identifiant de l'utilisateur
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
		//recupérér l'identifiant de l'utilisateur
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
		//recupérér l'identifiant de l'utilisateur
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