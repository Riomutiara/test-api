<div class="container">
  <div class="row mt-3 justify-content-center">
    <div class="col-md-8">
      <h2 class="text-center">Seacrh Movie</h2>
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Seacrh movies..." id="search-input" />
        <button class="btn btn-warning" type="button" id="search-button">
          Search Movie
        </button>
      </div>
    </div>
  </div>
  <hr />
  <div class="row mt-3 justify-content-center" id="movie-list"></div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Movie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">...</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>


<script src="<?= base_url(); ?>scriptjs/welcome_message.js"></script>