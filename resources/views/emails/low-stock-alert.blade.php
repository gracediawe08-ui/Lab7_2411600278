<x-mail::message>
# Low Stock Alert

<font></font>
A product in your inventory has reached its reorder level and requires attention.

**Product Name:** {{ $product->name }}

**SKU:** {{ $product->sku }}

**Category:** {{ $product->category ?? 'N/A' }}

**Current Stock:** {{ $product->quantity }}

**Reorder Level:** {{ $product->reorder_level }}

**Package Size:** {{ $product->package_size ?? 1 }}

**Supplier:** {{ $product->supplierRecord->company_name ?? $product->supplier ?? 'Not Assigned' }}

**Unit Price:** PHP {{ number_format((float) $product->unit_price, 2) }}

@if ($product->quantity <= 0)
**Stock Status:** OUT OF STOCK
@else
**Stock Status:** LOW STOCK
@endif

<x-mail::button :url="route('dashboard')">
View Inventory Dashboard
</x-mail::button>

Please review this product and arrange replenishment as soon as possible to avoid inventory shortages.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
