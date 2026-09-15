@extends('layouts.master')

@section('title')
    Raw Materials &amp; Ingredients Stock
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Raw Materials</li>
@endsection

@push('css')
<style>
    .raw-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 20px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
    }
    .btn-create-material {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 13.5px !important;
        border-radius: 8px !important;
        padding: 9px 18px !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35) !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-create-material:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.45) !important;
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
    .btn-table-action.btn-stock {
        background: #f59e0b !important;
        color: #fff !important;
    }
    .btn-table-action.btn-stock:hover {
        background: #d97706 !important;
        transform: translateY(-2px);
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
<div class="row">
    @if(isset($lowStockCount) && $lowStockCount > 0)
    <div class="col-lg-12">
        <div class="alert alert-warning alert-dismissible" style="border-radius: 8px; font-weight: 500; margin-bottom: 16px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> Low Stock Alert!</h4>
            There are <strong>{{ $lowStockCount }}</strong> raw ingredients at or below minimum threshold. Please re-order or adjust stock.
        </div>
    </div>
    @endif

    <div class="col-lg-12">
        <div class="box" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.04);">
            <div class="raw-card-header">
                <div>
                    <h3 class="box-title" style="font-weight: 800; font-size: 18px; color: #0f172a; margin: 0;">
                        <i class="fa fa-cubes" style="color: #8b5cf6; margin-right: 6px;"></i> Raw Materials &amp; Ingredients Stock
                    </h3>
                    <div style="font-size: 12.5px; color: #64748b; margin-top: 3px;">
                        Manage raw materials, inventory units (KG, Liter, Pieces), and stock alert thresholds.
                    </div>
                </div>
                <div>
                    <button onclick="addForm('{{ route('raw_material.store') }}')" class="btn-create-material">
                        <i class="fa fa-plus-circle"></i> Add New Raw Material
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive" style="padding: 18px;">
                <table class="table table-striped table-bordered table-hover" id="raw-materials-table" style="width: 100%;">
                    <thead>
                        <th width="4%">#</th>
                        <th width="10%">Code</th>
                        <th width="24%">Material / Ingredient Name</th>
                        <th width="10%">Unit</th>
                        <th width="20%">Stock Status</th>
                        <th width="12%">Min Alert</th>
                        <th width="10%">Cost/Unit</th>
                        <th width="10%"><i class="fa fa-cog"></i> Action</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('raw_material.form')

<!-- Quick Stock Adjustment Modal -->
<div class="modal fade" id="modal-adjust" tabindex="-1" role="dialog" aria-labelledby="modal-adjust">
    <div class="modal-dialog modal-sm" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                <div class="modal-header" style="background: #1e293b; color: #fff;">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title font-weight-bold"><i class="fa fa-cubes"></i> Adjust Stock</h4>
                </div>
                <div class="modal-body">
                    <p id="adjust-item-name" style="font-weight: 700; font-size: 15px; color: #0284c7;"></p>
                    <p>Current Stock: <strong id="adjust-current-stock"></strong></p>
                    <hr style="margin: 10px 0;">

                    <div class="form-group" style="padding: 0 15px;">
                        <label>Action Type</label>
                        <select name="type" class="form-control" required style="border-radius: 6px;">
                            <option value="add">➕ Add Stock (Purchase / In)</option>
                            <option value="subtract">➖ Subtract Stock (Wastage / Spoilage)</option>
                            <option value="set">🔄 Set Exact Stock (Audit Count)</option>
                        </select>
                    </div>

                    <div class="form-group" style="padding: 0 15px;">
                        <label>Quantity</label>
                        <input type="number" step="any" name="quantity" class="form-control" placeholder="e.g. 50" required min="0.01" style="border-radius: 6px;">
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update Stock</button>
                </div>
            </div>
        </form>
    </div>
</div>
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
            ajax: {
                url: '{{ route('raw_material.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
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
                        showSuccessToast('Raw Material saved successfully');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        showErrorToast('Unable to save raw material');
                        return;
                    });
            }
        });

        $('#modal-adjust form').on('submit', function (e) {
            e.preventDefault();
            $.post($(this).attr('action'), $(this).serialize())
                .done((res) => {
                    $('#modal-adjust').modal('hide');
                    showSuccessToast('Stock updated successfully');
                    table.ajax.reload();
                })
                .fail((err) => {
                    showErrorToast('Unable to adjust stock');
                });
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Raw Material / Ingredient');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_material]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Raw Material');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');

        $.get(url)
            .done((response) => {
                $('#modal-form [name=kode_material]').val(response.kode_material);
                $('#modal-form [name=nama_material]').val(response.nama_material);
                $('#modal-form [name=satuan]').val(response.satuan);
                $('#modal-form [name=stok]').val(response.stok);
                $('#modal-form [name=min_stok]').val(response.min_stok);
                $('#modal-form [name=harga_beli]').val(response.harga_beli);
            })
            .fail((errors) => {
                showErrorToast('Unable to display raw material data');
                return;
            });
    }

    function adjustStock(url, name, currentStock, unit) {
        $('#modal-adjust').modal('show');
        $('#modal-adjust form').attr('action', url);
        $('#modal-adjust form')[0].reset();
        $('#adjust-item-name').text(name);
        $('#adjust-current-stock').text(currentStock + ' ' + unit);
    }

    function deleteData(url) {
        showConfirmDialog('Delete Raw Material?', 'Are you sure you want to delete this ingredient? If it is linked to recipes, those recipes will also be affected.', 'Yes, delete', function() {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    showSuccessToast('Raw Material deleted successfully');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    showErrorToast('Cannot delete raw material');
                });
        });
    }
</script>
@endpush
