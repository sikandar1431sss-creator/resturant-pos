@extends('layouts.master')

@section('title')
    Seating Management
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Seating Management</li>
@endsection

@push('css')
<style>
    /* Hide AdminLTE default duplicate content-header & breadcrumbs */
    .content-header {
        display: none !important;
    }

    .seating-page-wrapper {
        margin-top: 5px;
    }

    /* Top Page Header */
    .seating-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .seating-heading {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 22px;
        letter-spacing: -0.02em;
    }

    .btn-create-table {
        background: #ea580c !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 13.5px !important;
        border-radius: 8px !important;
        padding: 9px 20px !important;
        border: none !important;
        box-shadow: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-create-table:hover {
        background: #c2410c !important;
        color: #ffffff !important;
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
        font-size: 13.5px !important;
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

    /* KPI Cards */
    .stat-kpi-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        transition: transform 0.2s;
    }
    .stat-kpi-card:hover {
        transform: translateY(-2px);
    }
    .stat-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stat-kpi-val {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .stat-kpi-lbl {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@section('content')
<div class="seating-page-wrapper">
    <!-- Top Page Header -->
    <div class="seating-top-bar">
        <div>
            <h2 class="seating-heading">Seating Management</h2>
        </div>
        <div>
            <button onclick="addForm('{{ route('meja.store') }}')" class="btn-create-table">
                <i class="fa fa-plus-circle"></i> Add New Seating
            </button>
        </div>
    </div>

    <!-- KPI STATS -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="stat-kpi-card">
                <div class="stat-kpi-icon" style="background:#f0fdf4; color:#16a34a;">
                    <i class="fa fa-cutlery"></i>
                </div>
                <div>
                    <div class="stat-kpi-val">{{ $totalTables }}</div>
                    <div class="stat-kpi-lbl">Total Tables</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-kpi-card">
                <div class="stat-kpi-icon" style="background:#ecfdf5; color:#059669;">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-kpi-val" style="color:#059669;">{{ $availableTables }}</div>
                    <div class="stat-kpi-lbl">Available (Free)</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-kpi-card">
                <div class="stat-kpi-icon" style="background:#fef2f2; color:#dc2626;">
                    <i class="fa fa-users"></i>
                </div>
                <div>
                    <div class="stat-kpi-val" style="color:#dc2626;">{{ $occupiedTables }}</div>
                    <div class="stat-kpi-lbl">Occupied (Dine-In)</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-kpi-card">
                <div class="stat-kpi-icon" style="background:#fffbeb; color:#d97706;">
                    <i class="fa fa-bookmark"></i>
                </div>
                <div>
                    <div class="stat-kpi-val" style="color:#d97706;">{{ $reservedTables }}</div>
                    <div class="stat-kpi-lbl">Reserved</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.03); background: #ffffff;">
                <div class="box-body table-responsive" style="padding: 18px;">
                    <table class="table table-striped table-bordered table-hover" id="meja-table" style="width: 100%;">
                        <thead>
                            <th width="5%">#</th>
                            <th width="25%">Table Name / Number</th>
                            <th width="15%">Capacity</th>
                            <th width="15%">Live Status</th>
                            <th width="25%">Active Order Details</th>
                            <th width="15%"><i class="fa fa-cog"></i> Action</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('meja.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('#meja-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('meja.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nomor_meja'},
                {data: 'kapasitas'},
                {data: 'status'},
                {data: 'active_order'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                        showSuccessToast(response.message || 'Seating saved successfully');
                    })
                    .fail((errors) => {
                        showErrorToast(errors.responseJSON?.message || 'Unable to save seating data');
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add New Seating');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nomor_meja]').focus();
        $('#modal-form [name=status]').val('available');
        $('#modal-form [name=kapasitas]').val(4);
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Seating');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nomor_meja]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nomor_meja]').val(response.nomor_meja);
                $('#modal-form [name=kapasitas]').val(response.kapasitas);
                $('#modal-form [name=status]').val(response.status);
            })
            .fail((errors) => {
                showErrorToast('Unable to fetch table data');
            });
    }

    function deleteData(url) {
        showConfirmDialog('Delete Table?', 'Are you sure you want to delete this dining table? This action cannot be undone.', 'Yes, delete', function() {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                table.ajax.reload();
                showSuccessToast(response.message || 'Table deleted successfully');
            })
            .fail((errors) => {
                showErrorToast(errors.responseJSON?.message || 'Unable to delete table');
            });
        });
    }
</script>
@endpush
