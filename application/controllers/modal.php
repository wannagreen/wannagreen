<?php
class Modal extends CI_Controller {
	
	public function index()
	{
		
	}
	
	public function load($filename = null)
	{
		if($filename != null)
			$this->load->view('modal/'.$filename);
		else
			$this->load->view('notice', array('notice_type'=>'error', 'notice'=>'Impossible de charger le contenu'));
	}
	
}
