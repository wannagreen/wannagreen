<?php
class Tag_model extends CI_Model {
	var $id_tag = null;
	var $libelle = null;
	var $date_creation=null;
	var $date_maj = null;
	
	/*
	* Cr�ation/ajout d'un tag
	*/
	function create_tag(){
		$this->date_creation = date_db_format();
		if($this->libelle == null) {
			$result = FALSE;
		}
		else {
			$result = $this->db->insert('tag', $this);
			$this->id_tag = $this->db->insert_id();
		}
		return $result;
	}
	
	
	/*
	* R�cup�re les tags d'un utilisateur
	*/
	function get_tag_utilisateur($id_utilisateur){
		$this->db->select('t.id_tag, t.libelle');
		$this->db->from('tag t');
		$this->db->join('tag_utilisateur tu', 'tu.id_tag = t.id_tag');
		$this->db->where('tu.id_utilisateur', $id_utilisateur);
		$query=$this->db->get();
		
		return $query;
	}
	
	/*
	* R�cup�re les tags d'une publication
	*/
	function get_tag_publication($id_publication){
		$this->db->select('t.id_tag, t.libelle');
		$this->db->from('tag t');
		$this->db->join('tag_publication tp', 'tp.id_tag = t.id_tag');
		$this->db->where('tp.id_publication', $id_publication);
		$query=$this->db->get();
		return $query->result();
	}

	/*
	* R�cup�re les tags pour une publication et un utilisateur
	*/
	function get_tag_publication_utilisateur($id_publication,$id_utilisateur){
		$this->db->select('t.id_tag, t.libelle');
		$this->db->from('tag t');
		$this->db->join('tag_publication tp', 'tp.id_tag = t.id_tag');
		$this->db->where('tp.id_publication', $id_publication);
		$this->db->where('tp.id_utilisateur', $id_utilisateur);
		$query=$this->db->get();
		return $query;
	}
	
	/*
	* Supprime les tags d'une publication
	*/
	function delete_tag_publication(){
		$this->db->where('id_publication', $this->id_publication);
		return $this->db->delete('tag_publication');
	}
	
	function get_id_tag(){
		$query=$this->db->get_where('tag', array('libelle' => $this->libelle));
		return $query->row();
	}
	
	/*
	* V�rifie si un tag existe dans la table tag
	*/
	function tag_exist(){
		$query=$this->db->get_where('tag', array('libelle' => $this->libelle));
		//$query=$this->db->get();
		return $query->num_rows();
	}
	
	/*
	* V�rifie si utilisateur poss�de un tag donn�
	*/
	function user_possede_tag($id_utilisateur,$id_tag){
		$this->db->select('id_tag,id_utilisateur');
		$this->db->from('tag_utilisateur');
		$this->db->where('id_tag', $id_tag);
		$this->db->where('id_utilisateur', $id_utilisateur);
		$query=$this->db->get();
		return $query->num_rows();
	}
	
	/*
	* V�rifie si un groupe poss�de un tag donn�
	*/
	function groupe_possede_tag($id_groupe,$id_tag){
		$this->db->select('id_tag,id_groupe');
		$this->db->from('tag_groupe');
		$this->db->where('id_tag', $id_tag);
		$this->db->where('id_groupe', $id_groupe);
		$query=$this->db->get();
		return $query->num_rows();
	}
	
	/*
	* V�rifie si une publication poss�de un tag donn�
	*/
	function publication_possede_tag($id_publication,$id_tag){
		$this->db->select('id_publication,id_tag');
		$this->db->from('tag_publication');
		$this->db->where('id_tag', $id_tag);
		$this->db->where('id_publication', $id_publication);
		$query=$this->db->get();
		return $query->num_rows();
	}
	
	/*
	* R�cup�re les tags d'un groupe 
	*/
	function get_tag_groupe($id_groupe){
		$this->db->select('t.id_tag, t.libelle');
		$this->db->from('tag t');
		$this->db->join('tag_groupe tg', 'tg.id_tag = t.id_tag');
		$this->db->where('tg.id_groupe', $id_groupe);
		$query=$this->db->get();
		return $query;
	}
	
	/*
	* R�cup�re les tags d'un groupe (seulement ceux ajout�s par l'admin du groupe)
	*/
	function get_tag_groupe_admin($id_groupe){
		$this->db->select('t.id_tag, t.libelle');
		$this->db->from('tag t');
		$this->db->join('tag_groupe tg', 'tg.id_tag = t.id_tag');
		$this->db->join('groupe g', 'tg.id_utilisateur = g.id_utilisateur');	
		$this->db->where('tg.id_groupe', $id_groupe);
		$this->db->where('g.id_groupe', $id_groupe);
		$query=$this->db->get();
		return $query;
	}
	
	//Ajouter des tags � une publication
	function add_tag_publication($id_tag,$id_publication,$id_utilisateur){
		$date_creation = date_db_format();
		$data = array(
		   'id_tag' =>$id_tag,
		   'id_publication' => $id_publication,
		   'date_creation' => $date_creation,
		   'id_utilisateur' => $id_utilisateur);
		return $this->db->insert('tag_publication', $data); 	
		
	}
	
	// Ajouter des tags � un groupe
	function add_tag_groupe($id_tag,$id_groupe,$id_utilisateur){
		$date_creation = date_db_format();
		$data = array(
		   'id_tag' =>$id_tag,
		   'id_groupe' => $id_groupe,
		   'date_creation' => $date_creation,
		   'id_utilisateur' => $id_utilisateur);
		return $this->db->insert('tag_groupe', $data); 	
	}
	
	// Ajouter des tags � un utilisateur
	function add_tag_user($id_tag,$id_utilisateur){
		$date_creation = date_db_format();
		$data = array(
		   'id_tag' =>$id_tag,
		   'id_utilisateur' => $id_utilisateur,
		   'date_creation' => $date_creation,
		   'id_utilisateur' => $id_utilisateur);
		return $this->db->insert('tag_utilisateur', $data); 	
	}
	
	//Supprimer un tag d'un groupe
	function supprimer_tag_groupe($id_tag,$id_groupe){
		$this->db->where('id_tag', $id_tag);
		$this->db->where('id_groupe', $id_groupe);
		return $this->db->delete('tag_groupe');
	}
}
?>
