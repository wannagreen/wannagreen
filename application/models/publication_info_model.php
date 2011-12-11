<?php
class Publication_info_model extends CI_Model {

	var $id_publication_info = null;
	var $libelle = null;
	var $contenu = null;
	var $id_publication = null;
	
	function __construct()
		{
			parent::__construct();
		}
		
	function create ()
	{
		if ($this->libelle == null || $this->contenu == null || $this->id_publication ==null)
			$result = FALSE;
		else{
			$result = $this->db->insert('publication_info', $this);
			$this->id_publication_info = $this->db->insert_id();
		}
		return $result;
	}
	
	function get_all ()
	{
		$query = $this->db->get('publication_info');
		return $query->result();
	}
	
	function get_by_id()
	{
		$query = $this->db->get_where('publication_info', array('id_publication_info' => $this->id_publication_info));
		
		$this->libelle = $query->row()->libelle;
		$this->contenu = $query->row()->contenu;
		$this->id_publication = $query->row()->id_publication;
	}
	
	function get_by_id_publication($id_publication)
	{
		$query = $this->db->get_where('publication_info', array('id_publication' => $id_publication));
		return $query->result();
	}
	
	function modifier_publi_info ()
	{
		if($this->id_publication == null || $this->id_publication_info == null || $this ->libelle ==null || $this->contenu==null )
			return FALSE;
		else {
			$fields = array (
				'libelle' => $this->libelle,
				'contenu' => $this->contenu,
				'id_publication' => $this->id_publication
			);
			$this->db->where('id_publication info', $this->id_publication_info);
			return $this->db->update('publication_info', $fields);
		}
	}
	
	/*
	* Supprime les lignes dans la table pour une publication
	*/
	function delete_publication_info(){
		$this->db->where('id_publication', $this->id_publication);
		return $this->db->delete('publication_info');
	}
	
	// function delete ()
	// {
		// $this->db->delete('publication_info', array('id_publication_info' => $this->id_publication_info));
	// }
}	