<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $order->invoice->invoice_number ?? $order->id }}</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #334155;
        }
        body { margin: 0; padding: 10px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .invoice-title { font-size: 20px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .address-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .address-box { width: 33.33%; vertical-align: top; padding-right: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 8px; font-size: 10px; font-weight: bold; text-align: left; text-transform: uppercase; color: #64748b; }
        .table td { border: 1px solid #e2e8f0; padding: 8px; vertical-align: top; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-slate-500 { color: #64748b; font-size: 10px; }
        .total-row td { background-color: #f8fafc; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <div class="invoice-title">INVOICE</div>
                <div style="margin-top: 5px;">
                    <strong>Invoice No:</strong> {{ $order->invoice->invoice_number ?? 'N/A' }}<br>
                    <strong>Order No:</strong> #{{ $order->order_number ?? $order->id }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1px solid #e2e8f0; margin-bottom: 20px;">

    <!-- Address Section: Dispatched From | Billed To | Shipping Address -->
    <table class="address-table">
        <tr>
            <!-- Warehouse / Origin Location -->
            <td class="address-box">
                <strong style="font-size: 11px; color: #4338ca; text-transform: uppercase;">Dispatched From:</strong>
                <div style="margin-top: 4px;">
                    @if($order->warehouse)
                        <strong style="color: #0f172a;">{{ $order->warehouse->name }}</strong><br>
                        {{ $order->warehouse->location }}
                    @else
                        <strong style="color: #0f172a;">Carolina Prime Main Hub</strong><br>
                        Garner, North Carolina
                    @endif
                </div>
            </td>

            <!-- Customer / Billed To -->
            <td class="address-box">
                <strong style="font-size: 11px; color: #0f172a; text-transform: uppercase;">Billed To:</strong>
                <div style="margin-top: 4px;">
                    <strong>{{ $order->user->name ?? 'Guest User' }}</strong><br>
                    Email: {{ $order->user->email ?? 'N/A' }}<br>
                    Phone: {{ $order->user->phone ?? 'N/A' }}
                </div>
            </td>

            <!-- Delivery / Shipping Address -->
            <td class="address-box">
                <strong style="font-size: 11px; color: #0f172a; text-transform: uppercase;">Shipping Address:</strong>
                <div style="margin-top: 4px;">
                    @if(is_array($order->shipping_address))
                        <strong>{{ $order->shipping_address['name'] ?? '' }}</strong><br>
                        {{ $order->shipping_address['address_line_1'] ?? '' }}<br>
                        @if(!empty($order->shipping_address['address_line_2']))
                            {{ $order->shipping_address['address_line_2'] }}<br>
                        @endif
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['pincode'] ?? '' }}<br>
                        Phone: {{ $order->shipping_address['phone'] ?? '' }}
                    @else
                        N/A
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="table">
        <thead>
            <tr>
                <th width="45%">Product Details</th>
                <th width="15%">Unit Price</th>
                <th width="10%">Qty</th>
                <th width="30%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <div class="font-bold" style="color: #0f172a;">
                        {{ $item->product->name ?? ($item->variant->product->name ?? 'N/A') }}
                    </div>
                    
                    @if($item->variant)
                        <div class="text-slate-500" style="margin-top: 2px;">
                            SKU: {{ $item->variant->variant_sku ?? 'N/A' }} 
                            @if(!empty($item->variant->size)) | Size: {{ $item->variant->size }} @endif
                            @if(!empty($item->variant->color)) | Color: {{ $item->variant->color }} @endif
                        </div>
                    @endif
                </td>
                <td>₹ {{ number_format($item->price, 2) }}</td>
                <td class="font-bold">{{ $item->quantity }}</td>
                <td class="text-right font-bold">₹ {{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">Grand Total</td>
                <td class="text-right" style="color: #4f46e5;">₹ {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>