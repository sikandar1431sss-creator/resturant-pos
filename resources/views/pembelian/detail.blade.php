<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <h4 class="modal-title" style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0;">
                        Purchase Order Details
                    </h4>
                    <span id="detailPurchasePoNum" class="label label-info" style="font-size: 12px; padding: 4px 10px; border-radius: 6px; font-weight: 700; background: #e0f2fe !important; color: #0284c7 !important; border: 1px solid #bae6fd;">#PO</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a id="btnDetailPrintThermal" href="#" target="_blank" class="btn btn-sm btn-default btn-flat" style="border-radius: 6px; font-weight: 700; color: #0284c7; border-color: #cbd5e1; background: #f8fafc; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa fa-print"></i> Print Thermal Slip
                    </a>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 24px; font-weight: 400; color: #94a3b8; opacity: 1; transition: color 0.15s ease; padding: 0; margin: 0; line-height: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding: 20px 24px; background: #ffffff;">
                <table class="table table-striped table-bordered table-detail" style="margin-bottom: 0;">
                    <thead style="background: #f8fafc;">
                        <th width="5%">#</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Cost Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </thead>
                </table>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 24px;">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Close</button>
            </div>
        </div>
    </div>
</div>