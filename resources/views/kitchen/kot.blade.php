<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KOT #{{ tambah_nol_didepan($penjualan->id_penjualan, 3) }} - Kitchen Order Token</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Courier New', monospace;
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
            padding: 14px 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
            border: 1px solid #e2e8f0;
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
            color: #222;
        }

        /* Token Display Box - Clean Crisp Border */
        .token-box {
            border: 2px solid #000000;
            background: #ffffff;
            color: #000000;
            text-align: center;
            padding: 8px 4px;
            margin: 8px 0;
            border-radius: 6px;
        }

        .token-label {
            font-size: 9pt;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .token-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 26pt;
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: 0.04em;
            margin: 3px 0;
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
            border-bottom: 1px dashed #bbb;
        }

        .item-qty-col {
            width: 32px;
            font-size: 14pt;
            font-weight: 900;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
        }

        .item-name-col {
            font-size: 11pt;
            font-weight: 800;
            line-height: 1.25;
            padding-left: 6px;
        }

        .item-category-tag {
            font-size: 7.5pt;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            display: block;
            margin-top: 1px;
        }

        .item-cooking-note-ticket {
            margin-top: 3px;
            font-size: 8.5pt;
            font-weight: 800;
            border: 1px solid #000;
            color: #000;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
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
            background: #ea580c;
            color: #ffffff !important;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12pt;
            text-decoration: none;
            cursor: pointer;
            border: none;
            margin-top: 14px;
            box-shadow: 0 4px 10px rgba(234, 88, 12, 0.25);
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
                border: none;
                padding: 4px 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
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

        <!-- Clean Crisp Token Display -->
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
            @elseif(!empty($penjualan->nama_pelanggan))
            <tr>
                <td colspan="2"><strong>Customer:</strong> {{ $penjualan->nama_pelanggan }} ({{ $penjualan->telepon_pelanggan ?? '' }})</td>
            </tr>
            @endif
            @if(!empty($penjualan->alamat_pengiriman))
            <tr>
                <td colspan="2" style="font-size: 8.5pt; color: #333;"><strong>Address:</strong> {{ $penjualan->alamat_pengiriman }}</td>
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
                        @if(!empty($item->catatan))
                            <div class="item-cooking-note-ticket">
                                NOTE: {{ strtoupper($item->catatan) }}
                            </div>
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
            <div class="kot-notes-title">SPECIAL INSTRUCTIONS:</div>
            <div class="kot-notes-text">{{ $penjualan->catatan }}</div>
        </div>
        @endif

        <div class="divider-dashed"></div>

        <div class="text-center" style="font-size: 8.5pt; font-weight: 700; margin-top: 6px; color: #444;">
            * PREPARE FRESH &amp; HOT *
        </div>

        <!-- Print Action Button (Hidden during print) -->
        <button onclick="window.print()" class="btn-print-action no-print">
            Print KOT Ticket
        </button>
    </div>

</body>
</html>

