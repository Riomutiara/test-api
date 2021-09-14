function apiBPJS() {
	// var data = "5231";
	// var secretKey = "7rA70A8D69";

	// // date_default_timezone_set("UTC");

	// // $tStamp = strval(time() - strtotime("1970-01-01 00:00:00"));
	// // $signature = hash_hmac("sha256", $data, $tStamp, $secretKey, true);
	// var signature = hash_hmac("sha256", data, secretKey, true);
	// var encodedSignature = base64_encode(signature);

	// var headers = array(data, $encodedSignature);

	// let header = headers;





	$.ajax({
		url: '<?= base_url?>',
		type: "GET",
		dataType: "JSON",
		// headers: { header },
		success: function (data) {
			console.log(data);
		},
	});
}

$("#search-button").on("click", function () {
	apiBPJS();
});
