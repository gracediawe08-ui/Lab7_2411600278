<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $supplierNames = Product::query()
                ->whereNotNull('supplier')
                ->distinct()
                ->pluck('supplier');

            foreach ($supplierNames as $name) {
                $supplier = Supplier::firstOrCreate(
                    ['company_name' => $name],
                    [
                        'contact_person' => 'Not Provided',
                        'payment_terms' => 'Net 30',
                        'lead_time_days' => 7,
                        'minimum_order_amount' => 0,
                        'status' => 'active',
                    ]
                );

                Product::where('supplier', $name)
                    ->whereNull('supplier_id')
                    ->update([
                        'supplier_id' => $supplier->id,
                    ]);
            }
        });
    }
}
