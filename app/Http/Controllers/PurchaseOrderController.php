<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Mail\PurchaseOrderCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseOrderApproved;
use App\Mail\PurchaseOrderSent;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
{
    Gate::authorize('viewAny', PurchaseOrder::class);

    $query = PurchaseOrder::with(['supplier', 'creator'])
            ->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($supplier) use ($search) {
                        $supplier->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $suppliers = Supplier::orderBy('company_name')->get();

        $totalOrders = PurchaseOrder::count();
        $pendingOrders = PurchaseOrder::where('status', 'pending_approval')->count();
        $approvedOrders = PurchaseOrder::where('status', 'approved')->count();
        $receivedOrders = PurchaseOrder::where('status', 'received')->count();

        return view('purchase-orders.index', compact(
            'orders',
            'suppliers',
            'totalOrders',
            'pendingOrders',
            'approvedOrders',
            'receivedOrders'
        ));
    }

    public function create()
    {
        Gate::authorize('create', PurchaseOrder::class);
        $suppliers = Supplier::active()
            ->orderBy('company_name')
            ->get();

        $products = Product::with('supplierRecord')
            ->orderBy('name')
            ->get();

        return view('purchase-orders.create', compact(
            'suppliers',
            'products'
        ));
    }

    public function store(Request $request)
{
    Gate::authorize('create', PurchaseOrder::class);
        $validated = $this->validateOrder($request);

        $order = DB::transaction(function () use ($validated) {
            $supplier = Supplier::active()
                ->findOrFail($validated['supplier_id']);

            $this->ensureProductsBelongToSupplier(
                $validated['items'],
                $supplier->id
            );

            $order = PurchaseOrder::create([
                'order_number' => $this->generateOrderNumber(),
                'supplier_id' => $supplier->id,
                'created_by' => Auth::id(),
                'order_date' => $validated['order_date'],
                'expected_delivery_date' =>
                    $validated['expected_delivery_date'] ?? null,
                'status' => 'draft',
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'received_quantity' => 0,
                    'unit_cost' => $item['unit_cost'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            $order->recalculateTotals();

            return $order;
        });

        $adminEmails = \App\Models\User::where('role', 'admin')
            ->whereNotNull('email')
            ->pluck('email');

        foreach ($adminEmails as $email) {
            Mail::to($email)->send(
                new PurchaseOrderCreated($order)
            );
        }

        return redirect()
            ->route('purchase-orders.show', $order)
            ->with('success', 'Purchase Order created successfully!');
}

    public function show(PurchaseOrder $purchaseOrder)
{
    Gate::authorize('view', $purchaseOrder);
        $purchaseOrder->load([
            'supplier',
            'creator',
            'approver',
            'items.product',
        ]);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
{
    Gate::authorize('update', $purchaseOrder);
        abort_unless(
            in_array($purchaseOrder->status, ['draft', 'rejected']),
            403,
            'Only draft or rejected orders can be edited.'
        );

        $purchaseOrder->load('items.product');

        $suppliers = Supplier::active()
            ->orderBy('company_name')
            ->get();

        $products = Product::with('supplierRecord')
            ->orderBy('name')
            ->get();

        return view('purchase-orders.edit', compact(
            'purchaseOrder',
            'suppliers',
            'products'
        ));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
{
    Gate::authorize('update', $purchaseOrder);
        abort_unless(
            in_array($purchaseOrder->status, ['draft', 'rejected']),
            403,
            'This Purchase Order can no longer be edited.'
        );

        $validated = $this->validateOrder($request);

        DB::transaction(function () use ($validated, $purchaseOrder) {
            $supplier = Supplier::active()
                ->findOrFail($validated['supplier_id']);

            $this->ensureProductsBelongToSupplier(
                $validated['items'],
                $supplier->id
            );

            $purchaseOrder->update([
                'supplier_id' => $supplier->id,
                'order_date' => $validated['order_date'],
                'expected_delivery_date' =>
                    $validated['expected_delivery_date'] ?? null,
                'status' => 'draft',
                'approved_by' => null,
                'approved_at' => null,
                'sent_at' => null,
                'received_at' => null,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            $purchaseOrder->items()->delete();

            foreach ($validated['items'] as $item) {
                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'received_quantity' => 0,
                    'unit_cost' => $item['unit_cost'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            $purchaseOrder->recalculateTotals();
        });

        return redirect()
            ->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order updated successfully!');
    }

   public function destroy(PurchaseOrder $purchaseOrder)
{
    Gate::authorize('delete', $purchaseOrder);

    abort_unless(
        in_array($purchaseOrder->status, ['draft', 'rejected']),
        403,
        'Only draft or rejected orders can be deleted.'
    );

    $purchaseOrder->delete();

    return redirect()
        ->route('purchase-orders.index')
        ->with('success', 'Purchase Order deleted successfully!');
}

public function submitForApproval(PurchaseOrder $purchaseOrder)
{
    Gate::authorize('submit', $purchaseOrder);

    abort_unless(
        in_array($purchaseOrder->status, ['draft', 'rejected']),
        403,
        'This order cannot be submitted.'
    );

    abort_if(
        $purchaseOrder->items()->count() === 0,
        422,
        'A Purchase Order must contain at least one product.'
    );

    $purchaseOrder->update([
        'status' => 'pending_approval',
    ]);

    return back()->with('success', 'Purchase Order submitted for approval!');
}

    
public function approve(PurchaseOrder $purchaseOrder)
{
    Gate::authorize('approve', $purchaseOrder);

    abort_unless(
        $purchaseOrder->canBeApproved(),
        403,
        'This order is not awaiting approval.'
    );

    $purchaseOrder->update([
        'status' => 'approved',
        'approved_by' => Auth::id(),
        'approved_at' => now(),
    ]);

    $purchaseOrder->load([
        'supplier',
        'creator',
        'approver',
        'items.product',
    ]);

    $recipients = collect([
        $purchaseOrder->creator?->email,
    ])
        ->merge(
            \App\Models\User::where('role', 'admin')
                ->pluck('email')
        )
        ->filter()
        ->unique();

    foreach ($recipients as $email) {
        Mail::to($email)->send(
            new PurchaseOrderApproved($purchaseOrder)
        );
    }

    return back()->with('success', 'Purchase Order approved successfully!');
}


    public function reject(Request $request, PurchaseOrder $purchaseOrder)
{
    Gate::authorize('reject', $purchaseOrder);
        abort_unless(
            $purchaseOrder->canBeApproved(),
            403,
            'This order is not awaiting approval.'
        );

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $purchaseOrder->update([
            'status' => 'rejected',
            'notes' => trim(
                ($purchaseOrder->notes ?? '')
                . "\nRejection reason: "
                . $validated['rejection_reason']
            ),
        ]);

        return back()->with('success', 'Purchase Order rejected.');
    }

    
public function markSent(PurchaseOrder $purchaseOrder)
{
    Gate::authorize('send', $purchaseOrder);

    abort_unless(
        $purchaseOrder->canBeSent(),
        403,
        'Only approved orders can be marked as sent.'
    );

    $purchaseOrder->update([
        'status' => 'sent',
        'sent_at' => now(),
    ]);

    $purchaseOrder->load([
        'supplier',
        'creator',
        'approver',
        'items.product',
    ]);

    $recipients = collect([
        $purchaseOrder->creator?->email,
    ])
        ->merge(
            \App\Models\User::where('role', 'admin')
                ->pluck('email')
        )
        ->filter()
        ->unique();

    foreach ($recipients as $email) {
        Mail::to($email)->send(
            new PurchaseOrderSent($purchaseOrder)
        );
    }

    return back()->with('success', 'Purchase Order marked as sent!');
}

   public function receive(Request $request, PurchaseOrder $purchaseOrder)
{
    Gate::authorize('receive', $purchaseOrder);
        $validated = $request->validate([
            'received_quantities' => 'required|array',
            'received_quantities.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $purchaseOrder) {
            $order = PurchaseOrder::whereKey($purchaseOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($order->status, ['sent', 'partially_received'])) {
                throw ValidationException::withMessages([
                    'received_quantities' =>
                        'Only sent orders can receive products.',
                ]);
            }

            $items = $order->items()
                ->lockForUpdate()
                ->get();

            $receivedSomething = false;

            foreach ($items as $item) {
                $received = (int) (
                    $validated['received_quantities'][$item->id] ?? 0
                );

                if ($received === 0) {
                    continue;
                }

                $remaining = $item->quantity - $item->received_quantity;

                if ($received > $remaining) {
                    throw ValidationException::withMessages([
                        'received_quantities' =>
                            "You cannot receive more than the remaining quantity for product "
                            . $item->product->name . ". Remaining: " . $remaining,
                    ]);
                }

                $receivedSomething = true;

            
                InventoryTransaction::create([
                    'product_id' => $item->product_id,
                    'transaction_type' => 'stock_in',
                    'quantity' => $received,
                    'reference_document' => $order->order_number,
                    'user_id' => Auth::id(),
                    'notes' => 'Received from Purchase Order '
                        . $order->order_number,
                ]);

                $item->increment('received_quantity', $received);
            }

            if (!$receivedSomething) {
                throw ValidationException::withMessages([
                    'received_quantities' =>
                        'Enter at least one quantity to receive.',
                ]);
            }

            $allReceived = $order->items()
                ->whereColumn('received_quantity', '<', 'quantity')
                ->doesntExist();

            $order->update([
                'status' => $allReceived ? 'received' : 'partially_received',
                'received_at' => $allReceived ? now() : null,
            ]);
        });

        return back()->with(
            'success',
            'Received quantities recorded and inventory updated successfully!'
        );
    }

    private function validateOrder(Request $request): array
    {
        return $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:2000',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|distinct|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0.01',
            'items.*.notes' => 'nullable|string|max:500',
        ]);
    }

    private function ensureProductsBelongToSupplier(
        array $items,
        int $supplierId
    ): void {
        $productIds = collect($items)
            ->pluck('product_id')
            ->unique()
            ->values();

        $matchingProducts = Product::whereIn('id', $productIds)
            ->where('supplier_id', $supplierId)
            ->count();

        if ($matchingProducts !== $productIds->count()) {
            throw ValidationException::withMessages([
                'items' =>
                    'All selected products must belong to the chosen supplier.',
            ]);
        }
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'PO-' . now()->format('Ymd') . '-'
                . strtoupper(Str::random(6));
        } while (PurchaseOrder::where('order_number', $number)->exists());

        return $number;
    }
}
