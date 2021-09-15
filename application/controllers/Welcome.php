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

	public function generateSigranure()
	{
		// $data = "1652";
		// $secretKey = "0060R004";
		// 0000024374856


		$username = "dian_rsj";
		$password = "Dian0301@6";
		// $kdAplikasi = "095";
		$user = hash_hmac('sha256', $username, $password, true);
		$Authorization = base64_encode($user);


		$data = "5231";
		$secretKey = "7rA70A8D69";
		// Computes the timestamp
		//$data = $this->uri->segment(3);
		//$secretKey = $this->uri->segment(4);
		// echo $secretKey;
		date_default_timezone_set('UTC');
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		// Computes the signature by hashing the salt with the secret key asthe key
		$signature = hash_hmac('sha256', $data, $secretKey, true);
		// base64 encode
		$encodedSignature = base64_encode($signature);

		$headers = array($data, $tStamp, $encodedSignature, $Authorization);
		//$hasil = json_encode($headers);
		// echo json_encode($headers);

        echo json_encode($headers);
        // echo json_encode($headers[$data]);
	}

	public function bpjs()
	{
		$data['title'] = 'BPJS API';

		$this->load->view('templates/header', $data);
		$this->load->view('api/api_bpjs');
		$this->load->view('templates/footer');
	}
}
