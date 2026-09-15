<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Receipt #PO-{{ tambah_nol_didepan($pembelian->id_pembelian, 4) }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Nastaliq+Urdu:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Courier New', monospace;
            color: #000000;
        }

        body {
            background-color: #f1f5f9;
            padding: 20px 10px;
            font-size: 10pt;
            line-height: 1.35;
        }

        .receipt-container {
            width: 80mm;
            max-width: 100%;
            margin: 0 auto;
            background: #ffffff;
            padding: 12px 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: 700; }
        .bolder { font-weight: 900; }

        /* Clean Logo Display */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 6px;
        }

        .invoice-logo {
            max-width: 90px;
            max-height: 55px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* Company Header */
        .company-header {
            text-align: center;
            margin-bottom: 4px;
        }

        .company-title {
            font-size: 13.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .company-info {
            font-size: 8.5pt;
            font-weight: 600;
            line-height: 1.3;
            color: #111;
        }

        /* Dashed Line Divider */
        .divider-dashed {
            border-top: 1px dashed #000000;
            margin: 5px 0;
            width: 100%;
        }

        .divider-solid {
            border-top: 1px solid #000000;
            margin: 4px 0;
        }

        .divider-double {
            border-top: 2.5px double #000000;
            margin: 4px 0;
        }

        /* Heading Title */
        .invoice-heading-title {
            text-align: center;
            font-size: 11.5pt;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 2px 0;
        }

        /* Metadata Grid */
        .meta-grid-table {
            width: 100%;
            font-size: 8.5pt;
            border-collapse: collapse;
        }

        .meta-grid-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin: 2px 0;
        }

        .items-table th {
            padding: 3px 0;
            font-size: 8.5pt;
            font-weight: 800;
            text-transform: uppercase;
            border-top: 1px dashed #000000;
            border-bottom: 1px dashed #000000;
        }

        .items-table td {
            padding: 2.5px 0;
            vertical-align: top;
        }

        .item-row td {
            font-size: 9pt;
        }

        /* Summary Section */
        .summary-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 9pt;
            margin: 4px 0;
        }

        .summary-left {
            width: 32%;
            font-weight: 700;
            padding-top: 2px;
            font-size: 8.5pt;
        }

        .summary-right-table {
            width: 68%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .summary-right-table td {
            padding: 1.5px 0;
        }

        .grand-total-row td {
            font-size: 11pt;
            font-weight: 900;
            border-top: 1px solid #000000;
            border-bottom: 2.5px double #000000;
            padding: 3px 0;
        }

        /* Urdu / Footer Section */
        .urdu-section {
            direction: rtl;
            text-align: right;
            padding-top: 4px;
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Urdu Typesetting', Tahoma, sans-serif;
        }

        .urdu-heading {
            font-size: 11pt;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4px;
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Urdu Typesetting', Tahoma, sans-serif;
        }

        .urdu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .urdu-list li {
            font-size: 8.5pt;
            line-height: 1.7;
            margin-bottom: 2px;
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Urdu Typesetting', Tahoma, sans-serif;
        }

        .footer-thanks {
            text-align: center;
            font-size: 8pt;
            font-weight: 700;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Floating Print Button */
        .btn-print-floating {
            position: fixed;
            top: 12px;
            right: 12px;
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 9999;
            transition: all 0.2s ease;
        }

        .btn-print-floating:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }

            body {
                background: #ffffff;
                padding: 0;
                margin: 0;
                width: 78mm;
            }

            .receipt-container {
                width: 76mm;
                padding: 2mm 1mm;
                box-shadow: none;
                border-radius: 0;
                margin: 0 auto;
            }

            .btn-print-floating {
                display: none !important;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="btn-print-floating" onclick="window.print()">🖨️ Print Receipt</button>

    <div class="receipt-container">
        <!-- 1. Top Logo (Clean, borderless) -->
        @if(isset($setting->show_logo_receipt) ? $setting->show_logo_receipt : true)
            @if(!empty($setting->path_logo))
            <div class="logo-wrapper">
                <img src="{{ url($setting->path_logo) }}" alt="Logo" class="invoice-logo">
            </div>
            @endif
        @endif

        <!-- 2. Company / Business Header -->
        <div class="company-header">
            <div class="company-title">{{ strtoupper($setting->nama_perusahaan ?? 'RESTAURANT POS') }}</div>
            @if(!empty($setting->telepon))
            <div class="company-info"><strong>Phone:</strong> {{ $setting->telepon }}</div>
            @endif
            @if(!empty($setting->alamat))
            <div class="company-info">{{ strtoupper($setting->alamat) }}</div>
            @endif
        </div>

        <div class="divider-dashed"></div>

        <!-- 3. PURCHASE RECEIPT Title -->
        <div class="invoice-heading-title">PURCHASE RECEIPT</div>

        <!-- 4. Metadata (2-Column Grid) -->
        <table class="meta-grid-table">
            <tr>
                <td width="50%">
                    <strong>PO No:</strong> #PO-{{ tambah_nol_didepan($pembelian->id_pembelian, 4) }}
                </td>
                <td width="50%" class="text-right">
                    <strong>Date:</strong> {{ date('d-m-Y', strtotime($pembelian->created_at ?? now())) }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Time:</strong> {{ date('h:i A', strtotime($pembelian->created_at ?? now())) }}
                </td>
                <td class="text-right">
                    <strong>Supplier:</strong> {{ !empty($pembelian->supplier->nama) ? $pembelian->supplier->nama : 'N/A' }}
                </td>
            </tr>
            @if(!empty($pembelian->supplier->telepon) || !empty($pembelian->supplier->alamat))
            <tr>
                <td colspan="2">
                    <strong>Contact:</strong> {{ $pembelian->supplier->telepon ?? '' }} {{ !empty($pembelian->supplier->alamat) ? '('. $pembelian->supplier->alamat .')' : '' }}
                </td>
            </tr>
            @endif
        </table>

        <!-- 5. Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-left" width="44%">Product</th>
                    <th class="text-center" width="14%">Qty</th>
                    <th class="text-right" width="20%">Cost</th>
                    <th class="text-right" width="22%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $item)
                <tr class="item-row">
                    <td class="bold">{{ $item->produk->nama_produk ?? 'Item' }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right">{{ format_uang($item->harga_beli) }}</td>
                    <td class="text-right bold">{{ format_uang($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider-dashed"></div>

        <!-- 6. Summary Breakdown -->
        <div class="summary-wrapper">
            <div class="summary-left">
                <div>Items: {{ $pembelian->total_item ?? count($detail) }}</div>
            </div>
            <table class="summary-right-table">
                <tr>
                    <td class="text-left">Sub Total</td>
                    <td class="text-right bold">{{ format_uang($pembelian->total_harga) }}</td>
                </tr>
                @if(($pembelian->diskon ?? 0) > 0)
                <tr>
                    <td class="text-left">Discount</td>
                    <td class="text-right">-{{ $pembelian->diskon }}%</td>
                </tr>
                @endif
                <tr class="grand-total-row">
                    <td class="text-left">Total Pay</td>
                    <td class="text-right">{{ format_currency($pembelian->bayar) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Type</td>
                    <td class="text-right bold" style="text-transform: uppercase;">
                        STOCK-IN / PURCHASE
                    </td>
                </tr>
            </table>
        </div>

        <!-- 7. Footer Notice / Urdu Note (Only if enabled in settings) -->
        @if(isset($setting->show_purchase_terms) ? $setting->show_purchase_terms : true)
            @php
                $purchaseTerms = !empty($setting->purchase_terms_conditions) 
                    ? $setting->purchase_terms_conditions 
                    : "یہ پرچیز رسید سٹاک میں اندراج کی تصدیق ہےـ\nتمام آئٹمز کی مقدار اور قیمت چیک کر لی گئی ہےـ";
                $purchaseLines = explode("\n", str_replace("\r", "", $purchaseTerms));
            @endphp
            @if(count($purchaseLines) > 0)
            <div class="divider-dashed"></div>
            <div class="urdu-section" dir="rtl">
                <div class="urdu-heading">{{ !empty($setting->purchase_terms_title) ? $setting->purchase_terms_title : 'خریداری رسید / سٹاک انوائس' }}</div>
                <ul class="urdu-list">
                    @foreach($purchaseLines as $pLine)
                        @if(trim($pLine) !== '')
                        <li>{{ trim($pLine) }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif
        @endif

        <div class="footer-thanks">*** STOCK-IN VERIFIED ***</div>
    </div>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
            body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight
        );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 40) * 0.264583);
    </script>
</body>
</html>
