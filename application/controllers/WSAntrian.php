<?php

class WSAntrian extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Web Service Antrian 2';

        $this->load->view('templates/header', $data);
		$this->load->view('api/wsantrian');
		$this->load->view('templates/footer');
    }

    


}
