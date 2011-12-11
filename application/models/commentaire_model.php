<?php
class Commentaire_model extends CI_Model {

	var $id_commentaire = null;
	var $contenu = null;
	var $date_creation = null;
	var $date_maj = null;
	var $id_utilisateur = null;
	var $id_publication =null;
	
	function __construct()
	{
		parent::__construct();
	}
		
	function create ()
	{
	
		if ($this->contenu == null || $this->id_utilisateur==null || $this->id_publication ==null)
			$result = FALSE;
		else{
			$this->date_creation = date_db_format();
			$result = $this->db->insert('commentaire', $this) ? $this->db->insert_id() : false;
			if($result) {
				$this->id_commentaire = $this->db->insert_id();
				$result = $this->id_commentaire;
			}
		}
		return $result;
	}
	
	function get_all ()
	{
		$query = $this->db->get('commentaire');
		return $query->result();
	}
	
	// function get_by_id()
	// {
		// $query = $this->db->get_where('commentaire', array('id_commentaire' => $this->id_commentaire));
		
		// $this->contenu = $query->row()->contenu;
		// $this->date_creation = $query->row()->date_creation;
		// $this->date_maj = $query->row()->date_maj;
		// $this->id_utilisateur = $query->row()->id_utilisateur;
		// $this->id_publication = $query->row()->id_publication;
	// }
	function get_by_id($id_commentaire)
	{
		$this->db->select('c.id_commentaire,c.contenu,c.date_creation,c.date_maj,c.id_utilisateur,c.id_publication,u.prenom,u.nom');
		$this->db->from('commentaire c');
        $this->db->join('utilisateur u','u.id_utilisateur=c.id_utilisateur');
        $this->db->where('c.id_commentaire',$id_commentaire);
        $this->db->limit(1);
        return $this->db->get()->row();
	}
	
	function get_by_id_publication($id_publication)
	{
		$this->db->select('c.id_commentaire,c.contenu,c.date_creation,c.date_maj,c.id_utilisateur,c.id_publication,u.prenom,u.nom');
		$this->db->from('commentaire c');
        $this->db->join('utilisateur u','u.id_utilisateur=c.id_utilisateur');
        $this->db->where('c.id_publication',$id_publication);
        $query = $this->db->get();
        return $query->result();
	}
	
	function get_by_id_publication2($id_publication)
	{
		$query = $this->db->get_where('commentaire', array('id_publication' => $id_publication));
		return $query->result();
	}
	
	function get_by_id_utilisateur($id_utilisateur)
	{
		$query = $this->db->get_where('commentaire', array('id_utilisateur' => $id_utilisateur));
		return $query->result();
	}
	
	function delete()
	{
		$this->db->delete('commentaire', array('id_publication_info' => $this->id_publication_info));
	}
}	