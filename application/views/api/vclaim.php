<div class="container">
  <div class="row mt-3 justify-content-center">
    <div class="col-md-8">
      <h2 class="text-center">VClaim</h2>
      <!-- <input type="text" name="consID" id="consID">
      <input type="text" name="Timestamp" id="Timestamp">
      <input type="text" name="Signature" id="Signature">
      <input type="text" name="Authorization" id="Authorization"> -->
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Masukkan nomor peserta BPJS..." id="search_input" name="search_input" />
        <button class="btn btn-success" type="button" id="search_button">
          Cari Nomor Kepesertaan
        </button>
      </div>
      <br>
      <!-- 0001769725541 Rio -->
      <!-- 0000024374856 Bebby -->

      <div class="container">
        <strong>Nama</strong> : <p id="nama_peserta"></p>
        <strong>NIK</strong> : <p id="nik_peserta"></p>
        <strong>Tgl. Lahir</strong> : <p id="tanggal_lahir"></p>
        <strong>Status</strong> :<p id="status_peserta" class="text-danger"></p>
        <strong>Jenis Peserta</strong> :<p id="jenis_peserta"></p>
      </div>


      <div class="row mt-3 justify-content-center" id="movie-list"></div>

    </div>
  </div>
  <hr />
  <div class="row mt-3 justify-content-center" id="movie-list"></div>
</div>


<script>
  $(document).ready(function() {
    $('#search_button').on('click', function() {
      var input = $('#search_input').val();

      $.ajax({
        url: '<?= base_url(); ?>vclaim/cekKartuPeserta',
        method: 'POST',
        data: {
          input: input
        },
        dataType: 'JSON',
        success: function(data) {
          if (data.metaData['code'] == "200") {
            let peserta = data.response['peserta'];
            let status = peserta['statusPeserta'];
            let jenis = peserta['jenisPeserta'];
            console.log(peserta);
            $('#nama_peserta').text(peserta['nama']);
            $('#nik_peserta').text(peserta['nik']);
            $('#tanggal_lahir').text(peserta['tglLahir']);
            $('#status_peserta').text(status['keterangan']);
            $('#jenis_peserta').text(jenis['keterangan']);
          } else {
            alert('Data Kepesertaan tidak ditemukan!')
          }
        }
      })
    })
  })
</script>



















<!-- <script>
  $(document).ready(function() {

    $.ajax({
        url: '<?= base_url(); ?>welcome/generateSigranure',
        type: "GET",
        success: function(data) {
          console.log(data);
          // let data = data.
          $('#consID').val(data);
        },
      });

      
    // function apiBPJS() {
    // $data = "1652";
    // $secretKey = "0060R004";
    // 0000024374856


    // $username = "dian_rsj";
    // $password = "Dian0301@6";
    // // $kdAplikasi = "095";
    // $user = hash_hmac('sha256', $username, $password, true);
    // $Authorization = base64_encode($user);


    // $data = "5231";
    // $secretKey = "7rA70A8D69";
    // // Computes the timestamp
    // //$data = $this->uri->segment(3);
    // //$secretKey = $this->uri->segment(4);
    // // echo $secretKey;
    // date_default_timezone_set('UTC');
    // $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
    // // Computes the signature by hashing the salt with the secret key asthe key
    // $signature = hash_hmac('sha256', $data, $secretKey, true);
    // // base64 encode
    // $encodedSignature = base64_encode($signature);

    // $headers = array($data, $tStamp, $encodedSignature, $Authorization);
    // //$hasil = json_encode($headers);
    // // echo json_encode($headers);
    $("#search-button").on("click", function() {
      var consID = $('#consID').val();
      // var Timestamp = "1631610150";
      // var Signature = "CkEzkzO+CkEzkzO+5McQ6HNLA9cySldbHfrwSnYcB3IHR2AJzYs=";
      // var Authorization = "wMk5+f1u4qBwZr2wxzc9o+Q6pVr7OSvoHk\/cLRAxdt8=";

      $.ajax({
        url: 'https://dvlp.bpjs-kesehatan.go.id/vclaim-rest-1.1',
        type: "GET",
        dataType: "JSON",
        headers: {
          consID
        },
        success: function(data) {
          console.log(data);
        },
      });
    });








  })
</script> -->