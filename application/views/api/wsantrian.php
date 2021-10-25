<div class="container">
    <div class="row mt-3 justify-content-center">
        <div class="col-md-8">
            <form action="POST" id="tambah_barang">
                <h2 class="text-center">CRUD CURL</h2>
                <input type="hidden" name="id_barang" id="id_barang">
                <input type="hidden" name="action" id="action" value="POST">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang">
                    <small><span class="text-danger" id="error_nama_barang"></span></small>
                </div>
                <div class="mb-3">
                    <label for="harga_barang" class="form-label">Harga Barang</label>
                    <input type="number" class="form-control" id="harga_barang" name="harga_barang">
                    <small><span class="text-danger" id="error_harga_barang"></span></small>
                </div>
                <hr>
                <button class="btn btn-success" type="submit" id="btn_simpan">
                    Tambah Barang
                </button>
            </form>
        </div>
    </div>
    <br>
    <hr>
    <div class="table-responsive">
        <table id="tabel_barang" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<script>
    $(document).ready(function() {
        // $(document).on('click', '#btn_simpan', function() {
        //     var nama_barang = $('#nama_barang').val();
        //     var harga_barang = $('#harga_barang').val();

        //     $.ajax({
        //         url: '<?php echo base_url(); ?>crudcurl/tambahBarang',
        //         method: 'POST',
        //         data: {
        //             nama_barang: nama_barang,
        //             harga_barang: harga_barang,
        //         },
        //         success: function(data) {
        //             alert('Data berhasil disimpan')
        //             dataTable.ajax.reload();
        //         }
        //     });
        // })

        $(document).on('submit', '#tambah_barang', function(event) {
            event.preventDefault();
            var nama_barang = $('#nama_barang').val();
            var harga_barang = $('#harga_barang').val();

            if ($('#nama_barang').val() == '') {
                error_nama_barang = 'Nama Barang tidak boleh kosong!';
                $('#error_nama_barang').text(error_nama_barang);
                nama_barang = '';
            } else {
                error_nama_barang = '';
                $('#error_nama_barang').text(error_nama_barang);
                nama_barang = $('#nama_barang').val();
            }

            if ($('#harga_barang').val() == '') {
                error_harga_barang = 'Harga tidak boleh kosong!';
                $('#error_harga_barang').text(error_harga_barang);
                harga_barang = '';
            } else {
                error_harga_barang = '';
                $('#error_harga_barang').text(error_harga_barang);
                harga_barang = $('#harga_barang').val();
            }

            if (error_nama_barang != '' || error_harga_barang != '') {
                toastr["error"]("Data belum lengkap");
            } else {
                $.ajax({
                    url: '<?php echo base_url(); ?>crudcurl/tambahBarang',
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#tambah_barang')[0].reset();
                        $('#action').val('POST');
                        $('#id_barang').val('');
                        dataTable.ajax.reload();
                        alert(data);
                    }
                });
            }
        });

        // Datatables menu
        dataTable = $('#tabel_barang').DataTable({
            "ajax": {
                "url": "<?php echo base_url(); ?>crudcurl/tabelBarang",
                "type": "GET",
            },
            columnDefs: [{
                orderable: !1,
                targets: [0, 3]
            }],
            autoWidth: !1
        });

        $(document).on('click', '.delete_product', function() {
            var id = $(this).attr('id');
            console.log(id);

            $.ajax({
                url: '<?php echo base_url(); ?>crudcurl/hapusBarang',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    alert('Data berhasil dihapus');
                    dataTable.ajax.reload();
                }
            })
        });

        $(document).on('click', '.update_product', function() {
            var id = $(this).attr('id');

            $.ajax({
                url: '<?php echo base_url(); ?>crudcurl/fetchSingleBarang',
                method: 'POST',
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    $('#nama_barang').val(data['product_name']);
                    $('#harga_barang').val(data['product_price']);
                    $('#id_barang').val(id);
                    $('#action').val('PUT');
                }
            })
        });


    })
</script>