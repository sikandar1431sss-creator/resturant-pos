<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
                    <h4 class="modal-title" style="font-weight: 800; font-size: 16px; color: #0f172a; margin: 0;">
                        <i class="fa fa-cubes" style="color: #ea580c; margin-right: 6px;"></i> Add Raw Material
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #64748b; font-size: 26px; border: none; background: none; margin: 0; outline: none;">&times;</button>
                </div>
                <div class="modal-body" style="padding: 24px 28px;">
                    <div class="form-group row">
                        <label for="nama_material" class="col-lg-3 control-label" style="font-weight: 700; color: #1e293b;">Material Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" name="nama_material" id="nama_material" class="form-control" placeholder="e.g. Burger Buns, Chicken Fillet, Cheese Slice, Cooking Oil" required autofocus style="border-radius: 8px; height: 42px; font-weight: 600;">
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="satuan" class="col-lg-3 control-label" style="font-weight: 700; color: #1e293b;">Unit of Measure <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select name="satuan" id="satuan" class="form-control" required style="border-radius: 8px; height: 42px; font-weight: 600;">
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
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="min_stok" class="col-lg-3 control-label" style="font-weight: 700; color: #1e293b;">Low Stock Alert <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="number" step="any" name="min_stok" id="min_stok" class="form-control" required value="5" min="0" style="border-radius: 8px; height: 42px; font-weight: 700;">
                            <small class="text-muted" style="font-size: 11.5px;">Notify when stock drops to or below this threshold.</small>
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="harga_beli" class="col-lg-3 control-label" style="font-weight: 700; color: #1e293b;">Default Cost Price</label>
                        <div class="col-lg-9">
                            <input type="number" step="any" name="harga_beli" id="harga_beli" class="form-control" value="0" min="0" style="border-radius: 8px; height: 42px; font-weight: 700;">
                            <small class="text-muted" style="font-size: 11.5px;">Standard cost per 1 unit (used for recipe dish cost estimation).</small>
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="stok" class="col-lg-3 control-label" style="font-weight: 700; color: #1e293b;">Current Stock</label>
                        <div class="col-lg-9">
                            <input type="number" step="any" name="stok" id="stok" class="form-control" value="0" min="0" style="border-radius: 8px; height: 42px; font-weight: 700;">
                            <small class="text-muted" style="font-size: 11.5px;"><i class="fa fa-info-circle text-primary"></i> Note: Stock is automatically added &amp; tracked with invoice numbers via <strong>Purchases</strong>.</small>
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700; padding: 9px 20px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #ea580c !important; border: none !important; border-radius: 8px; font-weight: 800; padding: 9px 24px; box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);">
                        <i class="fa fa-check-circle"></i> Save Material
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
