<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                <div class="modal-header" style="background: #1e293b; color: #fff;">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title font-weight-bold">Add Raw Material / Ingredient</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama_material" class="col-lg-3 control-label">Material / Ingredient Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" name="nama_material" id="nama_material" class="form-control" placeholder="e.g. Burger Buns, Chicken Patty, Cheese Slice, Mayo, Coke Can" required autofocus style="border-radius: 6px;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="satuan" class="col-lg-3 control-label">Unit of Measure <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select name="satuan" id="satuan" class="form-control" required style="border-radius: 6px;">
                                @if(isset($units) && $units->isNotEmpty())
                                    @foreach($units as $u)
                                        <option value="{{ $u->nama_satuan }}">{{ ucfirst($u->nama_satuan) }} ({{ $u->simbol ?: $u->nama_satuan }}) - {{ $u->deskripsi }}</option>
                                    @endforeach
                                @else
                                    <option value="kg">Kilogram (kg)</option>
                                    <option value="gram">Grams (g)</option>
                                    <option value="liter">Liters (L)</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="piece">Pieces (pcs)</option>
                                    <option value="box">Box / Carton (box)</option>
                                    <option value="pack">Packet (pack)</option>
                                    <option value="can">Can (can)</option>
                                    <option value="bottle">Bottle (btl)</option>
                                    <option value="dozen">Dozen (doz)</option>
                                @endif
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="stok" class="col-lg-3 control-label">Initial Current Stock <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="number" step="any" name="stok" id="stok" class="form-control" required value="0" min="0" style="border-radius: 6px;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="min_stok" class="col-lg-3 control-label">Low Stock Alert Threshold <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="number" step="any" name="min_stok" id="min_stok" class="form-control" required value="5" min="0" style="border-radius: 6px;">
                            <small class="text-muted">System will notify you when stock drops to or below this quantity.</small>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="harga_beli" class="col-lg-3 control-label">Cost Price / Unit</label>
                        <div class="col-lg-9">
                            <input type="number" step="any" name="harga_beli" id="harga_beli" class="form-control" value="0" min="0" style="border-radius: 6px;">
                            <small class="text-muted">Optional: Used to calculate dish food costs.</small>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc;">
                    <button class="btn btn-default btn-flat" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success btn-flat"><i class="fa fa-save"></i> Save Material</button>
                </div>
            </div>
        </form>
    </div>
</div>
