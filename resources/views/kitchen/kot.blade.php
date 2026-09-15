<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KOT #{{ tambah_nol_didepan($penjualan->id_penjualan, 3) }} - Kitchen Order Token</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

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

        .kot-container {
            width: 80mm;
            max-width: 100%;
            margin: 0 auto;
            background: #ffffff;
            padding: 14px 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: 700; }
        .bolder { font-weight: 900; }

        /* Header */
        .kot-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .kot-main-title {
            font-size: 15pt;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .kot-subtitle {
            font-size: 8.5pt;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: #333;
        }

        /* Token Display Box */
        .token-box {
            border: 2px solid #000000;
            background: #000000;
            color: #ffffff !important;
            text-align: center;
            padding: 8px 4px;
            margin: 8px 0;
            border-radius: 4px;
        }

        .token-box * {
            color: #ffffff !important;
        }

        .token-label {
            font-size: 9pt;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .token-number {
            font-size: 26pt;
            font-weight: 900;
            line-height: 1;
            letter-spacing: 0.05em;
            margin: 2px 0;
        }

        .token-order-type {
            font-size: 11pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Order Info Grid */
        .info-table {
            width: 100%;
            margin: 6px 0;
            font-size: 9.5pt;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Dividers */
        .divider-solid {
            border-top: 1.5px solid #000000;
            margin: 6px 0;
        }

        .divider-dashed {
            border-top: 1px dashed #000000;
            margin: 6px 0;
        }

        .divider-double {
            border-top: 3px double #000000;
            margin: 6px 0;
        }

        /* Items List */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        .items-table th {
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            padding: 5px 2px;
            font-size: 9pt;
            font-weight: 900;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 6px 2px;
            vertical-align: top;
            border-bottom: 1px dashed #ccc;
        }

        .item-qty-col {
            width: 32px;
            font-size: 14pt;
            font-weight: 900;
            text-align: center;
        }

        .item-name-col {
            font-size: 11pt;
            font-weight: 800;
            line-height: 1.25;
            padding-left: 4px;
        }

        .item-category-tag {
            font-size: 7.5pt;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            display: block;
            margin-top: 1px;
        }

        /* Notes Box */
        .kot-notes-box {
            border: 1.5px dashed #000;
            padding: 6px 8px;
            margin: 8px 0;
            background: #fff;
            border-radius: 4px;
        }

        .kot-notes-title {
            font-size: 8.5pt;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .kot-notes-text {
            font-size: 10pt;
            font-weight: 800;
            color: #000;
            line-height: 1.3;
        }

        /* Total items summary */
        .kot-summary {
            display: flex;
            justify-content: space-between;
            font-size: 11pt;
            font-weight: 900;
            padding: 6px 2px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin-top: 4px;
        }

        .btn-print-action {
            display: block;
            width: 100%;
            background: #0f172a;
            color: #ffffff !important;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12pt;
            text-decoration: none;
            cursor: pointer;
            border: none;
            margin-top: 14px;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
                margin: 0;
            }
            .kot-container {
                width: 100%;
                max-width: 100%;
                box-shadow: none;
                padding: 4px 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .token-box {
                background: #000 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .token-box * {
                color: #fff !important;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="kot-container">
        <!-- Header -->
        <div class="kot-header">
            <div class="kot-main-title">KITCHEN ORDER TICKET</div>
            <div class="kot-subtitle">{{ strtoupper($setting->nama_perusahaan ?? 'FAST FOOD RESTAURANT') }}</div>
        </div>

        <!-- Big Token Display -->
        <div class="token-box">
            <div class="token-label">KITCHEN TOKEN</div>
            <div class="token-number">#{{ tambah_nol_didepan($penjualan->id_penjualan, 3) }}</div>
            <div class="token-order-type">
                {{ strtoupper($penjualan->tipe_order ?? 'Dine-In') }}
                @if(($penjualan->tipe_order ?? 'Dine-In') == 'Dine-In' && !empty($penjualan->nomor_meja))
                    - {{ strtoupper($penjualan->nomor_meja) }}
                @endif
            </div>
        </div>

        <!-- Order Meta Details -->
        <table class="info-table">
            <tr>
                <td style="width: 45%;"><strong>Invoice:</strong> #INV-{{ tambah_nol_didepan($penjualan->id_penjualan, 5) }}</td>
                <td style="width: 55%; text-align: right;"><strong>Date:</strong> {{ date('d-M-Y', strtotime($penjualan->created_at)) }}</td>
            </tr>
            <tr>
                <td><strong>Cashier:</strong> {{ optional($penjualan->user)->name ?? 'Cashier' }}</td>
                <td style="text-align: right;"><strong>Time:</strong> {{ date('h:i:s A', strtotime($penjualan->created_at)) }}</td>
            </tr>
            @if(!empty($penjualan->member->nama))
            <tr>
                <td colspan="2"><strong>Customer:</strong> {{ $penjualan->member->nama }}</td>
            </tr>
            @endif
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 15%; text-align: center;">QTY</th>
                    <th style="width: 85%; text-align: left; padding-left: 6px;">ITEM DESCRIPTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $item)
                <tr>
                    <td class="item-qty-col">{{ $item->jumlah }}</td>
                    <td class="item-name-col">
                        {{ optional($item->produk)->nama_produk ?? 'Menu Item' }}
                        @if(!empty($item->produk->kategori->nama_kategori))
                            <span class="item-category-tag">[{{ $item->produk->kategori->nama_kategori }}]</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Items Summary -->
        <div class="kot-summary">
            <span>TOTAL PREP ITEMS:</span>
            <span>{{ $penjualan->total_item }}</span>
        </div>

        <!-- Special Instructions / Notes -->
        @if(!empty($penjualan->catatan))
        <div class="kot-notes-box">
            <div class="kot-notes-title">⚠️ SPECIAL INSTRUCTIONS:</div>
            <div class="kot-notes-text">{{ $penjualan->catatan }}</div>
        </div>
        @endif

        <div class="divider-dashed"></div>

        <div class="text-center" style="font-size: 8.5pt; font-weight: 700; margin-top: 6px; color: #444;">
            * PREPARE FRESH &amp; HOT *
        </div>

        <!-- Print Action Button (Hidden during print) -->
        <button onclick="window.print()" class="btn-print-action no-print">
            🖨️ Print KOT Ticket
        </button>
    </div>

</body>
</html>
