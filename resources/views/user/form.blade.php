<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal" id="userForm">
            @csrf
            @method('post')

            <div class="modal-content" style="border-radius: 12px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8; font-size: 22px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" style="font-weight: 700; font-size: 17px; margin: 0;"></h4>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group row">
                        <label for="name" class="col-lg-3 col-lg-offset-1 control-label" style="font-weight: 600; color: #1e293b;">Full Name <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="name" id="name" class="form-control" style="border-radius: 6px;" required autofocus placeholder="e.g. Bilal Ahmed">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-lg-3 col-lg-offset-1 control-label" style="font-weight: 600; color: #1e293b;">Email Address <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="email" name="email" id="email" class="form-control" style="border-radius: 6px;" required placeholder="staff@restaurant.com">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-lg-3 col-lg-offset-1 control-label" style="font-weight: 600; color: #1e293b;">Assigned Role <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <select name="role" id="role" class="form-control" style="border-radius: 6px; font-weight: 600;" required>
                                @if(isset($roles))
                                    @foreach($roles as $r)
                                        @php
                                            $desc = '';
                                            if ($r->name === 'admin') $desc = 'Admin / Owner (Full System Control & Settings)';
                                            elseif ($r->name === 'manager') $desc = 'Branch Manager (All Cashiers Sales, Reports, Inventory)';
                                            elseif ($r->name === 'cashier') $desc = 'Counter Cashier (POS Billing, KOT & Own Shift Invoices)';
                                            elseif ($r->name === 'kitchen') $desc = 'Kitchen Chef (KDS Monitor & Bump Status)';
                                            elseif ($r->name === 'waiter') $desc = 'Dine-In Waiter (Table Orders & Drafts)';
                                            else $desc = ucfirst(str_replace('_', ' ', $r->name)) . ' (Custom Role)';
                                        @endphp
                                        <option value="{{ $r->name }}">{{ $desc }}</option>
                                    @endforeach
                                @else
                                    <option value="cashier">Counter Cashier (POS Billing, KOT & Own Shift Invoices)</option>
                                    <option value="manager">Branch Manager (All Cashiers Sales, Reports, Inventory)</option>
                                    <option value="admin">Admin / Owner (Full System Control & Settings)</option>
                                    <option value="kitchen">Kitchen Chef (KDS Monitor & Bump Status)</option>
                                    <option value="waiter">Dine-In Waiter (Table Orders & Drafts)</option>
                                @endif
                            </select>
                            <small class="text-muted" style="margin-top: 4px; display: block;">
                                Determines dashboard access and multi-cashier data scoping.
                            </small>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-lg-3 col-lg-offset-1 control-label" style="font-weight: 600; color: #1e293b;">Password</label>
                        <div class="col-lg-7">
                            <input type="password" name="password" id="password" class="form-control" style="border-radius: 6px;" placeholder="Minimum 6 characters">
                            <small class="text-muted" id="password-help">Leave blank to keep existing password when editing.</small>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation" class="col-lg-3 col-lg-offset-1 control-label" style="font-weight: 600; color: #1e293b;">Confirm Password</label>
                        <div class="col-lg-7">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" style="border-radius: 6px;" placeholder="Re-type password">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" style="border-radius: 6px;">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat" id="btn-save-user" style="background: #ea580c; border-color: #ea580c; border-radius: 6px; font-weight: 700; padding: 6px 18px;">
                        <i class="fa fa-save"></i> Save Staff User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>