<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $col) {
            $col->id();
            $col->string('key')->unique();
            $col->text('value')->nullable();
            $col->string('group')->default('general');
            $col->timestamps();
        });

        // Seed default cartage rates
        DB::table('settings')->insert([
            ['key' => 'cartage_kict', 'value' => '23000', 'group' => 'cartage'],
            ['key' => 'cartage_port_qasim', 'value' => '30000', 'group' => 'cartage'],
            ['key' => 'agency_commission_default', 'value' => '8500', 'group' => 'billing'],
            ['key' => 'sales_tax_rate_default', 'value' => '15', 'group' => 'billing'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
