<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\InventoryLevel;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StockLocation;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseFourFactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_four_factories_and_states_create_valid_data(): void
    {
        $venue = Venue::factory()->create();
        $organization = $venue->organization;

        $customer = Customer::factory()
            ->for($organization)
            ->create();

        $category = ProductCategory::factory()
            ->for($organization)
            ->create();

        $product = Product::factory()
            ->for($organization)
            ->active()
            ->create([
                'product_category_id' => $category->id,
            ]);

        $variant = ProductVariant::factory()
            ->for($product)
            ->create();

        $location = StockLocation::factory()
            ->for($organization)
            ->warehouse()
            ->create([
                'venue_id' => $venue->id,
            ]);

        $level = InventoryLevel::factory()
            ->forVariantAtLocation($variant, $location)
            ->lowStock()
            ->create();

        $order = SalesOrder::factory()
            ->forCustomerAtLocation($customer, $location)
            ->fulfilled()
            ->create();

        $item = SalesOrderItem::factory()
            ->forOrderAndVariant($order, $variant)
            ->fulfilled()
            ->create();

        $movement = InventoryMovement::factory()
            ->forVariantAtLocation($variant, $location)
            ->sale()
            ->create([
                'sales_order_id' => $order->id,
                'sales_order_item_id' => $item->id,
                'quantity_after' => 2,
            ]);

        $this->assertSame('active', $product->status);
        $this->assertTrue($variant->product->is($product));
        $this->assertSame('warehouse', $location->type);
        $this->assertSame(3, $level->quantity_on_hand);
        $this->assertSame(1, $level->quantity_reserved);
        $this->assertSame('fulfilled', $order->status);
        $this->assertSame('fulfilled', $item->status);
        $this->assertSame('sale', $movement->type);
        $this->assertSame(-1, $movement->quantity);
    }
}
