<x-mail::message>
# New Purchase Order Created

A new purchase order has been created in the Inventory Management System.

**Order Number:** {{ $purchaseOrder->order_number }}

**Supplier:** {{ $purchaseOrder->supplier->company_name ?? 'N/A' }}

**Created By:** {{ $purchaseOrder->creator->name ?? 'N/A' }}

**Order Date:** {{ $purchaseOrder->order_date?->format('F d, Y') ?? 'N/A' }}

**Expected Delivery:** {{ $purchaseOrder->expected_delivery_date?->format('F d, Y') ?? 'Not specified' }}

**Status:** {{ ucfirst(str_replace('_', ' ', $purchaseOrder->status)) }}

<x-mail::table>
| Product | Quantity | Unit Cost | Total |
|:--------|--------:|----------:|------:|
@foreach ($purchaseOrder->items as $item)
| {{ $item->product->name ?? 'Unknown Product' }} | {{ $item->quantity }} | ₱{{ number_format((float) $item->unit_cost, 2) }} | ₱{{ number_format((float) $item->line_total, 2) }} |
@endforeach
</x-mail::table>

**Subtotal:** ₱{{ number_format((float) $purchaseOrder->subtotal, 2) }}

**Tax:** ₱{{ number_format((float) $purchaseOrder->tax_amount, 2) }}

**Shipping:** ₱{{ number_format((float) $purchaseOrder->shipping_cost, 2) }}

**Total Amount:** ₱{{ number_format((float) $purchaseOrder->total_amount, 2) }}

@if ($purchaseOrder->notes)
**Notes:** {{ $purchaseOrder->notes }}
@endif

<x-mail::button :url="route('purchase-orders.show', $purchaseOrder)">
View Purchase Order
</x-mail::button>

Please review this purchase order in the inventory management system.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>