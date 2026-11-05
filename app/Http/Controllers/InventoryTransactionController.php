<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryTransactionController extends Controller
{
    public function index()
    {
        $transactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->get();

        return view('inventory.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'transaction_type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'reference_document' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $validated['quantity'];

            if (
                $validated['transaction_type'] === 'stock_out'
                && $product->quantity < $quantity
            ) {
                throw ValidationException::withMessages([
                    'quantity' => 'Insufficient stock. Available quantity: '
                        . $product->quantity . '.',
                ]);
            }

            InventoryTransaction::create([
                'product_id' => $product->id,
                'transaction_type' => $validated['transaction_type'],
                'quantity' => $quantity,
                'reference_document' =>
                    $validated['reference_document'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Inventory transaction recorded successfully!'
            );
    }
}
