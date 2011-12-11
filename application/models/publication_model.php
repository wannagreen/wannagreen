<?php
class Publication_model extends CI_Model {

	var $id_publication = null;
	var $type = null;
	var $prive = null;
	var $date_creation = null;
	var $date_maj = null;
	var $id_utilisateur = null;
	var $info = array();
	var $commentaires = array();
	
	function __construct()
	{
		parent::__construct();
	}
		
	function create ()
	{
		$this->date_creation = date_db_format();
		if ($this->type == null || $this->id_utilisateur==null)
			$result = FALSE;
		else{
			$result = $this->db->insert('publication', $this);
			$this->id_publication = $this->db->insert_id();
			}			
		return $result;	
	}
	
	function get_all ()
	{
		$query = $this->db->get('publication');
		return $query->result();
	}
	
	function get_publication_by_id()
	{
		$query = $this->db->get_where('publication', array('id_publication' => $this->id_publication));
		$this->type = $query->row()->type;
		$this->prive = $query->row()->prive;
		$this->date_creation = $query->row()->date_creation;
		$this->date_maj = $query->row()->date_maj;
		$this->id_utilisateur =$query->row()->id_utilisateur;
		}
	
	function get_publication_by_id_groupe ($id_groupe)
	{
		$this->db->select('p.id_publication,p.type,p.prive,p.date_creation,p.date_maj,p.id_utilisateur,u.prenom,u.nom');
		$this->db->from('publication p');
		$this->db->join('publication_groupe g','g.id_publication=p.id_publication');
		$this->db->where('g.id_groupe',$id_groupe);
        $this->db->join('utilisateur u','u.id_utilisateur=p.id_utilisateur');
		$this->db->order_by('p.date_creation','DESC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_publication_publique_by_id_groupe ($id_groupe)
	{
		$this->db->select('p.id_publication,p.type,p.prive,p.date_creation,p.date_maj,p.id_utilisateur,u.prenom,u.nom');
		$this->db->from('publication p');
		$this->db->join('publication_groupe g','g.id_publication=p.id_publication');
		$this->db->where('g.id_groupe',$id_groupe);
        $this->db->join('utilisateur u','u.id_utilisateur=p.id_utilisateur');
		$this->db->where('p.prive',0);
		$this->db->order_by('p.date_creation','DESC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_publication_by_id_utilisateur ($id_utilisateur)
	{
		$this->db->select('p.id_publication,p.type,p.prive,p.date_creation,p.date_maj,p.id_utilisateur,u.nom,u.prenom');
		$this->db->from('publication p');
		$this->db->where('p.id_utilisateur',$id_utilisateur);
		$this->db->join('utilisateur u','u.id_utilisateur=p.id_utilisateur');
		$this->db->order_by('p.date_creation','DESC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_publication_by_id_tag($id_tag)
	{
		$this->db->select('p.id_publication,p.type,p.prive,p.date_creation,p.date_maj,p.id_utilisateur');
		$this->db->from('publication p');
		$this->db->join('tag_publication t','t.id_publication=p.id_publication');
		$this->db->where('t.id_tag',$id_tag);
		$this->db->order_by('p.date_creation','DESC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_publication_recente($limit=10)
	{
		$this->db->select('p.id_publication,p.type,p.prive,p.date_creation,p.date_maj,p.id_utilisateur,u.prenom,u.nom');
		$this->db->from('publication p');
		$this->db->join('utilisateur u','u.id_utilisateur=p.id_utilisateur');
		$this->db->order_by('date_creation','DESC');
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}
	
	function modifier_publi ()
	{
		$this->date_maj = date_db_format();
		if($this->type == null || $this->id_utilisateur==null)
			return FALSE;
		else {
			$fields = array (
				'type' => $this->type,
				'prive' => $this->prive,
				'date_creation' => $this->date_creation,
				'date_maj' => $this->date_maj,
				'id_utilisateur' => $this->id_utilisateur
				);
			$this->db->where('id_publication', $this->id_publication);
			return $this->db->update('publication', $fields);
		}
	}
	
	/*
	* Supprime une publication
	*/
	function delete_publication(){
		$this->db->where('id_publication', $this->id_publication);
		return $this->db->delete('publication');
	}
}
