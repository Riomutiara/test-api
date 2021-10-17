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

	public function cekKartuPesertaBPJS()
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

		echo $content;
	}

	public function cekKartuPesertaNIK()
	{
		$data = "5231";
		$secretKey = "7rA70A8D69";
		// $data = "23396";
		// $secretKey = "2uV7D5A77E";

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

		curl_setopt($ch, CURLOPT_URL, "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Peserta/nik/" . $_POST['input'] . "/tglSEP/" . $tgl);
		// curl_setopt($ch, CURLOPT_URL, "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest-1.1/Peserta/nik/" . $_POST['input'] . "/tglSEP/" . $tgl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		echo $content;
	}

	public function cariRujukanDenganNoBPJS()
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

		curl_setopt($ch, CURLOPT_URL, "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/Peserta/" . $_POST['input']);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		echo $content;
	}

	public function cariRujukanDenganNoRujukan()
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

		curl_setopt($ch, CURLOPT_URL, "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/" . $_POST['input']);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		echo $content;
	}

	public function monitoringDataKunjungan()
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
		// "Monitoring/Kunjungan/Tanggal/" & TanggalSEP & "/JnsPelayanan/" & JenisPel KMN------------------------------------
		curl_setopt($ch, CURLOPT_URL, "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Monitoring/Kunjungan/Tanggal/" . $_POST['tanggal'] . "/JnsPelayanan/" . $_POST['jenis']);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		echo $content;
	}

	public function monitoringHistoryPelayanan()
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

		curl_setopt($ch, CURLOPT_URL, "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/monitoring/HistoriPelayanan/noKartu/" . $_POST['no_kartu'] . "/tglAwal/" . $_POST['tgl_mulai'] . "/tglAkhir/" . $_POST['tgl_akhir']);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		echo $content;
	}
}
