<?php

class Vclaim extends CI_Controller
{
	public function index()
	{
		$data['title'] = 'Vclaim';

		$this->load->view('templates/header', $data);
		$this->load->view('api/vclaim');
		$this->load->view('templates/footer');
	}

	public function cekKartuPeserta()
	{
		$data = "5231";
		$secretKey = "7rA70A8D69";

		date_default_timezone_set('UTC');
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

		$signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
		$encodedSignature = base64_encode($signature);

		$ch = curl_init();
		$headers = [
			'X-cons-id: ' . $data . '',
			'X-timestamp: ' . $tStamp . '',
			'X-signature: ' . $encodedSignature . '',
			'Content-Type: application/json',
		];
		$tgl = date('Y-m-d');

		curl_setopt($ch, CURLOPT_URL, "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Peserta/nokartu/" . $_POST['input'] . "/tglSEP/" . $tgl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_error($ch);

		curl_close($ch);

	

		echo json_encode($content);

		// return $resultarr;

		// if ($resultarr['metaData']['code'] == 200) {

		// 	if ($resultarr['response']['peserta']['statusPeserta']['kode'] == 9) {
		// 		$response['kode'] = $resultarr['response']['peserta']['statusPeserta']['kode'];
		// 		$response['keterangan'] = "<div class='alert alert-danger' role='alert'><strong>CODE : " . $resultarr['metaData']['code'] . "</strong> - Kartu <strong>" . $resultarr['response']['peserta']['statusPeserta']['keterangan'] . "</strong> Anda tidak bisa mendaftar dengan <strong>BPJS</strong> Silahkan mendaftar dengan cara bayar <strong>UMUM</strong></div>";
		// 		header('Content-type: application/json');
		// 		echo json_encode($response);
		// 	} else {
		// 		$response['kode'] = $resultarr['response']['peserta']['statusPeserta']['kode'];
		// 		$response['keterangan'] = "<div class='alert alert-success' role='alert'><strong>CODE : " . $resultarr['metaData']['code'] . "</strong> - Kartu <strong>" . $resultarr['response']['peserta']['statusPeserta']['keterangan'] . "</strong> Dapat di gunakan</div>";
		// 		header('Content-type: application/json');
		// 		echo json_encode($response);
		// 	}
		// } else {
		// 	$response['kode'] = $resultarr['metaData']['code'];
		// 	$response['keterangan'] = "<div class='alert alert-danger' role='alert'><strong>CODE : " . $resultarr['metaData']['code'] . "</strong> - " . $resultarr['metaData']['message'] . "</div>";
		// 	header('Content-type: application/json');
		// 	echo json_encode($response);
		// }

		// exit();
	}
}
