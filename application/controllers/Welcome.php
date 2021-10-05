<?php

class Welcome extends CI_Controller
{
	public function index()
	{
		$data['title'] = 'OMDB API';

		$this->load->view('templates/header', $data);
		$this->load->view('api/welcome_message');
		$this->load->view('templates/footer');
	}
}
