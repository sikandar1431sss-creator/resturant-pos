@extends('layouts.master')

@section('title')
    Settings
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Settings</li>
@endsection

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Nastaliq+Urdu:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .settings-page-wrapper {
        margin: 10px -5px 30px -5px;
    }

    /* Left Sidebar Card */
    .settings-sidebar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .settings-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .settings-nav-item {
        display: block;
        padding: 13px 20px;
        color: #475569;
        font-size: 13.5px;
        font-weight: 500;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none !important;
        transition: all 0.15s ease;
        background: #ffffff;
        cursor: pointer;
        user-select: none;
    }

    .settings-nav-item:last-child {
        border-bottom: none;
    }

    .settings-nav-item:hover {
        background: #f8fafc;
        color: #0284c7;
    }

    /* Active Tab Style (Matching Screenshot: Cyan/Sky Blue underline) */
    .settings-nav-item.active {
        color: #0284c7 !important;
        font-weight: 600;
        border-bottom: 2px solid #38bdf8 !important;
        background: #ffffff !important;
    }

    /* Right Content Area Card */
    .settings-content-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        padding: 24px 28px;
        min-height: 480px;
        margin-bottom: 20px;
    }

    .settings-tab-pane {
        display: none;
    }

    .settings-tab-pane.active {
        display: block;
        animation: tabFadeIn 0.2s ease;
    }

    @keyframes tabFadeIn {
        from { opacity: 0; transform: translateY(3px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Clean Form Elements */
    .form-group-field {
        margin-bottom: 20px;
    }

    .form-field-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }

    .form-field-input {
        width: 100%;
        height: 40px;
        padding: 6px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-size: 13.5px;
        color: #0f172a;
        background: #ffffff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-field-input:focus {
        border-color: #0284c7;
        outline: none;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
    }

    textarea.form-field-input {
        height: auto;
        padding: 10px 12px;
    }

    /* Urdu Textarea */
    .urdu-field-textarea {
        direction: rtl;
        text-align: right;
        font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', Tahoma, sans-serif !important;
        font-size: 14px !important;
        line-height: 2 !important;
        padding: 12px !important;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        width: 100%;
    }

    .urdu-field-textarea:focus {
        border-color: #0284c7;
        outline: none;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
    }

    .urdu-field-title {
        direction: rtl;
        text-align: right;
        font-family: 'Noto Nastaliq Urdu', Tahoma, sans-serif !important;
        font-size: 13.5px !important;
        font-weight: 700;
    }

    /* Radio Options Style */
    .radio-option-group {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-top: 6px;
    }

    .radio-option-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13.5px;
        color: #334155;
        font-weight: 500;
        cursor: pointer;
    }

    /* Action Save Button */
    .btn-save-settings {
        background: #0284c7 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 4px !important;
        padding: 9px 24px !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.25);
    }

    .btn-save-settings:hover {
        background: #0369a1 !important;
        color: #ffffff !important;
    }

    .btn-insert-urdu-magic {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #0284c7;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-insert-urdu-magic:hover {
        background: #e2e8f0;
        color: #0369a1;
    }

    .preview-box-mini {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 4px;
        padding: 12px;
        text-align: center;
        margin-top: 8px;
    }
</style>
@endpush

@section('content')
<div class="settings-page-wrapper">
    <form action="{{ route('setting.update') }}" method="post" class="form-setting" data-toggle="validator" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- LEFT COLUMN: SIDEBAR MENU TABS -->
            <div class="col-md-3 col-sm-4">
                <div class="settings-sidebar-card">
                    <ul class="settings-nav-list">
                        <li>
                            <a class="settings-nav-item active" data-tab="tab-general">
                                General
                            </a>
                        </li>
                        <li>
                            <a class="settings-nav-item" data-tab="tab-company">
                                Company Information
                            </a>
                        </li>
                        <li>
                            <a class="settings-nav-item" data-tab="tab-invoice">
                                Invoices &amp; Receipts
                            </a>
                        </li>
                        <li>
                            <a class="settings-nav-item" data-tab="tab-finance">
                                Finance &amp; Currency
                            </a>
                        </li>
                        <li>
                            <a class="settings-nav-item" data-tab="tab-branding">
                                Logos &amp; Branding
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- RIGHT COLUMN: CONTENT PANELS -->
            <div class="col-md-9 col-sm-8">
                <div class="settings-content-card">
                    
                    <!-- TAB 1: GENERAL -->
                    <div class="settings-tab-pane active" id="tab-general">
                        <div class="form-group-field">
                            <label class="form-field-label" for="date_format">Date Format</label>
                            <input type="text" id="date_format" class="form-field-input" value="d-m-Y" readonly style="background: #f8fafc;">
                        </div>

                        <div class="form-group-field">
                            <label class="form-field-label" for="time_format">Time Format</label>
                            <input type="text" id="time_format" class="form-field-input" value="12 hours" readonly style="background: #f8fafc;">
                        </div>

                        <div class="form-group-field">
                            <label class="form-field-label" for="timezone">Default Timezone</label>
                            <input type="text" id="timezone" class="form-field-input" value="Asia/Dubai" readonly style="background: #f8fafc;">
                        </div>

                        <div class="form-group-field">
                            <label class="form-field-label" for="default_language">Default Language</label>
                            <input type="text" id="default_language" class="form-field-input" value="English" readonly style="background: #f8fafc;">
                        </div>
                    </div>

                    <!-- TAB 2: COMPANY INFORMATION -->
                    <div class="settings-tab-pane" id="tab-company">
                        <div class="form-group-field">
                            <label class="form-field-label" for="nama_perusahaan">Company / Store Name</label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-field-input" placeholder="e.g. TAJ ELECTRIC CENTER" required>
                        </div>

                        <div class="form-group-field">
                            <label class="form-field-label" for="telepon">Telephone / Phone Number</label>
                            <input type="text" name="telepon" id="telepon" class="form-field-input" placeholder="e.g. 03193712392" required>
                        </div>

                        <div class="form-group-field">
                            <label class="form-field-label" for="alamat">Physical Address / Shop Location</label>
                            <textarea name="alamat" id="alamat" class="form-field-input" rows="3" placeholder="e.g. CINEMA ROAD KHANEWAL" required></textarea>
                        </div>
                    </div>

                    <!-- TAB 3: INVOICES & RECEIPTS (SALE & PURCHASE THERMAL & A4 SETTINGS) -->
                    <div class="settings-tab-pane" id="tab-invoice">
                        <!-- 1. Print & Logo Preferences -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px 18px; margin-bottom: 22px;">
                            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-print" style="color: #0284c7;"></i> General Print Preferences
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-field" style="margin-bottom: 10px;">
                                        <label class="form-field-label" for="tipe_nota">Default Invoice Print Format</label>
                                        <select name="tipe_nota" id="tipe_nota" class="form-field-input" required>
                                            <option value="1">Small Thermal Slip (80mm / 78mm POS)</option>
                                            <option value="2">Full A4 PDF Invoice</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-field" style="margin-bottom: 10px;">
                                        <label class="form-field-label">Receipt Logo Visibility</label>
                                        <label style="display: flex; align-items: center; gap: 8px; margin-top: 8px; cursor: pointer; font-weight: 500; font-size: 13.5px; color: #334155;">
                                            <input type="checkbox" name="show_logo_receipt" id="show_logo_receipt" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                                            <span>Show Store Logo at Top of Receipts</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. SALE INVOICE URDU SETTINGS -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 18px; margin-bottom: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                                <div style="font-size: 14.5px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-shopping-cart" style="color: #10b981;"></i> Sale Invoice (سیل رسید) Urdu Terms
                                </div>
                                <label style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer; font-weight: 600; font-size: 13px; color: #0f172a;">
                                    <input type="checkbox" name="show_sale_terms" id="show_sale_terms" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                                    <span>Show Urdu Terms on Sale Invoices</span>
                                </label>
                            </div>

                            <div id="saleTermsContainer">
                                <div class="form-group-field">
                                    <label class="form-field-label" for="terms_title">
                                        Sale Terms Title (سیل شرائط و ضوابط کا عنوان)
                                    </label>
                                    <input type="text" name="terms_title" id="terms_title" class="form-field-input urdu-field-title" value="شرائط و ضوابط" placeholder="شرائط و ضوابط">
                                </div>

                                <div class="form-group-field" style="margin-bottom: 0;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <label class="form-field-label" for="terms_conditions" style="margin: 0;">
                                            Sale Terms Lines (سیل رسید پر شرائط کی تفصیل)
                                        </label>
                                        <button type="button" class="btn-insert-urdu-magic" onclick="fillDefaultUrduTerms()">
                                            <i class="fa fa-magic"></i> Default Sale Lines
                                        </button>
                                    </div>
                                    <textarea name="terms_conditions" id="terms_conditions" class="urdu-field-textarea" rows="4" placeholder="ہر لائن رسید پر الگ نکتہ کے طور پر پرنٹ ہوگی۔"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 3. PURCHASE INVOICE URDU SETTINGS -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 18px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                                <div style="font-size: 14.5px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-truck" style="color: #f59e0b;"></i> Purchase Receipt (خریداری رسید) Urdu Notes
                                </div>
                                <label style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer; font-weight: 600; font-size: 13px; color: #0f172a;">
                                    <input type="checkbox" name="show_purchase_terms" id="show_purchase_terms" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                                    <span>Show Urdu Notes on Purchase Receipts</span>
                                </label>
                            </div>

                            <div id="purchaseTermsContainer">
                                <div class="form-group-field">
                                    <label class="form-field-label" for="purchase_terms_title">
                                        Purchase Receipt Title (خریداری رسید کا عنوان)
                                    </label>
                                    <input type="text" name="purchase_terms_title" id="purchase_terms_title" class="form-field-input urdu-field-title" value="خریداری رسید / سٹاک انوائس" placeholder="خریداری رسید / سٹاک انوائس">
                                </div>

                                <div class="form-group-field" style="margin-bottom: 0;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <label class="form-field-label" for="purchase_terms_conditions" style="margin: 0;">
                                            Purchase Note Lines (پرچیز رسید پر نوٹ کی تفصیل)
                                        </label>
                                        <button type="button" class="btn-insert-urdu-magic" onclick="fillDefaultPurchaseUrduTerms()">
                                            <i class="fa fa-magic"></i> Default Purchase Lines
                                        </button>
                                    </div>
                                    <textarea name="purchase_terms_conditions" id="purchase_terms_conditions" class="urdu-field-textarea" rows="3" placeholder="ہر لائن رسید پر الگ نکتہ کے طور پر پرنٹ ہوگی۔"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: FINANCE & CURRENCY -->
                    <div class="settings-tab-pane" id="tab-finance">
                        <div class="form-group-field">
                            <label class="form-field-label" for="mata_uang">System Default Currency</label>
                            <select name="mata_uang" id="mata_uang" class="form-field-input" style="max-width: 380px;" required>
                                <option value="PKR">PKR - Pakistani Rupee (RS)</option>
                                <option value="USD">USD - US Dollar ($)</option>
                                <option value="EUR">EUR - Euro (€)</option>
                                <option value="GBP">GBP - British Pound (£)</option>
                                <option value="AED">AED - UAE Dirham (AED)</option>
                                <option value="SAR">SAR - Saudi Riyal (SAR)</option>
                                <option value="INR">INR - Indian Rupee (₹)</option>
                                <option value="IDR">IDR - Indonesian Rupiah (Rp)</option>
                            </select>
                        </div>

                        <div class="form-group-field">
                            <label class="form-field-label" for="diskon">Member Default Discount (%)</label>
                            <input type="number" name="diskon" id="diskon" class="form-field-input" style="max-width: 200px;" min="0" max="100" placeholder="0" required>
                        </div>
                    </div>

                    <!-- TAB 5: LOGOS & BRANDING -->
                    <div class="settings-tab-pane" id="tab-branding">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-field">
                                    <label class="form-field-label" for="path_logo">Company / Invoice Logo</label>
                                    <input type="file" name="path_logo" id="path_logo" class="form-field-input" onchange="preview('.tampil-logo', this.files[0])">
                                    
                                    <div class="preview-box-mini">
                                        <div style="font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 6px; text-transform: uppercase;">Current Receipt Logo</div>
                                        <div class="tampil-logo"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-field">
                                    <label class="form-field-label" for="path_kartu_member">Membership Card Background</label>
                                    <input type="file" name="path_kartu_member" id="path_kartu_member" class="form-field-input" onchange="preview('.tampil-kartu-member', this.files[0], 260)">
                                    
                                    <div class="preview-box-mini">
                                        <div style="font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 6px; text-transform: uppercase;">Current Member Card</div>
                                        <div class="tampil-kartu-member"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Save Bar -->
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 18px; margin-top: 24px; text-align: right;">
                        <button type="submit" class="btn-save-settings">
                            <i class="fa fa-check"></i> Save Settings
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const DEFAULT_URDU_TERMS = "خریدہ ہوا مال واپس یا تبدیل نہیں ہوگاـ\nوارنٹی صرف کمپنی / مینوفیکچرر کی شرائط کے مطابق ہوگیـ\nبل کے بغیر کسی قسم کی شکایت قبول نہیں کی جائے گیـ\nادھار رقم مقررہ تاریخ تک ادا کرنا ضروری ہےـ";
    const DEFAULT_PURCHASE_URDU_TERMS = "یہ پرچیز رسید سٹاک میں اندراج کی تصدیق ہےـ\nتمام آئٹمز کی مقدار اور قیمت چیک کر لی گئی ہےـ";

    $(function () {
        showData();

        // Left Sidebar Navigation Click Handler
        $('.settings-nav-item').on('click', function (e) {
            e.preventDefault();
            $('.settings-nav-item').removeClass('active');
            $(this).addClass('active');

            let targetTab = $(this).data('tab');
            $('.settings-tab-pane').removeClass('active');
            $('#' + targetTab).addClass('active');
        });

        // Toggle Sale Terms visibility animation
        $('#show_sale_terms').on('change', function() {
            if ($(this).is(':checked')) {
                $('#saleTermsContainer').slideDown(150);
            } else {
                $('#saleTermsContainer').slideUp(150);
            }
        });

        // Toggle Purchase Terms visibility animation
        $('#show_purchase_terms').on('change', function() {
            if ($(this).is(':checked')) {
                $('#purchaseTermsContainer').slideDown(150);
            } else {
                $('#purchaseTermsContainer').slideUp(150);
            }
        });

        // Form Submit Handler
        $('.form-setting').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                let $submitBtn = $(this).find('button[type="submit"]');
                let originalBtnHtml = $submitBtn.html();
                $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: $('.form-setting').attr('action'),
                    type: $('.form-setting').attr('method'),
                    data: new FormData($('.form-setting')[0]),
                    processData: false,
                    contentType: false
                })
                .done(response => {
                    showData();
                    showSuccessToast('Settings and preferences saved successfully!');
                })
                .fail(errors => {
                    showErrorToast('Unable to save settings. Please check required fields.');
                })
                .always(() => {
                    $submitBtn.prop('disabled', false).html(originalBtnHtml);
                });
            }
        });
    });

    function fillDefaultUrduTerms() {
        $('#terms_title').val('شرائط و ضوابط');
        $('#terms_conditions').val(DEFAULT_URDU_TERMS);
        showSuccessToast('Default Sale Urdu terms inserted');
    }

    function fillDefaultPurchaseUrduTerms() {
        $('#purchase_terms_title').val('خریداری رسید / سٹاک انوائس');
        $('#purchase_terms_conditions').val(DEFAULT_PURCHASE_URDU_TERMS);
        showSuccessToast('Default Purchase Urdu notes inserted');
    }

    function showData() {
        $.get('{{ route('setting.show') }}')
            .done(response => {
                $('[name=nama_perusahaan]').val(response.nama_perusahaan);
                $('[name=telepon]').val(response.telepon);
                $('[name=alamat]').val(response.alamat);
                $('[name=diskon]').val(response.diskon);
                $('[name=tipe_nota]').val(response.tipe_nota);
                $('[name=mata_uang]').val(response.mata_uang || 'PKR');

                // Checkboxes
                $('#show_logo_receipt').prop('checked', response.show_logo_receipt == 1);
                
                $('#show_sale_terms').prop('checked', response.show_sale_terms == 1);
                if (response.show_sale_terms == 1) {
                    $('#saleTermsContainer').show();
                } else {
                    $('#saleTermsContainer').hide();
                }

                $('#show_purchase_terms').prop('checked', response.show_purchase_terms == 1);
                if (response.show_purchase_terms == 1) {
                    $('#purchaseTermsContainer').show();
                } else {
                    $('#purchaseTermsContainer').hide();
                }

                // Values
                $('[name=terms_title]').val(response.terms_title || 'شرائط و ضوابط');
                $('[name=terms_conditions]').val(response.terms_conditions !== null ? response.terms_conditions : DEFAULT_URDU_TERMS);
                
                $('[name=purchase_terms_title]').val(response.purchase_terms_title || 'خریداری رسید / سٹاک انوائس');
                $('[name=purchase_terms_conditions]').val(response.purchase_terms_conditions !== null ? response.purchase_terms_conditions : DEFAULT_PURCHASE_URDU_TERMS);
                
                $('title').text(response.nama_perusahaan + ' | Settings');
                
                let words = (response.nama_perusahaan || '').split(' ');
                let word  = '';
                words.forEach(w => {
                    word += w.charAt(0);
                });
                $('.logo-mini').text(word);
                $('.logo-lg').text(response.nama_perusahaan);

                $('.tampil-logo').html(`<img src="{{ url('/') }}${response.path_logo}" style="max-height: 70px; max-width: 130px; object-fit: contain; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px; background: #fff;">`);
                $('.tampil-kartu-member').html(`<img src="{{ url('/') }}${response.path_kartu_member}" style="max-height: 90px; max-width: 160px; object-fit: contain; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px; background: #fff;">`);
                $('[rel=icon]').attr('href', `{{ url('/') }}/${response.path_logo}`);
            })
            .fail(errors => {
                showErrorToast('Unable to load settings data');
            });
    }
</script>
@endpush