@extends('layouts.master')

@section('title')
    Product List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Product List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="box-header-actions" style="display: inline-flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Add New Product</button>
                    <button onclick="openImportModal()" class="btn btn-primary btn-flat"><i class="fa fa-file-excel-o"></i> Import CSV Items</button>
                    <a href="{{ route('produk.sample_csv') }}" class="btn btn-default btn-flat" title="Download sample template"><i class="fa fa-download"></i> Sample CSV</a>
                    <button onclick="deleteSelected('{{ route('produk.delete_selected') }}')" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">#</th>
                            <th width="8%">Photo</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Discount</th>
                            <th>Stock</th>
                            <th width="12%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('produk.form')
@includeIf('produk.import_modal')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('produk.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'foto_preview', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'nama_kategori'},
                {data: 'merk'},
                {data: 'harga_beli'},
                {data: 'harga_jual'},
                {data: 'diskon'},
                {data: 'stok'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.ajax({
                    url: $('#modal-form form').attr('action'),
                    type: 'post',
                    data: new FormData($('#modal-form form')[0]),
                    processData: false,
                    contentType: false
                })
                .done((response) => {
                    $('#modal-form').modal('hide');
                    showSuccessToast('Product saved successfully');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    showErrorToast('Unable to save product. Please check required fields.');
                    return;
                });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Product / Dish');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_produk]').focus();
        $('#modal-form .tampil-foto-produk').empty();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Product / Dish');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_produk]').focus();
        $('#modal-form .tampil-foto-produk').empty();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_produk]').val(response.nama_produk);
                $('#modal-form [name=id_kategori]').val(response.id_kategori);
                $('#modal-form [name=merk]').val(response.merk);
                $('#modal-form [name=harga_beli]').val(response.harga_beli);
                $('#modal-form [name=harga_jual]').val(response.harga_jual);
                $('#modal-form [name=diskon]').val(response.diskon);
                $('#modal-form [name=stok]').val(response.stok);

                if (response.foto) {
                    $('#modal-form .tampil-foto-produk').html(`<img src="{{ url('/') }}${response.foto}" width="100" style="border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 6px;">`);
                }
            })
            .fail((errors) => {
                showErrorToast('Unable to display product data');
                return;
            });
    }

    function deleteData(url) {
        showConfirmDialog('Delete Product?', 'Are you sure you want to delete this menu item?', 'Yes, delete', function() {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    showSuccessToast('Product deleted successfully');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete product');
                });
        });
    }

    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            showConfirmDialog('Delete Selected?', 'Are you sure you want to delete all selected items?', 'Yes, delete all', function() {
                $.post(url, $('.form-produk').serialize())
                    .done((response) => {
                        showSuccessToast('Selected items deleted');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        showErrorToast('Unable to delete selected items');
                    });
            });
        } else {
            showWarningToast('Please select at least one item to delete');
            return;
        }
    }

    function cetakBarcode(url) {
        if ($('input:checked').length < 1) {
            showWarningToast('Select at least one product to print barcodes');
            return;
        } else if ($('input:checked').length < 3) {
            showWarningToast('Select at least 3 products to print barcode sheet');
            return;
        } else {
            $('.form-produk')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }

    /* ========================================================
     * 1-CLICK AUTOMATIC CSV IMPORT SCRIPT
     * ======================================================== */
    function openImportModal() {
        $('#simple-selected-file-info').hide();
        $('#simple-selected-filename').text('');
        $('#simple_csv_file_input').val('');
        $('#simple-import-loading').hide();
        $('#btn-do-import').prop('disabled', false).html('<i class="fa fa-cloud-upload"></i> Upload & Import Items');
        $('#simple-drop-zone').css({'border-color': '#3b82f6', 'background': '#ffffff'});
        $('#modal-import-csv').modal('show');
    }

    function handleSimpleFileChosen(file) {
        if (!file) return;
        $('#simple-selected-filename').text(file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)');
        $('#simple-selected-file-info').show();
        $('#simple-drop-zone').css({'border-color': '#16a34a', 'background': '#f0fdf4'});
    }

    // HTML5 Drag and Drop events
    $(function() {
        let dropZone = document.getElementById('simple-drop-zone');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#simple-drop-zone').css({'border-color': '#2563eb', 'background': '#eff6ff'});
                }, false);
            });

            ['dragleave', 'drop'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#simple-drop-zone').css({'border-color': '#3b82f6', 'background': '#ffffff'});
                }, false);
            });

            dropZone.addEventListener('drop', function(e) {
                let dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length) {
                    let file = dt.files[0];
                    let input = document.getElementById('simple_csv_file_input');
                    try {
                        let transfer = new DataTransfer();
                        transfer.items.add(file);
                        input.files = transfer.files;
                    } catch(err) {}
                    handleSimpleFileChosen(file);
                }
            }, false);
        }
    });

    function doDirectImport() {
        let fileInput = document.getElementById('simple_csv_file_input');
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            showWarningToast('Please select a CSV file first.');
            return;
        }

        let formData = new FormData($('#form-simple-csv-import')[0]);
        let btn = $('#btn-do-import');
        let originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Importing...');
        $('#simple-import-loading').show();

        $.ajax({
            url: '{{ route('produk.import_direct') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function(res) {
            btn.prop('disabled', false).html(originalText);
            $('#simple-import-loading').hide();

            if (res.status) {
                $('#modal-import-csv').modal('hide');
                showSuccessToast(res.message || 'Items imported successfully!');
                if (typeof table !== 'undefined' && table.ajax) {
                    table.ajax.reload();
                }
            } else {
                showErrorToast(res.message || 'Import failed.');
            }
        })
        .fail(function(xhr) {
            btn.prop('disabled', false).html(originalText);
            $('#simple-import-loading').hide();
            let msg = 'Failed to import CSV file.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showErrorToast(msg);
        });
    }
</script>
@endpush