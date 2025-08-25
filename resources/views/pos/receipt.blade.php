<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->sale_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .store-info {
            font-size: 12px;
            color: #666;
        }
        .sale-info {
            margin-bottom: 15px;
        }
        .sale-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .sale-date {
            font-size: 12px;
            color: #666;
        }
        .items {
            margin-bottom: 15px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .item-name {
            flex: 1;
        }
        .item-price {
            text-align: right;
        }
        .item-details {
            font-size: 10px;
            color: #666;
            margin-left: 10px;
        }
        .totals {
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 12px;
        }
        .total-row.final {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        .payment-info {
            margin-top: 15px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
        @media print {
            body {
                padding: 0;
            }
            .receipt {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="store-name">SPORTS SHOP</div>
            <div class="store-info">
                123 Sports Street<br>
                Lagos, Nigeria<br>
                Phone: +234 123 456 7890<br>
                Email: info@sportsshop.com
            </div>
        </div>

        <div class="sale-info">
            <div class="sale-number">Sale #: {{ $sale->sale_number }}</div>
            <div class="sale-date">Date: {{ $sale->formatted_date }}</div>
            <div class="sale-date">Cashier: {{ $sale->user->name }}</div>
        </div>

        <div class="items">
            @foreach($sale->items as $item)
                <div class="item">
                    <div class="item-name">
                        {{ $item->product->name }}
                        <div class="item-details">
                            Qty: {{ $item->quantity }} x ৳{{ number_format($item->unit_price, 2) }}
                            @if($item->discount_amount > 0)
                                <br>Discount: ৳{{ number_format($item->discount_amount, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="item-price">
                        ৳{{ number_format($item->subtotal, 2) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>৳{{ number_format($sale->subtotal, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Tax (5%):</span>
                <span>৳{{ number_format($sale->tax_amount, 2) }}</span>
            </div>
            @if($sale->discount_amount > 0)
                <div class="total-row">
                    <span>Discount:</span>
                    <span>-৳{{ number_format($sale->discount_amount, 2) }}</span>
                </div>
            @endif
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>৳{{ number_format($sale->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="payment-info">
            <div class="payment-row">
                <span>Payment Method:</span>
                <span>{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
            </div>
            <div class="payment-row">
                <span>Amount Paid:</span>
                <span>৳{{ number_format($sale->amount_paid, 2) }}</span>
            </div>
            @if($sale->change_amount > 0)
                <div class="payment-row">
                    <span>Change:</span>
                    <span>৳{{ number_format($sale->change_amount, 2) }}</span>
                </div>
            @endif
        </div>

        @if($sale->notes)
            <div style="margin-top: 15px; border-top: 1px dashed #000; padding-top: 10px;">
                <div style="font-size: 12px; font-weight: bold;">Notes:</div>
                <div style="font-size: 11px; color: #666;">{{ $sale->notes }}</div>
            </div>
        @endif

        <div class="footer">
            Thank you for your purchase!<br>
            Please keep this receipt for your records.<br>
            For returns, please bring this receipt within 7 days.
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
