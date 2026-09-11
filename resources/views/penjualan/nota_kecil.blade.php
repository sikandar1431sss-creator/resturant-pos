<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale Invoice #INV-{{ tambah_nol_didepan($penjualan->id_penjualan, 4) }}</title>

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
            font-size: 11pt;
            line-height: 1.35;
        }

        .receipt-container {
            width: 80mm;
            max-width: 100%;
            margin: 0 auto;
            background: #ffffff;
            padding: 14px 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: 700; }
        .bolder { font-weight: 900; }

        /* Logo Box */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 8px;
        }

        .logo-box {
            display: inline-block;
            border: 1px solid #000000;
            padding: 4px;
            border-radius: 4px;
            background: #ffffff;
        }

        .invoice-logo {
            max-width: 75px;
            max-height: 75px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* Company Header */
        .company-header {
            text-align: center;
            margin-bottom: 6px;
        }

        .company-title {
            font-size: 14pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        .company-info {
            font-size: 9.5pt;
            font-weight: 600;
            line-height: 1.3;
        }

        /* Dashed Line Divider */
        .divider-dashed {
            border-top: 1.5px dashed #000000;
            margin: 6px 0;
            width: 100%;
        }

        .divider-solid {
            border-top: 1.5px solid #000000;
            margin: 4px 0;
        }

        .divider-double {
            border-top: 3px double #000000;
            margin: 4px 0;
        }

        /* Invoice Main Title */
        .invoice-heading-title {
            text-align: center;
            font-size: 13pt;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 2px 0;
        }

        /* Metadata Grid */
        .meta-grid-table {
            width: 100%;
            font-size: 9pt;
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
            font-size: 9.5pt;
            margin: 2px 0;
        }

        .items-table th {
            padding: 4px 0;
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            border-bottom: 1.5px dashed #000000;
        }

        .items-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .item-row td {
            font-size: 9.5pt;
        }

        /* Summary Section */
        .summary-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 9.5pt;
            margin: 4px 0;
        }

        .summary-left {
            width: 32%;
            font-weight: 700;
            padding-top: 2px;
        }

        .summary-right-table {
            width: 68%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .summary-right-table td {
            padding: 1.5px 0;
        }

        .grand-total-row td {
            font-size: 11.5pt;
            font-weight: 900;
            border-top: 1.5px solid #000000;
            border-bottom: 3px double #000000;
            padding: 3px 0;
        }

        /* Urdu Terms & Conditions */
        .urdu-section {
            direction: rtl;
            text-align: right;
            padding-top: 4px;
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Urdu Typesetting', Tahoma, sans-serif;
        }

        .urdu-heading {
            font-size: 13pt;
            font-weight: 700;
            text-align: center;
            margin-bottom: 6px;
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Urdu Typesetting', Tahoma, sans-serif;
        }

        .urdu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .urdu-list li {
            font-size: 9.5pt;
            line-height: 1.7;
            margin-bottom: 2px;
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Urdu Typesetting', Tahoma, sans-serif;
        }

        /* Floating Print Button */
        .btn-print-floating {
            position: fixed;
            top: 12px;
            right: 12px;
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 9999;
            transition: all 0.2s ease;
        }

        .btn-print-floating:hover {
            background: #059669;
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
        <!-- 1. Top Logo Box -->
        <div class="logo-wrapper">
            <div class="logo-box">
                <img src="{{ !empty($setting->path_logo) ? url($setting->path_logo) : url('img/logo.png') }}" alt="Logo" class="invoice-logo">
            </div>
        </div>

        <!-- 2. Company / Business Header -->
        <div class="company-header">
            <div class="company-title">{{ strtoupper($setting->nama_perusahaan ?? 'TAJ ELECTRIC CENTER') }}</div>
            <div class="company-info"><strong>Phone:</strong> {{ $setting->telepon ?? '03193712392' }}</div>
            <div class="company-info">{{ strtoupper($setting->alamat ?? 'CINEMA ROAD KHANEWAL') }}</div>
        </div>

        <div class="divider-dashed"></div>

        <!-- 3. SALE INVOICE Title -->
        <div class="invoice-heading-title">SALE INVOICE</div>

        <!-- 4. Order Metadata (2-Column Grid) -->
        <table class="meta-grid-table">
            <tr>
                <td width="50%">
                    <strong>Invoice:</strong> INV-{{ tambah_nol_didepan($penjualan->id_penjualan, 4) }}
                </td>
                <td width="50%" class="text-right">
                    <strong>Date:</strong> {{ date('d-m-Y', strtotime($penjualan->created_at ?? now())) }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Time:</strong> {{ date('h:i A', strtotime($penjualan->created_at ?? now())) }}
                </td>
                <td class="text-right">
                    <strong>Customer:</strong> {{ !empty($penjualan->member->nama) ? $penjualan->member->nama : 'Walk In Customer' }}
                </td>
            </tr>
            @if(!empty($penjualan->tipe_order) || !empty($penjualan->nomor_meja))
            <tr>
                <td>
                    <strong>Order Type:</strong> {{ $penjualan->tipe_order ?? 'Dine-In' }}
                </td>
                <td class="text-right">
                    <strong>Table:</strong> {{ $penjualan->nomor_meja ?? 'Table 1' }}
                </td>
            </tr>
            @endif
        </table>

        <div class="divider-dashed"></div>

        <!-- 5. Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-left" width="42%">Product</th>
                    <th class="text-center" width="16%">Qty</th>
                    <th class="text-right" width="20%">Price</th>
                    <th class="text-right" width="22%">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $item)
                <tr class="item-row">
                    <td class="bold">{{ $item->produk->nama_produk ?? 'Item' }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right">{{ format_uang($item->harga_jual) }}</td>
                    <td class="text-right bold">{{ format_uang($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider-dashed"></div>

        <!-- 6. Summary Breakdown -->
        <div class="summary-wrapper">
            <div class="summary-left">
                <div>Items: {{ $penjualan->total_item ?? count($detail) }}</div>
            </div>
            <table class="summary-right-table">
                <tr>
                    <td class="text-left">Sub Total</td>
                    <td class="text-right bold">{{ format_uang($penjualan->total_harga) }}</td>
                </tr>
                @if(($penjualan->diskon ?? 0) > 0)
                <tr>
                    <td class="text-left">Discount</td>
                    <td class="text-right">-{{ $penjualan->diskon }}%</td>
                </tr>
                @endif
                <tr class="grand-total-row">
                    <td class="text-left">Grand Total</td>
                    <td class="text-right">{{ format_currency($penjualan->bayar) }}</td>
                </tr>
                @php
                    $isPaid = (strtolower($penjualan->status_pembayaran ?? '') === 'paid' || ($penjualan->diterima ?? 0) >= ($penjualan->bayar ?? 0));
                @endphp
                <tr>
                    <td class="text-left">Status</td>
                    <td class="text-right bold" style="text-transform: uppercase;">
                        @if($isPaid)
                            PAID
                        @else
                            UNPAID
                        @endif
                    </td>
                </tr>
                @if(!empty($penjualan->metode_pembayaran))
                <tr>
                    <td class="text-left">Payment Method</td>
                    <td class="text-right" style="text-transform: uppercase;">
                        @php
                            $pm = strtolower($penjualan->metode_pembayaran);
                        @endphp
                        @if($pm === 'card')
                            Debit Card
                        @elseif($pm === 'online' || $pm === 'e-wallet')
                            E-Wallet
                        @else
                            Cash
                        @endif
                    </td>
                </tr>
                @endif
            </table>
        </div>

        <div class="divider-dashed"></div>

        <!-- 7. Urdu Terms & Conditions (شرائط و ضوابط) -->
        <div class="urdu-section" dir="rtl">
            <div class="urdu-heading">{{ !empty($setting->terms_title) ? $setting->terms_title : 'شرائط و ضوابط' }}</div>
            <ul class="urdu-list">
                @php
                    $rawTerms = !empty($setting->terms_conditions) 
                        ? $setting->terms_conditions 
                        : "خریدہ ہوا مال واپس یا تبدیل نہیں ہوگاـ\nوارنٹی صرف کمپنی / مینوفیکچرر کی شرائط کے مطابق ہوگیـ\nبل کے بغیر کسی قسم کی شکایت قبول نہیں کی جائے گیـ\nہمارے ساتھ تعاون کا شکریہـ";
                    $termsLines = explode("\n", str_replace("\r", "", $rawTerms));
                @endphp
                @foreach($termsLines as $tLine)
                    @if(trim($tLine) !== '')
                    <li>{{ trim($tLine) }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
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