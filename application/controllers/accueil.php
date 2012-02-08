<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accueil extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('form');
		
		$this->data = array();
		
		$this->default_search = 'ecologie';
		
		/* Gestion des groupes de l'utilisateur connecté */
		if($this->session->userdata('is_connected') === TRUE) {
			$this->data['user_connected'] = TRUE;
			$id_utilisateur = $this->session->userdata('id_utilisateur');
			$this->data = array_merge($this->data, $this->Groupe_model->listes_groupes($id_utilisateur));
		}
		else
			$this->data['user_connected'] = FALSE;
	}
	
	/**
	 * Point d'entrée du controller principal
	 */
	public function index()
	{
	redirect(base_url()."publication/accueil_recente");
		/*$this->data['twttr_feed'] = $this->get_twitter_feed($this->default_search);
		$this->data['module'] = 'accueil';
		$this->data['liste'] = 'publications_recentes';
		$this->load->view('template', $this->data);*/
	}
	
	public function twitter_search()
	{
		$this->data['twttr_feed'] = $this->get_twitter_feed($this->input->post('twttr_search'));
		$this->data['module'] = 'accueil';
		$this->load->view('template', $this->data);
	}
	
	private function get_twitter_feed($search = null)
	{
		if($search != null && $search != '') {
			$stream_url = 'http://search.twitter.com/search.atom?q='.urlencode($search).'&lang=fr&rpp=10';
			$this->data['search_word'] = $search;
		}
		else {
			$stream_url = 'http://search.twitter.com/search.atom?q='.urlencode($this->default_search).'&lang=fr&rpp=10';
			$this->data['search_word'] = $this->default_search;
		}
		
		$twttr_xml = @file_get_contents($stream_url);
		return $twttr_xml !== FALSE ? new SimpleXMLElement($twttr_xml) : FALSE;
	}
}

/* End of file accueil.php */
/* Location: ./application/controllers/accueil.php */
