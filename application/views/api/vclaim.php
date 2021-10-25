<div class="container">
  <div class="row mt-3 justify-content-center">
    <div class="col-md-8">
      <h2 class="text-center">Cari Peserta</h2>
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Masukkan nomor peserta BPJS..." id="search_input" name="search_input" />
        <button class="btn btn-success" type="button" id="search_button">
          Nomor BPJS
        </button>
        <input type="text" class="form-control" placeholder="Masukkan nomor NIK..." id="search_input3" name="search_input3" />
        <button class="btn btn-success" type="button" id="search_button3">
          Nomor NIK
        </button>
      </div>
      <br>
    </div>
    <hr>
    <div class="col-md-8">
      <h2 class="text-center">Cari Rujukan</h2>
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Masukkan nomor peserta BPJS..." id="search_input2" name="search_input2" />
        <button class="btn btn-success" type="button" id="search_button2">
          No BPJS
        </button>
        <input type="text" class="form-control" placeholder="Masukkan nomor peserta BPJS..." id="search_input4" name="search_input4" />
        <button class="btn btn-success" type="button" id="search_button4">
          No. Rujukan
        </button>
      </div>
      <br>
    </div>
    <hr>
    <div class="col-md-8">
      <h2 class="text-center">Monitoring Kunjungan</h2>
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Masukkan nomor peserta BPJS..." id="search_input5" name="search_input5" />
        <select class="custom-select rounded-0" id="search_input6" name="search_input6">
          <option selected>Jenis Layanan</option>
          <option value="1">Rawat Inap</option>
          <option value="2">Rawat Jalan</option>
        </select>
        <button class="btn btn-success" type="button" id="search_button5">
          Cari
        </button>
      </div>
      <br>
    </div>
    <hr>
    <div class="col-md-8">
      <h2 class="text-center">Histori Pelayanan</h2>
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Masukkan nomor peserta BPJS..." id="search_input7" name="search_input7" />
        <input type="text" class="form-control" placeholder="Tgl Mulai" id="search_input8" name="search_input8" />
        <input type="text" class="form-control" placeholder="Tgl Akhir" id="search_input9" name="search_input9" />

        <button class="btn btn-success" type="button" id="search_button7">
          Cari
        </button>
      </div>
      <br>
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
        url: '<?= base_url(); ?>vclaim/cekKartuPesertaBPJS',
        method: 'POST',
        data: {
          input: input
        },
        dataType: 'JSON',
        success: function(data) {
          if (data.metaData['code'] == "200") {
            console.log(data);
            var aaa = data.response;

            $.ajax({
              url: "<?php echo base_url(); ?>vclaim/stringDecrypt",
              method: "POST",
              data: {
                string: aaa
              },
              dataType: 'JSON',
              success: function(data) {
                console.log(data);
              }
            });
          } else {
            alert('Data Kepesertaan tidak ditemukan!')
          }
        }
      })
    })

    $('#search_button2').on('click', function() {
      var input = $('#search_input2').val();

      $.ajax({
        url: '<?= base_url(); ?>vclaim/cariRujukanDenganNoBPJS',
        method: 'POST',
        data: {
          input: input
        },
        dataType: 'JSON',
        success: function(data) {
          if (data.metaData['code'] == "200") {
            console.log(data);
          } else {
            alert('Data Kepesertaan tidak ditemukan!')
          }
        }
      })
    })


    $('#search_button3').on('click', function() {
      var input = $('#search_input3').val();

      $.ajax({
        url: '<?= base_url(); ?>vclaim/cekKartuPesertaNIK',
        method: 'POST',
        data: {
          input: input
        },
        dataType: 'JSON',
        success: function(data) {
          if (data.metaData['code'] == "200") {
            // console.log(data);
            var aaa = data.response;

            $.ajax({
              url: "<?php echo base_url(); ?>vclaim/stringDecrypt",
              method: "POST",
              data: {
                string: aaa
              },
              dataType: 'JSON',
              success: function(data) {
                console.log(data);
              }
            });
          } else {
            alert('Data Kepesertaan tidak ditemukan!')
          }
        }
      })
    })
    $('#search_button4').on('click', function() {
      var input = $('#search_input4').val();

      $.ajax({
        url: '<?= base_url(); ?>vclaim/cariRujukanDenganNoRujukan',
        method: 'POST',
        data: {
          input: input
        },
        dataType: 'JSON',
        success: function(data) {
          if (data.metaData['code'] == "200") {
            console.log(data);
          } else {
            alert('Data Kepesertaan tidak ditemukan!')
          }
        }
      })
    })

    $('#search_button5').on('click', function() {
      var tanggal = $('#search_input5').val();
      var jenis = $('#search_input6').val();

      $.ajax({
        url: '<?= base_url(); ?>vclaim/monitoringDataKunjungan',
        method: 'POST',
        data: {
          tanggal: tanggal,
          jenis: jenis
        },
        dataType: 'JSON',
        success: function(data) {
          if (data.metaData['code'] == "200") {
            console.log(data);
          } else {
            alert('Data Kepesertaan tidak ditemukan!')
          }
        }
      })
    })

    $('#search_button7').on('click', function() {
      var no_kartu = $('#search_input7').val();
      var tgl_mulai = $('#search_input8').val();
      var tgl_akhir = $('#search_input9').val();

      $.ajax({
        url: '<?= base_url(); ?>vclaim/monitoringHistoryPelayanan',
        method: 'POST',
        data: {
          no_kartu: no_kartu,
          tgl_mulai: tgl_mulai,
          tgl_akhir: tgl_akhir
        },
        dataType: 'JSON',
        success: function(data) {
          if (data.metaData['code'] == "200") {
            console.log(data);
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