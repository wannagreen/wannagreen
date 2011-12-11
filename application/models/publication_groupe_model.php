<?php
class Publication_groupe_model extends CI_Model {

	var $id_publication = null;
	var $id_groupe = null;
	var $date_creation = null;
	var $date_maj = null;
						
	function __construct()
		{
			parent::__construct();
		}
	
	function create()
	{
		$this->date_creation = date_db_format();
		if ($this->id_publication == null || $this->id_groupe == null)
			$result = FALSE;
		else{
			$result = $this->db->insert('publication_groupe', $this);
			}
		return $result;
	}	
	
	function get_all ()
	{
		$query = $this->db->get('publication_groupe');
		return $query->result();
	}
	
	function get_by_id_publication($id_publication)
	{
		// $query = $this->db->get_where('publication_groupe', array('id_publication' => $id_publication));
		// return $query->result();
		
		$this->db->select('p.id_publication, p.id_groupe, p.date_creation, g.nom');
		$this->db->from('publication_groupe p');
        $this->db->join('groupe g','p.id_groupe=g.id_groupe');
        $this->db->where('p.id_publication',$id_publication);
        $query = $this->db->get();
        return $query->result();
	}
	
	/*
	* Supprime les lignes dans la table pour une publication
	*/
	function delete_publication_groupe(){
		$this->db->where('id_publication', $this->id_publication);
		return $this->db->delete('publication_groupe');
	}
	
	// function delete ()
	// {
		// $this->db->delete('publication_groupe', array('id_groupe' => $this->id_groupe ,'id_publication' => $this->id_publication));
	// }
	
	
	
}
