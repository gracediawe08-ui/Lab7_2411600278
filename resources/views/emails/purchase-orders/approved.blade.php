<x-mail::message>
# Purchase Order Approved

Good news! Your purchase order has been reviewed and approved.

**Order Number:** {{ $purchaseOrder->order_number }}

**Supplier:** {{ $purchaseOrder->supplier->company_name ?? 'N/A' }}

**Created By:** {{ $purchaseOrder->creator->name ?? 'N/A' }}

**Approved By:** {{ $purchaseOrder->approver->name ?? 'N/A' }}

**Order Date:** {{ $purchaseOrder->order_date?->format('F d, Y') ?? 'N/A' }}

**Expected Delivery:** {{ $purchaseOrder->expected_delivery_date?->format('F d, Y') ?? 'Not specified' }}

**Approval Date:** {{ $purchaseOrder->approved_at?->format('F d, Y h:i A') ?? 'N/A' }}

**Status:** {{ ucfirst(str_replace('_', ' ', $purchaseOrder->status)) }}

<x-mail::table>
| Product | Quantity | Unit Cost | Total |
|:--------|--------:|----------:|------:|
@foreach ($purchaseOrder->items as $item)
| {{ $item->product->name ?? 'Unknown Product' }} | {{ $item->quantity }} | PHP {{ number_format((float) $item->unit_cost, 2) }} | PHP {{ number_format((float) $item->line_total, 2) }} |
@endforeach
</x-mail::table>

**Total Amount:** PHP {{ number_format((float) $purchaseOrder->total_amount, 2) }}

<x-mail::button :url="route('purchase-orders.show', $purchaseOrder)">
View Approved Purchase Order
</x-mail::button>

Your purchase order is now approved and ready for the next processing step.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
