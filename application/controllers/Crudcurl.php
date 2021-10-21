<?php

class Crudcurl extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Vclaim';

        $this->load->view('templates/header', $data);
        $this->load->view('api/crudcurl');
        $this->load->view('templates/footer');
    }

    public function tambahBarang()
    {
        // $url = 'http://192.168.10.5:5000/products';


        // $ch = curl_init($url);

        if ($_POST['action'] == 'POST') {
            $url = 'http://192.168.10.5:5000/products';
            $data = array(
                "product_name" => $this->input->post('nama_barang'),
                "product_price" => $this->input->post('harga_barang'),
            );

            $postdata = json_encode($data); //ubah data array ke JSON

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $result = curl_exec($ch);
            curl_close($ch);
            echo 'Data Berhasil Disimpan';
        }

        if ($_POST['action'] == 'PUT') {
            $url = 'http://192.168.10.5:5000/products/' . $this->input->post('id_barang');
            $data = array(
                "product_name" => $this->input->post('nama_barang'),
                "product_price" => $this->input->post('harga_barang'),
            );

            $postdata = json_encode($data); //ubah data array ke JSON

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $result = curl_exec($ch);
            curl_close($ch);
            echo 'Data Berhasil Diubah';
        }

        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // $result = curl_exec($ch);
        // curl_close($ch);
        // print_r($result);
    }

    function getAllBarang()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://192.168.10.5:5000/products");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        return json_decode($content, true);
    }

    function fetchSingleBarang()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://192.168.10.5:5000/products/" . $_POST['id']);
        $content = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        return json_decode($content, true);
    }

    public function tabelBarang()
    {
        $fetch_data = $this->getAllBarang();
        $data = array();
        $no = 0;
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $sub_array[] = $no;
            $sub_array[] = $row['product_name'];
            $sub_array[] = $row['product_price'];
            $sub_array[] = '<a href="#" class="update_product" id="' . $row['id'] . '" data-toggle="tooltip" title="Akses"><span class="badge text-primary">UPDATE</span></a>
            <a href="#" class="delete_product" id="' . $row['id'] . '" data-toggle="modal" tooltip="Akses"><span class="badge text-danger">DELETE</span></a>
                            ';
            $data[] = $sub_array;
        }

        $output = array(
            "recordsTotal"        => count($fetch_data),
            "recordsFiltered"     => count($fetch_data),
            "data"                => $data
        );
        echo json_encode($output);
    }

    public function hapusBarang()
    {
        $url = 'http://192.168.10.5:5000/products/' . $_POST['id'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $result;
    }

    public function ubahData()
    {
        // $data = array("a" => $a);
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        // $response = curl_exec($ch);

        // if (!$response) {
        //     return false;
        // }
    }
}
