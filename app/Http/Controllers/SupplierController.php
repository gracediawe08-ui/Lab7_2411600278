<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('products');

        
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

       
        $allowedSorts = [
            'company_name',
            'lead_time_days',
            'minimum_order_amount',
            'created_at',
        ];

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $suppliers = $query
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255', 'unique:suppliers,company_name'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string'],
            'payment_terms' => ['required', Rule::in(['Net 30', 'COD'])],
            'lead_time_days' => ['required', 'integer', 'min:1'],
            'minimum_order_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully!');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('products');

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'company_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'company_name')->ignore($supplier->id),
            ],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string'],
            'payment_terms' => ['required', Rule::in(['Net 30', 'COD'])],
            'lead_time_days' => ['required', 'integer', 'min:1'],
            'minimum_order_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully!');
    }

    public function performance()
{
    $suppliers = Supplier::with([
        'purchaseOrders.items',
    ])->get();

    $suppliers->transform(function ($supplier) {

        $orders = $supplier->purchaseOrders;

        
        $supplier->total_orders = $orders->count();

        $supplier->total_purchase_value = $orders->sum(
            'total_amount'
        );

        $deliveredOrders = $orders->where('status', 'received');

        $ordersWithExpectedDate = $deliveredOrders->filter(
            fn ($order) => $order->expected_delivery_date !== null
                && $order->received_at !== null
        );

        $onTimeOrders = $ordersWithExpectedDate->filter(
            fn ($order) => $order->received_at->toDateString()
                <= $order->expected_delivery_date->toDateString()
        );

        $supplier->on_time_delivery_rate =
            $ordersWithExpectedDate->count() > 0
                ? round(
                    ($onTimeOrders->count() / $ordersWithExpectedDate->count()) * 100,
                    2
                )
                : null;

        $deliveryDays = $deliveredOrders
    ->filter(fn ($order) => $order->received_at && $order->order_date)
    ->map(fn ($order) => $order->order_date
        ->startOfDay()
        ->diffInDays($order->received_at->startOfDay())
    );

        $supplier->average_delivery_days = $deliveryDays->count() > 0
            ? round($deliveryDays->avg(), 2)
            : null;

        $activeDeliveryOrders = $orders->whereIn(
            'status',
            ['sent', 'partially_received', 'received']
        );

        $orderedQuantity = $activeDeliveryOrders
            ->flatMap(fn ($order) => $order->items)
            ->sum('quantity');

        $receivedQuantity = $activeDeliveryOrders
            ->flatMap(fn ($order) => $order->items)
            ->sum('received_quantity');

        $supplier->fulfillment_accuracy = $orderedQuantity > 0
            ? round(($receivedQuantity / $orderedQuantity) * 100, 2)
            : null;

        return $supplier;
    });

    $summary = [
        'total_suppliers' => $suppliers->count(),
        'total_orders' => $suppliers->sum('total_orders'),
        'total_purchase_value' => $suppliers->sum('total_purchase_value'),
        'average_on_time_rate' => $suppliers
            ->whereNotNull('on_time_delivery_rate')
            ->avg('on_time_delivery_rate'),
    ];

    return view('suppliers.performance', compact(
        'suppliers',
        'summary'
    ));
}
}
