<?php

class Laboratorium_model extends CI_Model
{
    // REGISTRASI RAJAL
    public function get_mr($id, $layanan)
    {
        $this->db->join('simrsj_master.pasien', 'pasien.id_pasien = pasien_kunjungan.id_pasien');
        $this->db->where('pasien_kunjungan.tanggal_registrasi', $id);
        $this->db->where('pasien_kunjungan.jenis_layanan', $layanan);
        $query = $this->db->get('pasien_kunjungan');
        return $query->result();
    }

    public function fetch_single_pasien($id, $tanggal)
    {
        $this->db->join('simrsj_master.pasien', 'pasien.id_pasien = pasien_kunjungan.id_pasien', 'LEFT');
        $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = pasien_kunjungan.id_dokter', 'LEFT');
        $this->db->join('simrsj_master.ruangan', 'ruangan.id_ruangan = pasien_kunjungan.id_poli', 'LEFT');
        $this->db->where('pasien_kunjungan.id_kunjungan', $id);
        $this->db->where('pasien_kunjungan.tanggal_registrasi', $tanggal);
        $query = $this->db->get('pasien_kunjungan');
        return $query->result();
    }

    public function fetch_single_order_lab($id)
    {
        $this->db->join('simrsj_master.pasien', 'pasien.id_pasien = laboratorium_order.id_pasien', 'LEFT');
        $this->db->where('laboratorium_order.id_order_labor', $id);
        $query = $this->db->get('laboratorium_order');
        return $query->result();
    }

    public function fetch_all_pemeriksaan($id)
    {
        $this->db->from('laboratorium_pemeriksaan');
        // $this->db->join('laboratorium_grup_tindakan', 'laboratorium_grup_tindakan.id_tarif = laboratorium_pemeriksaan.id_tindakan_labor');
        $this->db->join('simrsj_master.tarif', 'tarif.id_tarif = laboratorium_pemeriksaan.id_tindakan_labor', 'LEFT');
        // $this->db->group_by('laboratorium_pemeriksaan.id_tindakan_labor');
        $this->db->where('laboratorium_pemeriksaan.id_order_labor', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_sub_tindakan($id_order, $id)
    {
        $this->db->from('laboratorium_pemeriksaan');
        $this->db->join('laboratorium_grup_tindakan', 'laboratorium_grup_tindakan.id_tarif = laboratorium_pemeriksaan.id_tindakan_labor');
        $this->db->join('simrsj_master.tarif', 'tarif.id_tarif = laboratorium_grup_tindakan.id_sub_tindakan', 'LEFT');
        // $this->db->group_by('laboratorium_pemeriksaan.id_tindakan_labor');
        $this->db->where('laboratorium_grup_tindakan.id_tarif', $id);
        $this->db->where('laboratorium_pemeriksaan.id_order_labor', $id_order);
        $query = $this->db->get();
        return $query->result();
    }

    public function tambah_order_lab($data)
    {
        $this->db->insert('laboratorium_order', $data);
    }

    public function tambah_pemeriksaan($data)
    {
        $this->db->insert('laboratorium_pemeriksaan', $data);
    }

    public function input_hasil_pemeriksaan($data)
    {
        $this->db->insert('laboratorium_hasil', $data);
    }

    public function hitung_total_biaya($id)
    {
        $this->db->select_sum('tarif.tarif_layanan');
        $this->db->from('laboratorium_pemeriksaan');
        $this->db->where('laboratorium_pemeriksaan.id_order_labor', $id);
        $this->db->join('simrsj_master.tarif', 'tarif.id_tarif = laboratorium_pemeriksaan.id_tindakan_labor', 'LEFT');
        $query = $this->db->get();
        return $query->result();
    }

    public function batalkan_pemeriksaan($id)
    {
        $this->db->where('laboratorium_pemeriksaan.id_pemeriksaan_labor', $id);
        $this->db->delete('laboratorium_pemeriksaan');
    }

    public function get_tindakan()
    {
        $this->db->where('tarif.status_tarif', 1);
        $this->db->where('tarif.nama_layanan', 3); // 3 adalah tindakan laboratorium di tarif keuangan 
        $query = $this->db->get('simrsj_master.tarif');
        return $query->result();
    }

    // DATATABLE ORDER LABOR
    var $order_column = array(null, 'no_registrasi', 'nomor_lab', 'createdAt_order_labor', 'nama_pasien', 'nama_pegawai', 'status_order', null);

    public function make_query()
    {
        $this->db->from('laboratorium_order');
        $this->db->join('pasien_kunjungan', 'pasien_kunjungan.id_kunjungan = laboratorium_order.id_pendaftaran', 'LEFT');
        $this->db->join('simrsj_master.pasien', 'pasien.id_pasien = laboratorium_order.id_pasien', 'LEFT');
        $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = laboratorium_order.dokter', 'LEFT');

        if (isset($_POST["search"]["value"])) {
            $this->db->like('pasien_kunjungan.no_registrasi', $_POST["search"]["value"]);
            $this->db->or_like('pasien.nama_pasien', $_POST["search"]["value"]);
            $this->db->or_like('laboratorium_order. createdAt_order_labor', $_POST["search"]["value"]);
            $this->db->or_like('laboratorium_order. nomor_lab', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('laboratorium_order.id_order_labor', 'DESC');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data()
    {
        $this->db->select("*");
        $this->db->from('laboratorium_order');
        return $this->db->count_all_results();
    }

    // DATATABLE PEMERIKSAAN LABOR    
    public function make_query_pemeriksaan()
    {
        if ($this->input->post('idOrderLabor')) {
            $this->db->where('laboratorium_pemeriksaan.id_order_labor', $this->input->post('idOrderLabor'));
        }

        $this->db->from('laboratorium_pemeriksaan');
        $this->db->join('simrsj_master.tarif', 'tarif.id_tarif = laboratorium_pemeriksaan.id_tindakan_labor', 'LEFT');

        $this->db->order_by('laboratorium_pemeriksaan.id_pemeriksaan_labor', 'DESC');
    }

    public function make_datatables_pemeriksaan()
    {
        $this->make_query_pemeriksaan();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    // DATATABLE INPUT HASIL PEMERIKSAAN LABOR    
    public function make_query_hasil_pemeriksaan()
    {
        if ($this->input->post('idOrderLabor2')) {
            $this->db->where('laboratorium_hasil.id_order_labor', $this->input->post('idOrderLabor2'));
        }

        $this->db->from('laboratorium_hasil');
        $this->db->join('simrsj_master.tarif', 'tarif.id_tarif = laboratorium_hasil.id_tindakan_labor', 'LEFT');
        $this->db->join('laboratorium_parameter', 'laboratorium_parameter.id_parameter_labor = laboratorium_hasil.id_parameter_labor', 'LEFT');
        $this->db->join('simrsj_master.referensi_satuan_labor', 'referensi_satuan_labor.id_satuan_labor = laboratorium_parameter.id_satuan_parameter', 'LEFT');
    }

    public function make_datatables_hasil_pemeriksaan()
    {
        $this->make_query_hasil_pemeriksaan();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }







    // PARAMETER
    public function fetch_single_tindakan_lab($id)
    {
        $this->db->where('tarif.id_tarif', $id);
        // $this->db->join('laboratorium_parameter', 'laboratorium_parameter.id_tindakan_labor = tarif.id_tarif', 'LEFT');
        // $this->db->join('simrsj_master.referensi_grup_labor', 'referensi_grup_labor.id_grup_labor = laboratorium_parameter.id_grup_tindakan', 'LEFT');
        $query = $this->db->get('simrsj_master.tarif');
        return $query->result();
    }

    public function fetch_single_parameter_lab($id)
    {
        $this->db->where('laboratorium_parameter.id_parameter_labor', $id);
        $query = $this->db->get('laboratorium_parameter');
        return $query->result();
    }

    public function tambah_parameter($data)
    {
        $this->db->insert('laboratorium_parameter', $data);
    }

    public function tambah_sub_tindakan($data)
    {
        $this->db->insert('laboratorium_grup_tindakan', $data);
    }
    
    public function delete_sub_tindakan($id)
    {
        $this->db->where('id_grup_tindakan', $id);
        $this->db->delete('laboratorium_grup_tindakan');
    }

    public function edit_kategori_tarif($id, $data)
    {
        $this->db->where('id_tarif', $id);
        $this->db->update('simrsj_master.tarif', $data);
    }

    public function edit_parameter($id, $data)
    {
        $this->db->where('laboratorium_parameter.id_parameter_labor', $id);
        $this->db->update('laboratorium_parameter', $data);
    }

    // DATATABLE TINDAKAN
    var $order_column_tindakan = array(null, 'uraian', 'jasa_sarana', 'jasa_pelayanan', 'tarif_layanan', null);

    public function make_query_tindakan()
    {
        $this->db->select('*');
        $this->db->from('simrsj_master.tarif');
        $this->db->join('simrsj_master.referensi_kategori_tindakan_labor', 'referensi_kategori_tindakan_labor.id_kategori_tindakan_labor = tarif.kategori_tarif', 'LEFT');
        $this->db->where('tarif.nama_layanan', 3);

        if (isset($_POST["search"]["value"])) {
            $this->db->like('tarif.uraian', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_tindakan[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tarif.id_tarif', 'DESC');
        }
    }

    public function make_datatables_tindakan()
    {
        $this->make_query_tindakan();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_tindakan()
    {
        $this->make_query_tindakan();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_tindakan()
    {
        $this->db->select("*");
        $this->db->from('simrsj_master.tarif');
        return $this->db->count_all_results();
    }


    // DATATABLE PARAMETER
    public function make_query_parameter()
    {
        if ($this->input->post('idTindakan')) {
            $this->db->where('laboratorium_parameter.id_tindakan_labor', $this->input->post('idTindakan'));
        }

        $this->db->select('*');
        $this->db->from('laboratorium_parameter');
        $this->db->join('simrsj_master.referensi_satuan_labor', 'referensi_satuan_labor.id_satuan_labor = laboratorium_parameter.id_satuan_parameter', 'LEFT');

        $this->db->order_by('id_parameter_labor', 'DESC');
    }

    public function make_datatables_parameter()
    {
        $this->make_query_parameter();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }


    // DATATABLE GRUP TINDAKAN
    public function make_query_grup_tindakan()
    {
        if ($this->input->post('idTindakan3')) {
            $this->db->where('laboratorium_grup_tindakan.id_tarif', $this->input->post('idTindakan3'));
        }

        $this->db->select('*');
        $this->db->from('laboratorium_grup_tindakan');
        $this->db->join('simrsj_master.tarif', 'tarif.id_tarif = laboratorium_grup_tindakan.id_sub_tindakan', 'LEFT');

        $this->db->order_by('id_grup_tindakan', 'DESC');
    }

    public function make_datatables_grup_tindakan()
    {
        $this->make_query_grup_tindakan();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
    // END DATATABLE
}





































<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laboratorium extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Laboratorium_model');
    }

    // DASHBOARD LAB
    public function index()
    {
        $data['title'] = 'index';
        $data['user'] = $this->db->get_where('user', ['username' =>
        $this->session->userdata('username')])->row_array();

        $data['content'] = '';
        $page = 'laboratorium/index';
        echo modules::run('template/loadview', $data, $page);
    }






    // RAWAT JALAN
    public function rajal()
    {
        $data['title'] = 'Rawat Jalan';
        $data['user'] = $this->db->get_where('user', ['username' =>
        $this->session->userdata('username')])->row_array();

        $data['content'] = '';
        $page = 'laboratorium/registrasi_rajal';
        echo modules::run('template/loadview', $data, $page);
    }

    public function getMR()
    {
        $data = $this->Laboratorium_model->get_mr($_POST['tanggal'], $_POST['layanan']);
        echo json_encode($data);
    }

    public function getDokter()
    {
        $data = $this->db->order_by('nama_pegawai')->get_where('simrsj_master.pegawai', ['profesi' => 1])->result();
        echo json_encode($data);
    }

    public function getTindakan()
    {
        $data = $this->Laboratorium_model->get_tindakan();
        echo json_encode($data);
    }

    public function fetchSinglePasien()
    {
        $output = [];
        $data = $this->Laboratorium_model->fetch_single_pasien($_POST['id_kunjungan'], $_POST['tanggal']);
        foreach ($data as $row) {
            $output['nama_pasien'] = $row->nama_pasien;
            $output['no_registrasi'] = $row->no_registrasi;
            $output['nomr'] = $row->no_mr;
            $output['poli_tujuan'] = $row->nama_ruangan;
            $output['dokter_poli'] = $row->id_pegawai;
            $output['id_pendaftaran'] = $row->id_kunjungan;
            $output['id_pasien'] = $row->id_pasien;
        }
        echo json_encode($output);
    }

    public function tambahOrderLabor()
    {
        $last_row = $this->db->select('new_record')->order_by('id_order_labor', "desc")->limit(1)->get_where('laboratorium_order')->result();

        if ($last_row) {
            foreach ($last_row as $row) {
                $output = sprintf("%07d", $row->new_record + 1);
            }
        } else {
            $output = sprintf("%07d", +1);
        }
        $new_record = $output;
        $nomor_lab = 'L-' . $output;

        $data = array(
            'new_record'            => $new_record,
            'nomor_lab'             => $nomor_lab,
            'id_pasien'             => $this->input->post('id_pasien'),
            'id_pendaftaran'        => $this->input->post('id_pendaftaran'),
            'dokter'                => $this->input->post('dokter_poli'),
            'rujukan_luar'          => $this->input->post('rujukan_luar'),
            'dokter_perujuk'        => $this->input->post('dokter_perujuk'),
            'keterangan_rujukan'    => $this->input->post('keterangan'),
            'tgl_reg_labor'         => $this->input->post('tgl_reg_labor'),
            'status_order'          => 1,
        );

        $this->Laboratorium_model->tambah_order_lab($data);
        echo 'Order Labor berhasil disimpan!';
    }

    public function tambahPemeriksaan()
    {
        $data = array(
            'id_order_labor'        => $this->input->post('id_order_labor'),
            'id_tindakan_labor'     => $this->input->post('pemeriksaan')
        );

        $this->Laboratorium_model->tambah_pemeriksaan($data);
        echo 'Pemeriksaan Labor berhasil disimpan!';
    }

    public function inputHasilPemeriksaan()
    {
        $data = array(
            'id_order_labor'            => $this->input->post('id_order_labor2'),
            'id_tindakan_labor'         => $this->input->post('tampilkan_pemeriksaan'),
            'id_parameter_labor'        => $this->input->post('tampilkan_parameter'),
        );

        $this->Laboratorium_model->input_hasil_pemeriksaan($data);
        echo 'Input Pemeriksaan berhasil disimpan!';
    }

    public function hitungTotalBiaya()
    {
        $data = $this->Laboratorium_model->hitung_total_biaya($_POST['id']);
        foreach ($data as $row) {
            $output['tarif_layanan'] = 'Rp. ' . number_format($row->tarif_layanan) . ' ';
        }
        echo json_encode($output);
    }

    public function batalkanPemeriksaan()
    {
        $this->Laboratorium_model->batalkan_pemeriksaan($_POST['id_pemeriksaan']);
        echo 'Pemeriksaan telah dibatalkan';
    }

    public function tabelOrderLab()
    {
        $fetch_data = $this->Laboratorium_model->make_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $sub_array[] = $no;
            $sub_array[] = $row->no_registrasi;
            $sub_array[] = $row->nomor_lab;
            $sub_array[] = $row->createdAt_order_labor;
            $sub_array[] = $row->nama_pasien;
            if ($row->rujukan_luar == 1) {
                $sub_array[] = "<div> $row->dokter_perujuk   <br> <i>Keterangan :  $row->keterangan_rujukan</i> </div>";
            } else {
                $sub_array[] = $row->gelar_depan . ' ' . $row->nama_pegawai . ' ' . $row->gelar_belakang;
            }
            if ($row->status_order == 1) {
                $sub_array[] = '<span class="badge badge-warning">diproses</span>';
            } else {
                $sub_array[] = '<span class="badge badge-success">selesai</span>';
            }
            $sub_array[] = '<button type="button" class="btn btn-info btn-flat btn-sm input_pemeriksaan" id="' . $row->id_order_labor . '" data-toggle="modal" data-target="#staticBackdrop">Input Pemeriksaan</button><button type="button" class="btn btn-success btn-flat btn-sm input_hasil_pemeriksaan" id="' . $row->id_order_labor . '" data-toggle="modal" data-target="#staticBackdrop">Input Hasil</      button>                            
                            ';
            $data[] = $sub_array;
        }

        $output = array(
            "draw"                => intval($_POST['draw']),
            "recordsTotal"        => $this->Laboratorium_model->get_all_data(),
            "recordsFiltered"     => $this->Laboratorium_model->get_filtered_data(),
            "data"                => $data
        );
        echo json_encode($output);
    }

    public function tabelPemeriksaan()
    {
        $fetch_data = $this->Laboratorium_model->make_datatables_pemeriksaan();
        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $sub_array[] = $no;
            $sub_array[] = $row->uraian;
            $sub_array[] = number_format($row->tarif_layanan);
            $sub_array[] = '<a href="#" class="batalkan_pemeriksaan text-danger" id="' . $row->id_pemeriksaan_labor . '" data-toggle="modal" data-target="#staticBackdrop"><i class="fas fa-trash"></i></a>';
            $data[] = $sub_array;
        }

        $output = array(
            "draw"                => intval($_POST['draw']),
            "data"                => $data
        );
        echo json_encode($output);
    }

    public function tabelInputHasil()
    {
        $fetch_data = $this->Laboratorium_model->make_datatables_hasil_pemeriksaan();
        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $sub_array[] = $no;
            $sub_array[] = $row->uraian;
            $sub_array[] = $row->nama_parameter;
            $sub_array[] = $row->nilai_rujukan;
            $sub_array[] = $row->nama_satuan_labor;
            $sub_array[] = '<input type="text" class="form-control rounded-0" id="hasil_pemeriksaan" name="hasil_pemeriksaan">';
            $sub_array[] = 'Aksi';
            $data[] = $sub_array;
        }

        $output = array(
            "draw"                => intval($_POST['draw']),
            "data"                => $data
        );
        echo json_encode($output);
    }

    public function fetchSingleOrderLab()
    {
        $output = [];
        $data = $this->Laboratorium_model->fetch_single_order_lab($_POST['id']);
        foreach ($data as $row) {
            if ($row->jenis_kelamin == 1) {
                $output['nomor_lab'] = $row->nomor_lab . ' - ' . $row->nama_pasien . ' (L)';
            } else {
                $output['nomor_lab'] = $row->nomor_lab . ' - ' . $row->nama_pasien . ' (P)';
            }
            $output['tanggal_lahir'] = $row->tanggal_lahir;
        }
        echo json_encode($output);
    }

    public function fetchAllPemeriksaanLab()
    {
        $data = $this->Laboratorium_model->fetch_all_pemeriksaan($_POST['id_order_labor']);
        echo json_encode($data);
    }

    public function getSubTindakan()
    {
        $data = $this->Laboratorium_model->get_sub_tindakan($_POST['id_order_labor'], $_POST['id']);
        echo json_encode($data);
    }

    public function fetchAllParameter()
    {
        $data = $this->db->select('*')
            ->order_by('id_parameter_labor', "desc")
            ->join('simrsj_master.referensi_satuan_labor', 'referensi_satuan_labor.id_satuan_labor = laboratorium_parameter.id_satuan_parameter')
            ->get_where('laboratorium_parameter', ['id_tindakan_labor' =>  $_POST['id_tindakan_labor']])
            ->result();
        echo json_encode($data);
    }

    public function hitungUmur()
    {
        $birthDate = new DateTime($_POST['tgl']);
        $today = new DateTime("today");
        if ($birthDate > $today) {
            exit("0 tahun 0 bulan 0 hari");
        }
        $y = $today->diff($birthDate)->y;
        $m = $today->diff($birthDate)->m;
        $d = $today->diff($birthDate)->d;

        $output['y'] = $y;
        $output['m'] = $m;
        $output['d'] = $d;

        echo json_encode($output);
    }






    // PARAMETER
    public function parameter()
    {
        $data['title'] = 'Parameter';
        $data['user'] = $this->db->get_where('user', ['username' =>
        $this->session->userdata('username')])->row_array();

        $data['content'] = '';
        $page = 'laboratorium/parameter_labor';
        echo modules::run('template/loadview', $data, $page);
    }

    public function tabelTindakanLabor()
    {
        $fetch_data = $this->Laboratorium_model->make_datatables_tindakan();
        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $sub_array[] = $no;
            $sub_array[] = $row->uraian;
            $sub_array[] = number_format($row->jasa_sarana);
            $sub_array[] = number_format($row->jasa_pelayanan);
            $sub_array[] = number_format($row->tarif_layanan);
            if ($row->jenis_tarif == 1) {
                $sub_array[] = '<span class="badge badge-info">SINGLE</span>';
            } else {
                $sub_array[] = '<span class="badge badge-warning">KELOMPOK</span>';
            }
            $sub_array[] = $row->nama_kategori_tindakan_labor;
            if ($row->jenis_tarif == 1) {
                $sub_array[] = '<button type="button" class="btn btn-info btn-sm btn-flat edit_tarif" id="' . $row->id_tarif . '" data-toggle="modal" data-target="#staticBackdrop">Edit</button><button type="button" class="btn btn-success btn-sm btn-flat parameter" id="' . $row->id_tarif . '" data-toggle="modal" data-target="#staticBackdrop">Parameter</button>';
            } else {
                $sub_array[] = '<button type="button" class="btn btn-info btn-sm btn-flat edit_tarif" id="' . $row->id_tarif . '" data-toggle="modal" data-target="#staticBackdrop">Edit</button><button type="button" class="btn btn-warning btn-sm btn-flat sub_tindakan" id="' . $row->id_tarif . '" data-toggle="modal" data-target="#staticBackdrop">Sub Tindakan</button>';
            }

            $data[] = $sub_array;
        }

        $output = array(
            "draw"                => intval($_POST['draw']),
            "recordsTotal"        => $this->Laboratorium_model->get_all_tindakan(),
            "recordsFiltered"     => $this->Laboratorium_model->get_filtered_tindakan(),
            "data"                => $data
        );
        echo json_encode($output);
    }

    public function tabelParameter()
    {
        $fetch_data = $this->Laboratorium_model->make_datatables_parameter();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row->nama_parameter;
            $sub_array[] = $row->nilai_rujukan;
            $sub_array[] = $row->nama_satuan_labor;
            if ($row->status_parameter_labor == 1) {
                $sub_array[] = '<span class="badge badge-success">Aktif</span>';
            } else {
                $sub_array[] = '<span class="badge badge-danger">Non Aktif</span>';
            }

            $sub_array[] = '<a href="#" class="edit_parameter" id="' . $row->id_parameter_labor . '" data-toggle="modal" data-target="#staticBackdrop"><i class="fas fa-edit mr-2"></i></a>';
            $data[] = $sub_array;
        }

        $output = array(
            "draw"                => intval($_POST['draw']),
            "data"                => $data
        );
        echo json_encode($output);
    }

    public function tabelSubTindakan()
    {
        $fetch_data = $this->Laboratorium_model->make_datatables_grup_tindakan();
        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $sub_array[] = $no;
            $sub_array[] = $row->uraian;
            $sub_array[] = '<a href="#" class="text-danger delete_sub_tindakan" id="' . $row->id_grup_tindakan . '" data-toggle="modal" data-target="#staticBackdrop"><i class="fas fa-trash mr-2"></i></a>';
            $data[] = $sub_array;
        }

        $output = array(
            "draw"                => intval($_POST['draw']),
            "data"                => $data
        );
        echo json_encode($output);
    }

    public function fetchSingleTarif()
    {
        $output = [];
        $data = $this->Laboratorium_model->fetch_single_tindakan_lab($_POST['id']);
        foreach ($data as $row) {
            $output['nama_tindakan'] = $row->uraian;
            $output['kategori_tarif'] = $row->kategori_tarif;
            $output['jenis_tindakan'] = $row->jenis_tarif;
        }
        echo json_encode($output);
    }

    public function fetchSingleParameter()
    {
        $output = [];
        $data = $this->Laboratorium_model->fetch_single_parameter_lab($_POST['id']);
        foreach ($data as $row) {
            $output['nama_parameter'] = $row->nama_parameter;
            $output['nilai_rujukan'] = $row->nilai_rujukan;
            $output['satuan_parameter'] = $row->id_satuan_parameter;
            $output['status_parameter'] = $row->status_parameter_labor;
        }
        echo json_encode($output);
    }

    public function getAllSatuan()
    {
        $data = $this->db->get_where('simrsj_master.referensi_satuan_labor', ['status_satuan_labor' => 1])->result();
        echo json_encode($data);
    }

    public function tambahParameter()
    {
        if ($_POST['action'] == 'tambah') {
            $data = array(
                'id_tindakan_labor'         => $this->input->post('id_tindakan'),
                'nama_parameter'            => $this->input->post('nama_parameter'),
                'nilai_rujukan'             => $this->input->post('nilai_rujukan'),
                'id_satuan_parameter'       => $this->input->post('satuan_parameter'),
                'status_parameter_labor	'   => $this->input->post('status_parameter')
            );

            $this->Laboratorium_model->tambah_parameter($data);
            echo 'Data Parameter berhasil disimpan!';
        }

        if ($_POST['action'] == 'edit') {
            $data = array(
                'nama_parameter'            => $this->input->post('nama_parameter'),
                'nilai_rujukan'             => $this->input->post('nilai_rujukan'),
                'id_satuan_parameter'       => $this->input->post('satuan_parameter'),
                'status_parameter_labor	'   => $this->input->post('status_parameter')
            );

            $this->Laboratorium_model->edit_parameter($this->input->post('id_parameter_labor'), $data);
            echo 'Data Parameter berhasil diedit!';
        }
    }

    public function editKategoriTarif()
    {
        $data = array(
            'kategori_tarif'  => $this->input->post('nama_kategori'),
            'jenis_tarif'     => $this->input->post('jenis_tindakan'),
        );

        $this->Laboratorium_model->edit_kategori_tarif($this->input->post('id_tindakan2'), $data);
        echo 'Kategori Tarif berhasil diedit!';
    }

    public function tambahSubTindakan()
    {
        $data = array(
            'id_tarif'          => $this->input->post('id_tindakan3'),
            'id_sub_tindakan'   => $this->input->post('sub_tindakan'),
        );

        $this->Laboratorium_model->tambah_sub_tindakan($data);
        echo 'Sub Tindakan berhasil disimpan!';
    }

    public function getKategoriTindakan()
    {
        $data = $this->db->order_by('nama_kategori_tindakan_labor')->get_where('simrsj_master.referensi_kategori_tindakan_labor', ['status_kategori_tindakan_labor' => 1])->result();
        echo json_encode($data);
    }

    public function deleteSubTindakan()
    {
        $this->Laboratorium_model->delete_sub_tindakan($_POST['id']);
        echo 'Sub Tindakan berhasil dihapus';
    }
}



































<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-6">
                    <h1>Registrasi Labor <?= $title ?></h1>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-4">
                    <div class="form-group row">
                        <label for="tanggal" class="col-sm-6 col-form-label">Tanggal Pendaftaran</label>
                        <div class="col-sm-6">
                            <input type='input' class="form-control" id="tanggal" name="tanggal" autocomplete="off" value="<?= date('Y-m-d'); ?>">
                        </div>
                        <small><span class="text-danger" id="error_tanggal"></span></small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group row">
                        <label for="no_registrasi" class="col-sm-3 col-form-label">No. Registrasi</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control selectpicker" id="no_registrasi" name="no_registrasi" data-live-search="true"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-right">
                        <button type="button" class="btn btn-success btn-flat btn_registrasi"><i class="fas fa-plus-circle mr-2"></i>Registrasi Labor</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Order Labor Rawat Jalan</h3>
                        </div>
                        <div class="card-body">
                            <table id="tabel_order_lab" class="table table-bordered table-hover table-sm display">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>No. Registrasi</th>
                                        <th>No. Lab</th>
                                        <th>Tanggal Order</th>
                                        <th>Nama Pasien</th>
                                        <th>Dokter</th>
                                        <th>Status Order</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Info Pasien -->
<div class="modal fade" id="modalRegistrasi" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Informasi Pasien</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="POST" id="form_order_lab">
                    <input type="hidden" id="id_pasien" name="id_pasien">
                    <input type="hidden" id="id_pendaftaran" name="id_pendaftaran">
                    <input type="hidden" id="tgl_reg_labor" name="tgl_reg_labor" value="<?= date('Y-m-d') ?>">
                    <div class="form-group row">
                        <label for="nama_pasien" class="col-sm-3 col-form-label">Nama Pasien</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-0" id="nama_pasien" name="nama_pasien" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nomr" class="col-sm-3 col-form-label">No. MR</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-0" id="nomr" name="nomr" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="no_registrasi2" class="col-sm-3 col-form-label">No. Registrasi</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-0" id="no_registrasi2" name="no_registrasi2" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="poli_tujuan" class="col-sm-3 col-form-label">Poli Tujuan</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-0" id="poli_tujuan" name="poli_tujuan" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dokter_poli" class="col-sm-3 col-form-label">Dokter</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control" id="dokter_poli" name="dokter_poli"></select>
                            </div>
                            <small><span class="text-danger" id="error_dokter_poli"></span></small>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label for="rujukan_luar" class="col-sm-3 col-form-label">Rujukan Luar</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control" id="rujukan_luar" name="rujukan_luar">
                                    <option selected value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="dokter_perujuk"></div>
                    <div class="modal-footer justify-content-right">
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn-flat" id="action">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Input Pemeriksaan -->
<div class="modal fade" id="modalPemeriksaan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="post" id="tambah_pemeriksaan">
                    <input type="hidden" id="id_order_labor" name="id_order_labor">
                    <div class="form-group row">
                        <label for="nomor_lab" class="col-sm-3 col-form-label">Nomor Labor</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-0" id="nomor_lab" name="nomor_lab" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="usia" class="col-sm-3 col-form-label">Usia Pasien</label>
                        <div class="col-sm-9 mt-1">
                            <label for="">
                                <span class="text-info">
                                    <span id="tahun2">0</span> tahun
                                    <span id="bulan2">0</span> bulan
                                    <span id="hari2">0</span> hari
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pemeriksaan" class="col-sm-3 col-form-label">Pemeriksaan</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control rounded-0 selectpicker" id="pemeriksaan" name="pemeriksaan" data-live-search="true"></select>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-success btn-flat" id="btn_cek_no_rujukan"><i class="fas fa-plus"></i></button>
                                </span>
                            </div>
                            <small><span class="text-danger" id="error_pemeriksaan"></span></small>
                        </div>
                    </div>
                </form>
                <hr>
                <table id="tabel_pemeriksaan" class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pemeriksaaan</th>
                            <th>Tarif</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                <hr>
                <div class="row">
                    <div class="col-6 text-left mt-1">
                        <label>TOTAL BIAYA :</label>
                    </div>
                    <div class="col-6 text-right">
                        <h4 id="total_biaya" class="text-primary"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Input Hasil -->
<div class="modal fade" id="modalInputHasil" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="post" id="input_hasil_pemeriksaan">
                    <input type="text" id="id_order_labor2" name="id_order_labor2">
                    <div class="row">
                        <div class="col-8">
                            <div>
                                <label>Nomor Labor : <span id="nomor_lab2"></span></label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <label>Usia :
                                    <span class="text-info">
                                        <span id="tahun">0</span> tahun
                                        <span id="bulan">0</span> bulan
                                        <span id="hari">0</span> hari
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="list_pemeriksaan">
                        <div class="list_sub_tindakan"></div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="tampilkan_pemeriksaan" class="col-sm-3 col-form-label">Pilih Pemeriksaan</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control rounded-0" id="tampilkan_pemeriksaan" name="tampilkan_pemeriksaan" data-live-search="true"></select>
                            </div>
                            <small><span class="text-danger" id="error_tampilkan_pemeriksaan"></span></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tampilkan_parameter" class="col-sm-3 col-form-label">Pilih Parameter</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control rounded-0" id="tampilkan_parameter" name="tampilkan_parameter" data-live-search="true"></select>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-success btn-flat" id="btn_input_hasil"><i class="fas fa-plus"></i></button>
                                </span>
                            </div>
                            <small><span class="text-danger" id="error_tampilkan_parameter"></span></small>
                        </div>
                    </div> -->
                </form>
                <hr>
                <table id="tabel_input_hasil" class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pemeriksaaan</th>
                            <th>Parameter</th>
                            <th>Nilai Rujukan</th>
                            <th>Satuan</th>
                            <th>Hasil</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                <hr>
                <div class="row">
                    <div class="col-6 text-left mt-1">
                        <label>TOTAL BIAYA :</label>
                    </div>
                    <div class="col-6 text-right">
                        <h4 id="total_biaya" class="text-primary"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tanggal').datetimepicker({
            timepicker: false,
            datepicker: true,
            scrollInput: false,
            theme: 'success',
            format: 'Y-m-d',
            maxDate: '+2y',
        });

        //  Datatable Order Labor
        dataTable = $('#tabel_order_lab').DataTable({
            "serverSide": true,
            "processing": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url(); ?>laboratorium/tabelOrderLab",
                "type": "POST",
            },
            columnDefs: [{
                orderable: !1,
                targets: [0, 7]
            }],
            autoWidth: !1
        });

        //  Datatable Pemeriksaan Labor
        dataTable2 = $('#tabel_pemeriksaan').DataTable({
            "serverSide": true,
            "processing": true,
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url(); ?>laboratorium/tabelPemeriksaan",
                "type": "POST",
                "data": function(data) {
                    data.idOrderLabor = $('#id_order_labor').val();
                },
            },
            columnDefs: [{
                orderable: !1,
                targets: [0]
            }],
            autoWidth: !1
        });

        // Menampilkan Pasien berdasarkan Tanggal Kunjungan
        var tanggal = $('#tanggal').val();
        $.ajax({
            url: "<?php echo base_url(); ?>laboratorium/getMR",
            method: "POST",
            data: {
                tanggal: tanggal,
                layanan: 2
            },
            dataType: 'JSON',
            success: function(data) {
                // console.log(data);
                var html = '';
                var i;
                html += '<option selected>Cari</option>';
                for (i = 0; i < data.length; i++) {
                    if (data[i].jenis_layanan == 2) {
                        html += '<option value="' + data[i].id_kunjungan + '">' + data[i].no_registrasi + ' - ' + data[i].nama_pasien + '</option>';
                    }
                }
                $('#no_registrasi').html(html);
                $('.selectpicker').selectpicker('refresh');
            }
        });

        $.ajax({
            url: "<?php echo base_url(); ?>laboratorium/getTindakan",
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                var html = '';
                var i;
                html += '<option selected>Cari</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value="' + data[i].id_tarif + '">' + data[i].uraian + '  Rp.' + data[i].tarif_layanan + '</option>';
                }
                $('#pemeriksaan').html(html);
                $('.selectpicker').selectpicker('refresh');
            }
        });

        // GET Dokter
        $.ajax({
            url: "<?php echo base_url(); ?>laboratorium/getDokter",
            method: "POST",
            dataType: 'JSON',
            success: function(data) {
                var html = '';
                var i;
                html += '<option selected>Pilih Dokter</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value="' + data[i].id_pegawai + '">' + data[i].gelar_depan + ' ' + data[i].nama_pegawai + ' ' + data[i].gelar_belakang + '</option>';
                }
                $('#dokter_poli').html(html);
            }
        });


        // Menampilkan Pasien berdasarkan Tanggal Kunjungan
        $(document).on('change', '#tanggal', function() {
            var tanggal = $('#tanggal').val();

            $.ajax({
                url: "<?php echo base_url(); ?>laboratorium/getMR",
                method: "POST",
                data: {
                    tanggal: tanggal,
                    layanan: 2
                },
                dataType: 'JSON',
                success: function(data) {
                    var html = '';
                    var i;
                    html += '<option selected>Cari</option>';
                    for (i = 0; i < data.length; i++) {
                        if (data[i].jenis_layanan == 2) {
                            html += '<option value="' + data[i].id_kunjungan + '">' + data[i].no_registrasi + ' - ' + data[i].nama_pasien + '</option>';
                        }
                    }
                    $('#no_registrasi').html(html);
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        });

        // Klik Registrasi
        $('.btn_registrasi').on('click', function() {
            var id_kunjungan = $('#no_registrasi').val();
            var tanggal = $('#tanggal').val();

            if (id_kunjungan == 'Cari') {
                toastr["info"]('Pilih nama Pasien terlebih dahulu!');
            } else {
                $('#nama_pasien').val('');
                $('#rujukan_luar').val(0);
                $('#poli_tujuan').val('');
                $('#dokter_poli').val('Pilih Dokter');
                $('.dokter_perujuk').html('');
                $('#error_dokter_poli').text('');

                $.ajax({
                    url: "<?php echo base_url(); ?>laboratorium/fetchSinglePasien",
                    method: "POST",
                    data: {
                        id_kunjungan: id_kunjungan,
                        tanggal: tanggal
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data.dokter_poli);
                        $('#modalRegistrasi').modal('show');
                        $('.modal-title').text('Registrasi Labor');
                        $('#nama_pasien').val(data.nama_pasien);
                        $('#nomr').val(data.nomr);
                        $('#no_registrasi2').val(data.no_registrasi);
                        $('#poli_tujuan').val(data.poli_tujuan);
                        $('#dokter_poli').val(data.dokter_poli);
                        $('#id_pasien').val(data.id_pasien);
                        $('#id_pendaftaran').val(data.id_pendaftaran);
                    }
                });
            }
        });

        $(document).on('submit', '#form_order_lab', function(event) {
            event.preventDefault()

            var dokter_poli = $('#dokter_poli').val();
            var error_dokter_poli = $('#error_dokter_poli').val();

            if ($('#dokter_poli').val() == 'Pilih Dokter') {
                error_dokter_poli = 'Pilih Dokter';
                $('#error_dokter_poli').text(error_dokter_poli);
                dokter_poli = '';
            } else {
                error_dokter_poli = '';
                $('#error_dokter_poli').text(error_dokter_poli);
                dokter_poli = $('#dokter_poli').val();
            }

            if (error_dokter_poli != '') {
                toastr["error"]("Data belum lengkap");
            } else {
                $.ajax({
                    url: '<?php echo base_url(); ?>laboratorium/tambahOrderLabor',
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#form_order_lab')[0].reset();
                        $('#id_pasien').val('');
                        $('#id_pendaftaran').val('');
                        $('#modalRegistrasi').modal('hide');
                        toastr["success"](data);
                        dataTable.ajax.reload();
                    }
                });
            }
        });

        $('#rujukan_luar').on('change', function() {
            var id = $('#rujukan_luar').val();
            $('.dokter_perujuk').html('');

            if (id == 1) {
                $('.dokter_perujuk').append(`
                <div class="form-group row">
                    <label for="dokter_perujuk" class="col-sm-3 col-form-label">Dokter</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-0" id="dokter_perujuk" name="dokter_perujuk" placeholder="Nama Dokter">
                        </div>
                    </div>
                    <small><span class="text-danger" id="error_rujukan_luar"></span></small>
                </div>
                <div class="form-group row">
                    <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-0" id="keterangan" name="keterangan" placeholder="Keterangan Rujukan / RS - Asal Rujukan">
                        </div>
                    </div>
                    <small><span class="text-danger" id="error_keterangan"></span></small>
                </div>
                `);
            } else {
                $('.dokter_perujuk').html('');
            }
        });


        // KLIK INPUT PEMERIKSAAN
        $(document).on('click', '.input_pemeriksaan', function() {
            var id = $(this).attr('id');
            $('#pemeriksaan').val('Cari');
            $('.selectpicker').selectpicker('refresh');

            $.ajax({
                url: "<?php echo base_url(); ?>laboratorium/fetchSingleOrderLab",
                method: "POST",
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#modalPemeriksaan').modal('show');
                    $('.modal-title').text('Input Pemeriksaan Labor');
                    $('#id_order_labor').val(id);
                    $('#nomor_lab').val(data.nomor_lab);
                    dataTable2.ajax.reload()

                    var tgl = data.tanggal_lahir;
                    $.ajax({
                        url: '<?php echo base_url(); ?>laboratorium/hitungUmur',
                        method: 'POST',
                        data: {
                            tgl: tgl
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            console.log(data);
                            $('#tahun2').text(data.y);
                            $('#bulan2').text(data.m);
                            $('#hari2').text(data.d);
                        }
                    });
                }
            });

            $.ajax({
                url: "<?php echo base_url(); ?>laboratorium/hitungTotalBiaya",
                method: "POST",
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#total_biaya').text(data.tarif_layanan);
                }
            });
        });

        $(document).on('submit', '#tambah_pemeriksaan', function(event) {
            event.preventDefault()

            var pemeriksaan = $('#pemeriksaan').val();
            var error_pemeriksaan = $('#error_pemeriksaan').val();

            if ($('#pemeriksaan').val() == 'Cari') {
                error_pemeriksaan = 'Pilih Pemeriksaan';
                $('#error_pemeriksaan').text(error_pemeriksaan);
                pemeriksaan = '';
            } else {
                error_pemeriksaan = '';
                $('#error_pemeriksaan').text(error_pemeriksaan);
                pemeriksaan = $('#pemeriksaan').val();
            }

            if (error_pemeriksaan != '') {
                toastr["error"]("Data belum lengkap");
            } else {
                $.ajax({
                    url: '<?php echo base_url(); ?>laboratorium/tambahPemeriksaan',
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#pemeriksaan').val('Cari');
                        $('.selectpicker').selectpicker('refresh');
                        dataTable2.ajax.reload();
                        toastr["success"](data);
                        var id = $('#id_order_labor').val();
                        $.ajax({
                            url: "<?php echo base_url(); ?>laboratorium/hitungTotalBiaya",
                            method: "POST",
                            data: {
                                id: id
                            },
                            dataType: 'JSON',
                            success: function(data) {
                                $('#total_biaya').text(data.tarif_layanan);
                            }
                        });
                    }
                });
            }
        });

        $(document).on('click', '.batalkan_pemeriksaan', function() {
            var id_pemeriksaan = $(this).attr('id');

            if (confirm('Anda yakin batalkan pemeriksaan ini?')) {
                $.ajax({
                    url: "<?php echo base_url(); ?>laboratorium/batalkanPemeriksaan",
                    method: "POST",
                    data: {
                        id_pemeriksaan: id_pemeriksaan
                    },
                    // dataType: 'JSON',
                    success: function(data) {
                        toastr["success"](data);
                        dataTable2.ajax.reload();
                        var id = $('#id_order_labor').val();
                        $.ajax({
                            url: "<?php echo base_url(); ?>laboratorium/hitungTotalBiaya",
                            method: "POST",
                            data: {
                                id: id
                            },
                            dataType: 'JSON',
                            success: function(data) {
                                $('#total_biaya').text(data.tarif_layanan);
                            }
                        });
                    }
                });
            }
        });


        // KLIK INPUT HASIL
        $(document).on('click', '.input_hasil_pemeriksaan', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: "<?php echo base_url(); ?>laboratorium/fetchSingleOrderLab",
                method: "POST",
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    // console.log(data);
                    $('#modalInputHasil').modal('show');
                    $('.modal-title').text('Input Hasil Pemeriksaan Labor');
                    $('#id_order_labor2').val(id);
                    $('#nomor_lab2').text(data.nomor_lab);
                    dataTable3.ajax.reload();

                    var tgl = data.tanggal_lahir
                    $.ajax({
                        url: '<?php echo base_url(); ?>laboratorium/hitungUmur',
                        method: 'POST',
                        data: {
                            tgl: tgl
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            $('#tahun').text(data.y);
                            $('#bulan').text(data.m);
                            $('#hari').text(data.d);
                        }
                    });

                    var id_order_labor = id;
                    $.ajax({
                        url: '<?php echo base_url(); ?>laboratorium/fetchAllPemeriksaanLab',
                        method: 'POST',
                        data: {
                            id_order_labor: id_order_labor
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            console.log(data);
                            $.each(data, function(i, data) {
                                $('.list_pemeriksaan').append(`
                                    <h4>` + data.uraian + `</h4>
                                    <input type="text" value="` + data.id_tindakan_labor + `">
                                    <button type="button" class="btn btn-warning btn-sm btn-flat input_parameter" id="` + data.id_tindakan_labor + `" data-toggle="modal" data-target="#staticBackdrop">Input Parameter</button>                               
                                                                
                                `);

                            });
                        }
                    });
                }
            });
        });

        $('.list_pemeriksaan').on('click', '.input_parameter', function() {
            var id = $(this).attr('id');
            var id_order_labor = $('#id_order_labor2').val();

            // console.log(id_order_labor);
            // console.log(id);
            $.ajax({
                url: "<?= base_url(); ?>laboratorium/getSubTindakan",
                method: 'POST',
                data: {
                    id_order_labor: id_order_labor,
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    if (data != '') {
                        $.each(data, function(i, data) {
                            $('.list_sub_tindakan').append(`
                                <p>` + data.uraian + `</p>                                                                      
                        `);
                        });
                    } else {

                        $('.list_sub_tindakan').html('');

                    }

                }
            });
        });

        $('#tampilkan_pemeriksaan').on('change', function() {
            var id_tindakan_labor = $('#tampilkan_pemeriksaan').val();

            $.ajax({
                url: '<?php echo base_url(); ?>laboratorium/fetchAllParameter',
                method: 'POST',
                data: {
                    id_tindakan_labor: id_tindakan_labor
                },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    var html = '';
                    var i;
                    html += '<option selected>Pilih Parameter</option>';
                    for (i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id_parameter_labor + '">' + data[i].nama_parameter + ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; | Nilai Rujukan : ' + data[i].nilai_rujukan + ' ' + data[i].nama_satuan_labor + '</option>';
                    }
                    $('#tampilkan_parameter').html(html);
                }
            });
        });

        $(document).on('submit', '#input_hasil_pemeriksaan', function(event) {
            event.preventDefault()

            var tampilkan_pemeriksaan = $('#tampilkan_pemeriksaan').val();
            var tampilkan_parameter = $('#tampilkan_parameter').val();
            var error_tampilkan_parameter = $('#error_tampilkan_parameter').val();
            var error_tampilkan_pemeriksaan = $('#error_tampilkan_pemeriksaan').val();

            if ($('#tampilkan_parameter').val() == 'Pilih Dokter') {
                error_tampilkan_parameter = 'Pilih Dokter';
                $('#error_tampilkan_parameter').text(error_tampilkan_parameter);
                tampilkan_parameter = '';
            } else {
                error_tampilkan_parameter = '';
                $('#error_tampilkan_parameter').text(error_tampilkan_parameter);
                tampilkan_parameter = $('#tampilkan_parameter').val();
            }
            if ($('#tampilkan_pemeriksaan').val() == 'Pilih Dokter') {
                error_tampilkan_pemeriksaan = 'Pilih Dokter';
                $('#error_tampilkan_pemeriksaan').text(error_tampilkan_pemeriksaan);
                tampilkan_pemeriksaan = '';
            } else {
                error_tampilkan_pemeriksaan = '';
                $('#error_tampilkan_pemeriksaan').text(error_tampilkan_pemeriksaan);
                tampilkan_pemeriksaan = $('#tampilkan_pemeriksaan').val();
            }

            if (error_tampilkan_pemeriksaan != '' || error_tampilkan_parameter != '') {
                toastr["error"]("Data belum lengkap");
            } else {
                $.ajax({
                    url: '<?php echo base_url(); ?>laboratorium/inputHasilPemeriksaan',
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#tampilkan_pemeriksaan').val('Pilih Pemeriksaan');
                        $('#tampilkan_parameter').val('Pilih Parameter');
                        dataTable3.ajax.reload();
                        toastr["success"](data);
                    }
                });
            }
        });

        //  Datatable Pemeriksaan Labor
        dataTable3 = $('#tabel_input_hasil').DataTable({
            "serverSide": true,
            "processing": true,
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url(); ?>laboratorium/tabelInputHasil",
                "type": "POST",
                "data": function(data) {
                    data.idOrderLabor2 = $('#id_order_labor2').val();
                },
            },
            columnDefs: [{
                orderable: !1,
                targets: [0]
            }],
            autoWidth: !1
        });

    });
</script>
