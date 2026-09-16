<!-- CREATE / EDIT DEAL MODAL -->
<div class="modal fade" id="modal-deal-form" tabindex="-1" role="dialog" aria-labelledby="modal-deal-form">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #1e3a68; color: #fff; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="modal-title" id="dealModalTitle" style="font-weight: 800; font-size: 16px; color: #fff;">
                    <i class="fa fa-plus-circle"></i> Create New Deal
                </h4>
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9; margin: 0;">&times;</button>
            </div>
            <form id="dealForm" onsubmit="submitDealForm(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="dealFormId" name="id">
                <input type="hidden" id="dealFormMethod" name="_method" value="POST">

                <div class="modal-body" style="padding: 22px;">
                    <!-- Top section: Deal Basic Info -->
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label style="font-size: 12.5px; font-weight: 700; color: #1e293b;">Deal Name <span class="text-danger">*</span></label>
                                <input type="text" id="deal_nama" name="nama_deal" class="form-control" placeholder="e.g. Zinger Feast Deal, Family Mega Deal, Lunch Deal 1" required style="border-radius: 8px; font-weight: 700; height: 42px;">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label style="font-size: 12.5px; font-weight: 700; color: #1e293b;">Deal Code / SKU</label>
                                <input type="text" id="deal_kode" name="kode_deal" class="form-control" placeholder="Leave empty for auto-generated" style="border-radius: 8px; font-weight: 700; height: 42px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size: 12.5px; font-weight: 700; color: #1e293b;">Selling Price ({{ get_currency_symbol() }}) <span class="text-danger">*</span></label>
                                <input type="number" step="any" id="deal_harga_jual" name="harga_jual" class="form-control" placeholder="0" required min="0" style="border-radius: 8px; font-weight: 800; font-size: 15px; color: #ea580c; height: 42px;">
                                <small class="text-muted" style="font-size: 11px;">Regular Items Total: <strong id="lblItemsSellingTotal" style="color: #2563eb;">{{ get_currency_symbol() }} 0</strong></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size: 12.5px; font-weight: 700; color: #1e293b;"><i class="fa fa-lock text-muted"></i> Cost Price (Auto Calculated)</label>
                                <input type="number" step="any" id="deal_cost_price" name="cost_price" class="form-control" placeholder="0" min="0" readonly style="border-radius: 8px; font-weight: 800; height: 42px; background: #f1f5f9; color: #334155; cursor: not-allowed;">
                                <small class="text-muted" style="font-size: 11px;">Calculated Est. Cost: <strong id="lblEstimatedCost" style="color: #059669;">{{ get_currency_symbol() }} 0</strong></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size: 12.5px; font-weight: 700; color: #1e293b;">Deal Photo / Banner</label>
                                <input type="file" id="deal_foto" name="foto" class="form-control" accept="image/*" style="border-radius: 8px;">
                                <div id="tampil-foto-deal"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 12.5px; font-weight: 700; color: #1e293b;">Deal Description / Highlights</label>
                        <textarea id="deal_deskripsi" name="deskripsi" class="form-control" rows="2" placeholder="e.g. 1 Large Zinger Burger, 1 Regular French Fries, and 1 Chilled 300ml Soft Drink." style="border-radius: 8px; font-size: 12.5px;"></textarea>
                    </div>

                    <!-- Included Dishes Box -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-top: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <div>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0;">
                                    <i class="fa fa-list text-warning"></i> Included Menu Dishes &amp; Quantities
                                </h4>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                                    Add all the dishes that form this deal package. Inventory recipe stocks for all dishes will automatically be deducted upon sale.
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary btn-flat" onclick="addDishItemRow()" style="border-radius: 6px; font-weight: 700;">
                                    <i class="fa fa-plus"></i> Add Dish to Deal
                                </button>
                            </div>
                        </div>

                        <!-- Dynamic Items Container -->
                        <div id="dealItemsContainer">
                            <!-- Injected by JavaScript -->
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 16px; margin-bottom: 0;">
                        <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="deal_status" name="status" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                            <strong style="font-size: 13px; color: #0f172a;">Active in POS (Available for Ordering)</strong>
                        </label>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 22px; display: flex; justify-content: space-between; align-items: center;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-success btn-flat" style="border-radius: 6px; font-weight: 800; padding: 9px 24px;">
                        <i class="fa fa-save"></i> Save &amp; Publish Deal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
