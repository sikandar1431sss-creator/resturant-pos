@extends('layouts.master')

@section('title')
    Raw Stock
@endsection

@push('css')
<style>
    /* Hide AdminLTE default duplicate content-header & breadcrumbs */
    .content-header {
        display: none !important;
    }

    .raw-stock-page-wrapper {
        margin-top: 5px;
    }

    /* Top Page Header */
    .raw-stock-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .raw-stock-heading {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 22px;
        letter-spacing: -0.02em;
    }

    .top-actions-cluster {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-purchase-link {
        background: #ffffff !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        border-radius: 8px !important;
        padding: 9px 16px !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }
    .btn-purchase-link:hover {
        background: #f8fafc !important;
        border-color: #94a3b8 !important;
        color: #0f172a !important;
        transform: translateY(-1px);
    }

    .btn-create-material {
        background: #ea580c !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 13px !important;
        border-radius: 8px !important;
        padding: 9px 18px !important;
        border: none !important;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25) !important;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-create-material:hover {
        background: #c2410c !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(234, 88, 12, 0.35) !important;
    }

    /* KPI Summary Row */
    .kpi-raw-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .kpi-raw-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
    }
    .kpi-raw-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .kpi-raw-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .kpi-raw-val {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .kpi-raw-lbl {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Table Container Card */
    .raw-table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        padding: 20px;
    }

    .table-actions-group {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-table-action {
        width: 34px !important;
        height: 34px !important;
        min-width: 34px !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 13px !important;
        border: 1px solid transparent !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        padding: 0 !important;
        text-decoration: none !important;
    }
    .btn-table-action.btn-edit {
        background: #2563eb !important;
        color: #fff !important;
    }
    .btn-table-action.btn-edit:hover {
        background: #1d4ed8 !important;
        transform: translateY(-2px);
    }
    .btn-table-action.btn-delete {
        background: #ef4444 !important;
        color: #fff !important;
    }
    .btn-table-action.btn-delete:hover {
        background: #dc2626 !important;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="raw-stock-page-wrapper">
    <!-- Top Page Header -->
    <div class="raw-stock-top-bar">
        <div>
            <h2 class="raw-stock-heading">Raw Stock</h2>
        </div>
        <div class="top-actions-cluster">
            <a href="{{ route('pembelian.index') }}" class="btn-purchase-link">
                <i class="fa fa-shopping-cart text-warning"></i> Purchases
            </a>
            <button type="button" onclick="openImportModal()" class="btn-purchase-link" style="background: #eff6ff !important; color: #1d4ed8 !important; border-color: #bfdbfe !important;">
                <i class="fa fa-file-excel-o text-primary"></i> Import CSV
            </button>
            <a href="{{ route('raw_material.export_csv') }}" class="btn-purchase-link" style="background: #f0fdf4 !important; color: #15803d !important; border-color: #bbf7d0 !important;" title="Export all raw materials to CSV">
                <i class="fa fa-download text-success"></i> Export CSV
            </a>
            <button onclick="addForm('{{ route('raw_material.store') }}')" class="btn-create-material">
                <i class="fa fa-plus-circle"></i> Add Raw Material
            </button>
        </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="kpi-raw-grid">
        <div class="kpi-raw-card">
            <div class="kpi-raw-icon" style="background: #eff6ff; color: #2563eb;">
                <i class="fa fa-cubes"></i>
            </div>
            <div>
                <div class="kpi-raw-val">{{ $totalMaterials ?? 0 }}</div>
                <div class="kpi-raw-lbl">Total Raw Materials</div>
            </div>
        </div>

        <div class="kpi-raw-card">
            <div class="kpi-raw-icon" style="background: #f0fdf4; color: #16a34a;">
                <i class="fa fa-check-circle"></i>
            </div>
            <div>
                <div class="kpi-raw-val" style="color: #16a34a;">{{ $inStockCount ?? 0 }}</div>
                <div class="kpi-raw-lbl">In Stock (Healthy)</div>
            </div>
        </div>

        <div class="kpi-raw-card">
            <div class="kpi-raw-icon" style="background: #fffbeb; color: #d97706;">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div>
                <div class="kpi-raw-val" style="color: #d97706;">{{ $lowStockCount ?? 0 }}</div>
                <div class="kpi-raw-lbl">Low Stock Alert</div>
            </div>
        </div>

        <div class="kpi-raw-card">
            <div class="kpi-raw-icon" style="background: #fef2f2; color: #dc2626;">
                <i class="fa fa-times-circle"></i>
            </div>
            <div>
                <div class="kpi-raw-val" style="color: #dc2626;">{{ $outOfStockCount ?? 0 }}</div>
                <div class="kpi-raw-lbl">Out of Stock</div>
            </div>
        </div>
    </div>

    <!-- Raw Materials Table Card -->
    <div class="raw-table-card">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="raw-materials-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="10%">Code</th>
                        <th width="26%">Material / Ingredient Name</th>
                        <th width="10%">Unit</th>
                        <th width="20%">Current Stock</th>
                        <th width="12%">Min Alert</th>
                        <th width="10%">Cost/Unit</th>
                        <th width="8%"><i class="fa fa-cog"></i> Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@includeIf('raw_material.form')
@includeIf('raw_material.import_modal')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('#raw-materials-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url: '{{ route('raw_material.data') }}',
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false,
                    render: function (data, type, row, meta) {
                        if (meta && meta.settings) {
                            let total = meta.settings.fnRecordsDisplay();
                            let start = meta.settings._iDisplayStart || 0;
                            let num = total - (start + meta.row);
                            if (num > 0) {
                                return '<strong style="color: #64748b;">' + num + '</strong>';
                            }
                        }
                        return '<strong style="color: #64748b;">' + (data || 1) + '</strong>';
                    }
                },
                {data: 'kode_material'},
                {data: 'nama_material'},
                {data: 'satuan'},
                {data: 'stok_status'},
                {data: 'min_stok'},
                {data: 'harga_beli'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                        showSuccessToast(response || 'Raw Material saved successfully');
                    })
                    .fail((errors) => {
                        showErrorToast(errors.responseJSON?.message || 'Unable to save data');
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').html('<i class="fa fa-plus-circle" style="color:#ea580c;"></i> Add Raw Material');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_material]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').html('<i class="fa fa-pencil" style="color:#ea580c;"></i> Edit Raw Material');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_material]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_material]').val(response.nama_material);
                $('#modal-form [name=satuan]').val(response.satuan);
                $('#modal-form [name=stok]').val(response.stok);
                $('#modal-form [name=min_stok]').val(response.min_stok);
                $('#modal-form [name=harga_beli]').val(response.harga_beli);
            })
            .fail((errors) => {
                showErrorToast('Unable to fetch data');
            });
    }

    function deleteData(url) {
        showConfirmDialog('Delete Raw Material?', 'Are you sure you want to delete this raw material?', 'Yes, delete', function() {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                    showSuccessToast('Raw Material deleted successfully');
                })
                .fail((errors) => {
                    showErrorToast('Unable to delete raw material');
                });
        });
    }

    /* ========================================================
     * 1-CLICK AUTOMATIC CSV IMPORT SCRIPT FOR RAW MATERIALS
     * ======================================================== */
    function openImportModal() {
        $('#raw-selected-file-info').hide();
        $('#raw-selected-filename').text('');
        $('#raw_csv_file_input').val('');
        $('#raw-import-loading').hide();
        $('#btn-do-raw-import').prop('disabled', false).html('<i class="fa fa-cloud-upload"></i> Upload &amp; Import Materials');
        $('#raw-drop-zone').css({'border-color': '#ea580c', 'background': '#ffffff'});
        $('#modal-import-raw-csv').modal('show');
    }

    function handleRawFileChosen(file) {
        if (!file) return;
        $('#raw-selected-filename').text(file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)');
        $('#raw-selected-file-info').show();
        $('#raw-drop-zone').css({'border-color': '#16a34a', 'background': '#f0fdf4'});
    }

    // HTML5 Drag and Drop events
    $(function() {
        let dropZone = document.getElementById('raw-drop-zone');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#raw-drop-zone').css({'border-color': '#ea580c', 'background': '#fff7ed'});
                }, false);
            });

            ['dragleave', 'drop'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#raw-drop-zone').css({'border-color': '#ea580c', 'background': '#ffffff'});
                }, false);
            });

            dropZone.addEventListener('drop', function(e) {
                let dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length) {
                    let file = dt.files[0];
                    let input = document.getElementById('raw_csv_file_input');
                    try {
                        let transfer = new DataTransfer();
                        transfer.items.add(file);
                        input.files = transfer.files;
                    } catch(err) {}
                    handleRawFileChosen(file);
                }
            }, false);
        }
    });

    function doDirectRawImport() {
        let fileInput = document.getElementById('raw_csv_file_input');
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            showWarningToast('Please select a CSV file first.');
            return;
        }

        let formData = new FormData($('#form-raw-csv-import')[0]);
        let btn = $('#btn-do-raw-import');
        let originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Importing...');
        $('#raw-import-loading').show();

        $.ajax({
            url: '{{ route('raw_material.import_direct') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function(res) {
            btn.prop('disabled', false).html(originalText);
            $('#raw-import-loading').hide();

            if (res.status) {
                $('#modal-import-raw-csv').modal('hide');
                showSuccessToast(res.message || 'Raw materials imported successfully!');
                if (typeof table !== 'undefined' && table.ajax) {
                    table.ajax.reload();
                }
            } else {
                showErrorToast(res.message || 'Import failed.');
            }
        })
        .fail(function(xhr) {
            btn.prop('disabled', false).html(originalText);
            $('#raw-import-loading').hide();
            let msg = 'Failed to import CSV file.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showErrorToast(msg);
        });
    }
</script>
@endpush
