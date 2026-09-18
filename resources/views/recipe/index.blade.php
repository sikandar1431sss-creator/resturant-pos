@extends('layouts.master')

@section('title')
    Recipes
@endsection

@push('css')
<style>
    /* Hide AdminLTE default duplicate content-header & breadcrumbs */
    .content-header {
        display: none !important;
    }

    .recipes-page-wrapper {
        margin-top: 5px;
    }

    /* Top Page Header */
    .recipes-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .recipes-heading {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 22px;
        letter-spacing: -0.02em;
    }

    /* KPI Cards Grid */
    .kpi-recipe-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .kpi-recipe-card {
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
    .kpi-recipe-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .kpi-recipe-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .kpi-recipe-val {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .kpi-recipe-lbl {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Modern Info / Guide Banner */
    .recipe-guide-banner {
        background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .recipe-guide-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #0284c7;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .recipe-guide-text {
        font-size: 13px;
        color: #0369a1;
        line-height: 1.5;
        margin: 0;
    }

    .recipe-guide-text strong {
        color: #0c4a6e;
        font-weight: 700;
    }

    /* Table & Container Card */
    .recipe-table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        padding: 20px;
    }

    .table-modern {
        width: 100% !important;
        margin-bottom: 0 !important;
    }

    .table-modern thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.03em !important;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 12px 14px !important;
        vertical-align: middle !important;
    }

    .table-modern tbody td {
        padding: 14px 14px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f1f5f9 !important;
        font-size: 13px;
        color: #334155;
    }

    .table-modern tbody tr:hover td {
        background: #f8fafc !important;
    }

    /* Badges & Pills */
    .badge-item-code {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-weight: 700;
        font-size: 11.5px;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .badge-category {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 11.5px;
        padding: 4px 9px;
        border-radius: 6px;
        display: inline-block;
    }

    .ingredient-pill {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        padding: 4px 9px;
        font-weight: 600;
        font-size: 11.5px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin: 2px;
        transition: all 0.15s ease;
    }
    .ingredient-pill:hover {
        background: #dcfce7;
        border-color: #86efac;
    }

    .direct-item-badge {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit-recipe {
        background: #ea580c !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 12.5px !important;
        border-radius: 7px !important;
        padding: 7px 14px !important;
        border: none !important;
        box-shadow: 0 1px 2px rgba(234, 88, 12, 0.2) !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-edit-recipe:hover {
        background: #c2410c !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(234, 88, 12, 0.3) !important;
    }

    /* ==========================================================================
       PROFESSIONAL EDIT RECIPE MODAL DESIGN (CLEAN LIGHT THEME)
       ========================================================================== */
    #modal-recipe .modal-dialog {
        max-width: 740px;
        margin: 40px auto;
    }

    #modal-recipe .modal-content {
        border-radius: 14px !important;
        overflow: hidden !important;
        border: none !important;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12) !important;
        background: #ffffff;
    }

    #modal-recipe .modal-header {
        background: #ffffff !important;
        color: #0f172a !important;
        padding: 18px 24px !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .modal-title-text {
        font-weight: 800;
        font-size: 16.5px;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-dish-tag {
        color: #ea580c;
        font-weight: 800;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: #64748b;
        font-size: 26px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
        transition: all 0.2s ease;
        outline: none;
    }
    .modal-close-btn:hover {
        color: #0f172a;
    }

    #modal-recipe .modal-body {
        padding: 22px 24px !important;
        background: #ffffff;
    }

    /* Recipe Table Inside Modal */
    .table-modal-recipe {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-bottom: 12px;
    }

    .table-modal-recipe thead th {
        font-size: 11.5px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.04em;
        padding: 4px 6px 8px 6px;
        border-bottom: 1px solid #e2e8f0;
    }

    .recipe-row-item td {
        vertical-align: middle;
        padding: 4px 6px;
    }

    .recipe-ingredient-select {
        height: 42px !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        background: #ffffff !important;
        padding: 8px 12px !important;
        transition: all 0.2s ease !important;
        width: 100%;
    }
    .recipe-ingredient-select:focus {
        border-color: #ea580c !important;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12) !important;
    }

    .recipe-qty-input {
        height: 42px !important;
        border-radius: 8px 0 0 8px !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        color: #0f172a !important;
        padding: 8px 12px !important;
        transition: all 0.2s ease !important;
    }
    .recipe-qty-input:focus {
        border-color: #ea580c !important;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12) !important;
    }

    .recipe-unit-addon {
        background: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
        border-left: none !important;
        border-radius: 0 8px 8px 0 !important;
        font-weight: 700 !important;
        font-size: 12.5px !important;
        color: #475569 !important;
        min-width: 65px;
        text-align: center;
        padding: 0 12px;
    }

    .btn-recipe-delete {
        width: 38px;
        height: 38px;
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
        margin: 0;
    }
    .btn-recipe-delete:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

    /* Add Row Button */
    .btn-add-ingredient-bar {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        color: #ea580c;
        font-weight: 700;
        font-size: 13.5px;
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        margin-top: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-add-ingredient-bar:hover {
        background: #fff7ed;
        border-color: #ea580c;
        color: #c2410c;
    }

    #modal-recipe .modal-footer {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 16px 24px !important;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .btn-modal-cancel {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #475569 !important;
        font-weight: 700 !important;
        padding: 9px 20px !important;
        border-radius: 8px !important;
        transition: all 0.2s ease;
    }
    .btn-modal-cancel:hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
    }

    .btn-modal-save {
        background: #ea580c !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        border: none !important;
        padding: 9px 24px !important;
        border-radius: 8px !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25) !important;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-modal-save:hover {
        background: #c2410c !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(234, 88, 12, 0.35) !important;
    }
</style>
@endpush

@section('content')
<div class="recipes-page-wrapper">
    <!-- Top Page Header -->
    <div class="recipes-top-bar">
        <div>
            <h2 class="recipes-heading">Recipes</h2>
        </div>
    </div>

    <!-- KPI Summary Row -->
    @php
        $totalDishes = $produk->count();
        $withRecipes = $produk->filter(fn($p) => $p->recipes->isNotEmpty())->count();
        $directItems = $totalDishes - $withRecipes;
        $totalRawMaterials = $rawMaterials->count();
    @endphp
    <div class="kpi-recipe-grid">
        <div class="kpi-recipe-card">
            <div class="kpi-recipe-icon" style="background: #eff6ff; color: #2563eb;">
                <i class="fa fa-cutlery"></i>
            </div>
            <div>
                <div class="kpi-recipe-val">{{ $totalDishes }}</div>
                <div class="kpi-recipe-lbl">Total Menu Items</div>
            </div>
        </div>

        <div class="kpi-recipe-card">
            <div class="kpi-recipe-icon" style="background: #f0fdf4; color: #16a34a;">
                <i class="fa fa-check-circle"></i>
            </div>
            <div>
                <div class="kpi-recipe-val" style="color: #16a34a;">{{ $withRecipes }}</div>
                <div class="kpi-recipe-lbl">Set Recipe</div>
            </div>
        </div>

        <div class="kpi-recipe-card">
            <div class="kpi-recipe-icon" style="background: #fffbeb; color: #d97706;">
                <i class="fa fa-minus-circle"></i>
            </div>
            <div>
                <div class="kpi-recipe-val" style="color: #d97706;">{{ $directItems }}</div>
                <div class="kpi-recipe-lbl">Not Set Recipe</div>
            </div>
        </div>

        <div class="kpi-recipe-card">
            <div class="kpi-recipe-icon" style="background: #fdf2f8; color: #db2777;">
                <i class="fa fa-database"></i>
            </div>
            <div>
                <div class="kpi-recipe-val" style="color: #db2777;">{{ $totalRawMaterials }}</div>
                <div class="kpi-recipe-lbl">Raw Ingredients Available</div>
            </div>
        </div>
    </div>

    <!-- Informational Guide Banner -->
    <div class="recipe-guide-banner">
        <div class="recipe-guide-icon">
            <i class="fa fa-lightbulb-o"></i>
        </div>
        <div>
            <p class="recipe-guide-text">
                <strong>How Recipe Works:</strong> Define raw ingredients required to prepare 1 serving of each dish (e.g. <em>1 Zinger Burger = 1 Bun + 1 Patty + 1 Cheese Slice + 20g Sauce</em>). When an order is completed in POS, these exact quantities are automatically deducted from your raw stock in real-time.
            </p>
        </div>
    </div>

    <!-- Recipe Table Card -->
    <div class="recipe-table-card">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-modern datatable-recipes" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="10%">Item Code</th>
                        <th width="22%">Dish / Menu Item</th>
                        <th width="12%">Category</th>
                        <th width="12%">Selling Price</th>
                        <th>Recipe Ingredients</th>
                        <th width="12%" style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produk as $index => $item)
                    <tr>
                        <td>
                            <strong style="color: #64748b;">{{ $index + 1 }}</strong>
                        </td>
                        <td>
                            <span class="badge-item-code">{{ $item->kode_produk }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">
                                {{ $item->nama_produk }}
                            </div>
                        </td>
                        <td>
                            <span class="badge-category">{{ $item->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                        </td>
                        <td>
                            <strong style="color: #ea580c; font-size: 13.5px;">{{ format_currency($item->harga_jual) }}</strong>
                        </td>
                        <td>
                            @if($item->recipes->isNotEmpty())
                                <div style="display: flex; flex-wrap: wrap; gap: 4px; align-items: center;">
                                    @foreach($item->recipes as $r)
                                        <span class="ingredient-pill">
                                            <i class="fa fa-check" style="font-size: 9px; color: #16a34a;"></i>
                                            <strong>{{ $r->jumlah }} {{ $r->rawMaterial->satuan ?? '' }}</strong>
                                            <span>{{ $r->rawMaterial->nama_material ?? 'Ingredient' }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="direct-item-badge">
                                    <i class="fa fa-minus-circle text-muted"></i> Not Set Recipe
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <button type="button" onclick="openRecipeModal({{ $item->id_produk }}, `{{ addslashes($item->nama_produk) }}`)" class="btn-edit-recipe">
                                <i class="fa fa-pencil-square-o"></i> Edit Recipe
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- PROFESSIONAL RECIPE BUILDER MODAL (CLEAN LIGHT THEME) -->
<div class="modal fade" id="modal-recipe" tabindex="-1" role="dialog" aria-labelledby="modal-recipe">
    <div class="modal-dialog" role="document">
        <form id="recipe-form" action="" method="post">
            @csrf
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title-text">
                        <i class="fa fa-book" style="color: #ea580c;"></i> Configure Recipe: <span id="modal-dish-name" class="modal-dish-tag"></span>
                    </h4>
                    <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Ingredients Table -->
                    <div class="table-responsive" style="overflow-x: visible;">
                        <table class="table-modal-recipe" id="recipe-table">
                            <thead>
                                <tr>
                                    <th style="width: 58%; padding-left: 0;">Raw Ingredient / Material</th>
                                    <th style="width: 34%;">Quantity</th>
                                    <th style="width: 8%; text-align: right; padding-right: 0;"></th>
                                </tr>
                            </thead>
                            <tbody id="recipe-rows">
                                <!-- Populated dynamically via Javascript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Ingredient Button -->
                    <button type="button" class="btn-add-ingredient-bar" onclick="addIngredientRow()">
                        <i class="fa fa-plus-circle" style="font-size: 16px;"></i> Add Another Ingredient
                    </button>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="btnSaveRecipe">
                        <i class="fa fa-check-circle"></i> Save Recipe
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const availableIngredients = @json($rawMaterials);
    let currentRowIndex = 0;

    $(function() {
        $('.datatable-recipes').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search dishes or codes...",
            }
        });

        $('#recipe-form').on('submit', function(e) {
            e.preventDefault();
            const actionUrl = $(this).attr('action');
            const $saveBtn = $('#btnSaveRecipe');
            $saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#modal-recipe').modal('hide');
                    showSuccessToast(res.message || 'Recipe updated successfully!');
                    setTimeout(() => location.reload(), 700);
                },
                error: function() {
                    $saveBtn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> Save Recipe');
                    showErrorToast('Unable to save recipe. Please check your inputs.');
                }
            });
        });
    });

    function openRecipeModal(productId, productName) {
        $('#modal-dish-name').text(productName);
        $('#recipe-form').attr('action', `/recipe/${productId}/save`);
        $('#recipe-rows').empty();
        $('#btnSaveRecipe').prop('disabled', false).html('<i class="fa fa-check-circle"></i> Save Recipe');
        currentRowIndex = 0;

        // Fetch current recipe
        $.get(`/recipe/${productId}/get`, function(data) {
            if (data.recipes && data.recipes.length > 0) {
                data.recipes.forEach(r => {
                    addIngredientRow(r.id_raw_material, r.jumlah);
                });
            } else {
                // Add 1 empty row by default
                addIngredientRow();
            }
            $('#modal-recipe').modal('show');
        }).fail(function() {
            showErrorToast('Failed to load recipe data');
        });
    }

    function addIngredientRow(selectedId = '', quantity = 1) {
        let options = '<option value="">-- Select Raw Ingredient --</option>';
        availableIngredients.forEach(ing => {
            const isSelected = ing.id == selectedId ? 'selected' : '';
            options += `<option value="${ing.id}" data-unit="${ing.satuan}" ${isSelected}>${ing.nama_material} (${ing.satuan})</option>`;
        });

        const row = `
            <tr id="recipe-row-${currentRowIndex}" class="recipe-row-item">
                <td style="padding-left: 0;">
                    <select name="ingredients[${currentRowIndex}][id_raw_material]" class="form-control recipe-ingredient-select" required onchange="updateUnitLabel(this, ${currentRowIndex})">
                        ${options}
                    </select>
                </td>
                <td>
                    <div class="input-group" style="width: 100%;">
                        <input type="number" step="any" min="0.001" name="ingredients[${currentRowIndex}][jumlah]" class="form-control recipe-qty-input" value="${quantity}" required placeholder="0.00">
                        <span class="input-group-addon recipe-unit-addon unit-label-${currentRowIndex}">Qty</span>
                    </div>
                </td>
                <td style="text-align: right; padding-right: 0;">
                    <button type="button" class="btn-recipe-delete" onclick="removeRow(${currentRowIndex})" title="Remove Ingredient">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#recipe-rows').append(row);
        const addedSelect = $(`#recipe-row-${currentRowIndex} .recipe-ingredient-select`);
        updateUnitLabel(addedSelect[0], currentRowIndex);
        currentRowIndex++;
    }

    function updateUnitLabel(selectElem, index) {
        const selectedOption = $(selectElem).find(':selected');
        const unit = selectedOption.data('unit') || 'Unit';
        $(`.unit-label-${index}`).text(unit);
    }

    function removeRow(index) {
        $(`#recipe-row-${index}`).remove();
    }
</script>
@endpush
