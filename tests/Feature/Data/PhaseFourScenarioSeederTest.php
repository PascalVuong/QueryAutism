<?php

namespace Tests\Feature\Data;

use App\Models\InventoryLevel;
use App\Models\ProductVariant;
use App\Models\SalesOrder;
use Database\Seeders\PhaseFourScenarioSeeder;
use Database\Seeders\PhaseOneScenarioSeeder;
use Database\Seeders\PhaseThreeScenarioSeeder;
use Database\Seeders\PhaseTwoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseFourScenarioSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PhaseOneScenarioSeeder::class,
            PhaseTwoScenarioSeeder::class,
            PhaseThreeScenarioSeeder::class,
            PhaseFourScenarioSeeder::class,
        ]);
    }

    public function test_seeder_creates_expected_phase_four_dataset(): void
    {
        $this->assertDatabaseCount('product_categories', 5);
        $this->assertDatabaseCount('products', 9);
        $this->assertDatabaseCount('product_variants', 9);
        $this->assertDatabaseCount('stock_locations', 5);
        $this->assertDatabaseCount('inventory_levels', 8);
        $this->assertDatabaseCount('sales_orders', 8);
        $this->assertDatabaseCount('sales_order_items', 10);
        $this->assertDatabaseCount('inventory_movements', 24);
    }

    public function test_seeder_contains_required_inventory_scenarios(): void
    {
        $lowStockSkus = InventoryLevel::query()
            ->join(
                'product_variants',
                'product_variants.id',
                '=',
                'inventory_levels.product_variant_id',
            )
            ->whereRaw(
                'quantity_on_hand - quantity_reserved <= reorder_point',
            )
            ->orderBy('product_variants.sku')
            ->pluck('product_variants.sku')
            ->all();

        $this->assertSame([
            'GV-GLOVE-L',
            'GV-GLOVE-M',
            'RP-GRIP-BLK',
        ], $lowStockSkus);

        $withoutLevel = ProductVariant::query()
            ->whereHas('product', function ($query) {
                $query->where('is_stock_tracked', true);
            })
            ->doesntHave('inventoryLevels')
            ->orderBy('sku')
            ->pluck('sku')
            ->all();

        $this->assertSame([
            'GV-TOWEL-BLUE',
        ], $withoutLevel);

        $grip = ProductVariant::query()
            ->where('sku', 'RP-GRIP-BLK')
            ->with([
                'inventoryLevels',
                'inventoryMovements' => fn ($query) => $query
                    ->latest('occurred_at')
                    ->latest('id'),
            ])
            ->firstOrFail();

        $this->assertSame(
            1,
            $grip->inventoryLevels->first()->quantity_on_hand,
        );
        $this->assertSame(
            2,
            $grip->inventoryMovements->first()->quantity_after,
        );
    }

    public function test_seeder_contains_required_order_scenarios(): void
    {
        $withoutItems = SalesOrder::query()
            ->doesntHave('items')
            ->pluck('order_number')
            ->all();

        $this->assertSame([
            'VO-SO-0001',
        ], $withoutItems);

        $mismatch = SalesOrder::query()
            ->where('order_number', 'SW-SO-0001')
            ->withSum('items', 'line_total')
            ->firstOrFail();

        $this->assertSame('90.00', $mismatch->total);
        $this->assertSame(
            86.0,
            (float) $mismatch->items_sum_line_total,
        );

        $customerOrders = SalesOrder::query()
            ->whereHas('customer', function ($query) {
                $query->where('customer_number', 'GV-0001');
            })
            ->count();

        $this->assertSame(2, $customerOrders);

        $this->assertDatabaseHas('sales_orders', [
            'order_number' => 'GV-SO-0003',
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('sales_orders', [
            'order_number' => 'RP-SO-0002',
            'status' => 'refunded',
        ]);
    }
}
