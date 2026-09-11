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
                    <button onclick="deleteSelected('{{ route('produk.delete_selected') }}')" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                    <button onclick="cetakBarcode('{{ route('produk.cetak_barcode') }}')" class="btn btn-warning btn-flat"><i class="fa fa-barcode"></i> Print Barcode</button>
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
                            <th>Purchase Price</th>
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
                    async: false,
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
</script>
@endpush