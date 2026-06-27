<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE product_categories
            ADD CONSTRAINT product_categories_status_check
            CHECK (status IN ('active', 'inactive', 'archived'))
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT products_status_check
            CHECK (status IN ('draft', 'active', 'inactive', 'archived'))
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT products_type_check
            CHECK (product_type IN ('physical', 'service', 'digital'))
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT products_tax_rate_check
            CHECK (tax_rate BETWEEN 0 AND 100)
        ");

        DB::statement("
            ALTER TABLE product_variants
            ADD CONSTRAINT product_variants_status_check
            CHECK (status IN ('active', 'inactive', 'archived'))
        ");

        DB::statement("
            ALTER TABLE product_variants
            ADD CONSTRAINT product_variants_price_check
            CHECK (
                price >= 0
                AND (cost_price IS NULL OR cost_price >= 0)
            )
        ");

        DB::statement("
            ALTER TABLE stock_locations
            ADD CONSTRAINT stock_locations_type_check
            CHECK (type IN ('retail', 'warehouse', 'service'))
        ");

        DB::statement("
            ALTER TABLE stock_locations
            ADD CONSTRAINT stock_locations_status_check
            CHECK (status IN ('active', 'inactive'))
        ");

        DB::statement("
            ALTER TABLE inventory_levels
            ADD CONSTRAINT inventory_levels_quantity_check
            CHECK (
                quantity_on_hand >= 0
                AND quantity_reserved >= 0
                AND reorder_point >= 0
                AND quantity_reserved <= quantity_on_hand
            )
        ");

        DB::statement("
            ALTER TABLE sales_orders
            ADD CONSTRAINT sales_orders_status_check
            CHECK (
                status IN (
                    'draft',
                    'pending',
                    'paid',
                    'fulfilled',
                    'cancelled',
                    'refunded'
                )
            )
        ");

        DB::statement("
            ALTER TABLE sales_orders
            ADD CONSTRAINT sales_orders_amount_check
            CHECK (
                subtotal >= 0
                AND discount_total >= 0
                AND tax_total >= 0
                AND total >= 0
            )
        ");

        DB::statement("
            ALTER TABLE sales_order_items
            ADD CONSTRAINT sales_order_items_status_check
            CHECK (
                status IN (
                    'ordered',
                    'fulfilled',
                    'cancelled',
                    'refunded'
                )
            )
        ");

        DB::statement("
            ALTER TABLE sales_order_items
            ADD CONSTRAINT sales_order_items_amount_check
            CHECK (
                quantity > 0
                AND unit_price >= 0
                AND discount_total >= 0
                AND tax_total >= 0
                AND line_total >= 0
            )
        ");

        DB::statement("
            ALTER TABLE inventory_movements
            ADD CONSTRAINT inventory_movements_type_check
            CHECK (
                type IN (
                    'opening',
                    'purchase',
                    'sale',
                    'return',
                    'adjustment',
                    'transfer_in',
                    'transfer_out'
                )
            )
        ");

        DB::statement("
            ALTER TABLE inventory_movements
            ADD CONSTRAINT inventory_movements_quantity_check
            CHECK (
                quantity <> 0
                AND quantity_after >= 0
            )
        ");
    }

    public function down(): void
    {
        $constraints = [
            ['inventory_movements', 'inventory_movements_quantity_check'],
            ['inventory_movements', 'inventory_movements_type_check'],
            ['sales_order_items', 'sales_order_items_amount_check'],
            ['sales_order_items', 'sales_order_items_status_check'],
            ['sales_orders', 'sales_orders_amount_check'],
            ['sales_orders', 'sales_orders_status_check'],
            ['inventory_levels', 'inventory_levels_quantity_check'],
            ['stock_locations', 'stock_locations_status_check'],
            ['stock_locations', 'stock_locations_type_check'],
            ['product_variants', 'product_variants_price_check'],
            ['product_variants', 'product_variants_status_check'],
            ['products', 'products_tax_rate_check'],
            ['products', 'products_type_check'],
            ['products', 'products_status_check'],
            ['product_categories', 'product_categories_status_check'],
        ];

        foreach ($constraints as [$table, $constraint]) {
            DB::statement(
                "ALTER TABLE {$table} "
                ."DROP CONSTRAINT IF EXISTS {$constraint}",
            );
        }
    }
};
