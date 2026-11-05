<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('company_name')->unique();
            $table->string('contact_person');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();

            $table->string('payment_terms')->default('Net 30');
            $table->unsignedInteger('lead_time_days')->default(7);
            $table->decimal('minimum_order_amount', 10, 2)->default(0);

            $table->string('status')->default('active');

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['company_name']);

            $table->dropColumn([
                'company_name',
                'contact_person',
                'email',
                'phone',
                'address',
                'payment_terms',
                'lead_time_days',
                'minimum_order_amount',
                'status',
                'deleted_at',
            ]);
        });
    }
};
