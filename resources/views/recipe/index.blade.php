@extends('layouts.master')

@section('title')
    Dish Recipes & Ingredients Mapping (BOM)
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dish Recipes (BOM)</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary" style="border-radius: 8px; border-top: 3px solid #0284c7;">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: 700;"><i class="fa fa-book text-primary"></i> Fast Food Menu Items Recipe Configuration</h3>
                <a href="{{ route('raw_material.index') }}" class="btn btn-default pull-right" style="border-radius: 6px;"><i class="fa fa-cubes"></i> View Raw Materials Stock</a>
            </div>
            <div class="box-body">
                <div class="alert alert-info" style="border-radius: 6px; font-size: 13px;">
                    <i class="fa fa-info-circle"></i> <strong>How it works:</strong> Define the raw ingredients required to make 1 serving of each dish (e.g., 1 Zinger Burger = 1 Bun + 1 Patty + 1 Cheese Slice + 20g Sauce). When this item is sold in POS, these exact quantities will be auto-deducted from your inventory in real-time!
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover datatable-recipes">
                        <thead>
                            <th width="5%">#</th>
                            <th>Item Code</th>
                            <th>Dish / Menu Item</th>
                            <th>Category</th>
                            <th>Selling Price</th>
                            <th>Linked Ingredients (Recipe)</th>
                            <th width="15%"><i class="fa fa-cog"></i> Action</th>
                        </thead>
                        <tbody>
                            @foreach($produk as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="label label-info">{{ $item->kode_produk }}</span></td>
                                <td>
                                    <strong style="font-size: 14px;">{{ $item->nama_produk }}</strong>
                                </td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td><strong>{{ format_currency($item->harga_jual) }}</strong></td>
                                <td>
                                    @if($item->recipes->isNotEmpty())
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                            @foreach($item->recipes as $r)
                                                <span class="badge" style="background: #0369a1; color: #fff; padding: 4px 8px; font-weight: 600; border-radius: 4px;">
                                                    {{ $r->jumlah }} {{ $r->rawMaterial->satuan ?? '' }} {{ $r->rawMaterial->nama_material ?? 'Item' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-style: italic;"><i class="fa fa-cube"></i> Direct Item (No recipe - direct stock deduction)</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" onclick="openRecipeModal({{ $item->id_produk }}, `{{ $item->nama_produk }}`)" class="btn btn-sm btn-primary btn-flat" style="border-radius: 4px;">
                                        <i class="fa fa-edit"></i> Edit Recipe
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recipe Builder Modal -->
<div class="modal fade" id="modal-recipe" tabindex="-1" role="dialog" aria-labelledby="modal-recipe">
    <div class="modal-dialog modal-lg" role="document">
        <form id="recipe-form" action="" method="post">
            @csrf
            <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                <div class="modal-header" style="background: #1e293b; color: #fff;">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title font-weight-bold"><i class="fa fa-book"></i> Configure Recipe: <span id="modal-dish-name" class="text-warning"></span></h4>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Specify the ingredients needed to prepare <strong>1 single portion/serving</strong> of this dish:</p>
                    
                    <table class="table table-bordered" id="recipe-table">
                        <thead style="background: #f1f5f9;">
                            <tr>
                                <th style="width: 55%;">Raw Ingredient / Material</th>
                                <th style="width: 30%;">Quantity per 1 Dish</th>
                                <th style="width: 15%; text-align: center;"><i class="fa fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="recipe-rows">
                            <!-- Populated via Javascript -->
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-info btn-sm" onclick="addIngredientRow()" style="border-radius: 4px;">
                        <i class="fa fa-plus"></i> Add Another Ingredient
                    </button>
                </div>
                <div class="modal-footer" style="background: #f8fafc;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Recipe</button>
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
            autoWidth: false
        });

        $('#recipe-form').on('submit', function(e) {
            e.preventDefault();
            const actionUrl = $(this).attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#modal-recipe').modal('hide');
                    showSuccessToast(res.message || 'Recipe updated successfully!');
                    setTimeout(() => location.reload(), 800);
                },
                error: function() {
                    showErrorToast('Unable to save recipe. Please check your inputs.');
                }
            });
        });
    });

    function openRecipeModal(productId, productName) {
        $('#modal-dish-name').text(productName);
        $('#recipe-form').attr('action', `/recipe/${productId}/save`);
        $('#recipe-rows').empty();
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
            <tr id="recipe-row-${currentRowIndex}">
                <td>
                    <select name="ingredients[${currentRowIndex}][id_raw_material]" class="form-control ingredient-select" required onchange="updateUnitLabel(this, ${currentRowIndex})" style="border-radius: 4px;">
                        ${options}
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" step="any" min="0.001" name="ingredients[${currentRowIndex}][jumlah]" class="form-control" value="${quantity}" required style="border-radius: 4px 0 0 4px;">
                        <span class="input-group-addon unit-label-${currentRowIndex}" style="font-weight: bold; background: #f8fafc;">Qty</span>
                    </div>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${currentRowIndex})"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

        $('#recipe-rows').append(row);
        const addedSelect = $(`#recipe-row-${currentRowIndex} .ingredient-select`);
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
