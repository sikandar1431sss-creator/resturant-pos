<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale Invoice #INV-{{ tambah_nol_didepan($penjualan->id_penjualan, 4) }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000000;
        }

        body {
            background-color: #ffffff;
            color: #000000;
            font-size: 11px;
            padding: 20px;
            line-height: 1.4;
        }

        .invoice-wrapper {
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            padding: 20px;
            background: #ffffff;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }

        /* Logo Box */
        .logo-box {
            text-align: center;
            margin-bottom: 8px;
        }

        .logo-img {
            max-width: 80px;
            max-height: 80px;
            border: 1px solid #000000;
            padding: 4px;
            border-radius: 4px;
        }

        .company-name {
            font-size: 18px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 2px;
        }

        .company-meta {
            font-size: 11px;
            font-weight: 600;
        }

        .divider-dashed {
            border-top: 1.5px dashed #000000;
            margin: 8px 0;
        }

        .invoice-title {
            font-size: 15px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
            padding: 2px 0;
        }

        .meta-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 4px 0;
        }

        .items-table th {
            border-bottom: 1.5px dashed #000000;
            padding: 5px 2px;
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 4px 2px;
            vertical-align: top;
        }

        .summary-box {
            width: 100%;
            font-size: 11px;
            margin: 6px 0;
        }

        .grand-total-row td {
            font-size: 13px;
            font-weight: 900;
            border-top: 1.5px solid #000000;
            border-bottom: 3px double #000000;
            padding: 4px 0;
        }

        .urdu-section {
            direction: rtl;
            text-align: right;
            padding-top: 6px;
            font-size: 11px;
        }

        .urdu-heading {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 6px;
        }

        .urdu-line {
            margin-bottom: 3px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <!-- Logo -->
        <div class="logo-box">
            @php
                $logoPath = !empty($setting->path_logo) ? public_path($setting->path_logo) : public_path('img/logo.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Logo" class="logo-img">
            @else
                <img src="{{ public_path('img/logo.png') }}" alt="Logo" class="logo-img">
            @endif
        </div>

        <!-- Company Name & Contact -->
        <div class="text-center">
            <div class="company-name">{{ strtoupper($setting->nama_perusahaan ?? 'TAJ ELECTRIC CENTER') }}</div>
            <div class="company-meta"><strong>Phone:</strong> {{ $setting->telepon ?? '03193712392' }}</div>
            <div class="company-meta">{{ strtoupper($setting->alamat ?? 'CINEMA ROAD KHANEWAL') }}</div>
        </div>

        <div class="divider-dashed"></div>

        <!-- Title -->
        <div class="invoice-title">SALE INVOICE</div>

        <!-- Meta Table -->
        <table class="meta-table">
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

        <!-- Items Table -->
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
                <tr>
                    <td class="bold">{{ $item->produk->nama_produk ?? 'Item' }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right">{{ format_uang($item->harga_jual) }}</td>
                    <td class="text-right bold">{{ format_uang($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider-dashed"></div>

        <!-- Summary Table -->
        <table class="summary-box">
            <tr>
                <td width="35%" style="vertical-align: top;">
                    <strong>Items: {{ $penjualan->total_item ?? count($detail) }}</strong>
                </td>
                <td width="65%">
                    <table width="100%" style="border-collapse: collapse;">
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
                </td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        <!-- Urdu Terms & Conditions (شرائط و ضوابط) -->
        <div class="urdu-section" dir="rtl">
            <div class="urdu-heading">{{ !empty($setting->terms_title) ? $setting->terms_title : 'شرائط و ضوابط' }}</div>
            @php
                $rawTerms = !empty($setting->terms_conditions) 
                    ? $setting->terms_conditions 
                    : "خریدہ ہوا مال واپس یا تبدیل نہیں ہوگاـ\nوارنٹی صرف کمپنی / مینوفیکچرر کی شرائط کے مطابق ہوگیـ\nبل کے بغیر کسی قسم کی شکایت قبول نہیں کی جائے گیـ\nہمارے ساتھ تعاون کا شکریہـ";
                $termsLines = explode("\n", str_replace("\r", "", $rawTerms));
            @endphp
            @foreach($termsLines as $tLine)
                @if(trim($tLine) !== '')
                <div class="urdu-line">{{ trim($tLine) }}</div>
                @endif
            @endforeach
        </div>
    </div>
</body>
</html>