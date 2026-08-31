<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Wholesale Tax Invoice - {{ $order->invoice->invoice_number ?? $order->id }}</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
        }
        body { margin: 0; padding: 15px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .brand-title { font-size: 18px; font-weight: bold; color: #0b2212; letter-spacing: 0.5px; }
        .brand-sub { font-size: 9.5px; color: #546b5a; margin-top: 2px; }
        .invoice-title { font-size: 20px; font-weight: bold; color: #0b2212; text-align: right; text-transform: uppercase; }
        .address-table { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
        .address-box { width: 33.33%; vertical-align: top; padding-right: 12px; }
        .box-title { font-size: 10px; font-weight: bold; text-transform: uppercase; color: #0b2212; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; margin-bottom: 6px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background-color: #0b2212; color: #ffffff; border: 1px solid #0b2212; padding: 8px 10px; font-size: 10px; font-weight: bold; text-align: left; text-transform: uppercase; }
        .table td { border: 1px solid #e2e8f0; padding: 8px 10px; vertical-align: middle; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .total-row td { background-color: #f8fafc; font-size: 12px; font-weight: bold; border-top: 2px solid #0b2212; }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="brand-title">CAROLINA PRIME DISTRIBUTORS</div>
                <div class="brand-sub">Wholesale Distribution Central Hub &middot; Garner, NC</div>
                <div class="brand-sub">Phone: (478) 444-5385 &middot; Web: carolinaprimedistributors.com</div>
            </td>
            <td style="vertical-align: middle; text-align: right;">
                <div class="invoice-title">TAX INVOICE</div>
                <div style="margin-top: 5px; font-size: 10.5px;">
                    <strong>Invoice No:</strong> {{ $order->invoice->invoice_number ?? ('INV-' . $order->id) }}<br>
                    <strong>Order No:</strong> #{{ $order->order_number ?? $order->id }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}
                </div>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1.5px solid #0b2212; margin-bottom: 18px;">

    <!-- Address Section -->
    <table class="address-table">
        <tr>
            <!-- Dispatched From -->
            <td class="address-box">
                <div class="box-title">DISPATCH HUB</div>
                <div>
                    <strong>Garner Central Distribution Hub</strong><br>
                    Carolina Prime Logistics Division<br>
                    Garner, North Carolina &middot; USA
                </div>
            </td>

            <!-- Billed To -->
            <td class="address-box">
                <div class="box-title">BILLED TO (BUYER)</div>
                <div>
                    <strong>{{ $order->user->name ?? 'Verified Trade Buyer' }}</strong><br>
                    @if(!empty($order->user->business_name))
                        <strong>{{ $order->user->business_name }}</strong><br>
                    @endif
                    Email: {{ $order->user->email ?? 'N/A' }}<br>
                    Phone: {{ $order->user->phone ?? 'N/A' }}
                </div>
            </td>

            <!-- Shipping Address -->
            <td class="address-box">
                <div class="box-title">DELIVERY DESTINATION</div>
                <div>
                    @php
                        $ship = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                    @endphp
                    @if(!empty($ship))
                        <strong>{{ $ship['name'] ?? '' }}</strong><br>
                        {{ $ship['address_line_1'] ?? '' }}<br>
                        @if(!empty($ship['address_line_2'])) {{ $ship['address_line_2'] }}<br> @endif
                        {{ $ship['city'] ?? '' }}, {{ $ship['state'] ?? '' }} - {{ $ship['pincode'] ?? '' }}<br>
                        Phone: {{ $ship['phone'] ?? '' }}
                    @else
                        Standard Customer Address
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="table">
        <thead>
            <tr>
                <th width="45%">Product &amp; Variant Specifications</th>
                <th width="16%">Unit Rate</th>
                <th width="12%">Quantity</th>
                <th width="27%" class="text-right">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            @php
                $prod = $item->variant->product ?? ($item->product ?? null);
            @endphp
            <tr>
                <td>
                    <strong style="color: #0b2212; font-size: 11px;">
                        {{ $prod->name ?? 'Product Unavailable' }}
                    </strong>
                    <div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">
                        SKU: {{ $item->variant->variant_sku ?? ($prod->sku ?? '-') }}
                        @if(!empty($item->variant->size)) | Pack: {{ $item->variant->size }} @endif
                        @if(!empty($item->variant->color)) | Color: {{ $item->variant->color }} @endif
                    </div>
                </td>
                <td>$ {{ number_format($item->price, 2) }}</td>
                <td class="font-bold">{{ $item->quantity }} units</td>
                <td class="text-right font-bold">$ {{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right" style="text-transform: uppercase; letter-spacing: 0.5px;">Grand Wholesale Total</td>
                <td class="text-right" style="color: #0b2212; font-size: 13px;">$ {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer Terms & Note -->
    <div style="margin-top: 26px; border-top: 1px solid #e2e8f0; padding-top: 12px; font-size: 9px; color: #64748b; text-align: center;">
        Thank you for your wholesale business with Carolina Prime Distributors. All shipments are route inspected and verified before dispatch.
    </div>

</body>
</html>