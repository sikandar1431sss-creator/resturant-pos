@extends('layouts.master')

@section('title')
    Purchase Order
@endsection

@push('css')
<style>
    .purchase-header-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .supplier-info-grid {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .supplier-badge-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .table-actions-group {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        flex-wrap: nowrap !important;
        vertical-align: middle !important;
    }
    .btn-table-action {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 13px !important;
        border: 1px solid transparent !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    }
    .btn-table-action.btn-delete {
        background-color: #fee2e2 !important;
        color: #ef4444 !important;
        border-color: #fca5a5 !important;
    }
    .btn-table-action.btn-delete:hover {
        background-color: #ef4444 !important;
        color: #ffffff !important;
    }
    .tampil-bayar {
        font-size: 3.2em;
        text-align: center;
        min-height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
        border-radius: 12px 12px 0 0;
        letter-spacing: -0.02em;
    }
    .tampil-terbilang {
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 12px 12px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        font-style: italic;
        text-align: center;
    }
    .billing-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .billing-card .form-group {
        margin-bottom: 12px;
    }
    .billing-card label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
    }
    .table-pembelian tbody tr:last-child {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- Supplier & PO Header Card -->
        <div class="purchase-header-card">
            <div class="supplier-info-grid">
                <div class="supplier-badge-icon">
                    <i class="fa fa-truck"></i>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Supplier Information</div>
                    <div style="font-size: 16px; font-weight: 800; color: #0f172a;">{{ $supplier->nama }}</div>
                </div>
                <div style="border-left: 1px solid #e2e8f0; padding-left: 15px;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748b;">Phone</div>
                    <div style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $supplier->telepon ?: 'N/A' }}</div>
                </div>
                <div style="border-left: 1px solid #e2e8f0; padding-left: 15px;">
                    <div style="font-size: 11px; font-weight: 600; color: #64748b;">Address</div>
                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;">{{ $supplier->alamat ?: 'N/A' }}</div>
                </div>
            </div>
            <div>
                <span class="label label-primary" style="font-size: 13px; padding: 6px 12px; border-radius: 6px; font-weight: 700; background-color: #e0f2fe !important; color: #0284c7 !important; border: 1px solid #bae6fd;">
                    PO #{{ tambah_nol_didepan($id_pembelian, 4) }}
                </span>
                <a href="{{ route('pembelian.index') }}" class="btn btn-default btn-sm" style="margin-left: 10px; border-radius: 6px; font-weight: 600;">
                    <i class="fa fa-arrow-left"></i> Purchases List
                </a>
            </div>
        </div>

        <div class="box" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
            <div class="box-body" style="padding: 20px;">
                <!-- Barcode & Item Picker Form -->
                <form class="form-produk" onsubmit="return false;" style="margin-bottom: 20px;">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="hidden" name="id_raw_material" id="id_raw_material">
                                <span class="input-group-addon" style="background: #f8fafc; border-color: #cbd5e1;"><i class="fa fa-barcode text-muted"></i></span>
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk" placeholder="Scan barcode or enter code and press Enter..." style="height: 42px; font-size: 14px;" autofocus autocomplete="off">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-primary btn-flat" type="button" style="height: 42px; border-radius: 0 8px 8px 0; font-weight: 700; background-color: #0284c7; border-color: #0284c7; padding: 0 18px;">
                                        <i class="fa fa-search"></i> Select Item
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-7" style="display:flex; align-items:center;">
                            <span class="text-muted" style="font-size: 12.5px;">
                                <i class="fa fa-info-circle text-info"></i> Click <strong>Select Item</strong> to pick Raw Stock Materials or Menu Items to restock.
                            </span>
                        </div>
                    </div>
                </form>

                <!-- Items Table -->
                <div class="table-responsive" style="margin-bottom: 25px;">
                    <table class="table table-striped table-bordered table-pembelian table-hover" style="width:100%;">
                        <thead style="background: #f8fafc;">
                            <th width="5%">#</th>
                            <th width="12%">Code</th>
                            <th>Item / Material Name</th>
                            <th width="15%">Cost Price</th>
                            <th width="18%">Quantity</th>
                            <th width="15%">Subtotal</th>
                            <th width="8%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>

                <!-- Totals & Payment Section -->
                <div class="row">
                    <div class="col-lg-7 col-md-6">
                        <div class="tampil-bayar">Rs 0</div>
                        <div class="tampil-terbilang">Zero Rupees</div>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <div class="billing-card">
                            <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                                @csrf
                                <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="total" id="total" value="0">
                                <input type="hidden" name="total_item" id="total_item" value="0">
                                <input type="hidden" name="bayar" id="bayar" value="0">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-sm-4 control-label" style="padding-top: 7px;">Gross Total</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="totalrp" class="form-control input-sm" style="font-weight: 700; font-size: 14px; background: #f8fafc;" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diskon" class="col-sm-4 control-label" style="padding-top: 7px;">Discount (%)</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100" name="diskon" id="diskon" class="form-control input-sm" value="{{ $diskon }}" style="font-weight: 700;">
                                            <span class="input-group-addon" style="font-weight: 700;">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayarrp" class="col-sm-4 control-label" style="padding-top: 7px;">Paid Amount</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="font-weight: 700; background: #f1f5f9;">Rs</span>
                                            <input type="number" step="any" min="0" id="bayarrp" class="form-control input-sm" style="font-weight: 800; font-size: 15px; color: #0f172a;">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 20px; border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-success btn-flat pull-right btn-simpan" style="border-radius: 8px; font-weight: 700; padding: 8px 20px; background-color: #10b981; border-color: #10b981;">
                    <i class="fa fa-check-circle"></i> Save & Finish Purchase
                </button>
                <a href="{{ route('pembelian.nota_kecil', $id_pembelian) }}" target="_blank" class="btn btn-default btn-flat pull-right" style="margin-right: 10px; border-radius: 8px; font-weight: 600; padding: 8px 16px;">
                    <i class="fa fa-print"></i> Thermal Receipt Preview
                </a>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian_detail.produk')
@endsection

@push('scripts')
<script>
    let table, tableRaw, tableProduk;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian_detail.data', $id_pembelian) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
        });

        tableRaw = $('.table-raw-material-picker').DataTable({
            autoWidth: false
        });
        tableProduk = $('.table-produk-picker').DataTable({
            autoWidth: false
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        $('.form-produk').on('submit', function (e) {
            e.preventDefault();
            tambahProduk();
        });

        $(document).on('input change', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseFloat($(this).val());

            if (isNaN(jumlah) || jumlah <= 0) {
                $(this).val(1);
                showWarningToast('The quantity must be greater than 0');
                return;
            }
            if (jumlah > 100000) {
                $(this).val(100000);
                showWarningToast('The quantity cannot exceed 100,000');
                return;
            }

            $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail(errors => {
                    showErrorToast('Unable to save quantity change');
                    return;
                });
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() === "") {
                $(this).val(0);
            }
            loadForm($(this).val());
        });

        $(document).on('input', '#bayarrp', function () {
            let val = parseFloat($(this).val()) || 0;
            $('#bayar').val(val);
            $('.tampil-bayar').text('{{ get_currency_symbol() }} ' + Number(val).toLocaleString());
        });

        $('.btn-simpan').on('click', function (e) {
            e.preventDefault();
            let totalItem = parseFloat($('#total_item').val()) || 0;
            if (totalItem <= 0) {
                showWarningToast('Please add at least one item or material before saving the transaction.');
                return;
            }
            $('.form-pembelian').submit();
        });
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
        setTimeout(() => {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        }, 200);
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#id_raw_material').val('');
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
    }

    function pilihRawMaterial(id, kode) {
        $('#id_raw_material').val(id);
        $('#id_produk').val('');
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
    }

    function tambahProduk() {
        let code = $('#kode_produk').val().trim();
        let idProd = $('#id_produk').val();
        let idMat = $('#id_raw_material').val();

        if (!code && !idProd && !idMat) {
            showWarningToast('Please enter a barcode/code or select an item from the list.');
            return;
        }

        $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').val('').focus();
                $('#id_produk').val('');
                $('#id_raw_material').val('');
                table.ajax.reload(() => loadForm($('#diskon').val()));
                showSuccessToast('Item added to purchase order');
            })
            .fail(errors => {
                let msg = errors.responseJSON;
                if (typeof msg === 'object' && msg !== null) {
                    msg = msg.message || 'Unable to add item';
                } else if (!msg) {
                    msg = 'Unable to add item';
                }
                showErrorToast(msg);
                return;
            });
    }

    function deleteData(url) {
        showConfirmDialog('Delete Item?', 'Are you sure you want to remove this item from the purchase order?', 'Yes, delete', function() {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                    showSuccessToast('Item removed successfully');
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete item');
                    return;
                });
        });
    }

    function loadForm(diskon = 0) {
        let total = $('.total').text().trim() || '0';
        let total_item = $('.total_item').text().trim() || '0';
        $('#total').val(total);
        $('#total_item').val(total_item);

        $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon || 0}/${total || 0}`)
            .done(response => {
                $('#totalrp').val('{{ get_currency_symbol() }} ' + response.totalrp);
                $('#bayarrp').val(response.bayar);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('{{ get_currency_symbol() }} ' + response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
            })
            .fail(errors => {
                console.error('loadForm error', errors);
            });
    }
</script>
@endpush