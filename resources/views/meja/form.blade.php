<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-md" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; padding: 16px 20px;">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; color: #fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="font-weight: 700; font-size: 16px;"><i class="fa fa-cutlery"></i> Seating</h4>
                </div>
                <div class="modal-body" style="padding: 24px 28px;">
                    
                    <div class="form-group row">
                        <label for="nomor_meja" class="col-md-4 col-form-label" style="font-weight: 600; color: #334155; padding-top: 8px;">Table Name / Number <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" name="nomor_meja" id="nomor_meja" class="form-control" placeholder="e.g. Table 1, Table 2, Rooftop 1" style="border-radius: 6px; border: 1px solid #cbd5e1; height: 38px;" required autofocus>
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kapasitas" class="col-md-4 col-form-label" style="font-weight: 600; color: #334155; padding-top: 8px;">Seating Capacity <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="number" name="kapasitas" id="kapasitas" class="form-control" placeholder="4" min="1" max="50" style="border-radius: 6px 0 0 6px; border: 1px solid #cbd5e1; height: 38px;" required>
                                <span class="input-group-addon" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-left: none; border-radius: 0 6px 6px 0; font-weight: 600; color: #475569;">Seats</span>
                            </div>
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-md-4 col-form-label" style="font-weight: 600; color: #334155; padding-top: 8px;">Initial Status</label>
                        <div class="col-md-8">
                            <select name="status" id="status" class="form-control" style="border-radius: 6px; border: 1px solid #cbd5e1; height: 38px;">
                                <option value="available">Available (Free for customers)</option>
                                <option value="occupied">Occupied (Currently in use)</option>
                                <option value="reserved">Reserved (Booked in advance)</option>
                            </select>
                            <span class="help-block with-errors text-danger" style="font-size: 12px;"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 20px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; font-weight: 600; padding: 7px 16px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #0284c7; border-color: #0284c7; border-radius: 6px; font-weight: 700; padding: 7px 20px;">
                        <i class="fa fa-save"></i> Save Seating
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
