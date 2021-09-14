function searchMovie() {
  $("#movie-list").html("");

  $.ajax({
    url: "http://omdbapi.com",
    type: "GET",
    dataType: "JSON",
    data: {
      apikey: "4232bb2b",
      s: $("#search-input").val(),
    },
    success: function (data) {
      if (data.Response == "True") {
        let movies = data.Search;
        // console.log(movies);
        $.each(movies, function (i, data) {
          $("#movie-list").append(
            `
            <div class="col-md-4">
                <div class="card">
                    <img src="` +
              data.Poster +
              `" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">` +
              data.Title +
              `</h5>
                        <h6 class="card-subtitle mb-2 text-muted">` +
              data.Year +
              `</h6>
                        <p class="card-text">` +
              data.Type +
              `</p>
              <button
                  type="button"
                  class="btn btn-warning see-detail"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal"
                  data-id="` +
              data.imdbID +
              `"
                >
                  See Detail
              </button>
                    </div>
                </div>
            </div>
          `,
          );
        });
        $("#search-input").val("");
      } else {
        $("#movie-list").html(
          `<h4 class="text-center text-danger">` + data.Error + `</h4>`,
        );
      }
    },
  });
}

$("#search-button").on("click", function () {
  let input = $("#search-input").val();
  if (input == "") {
    alert("Masukkan Judul Film!");
  } else {
    searchMovie();
  }
});

$("#search-input").on("keyup", function (event) {
  if (event.keyCode == 13) {
    searchMovie();
  }
});

$("#movie-list").on("click", ".see-detail", function () {
  // console.log($(this).data("id"));
  $.ajax({
      url: "http://omdbapi.com",
      type: "GET",
      dataType: "JSON",
      data: {
        'apikey': "4232bb2b",
        'i': $(this).data("id"),
      },
      success: function (data) {
        $('.modal-body').html(`
          <div class="row mb-3">
            <div class="col-md-6">
              <img src="` + data.Poster + `" class="card-img-top">
            </div>
            <div class="col-md-6">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">`+ data.Title +`</h5>
                </div>               
                <span class="badge bg-warning text-dark">`+ data.Year +`</span> <br>
                <small>Actors : `+ data.Actors +`.</small> <br>                                
                <small>Date Release : `+ data.Released +`.</small> <br>                                
                <small>Duration : `+ data.Runtime +`.</small> <br>                                
                <small>Genre : `+ data.Genre +`.</small> <br>                                 
                <small>Director : `+ data.Director +`.</small> <br>                               
                <small>Writer : `+ data.Writer +`.</small> <br>                               
                <small>Awards : `+ data.Awards +`.</small> <br>
                <hr> 
                <p class="mb-1"><i>`+ data.Plot +`</i></p>                                
            </div>
          </div>
        `)     
      },
    });
});
