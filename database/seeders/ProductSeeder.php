<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Portland Cement',
            'sku' => 'CEM-001',
            'description' => 'High-quality Portland cement for general construction.',
            'category' => 'Hardware',
            'quantity' => 50,
            'reorder_level' => 10,
            'unit_price' => 250.00,
            'supplier' => 'ABC Hardware Supply',
        ]);

        Product::create([
            'name' => 'Steel Bar 10mm',
            'sku' => 'STL-001',
            'description' => '10mm steel reinforcing bar for concrete construction.',
            'category' => 'Hardware',
            'quantity' => 35,
            'reorder_level' => 10,
            'unit_price' => 180.00,
            'supplier' => 'Prime Builders Supply',
        ]);

        Product::create([
            'name' => 'Plywood 1/2 Inch',
            'sku' => 'PLY-001',
            'description' => '1/2 inch plywood for construction and interior work.',
            'category' => 'Lumber',
            'quantity' => 12,
            'reorder_level' => 5,
            'unit_price' => 650.00,
            'supplier' => 'Woodland Construction Supply',
        ]);

        Product::create([
            'name' => 'Marine Plywood 1/4 Inch',
            'sku' => 'PLY-002',
            'description' => 'Durable marine plywood for construction projects.',
            'category' => 'Lumber',
            'quantity' => 20,
            'reorder_level' => 5,
            'unit_price' => 480.00,
            'supplier' => 'Woodland Construction Supply',
        ]);

        Product::create([
            'name' => 'Claw Hammer',
            'sku' => 'TOL-001',
            'description' => 'Steel claw hammer with durable handle.',
            'category' => 'Tools',
            'quantity' => 15,
            'reorder_level' => 5,
            'unit_price' => 350.00,
            'supplier' => 'ToolMaster Philippines',
        ]);

        Product::create([
            'name' => 'Screwdriver Set',
            'sku' => 'TOL-002',
            'description' => '10-piece screwdriver set for repair and construction.',
            'category' => 'Tools',
            'quantity' => 7,
            'reorder_level' => 10,
            'unit_price' => 280.00,
            'supplier' => 'ToolMaster Philippines',
        ]);

        Product::create([
            'name' => 'Adjustable Wrench',
            'sku' => 'TOL-003',
            'description' => 'Heavy-duty adjustable wrench.',
            'category' => 'Tools',
            'quantity' => 18,
            'reorder_level' => 5,
            'unit_price' => 220.00,
            'supplier' => 'ToolMaster Philippines',
        ]);

        Product::create([
            'name' => 'Electrical Wire THHN 2.0mm',
            'sku' => 'ELE-001',
            'description' => 'Copper electrical wire for installation.',
            'category' => 'Electrical',
            'quantity' => 25,
            'reorder_level' => 10,
            'unit_price' => 450.00,
            'supplier' => 'ElectroPro Supply',
        ]);

        Product::create([
            'name' => 'LED Bulb 12W',
            'sku' => 'ELE-002',
            'description' => 'Energy-efficient 12W LED bulb.',
            'category' => 'Electrical',
            'quantity' => 30,
            'reorder_level' => 10,
            'unit_price' => 120.00,
            'supplier' => 'ElectroPro Supply',
        ]);

        Product::create([
            'name' => 'PVC Pipe 1/2 Inch',
            'sku' => 'PLM-001',
            'description' => 'PVC pipe for water supply and plumbing.',
            'category' => 'Plumbing',
            'quantity' => 8,
            'reorder_level' => 10,
            'unit_price' => 95.00,
            'supplier' => 'AquaFlow Hardware',
        ]);

        Product::create([
            'name' => 'PVC Elbow 1/2 Inch',
            'sku' => 'PLM-002',
            'description' => 'PVC elbow connector for plumbing systems.',
            'category' => 'Plumbing',
            'quantity' => 40,
            'reorder_level' => 10,
            'unit_price' => 35.00,
            'supplier' => 'AquaFlow Hardware',
        ]);

        Product::create([
            'name' => 'Faucet Chrome Finish',
            'sku' => 'PLM-003',
            'description' => 'Chrome-finish faucet for residential plumbing.',
            'category' => 'Plumbing',
            'quantity' => 6,
            'reorder_level' => 8,
            'unit_price' => 550.00,
            'supplier' => 'AquaFlow Hardware',
        ]);

        Product::create([
            'name' => 'Interior Paint White 1L',
            'sku' => 'PNT-001',
            'description' => 'White interior paint for walls and ceilings.',
            'category' => 'Paint',
            'quantity' => 6,
            'reorder_level' => 10,
            'unit_price' => 220.00,
            'supplier' => 'ColorPro Paint Supply',
        ]);

        Product::create([
            'name' => 'Exterior Paint Gray 1L',
            'sku' => 'PNT-002',
            'description' => 'Weather-resistant gray exterior paint.',
            'category' => 'Paint',
            'quantity' => 14,
            'reorder_level' => 5,
            'unit_price' => 260.00,
            'supplier' => 'ColorPro Paint Supply',
        ]);

        Product::create([
            'name' => 'Concrete Nails 2 Inch',
            'sku' => 'HRD-001',
            'description' => 'Strong 2-inch nails for construction work.',
            'category' => 'Hardware',
            'quantity' => 0,
            'reorder_level' => 10,
            'unit_price' => 90.00,
            'supplier' => 'ABC Hardware Supply',
        ]);
    }
}

