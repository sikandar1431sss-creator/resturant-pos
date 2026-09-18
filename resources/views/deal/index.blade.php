@extends('layouts.master')

@section('title')
    Deals
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Deals</li>
@endsection

@push('css')
<style>
    /* Hide AdminLTE default duplicate content-header & breadcrumbs */
    .content-header {
        display: none !important;
    }

    .deals-page-wrapper {
        margin-top: 5px;
    }

    /* Top Page Header */
    .deals-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .deals-heading {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 22px;
        letter-spacing: -0.02em;
    }

    .btn-create-deal {
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
        cursor: pointer;
    }
    .btn-create-deal:hover {
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
        font-size: 13px !important;
        border: 1px solid transparent !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        padding: 0 !important;
    }
    .btn-table-action.btn-view {
        background: #0284c7 !important;
        color: #fff !important;
    }
    .btn-table-action.btn-edit {
        background: #2563eb !important;
        color: #fff !important;
    }
    .btn-table-action.btn-delete {
        background: #ef4444 !important;
        color: #fff !important;
    }
    .btn-table-action:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="deals-page-wrapper">
    <!-- Top Page Header -->
    <div class="deals-top-bar">
        <div>
            <h2 class="deals-heading">Deals</h2>
        </div>
        <div>
            <button onclick="addDealForm()" class="btn-create-deal">
                <i class="fa fa-plus-circle"></i> Add New Deal
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="box" style="border-top: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden;">
                <div class="box-body table-responsive" style="padding: 20px;">
                    <table class="table table-striped table-deals table-bordered table-hover" style="width: 100%;">
                        <thead>
                            <th width="4%">#</th>
                            <th width="6%">Image</th>
                            <th width="22%">Description</th>
                            <th width="10%">Code</th>
                            <th>Items</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Status</th>
                            <th width="12%"><i class="fa fa-cog"></i> Action</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('deal.form')
@includeIf('deal.detail')
@endsection

@push('scripts')
<script>
    let tableDeals;
    let allProductsList = @json($products);

    $(function () {
        tableDeals = $('.table-deals').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('deal.data') }}',
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
                {data: 'foto', searchable: false, sortable: false},
                {data: 'nama_deal'},
                {data: 'kode_deal'},
                {data: 'items_summary', searchable: false, sortable: false},
                {data: 'cost_price'},
                {data: 'harga_jual'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });
    });

    function addDealForm() {
        $('#modal-deal-form').modal('show');
        $('#dealForm')[0].reset();
        $('#dealFormId').val('');
        $('#dealFormMethod').val('POST');
        $('#dealModalTitle').html('<i class="fa fa-plus-circle text-warning"></i> Create New Deal');
        $('#dealItemsContainer').empty();
        $('#tampil-foto-deal').html('');
        addDishItemRow(); // Add one initial dish row
        recalculateDealCost();
    }

    function editDealForm(id) {
        $.get(`{{ url('/deal') }}/${id}`)
            .done(deal => {
                $('#modal-deal-form').modal('show');
                $('#dealForm')[0].reset();
                $('#dealFormId').val(deal.id);
                $('#dealFormMethod').val('PUT');
                $('#dealModalTitle').html('<i class="fa fa-edit text-primary"></i> Edit Deal: ' + deal.nama_deal);

                $('#deal_nama').val(deal.nama_deal);
                $('#deal_kode').val(deal.kode_deal);
                $('#deal_harga_jual').val(deal.harga_jual);
                $('#deal_cost_price').val(deal.cost_price);
                $('#deal_deskripsi').val(deal.deskripsi || '');
                $('#deal_status').prop('checked', deal.status == 1);

                if (deal.foto) {
                    $('#tampil-foto-deal').html(`<img src="{{ url('/') }}/${deal.foto}" style="width:70px; height:70px; object-fit:cover; border-radius:8px; margin-top:6px; border:1px solid #cbd5e1;">`);
                } else {
                    $('#tampil-foto-deal').html('');
                }

                // Populate included items
                let container = $('#dealItemsContainer');
                container.empty();
                if (deal.items && deal.items.length > 0) {
                    deal.items.forEach((item, index) => {
                        addDishItemRow(item.id_produk, item.jumlah);
                    });
                } else {
                    addDishItemRow();
                }

                recalculateDealCost();
            })
            .fail(() => {
                showErrorToast('Failed to load deal data');
            });
    }

    function addDishItemRow(selectedProductId = '', quantity = 1) {
        let rowIndex = new Date().getTime() + Math.floor(Math.random() * 1000);
        let options = '<option value="">-- Choose Menu Dish --</option>';
        allProductsList.forEach(p => {
            let sel = (p.id_produk == selectedProductId) ? 'selected' : '';
            options += `<option value="${p.id_produk}" data-cost="${p.harga_beli}" data-price="${p.harga_jual}" ${sel}>${p.nama_produk} (${p.kode_produk || ''}) - Cost: {{ get_currency_symbol() }}${parseFloat(p.harga_beli || 0).toLocaleString()}</option>`;
        });

        let html = `
            <div class="deal-item-row" id="dishRow_${rowIndex}" style="display:flex; gap:10px; align-items:center; background:#f8fafc; padding:10px 12px; border-radius:8px; margin-bottom:8px; border:1px solid #e2e8f0;">
                <div style="flex:2;">
                    <select name="items[${rowIndex}][id_produk]" class="form-control deal-dish-select" required onchange="recalculateDealCost()" style="border-radius:6px; font-weight:600; font-size:13px; height:38px;">
                        ${options}
                    </select>
                </div>
                <div style="width:110px;">
                    <div class="input-group">
                        <span class="input-group-addon" style="font-weight:700; font-size:11px; padding:6px 8px;">Qty</span>
                        <input type="number" name="items[${rowIndex}][jumlah]" class="form-control deal-dish-qty" value="${quantity}" min="1" required oninput="recalculateDealCost()" style="border-radius:0 6px 6px 0; font-weight:700; height:38px; text-align:center;">
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-danger btn-flat" onclick="$('#dishRow_${rowIndex}').remove(); recalculateDealCost();" style="border-radius:6px; height:38px; width:38px;" title="Remove dish">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#dealItemsContainer').append(html);
        recalculateDealCost();
    }

    function recalculateDealCost() {
        let totalCost = 0;
        let totalSelling = 0;
        $('.deal-item-row').each(function () {
            let select = $(this).find('.deal-dish-select');
            let cost = parseFloat(select.find(':selected').data('cost')) || 0;
            let price = parseFloat(select.find(':selected').data('price')) || 0;
            let qty = parseInt($(this).find('.deal-dish-qty').val()) || 1;
            totalCost += (cost * qty);
            totalSelling += (price * qty);
        });

        $('#lblEstimatedCost').text('{{ get_currency_symbol() }} ' + totalCost.toLocaleString());
        $('#lblItemsSellingTotal').text('{{ get_currency_symbol() }} ' + totalSelling.toLocaleString());
        $('#deal_cost_price').val(totalCost);
    }

    function submitDealForm(e) {
        e.preventDefault();
        let id = $('#dealFormId').val();
        let url = id ? `{{ url('/deal') }}/${id}` : '{{ route('deal.store') }}';
        
        let formData = new FormData($('#dealForm')[0]);
        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#modal-deal-form').modal('hide');
                tableDeals.ajax.reload();
                showSuccessToast(response.message);
            },
            error: function (errors) {
                let msg = errors.responseJSON ? (errors.responseJSON.message || errors.responseJSON.error) : 'Failed to save deal';
                showErrorToast(msg);
            }
        });
    }

    function showDealDetails(id) {
        $.get(`{{ url('/deal') }}/${id}`)
            .done(deal => {
                $('#detailDealCode').text(deal.kode_deal);
                $('#detailDealTitle').text(deal.nama_deal);
                $('#detailDealPrice').text(deal.currency_symbol + ' ' + parseFloat(deal.harga_jual).toLocaleString());
                $('#detailDealCost').text(deal.currency_symbol + ' ' + parseFloat(deal.cost_price || deal.estimated_cost).toLocaleString());
                
                let profit = deal.harga_jual - (deal.cost_price || deal.estimated_cost);
                $('#detailDealProfit').text(deal.currency_symbol + ' ' + profit.toLocaleString());
                if (profit >= 0) {
                    $('#detailDealProfit').css('color', '#15803d');
                } else {
                    $('#detailDealProfit').css('color', '#b91c1c');
                }

                $('#detailDealDesc').text(deal.deskripsi && deal.deskripsi.trim() ? deal.deskripsi : 'No additional description provided.');
                
                if (deal.status == 1) {
                    $('#detailDealStatusBadge').text('Active').css({'background': '#dcfce7', 'color': '#15803d', 'border': '1px solid #86efac'});
                } else {
                    $('#detailDealStatusBadge').text('Inactive').css({'background': '#fee2e2', 'color': '#b91c1c', 'border': '1px solid #fca5a5'});
                }

                let itemsCount = (deal.items && deal.items.length) ? deal.items.length : 0;
                $('#detailItemsCountBadge').text(`${itemsCount} ${itemsCount === 1 ? 'Dish' : 'Dishes'}`);

                let itemsTable = $('#detailDealItemsList');
                itemsTable.empty();
                if (deal.items && deal.items.length > 0) {
                    deal.items.forEach((it, idx) => {
                        let dish = it.produk || {};
                        let codeTag = dish.kode_produk ? `<span style="font-size:11px; color:#64748b; font-weight:600; font-family:monospace; background:#f1f5f9; padding:1px 5px; border-radius:4px;">${dish.kode_produk}</span>` : '';
                        itemsTable.append(`
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding:12px 14px; vertical-align:middle; color:#64748b; font-weight:700;">${idx + 1}</td>
                                <td style="padding:12px 14px; vertical-align:middle;">
                                    <div style="font-weight:700; color:#0f172a; font-size:13px;">${dish.nama_produk || 'Dish'}</div>
                                    ${codeTag}
                                </td>
                                <td class="text-center" style="padding:12px 14px; vertical-align:middle;">
                                    <span class="badge" style="background:#0f172a; color:#fff; font-size:11.5px; font-weight:700; padding:4px 8px; border-radius:6px;">${it.jumlah}x</span>
                                </td>
                                <td class="text-right" style="padding:12px 16px; vertical-align:middle;">
                                    <span style="font-weight:700; color:#1e293b; font-size:13px;">${deal.currency_symbol} ${parseFloat(dish.harga_beli || 0).toLocaleString()}</span>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    itemsTable.html('<tr><td colspan="4" class="text-center text-muted" style="padding: 20px;">No dishes attached to this deal.</td></tr>');
                }

                $('#modal-deal-detail').modal('show');
            })
            .fail(() => {
                showErrorToast('Failed to load deal details');
            });
    }

    function deleteDeal(url) {
        showConfirmDialog('Delete Deal?', 'Are you sure you want to delete this deal? It will also be removed from the POS Terminal.', 'Yes, delete', function () {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done(response => {
                tableDeals.ajax.reload();
                showSuccessToast('Deal deleted successfully');
            })
            .fail(() => {
                showErrorToast('Failed to delete deal');
            });
        });
    }
</script>
@endpush
