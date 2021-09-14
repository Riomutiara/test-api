<div class="container">
  <div class="row mt-3 justify-content-center">
    <div class="col-md-8">
      <h2 class="text-center">BPJS API</h2>
      <input type="text" name="consID" id="consID">
      <input type="text" name="Timestamp" id="Timestamp">
      <input type="text" name="Signature" id="Signature">
      <input type="text" name="Authorization" id="Authorization">
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Cari No. Peserta..." id="search-input" />
        <button class="btn btn-success" type="button" id="search-button">
          Cari
        </button>
      </div>
    </div>
  </div>
  <hr />
  <div class="row mt-3 justify-content-center" id="movie-list"></div>
</div>


<script>
  $(document).ready(function() {

    $.ajax({
        url: '<?= base_url(); ?>welcome/generateSigranure',
        type: "GET",
        success: function(data) {
          console.log(data);
          // let data = data.
          // $('#consID').val(data.data);
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
      var consID = "5231";
      var Timestamp = "1631610150";
      var Signature = "CkEzkzO+CkEzkzO+5McQ6HNLA9cySldbHfrwSnYcB3IHR2AJzYs=";
      var Authorization = "wMk5+f1u4qBwZr2wxzc9o+Q6pVr7OSvoHk\/cLRAxdt8=";

      $.ajax({
        url: 'https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/',
        type: "GET",
        dataType: "JSON",
        headers: {
          "X-cons-id": consID,
          "X-Timestamp": Timestamp,
          "X-Signature": Signature,
          "X-Authorization": Authorization
        },
        success: function(data) {
          console.log(data);
        },
      });
    });








  })
</script>