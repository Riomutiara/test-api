<?php

class Antrian extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Antrian Offline';

        $this->load->view('templates/header', $data);
		$this->load->view('api/antrian');
		$this->load->view('templates/footer');
    }


}
