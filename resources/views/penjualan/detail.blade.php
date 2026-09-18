<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #1e3a68; color: #fff; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="modal-title" style="font-weight: 800; font-size: 15px; color: #fff;"><i class="fa fa-list-alt text-warning"></i> Order Items &amp; Sales Detail <span id="detailInvoiceNum" style="color: #ffaa5a; font-weight: 900;"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; margin: 0;"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding: 16px;">
                <table class="table table-striped table-bordered table-detail table-hover" style="width: 100%;">
                    <thead>
                        <th width="5%">#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </thead>
                </table>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 18px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <a id="btnDetailEditPos" href="#" class="btn btn-sm btn-primary btn-flat" style="border-radius: 6px; font-weight: 700;">
                        <i class="fa fa-edit"></i> Edit Invoice
                    </a>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>