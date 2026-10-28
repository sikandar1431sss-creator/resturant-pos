<div class="modal fade" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="modal-member">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <!-- Modal Header -->
            <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="modal-title" style="margin: 0; font-weight: 800; color: #0f172a; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-users" style="color: #ff521d;"></i> Select Customer / Member
                </h4>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <button type="button" class="btn btn-success btn-sm btn-flat" id="btnToggleCustomerForm" onclick="toggleQuickCustomerForm()" style="border-radius: 6px; font-weight: 700; font-size: 12px; padding: 6px 14px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);">
                        Add New Customer
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 22px; opacity: 0.6; margin-left: 6px; margin-top: -2px;">&times;</button>
                </div>
            </div>

            <div class="modal-body" style="padding: 16px 20px; background: #ffffff;">
                <!-- QUICK ADD CUSTOMER PANEL (COLLAPSIBLE) -->
                <div id="quickAddCustomerPanel" style="display: none; background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 10px; padding: 16px; margin-bottom: 16px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.08);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h5 style="margin: 0; font-weight: 800; font-size: 13.5px; color: #166534; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-user-plus" style="color: #15803d;"></i> Quick Register New Customer for Invoice
                        </h5>
                        <button type="button" onclick="toggleQuickCustomerForm(false)" class="btn btn-xs btn-default" style="border: none; background: transparent; color: #64748b; font-size: 16px;" title="Close form">&times;</button>
                    </div>

                    <div id="quickCustAlert" style="display: none; padding: 8px 12px; border-radius: 6px; margin-bottom: 10px; font-size: 12px; font-weight: 600;"></div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label style="font-size: 11.5px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Customer Name <span style="color:#ef4444;">*</span></label>
                                <input type="text" id="quick_cust_nama" class="form-control input-sm" placeholder="e.g. Haider / Ali Khan" style="border-radius: 6px; border: 1px solid #cbd5e1; font-weight: 600;">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label style="font-size: 11.5px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Telephone / Mobile</label>
                                <input type="text" id="quick_cust_telepon" class="form-control input-sm" placeholder="0344-0809116" style="border-radius: 6px; border: 1px solid #cbd5e1;">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label style="font-size: 11.5px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Address (Optional)</label>
                                <input type="text" id="quick_cust_alamat" class="form-control input-sm" placeholder="House #, Street, City..." style="border-radius: 6px; border: 1px solid #cbd5e1;">
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 10px;">
                        <button type="button" class="btn btn-default btn-sm btn-flat" onclick="toggleQuickCustomerForm(false)" style="border-radius: 6px; font-weight: 600; padding: 5px 14px;">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-success btn-sm btn-flat" id="btnSaveQuickCust" onclick="submitQuickCustomer()" style="border-radius: 6px; font-weight: 700; padding: 5px 16px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa fa-check-circle" id="iconSaveQuickCust"></i> Save &amp; Select for Invoice
                        </button>
                    </div>
                </div>

                <!-- CONTROLS BAR: SEARCH FILTER & WALK-IN RESET -->
                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 12px; flex-wrap: wrap;">
                    <div style="position: relative; flex: 1; min-width: 220px;">
                        <i class="fa fa-search" style="position: absolute; left: 12px; top: 10px; color: #94a3b8; font-size: 12px;"></i>
                        <input type="text" id="custSearchInput" class="form-control input-sm" placeholder="Search customer by name, telephone, or address..." style="padding-left: 32px; border-radius: 6px; height: 34px; font-size: 12px;" oninput="filterCustomerTable(this.value)">
                    </div>
                    <button type="button" class="btn btn-default btn-sm btn-flat" onclick="pilihMember('', '', 'Walk In Customer', '', '')" style="border-radius: 6px; font-weight: 700; height: 34px; padding: 0 14px; color: #475569;" title="Set order back to Walk-In Customer">
                        <i class="fa fa-user-times"></i> Walk-In Customer
                    </button>
                </div>

                <!-- CUSTOMER MEMBERS TABLE -->
                <div style="max-height: 380px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <table class="table table-hover table-striped" id="tableCustomerMembers" style="margin-bottom: 0;">
                        <thead style="background: #f8fafc; position: sticky; top: 0; z-index: 2; border-bottom: 1.5px solid #e2e8f0;">
                            <tr>
                                <th width="6%" style="font-size: 11.5px; font-weight: 700; color: #475569;">#</th>
                                <th style="font-size: 11.5px; font-weight: 700; color: #475569;">Name</th>
                                <th style="font-size: 11.5px; font-weight: 700; color: #475569;">Telephone</th>
                                <th style="font-size: 11.5px; font-weight: 700; color: #475569;">Address</th>
                                <th width="12%" style="text-align: center; font-size: 11.5px; font-weight: 700; color: #475569;"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody id="memberTableBody">
                            @forelse ($member as $key => $item)
                                <tr class="member-row" data-name="{{ strtolower($item->nama) }}" data-phone="{{ strtolower($item->telepon) }}" data-address="{{ strtolower($item->alamat) }}">
                                    <td width="6%" style="font-size: 12px; font-weight: 600; color: #64748b;">{{ $key+1 }}</td>
                                    <td style="font-size: 12.5px; font-weight: 700; color: #0f172a;">{{ $item->nama }}</td>
                                    <td style="font-size: 12px; color: #334155;">{{ $item->telepon ?: '-' }}</td>
                                    <td style="font-size: 12px; color: #64748b;">{{ $item->alamat ?: '-' }}</td>
                                    <td width="12%" style="text-align: center;">
                                        <button type="button" class="btn btn-primary btn-xs btn-flat" style="border-radius: 6px; font-weight: 700; padding: 4px 10px; background: #ea580c; border-color: #ea580c;"
                                            onclick="pilihMember('{{ $item->id_member }}', '{{ $item->kode_member }}', '{{ addslashes($item->nama) }}', '{{ addslashes($item->telepon) }}', '{{ addslashes($item->alamat) }}')">
                                            <i class="fa fa-check-circle"></i> Select
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noCustomersRow">
                                    <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8; font-size: 13px;">
                                        <i class="fa fa-users" style="font-size: 24px; margin-bottom: 6px; display: block;"></i>
                                        No registered customers yet. Click <strong>+ Add New Customer</strong> above to register one!
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="noMatchRow" style="display: none;">
                                <td colspan="5" style="text-align: center; padding: 25px; color: #94a3b8; font-size: 12.5px;">
                                    No customers found matching your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleQuickCustomerForm(forceState) {
        let panel = $('#quickAddCustomerPanel');
        let btn = $('#btnToggleCustomerForm');
        let isOpening = (forceState !== undefined) ? forceState : !panel.is(':visible');

        if (isOpening) {
            panel.slideDown(150, function() {
                $('#quick_cust_nama').focus();
            });
            btn.text('Close Form').removeClass('btn-success').addClass('btn-default');
            $('#quickCustAlert').hide();
        } else {
            panel.slideUp(150);
            btn.text('Add New Customer').removeClass('btn-default').addClass('btn-success');
            $('#quick_cust_nama, #quick_cust_telepon, #quick_cust_alamat').val('');
            $('#quickCustAlert').hide();
        }
    }

    function showQuickCustomerForm() {
        toggleQuickCustomerForm(true);
    }

    function filterCustomerTable(keyword) {
        let q = (keyword || '').toLowerCase().trim();
        let rows = $('.member-row');
        let visibleCount = 0;

        rows.each(function() {
            let row = $(this);
            let name = row.attr('data-name') || '';
            let phone = row.attr('data-phone') || '';
            let address = row.attr('data-address') || '';

            if (name.includes(q) || phone.includes(q) || address.includes(q)) {
                row.show();
                visibleCount++;
            } else {
                row.hide();
            }
        });

        if (visibleCount === 0 && rows.length > 0) {
            $('#noMatchRow').show();
        } else {
            $('#noMatchRow').hide();
        }
    }

    function submitQuickCustomer() {
        let nama = $('#quick_cust_nama').val().trim();
        let telepon = $('#quick_cust_telepon').val().trim();
        let alamat = $('#quick_cust_alamat').val().trim();
        let alertBox = $('#quickCustAlert');

        if (!nama) {
            alertBox.removeClass('alert-success').addClass('alert-danger')
                .css({'background': '#fee2e2', 'color': '#b91c1c', 'border': '1px solid #fecaca'})
                .html('<i class="fa fa-exclamation-circle"></i> Customer name is required.').slideDown(100);
            $('#quick_cust_nama').focus();
            return;
        }

        let btn = $('#btnSaveQuickCust');
        let icon = $('#iconSaveQuickCust');
        btn.prop('disabled', true);
        icon.removeClass('fa-check-circle').addClass('fa-spinner fa-spin');
        alertBox.hide();

        $.ajax({
            url: '{{ route('member.quick_store') }}',
            type: 'POST',
            data: {
                '_token': $('[name=csrf-token]').attr('content') || '{{ csrf_token() }}',
                'nama': nama,
                'telepon': telepon,
                'alamat': alamat
            },
            success: function(response) {
                btn.prop('disabled', false);
                icon.removeClass('fa-spinner fa-spin').addClass('fa-check-circle');

                let m = response.member;
                if (!m) {
                    toggleQuickCustomerForm(false);
                    return;
                }

                // Add to table dynamically
                $('#noCustomersRow').remove();
                let rowCount = $('.member-row').length + 1;
                let safeName = $('<div>').text(m.nama).html();
                let safePhone = $('<div>').text(m.telepon || '-').html();
                let safeAddress = $('<div>').text(m.alamat || '-').html();

                let newRow = `
                    <tr class="member-row" style="background: #f0fdf4;" data-name="${(m.nama||'').toLowerCase()}" data-phone="${(m.telepon||'').toLowerCase()}" data-address="${(m.alamat||'').toLowerCase()}">
                        <td width="6%" style="font-size: 12px; font-weight: 600; color: #166534;"><span class="label label-success">NEW</span></td>
                        <td style="font-size: 12.5px; font-weight: 800; color: #166534;">${safeName}</td>
                        <td style="font-size: 12px; color: #334155;">${safePhone}</td>
                        <td style="font-size: 12px; color: #64748b;">${safeAddress}</td>
                        <td width="12%" style="text-align: center;">
                            <button type="button" class="btn btn-primary btn-xs btn-flat" style="border-radius: 6px; font-weight: 700; padding: 4px 10px; background: #ea580c; border-color: #ea580c;"
                                onclick="pilihMember('${m.id_member}', '${m.kode_member}', '${safeName.replace(/'/g, "\\'")}', '${safePhone.replace(/'/g, "\\'")}', '${safeAddress.replace(/'/g, "\\'")}')">
                                <i class="fa fa-check-circle"></i> Select
                            </button>
                        </td>
                    </tr>
                `;
                $('#memberTableBody').prepend(newRow);

                // Auto-select for the invoice
                toggleQuickCustomerForm(false);
                pilihMember(m.id_member, m.kode_member, m.nama, m.telepon, m.alamat);
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                icon.removeClass('fa-spinner fa-spin').addClass('fa-check-circle');

                let msg = 'Failed to save customer. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                alertBox.removeClass('alert-success').addClass('alert-danger')
                    .css({'background': '#fee2e2', 'color': '#b91c1c', 'border': '1px solid #fecaca'})
                    .html('<i class="fa fa-exclamation-circle"></i> ' + msg).slideDown(100);
            }
        });
    }

    // Enter key submits the quick customer form
    $(document).on('keypress', '#quick_cust_nama, #quick_cust_telepon, #quick_cust_alamat', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            submitQuickCustomer();
        }
    });
</script>