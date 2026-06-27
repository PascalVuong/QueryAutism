<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\InventoryLevel;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\Reservation;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StockLocation;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseFourRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_four_relationships_are_configured(): void
    {
        $venue = Venue::factory()->create();
        $organization = $venue->organization;

        $customer = Customer::factory()
            ->for($organization)
            ->create();

        $reservation = Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
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
            ->create([
                'venue_id' => $venue->id,
            ]);

        $level = InventoryLevel::factory()
            ->forVariantAtLocation($variant, $location)
            ->create();

        $order = SalesOrder::factory()
            ->forCustomerAtLocation($customer, $location)
            ->create([
                'reservation_id' => $reservation->id,
            ]);

        $item = SalesOrderItem::factory()
            ->forOrderAndVariant($order, $variant)
            ->create();

        $movement = InventoryMovement::factory()
            ->forVariantAtLocation($variant, $location)
            ->create([
                'sales_order_id' => $order->id,
                'sales_order_item_id' => $item->id,
            ]);

        $this->assertTrue(
            $organization->productCategories->contains($category),
        );
        $this->assertTrue($organization->products->contains($product));
        $this->assertTrue(
            $organization->stockLocations->contains($location),
        );
        $this->assertTrue($organization->salesOrders->contains($order));

        $this->assertTrue($venue->stockLocations->contains($location));
        $this->assertTrue($venue->salesOrders->contains($order));
        $this->assertTrue($customer->salesOrders->contains($order));
        $this->assertTrue($reservation->salesOrders->contains($order));

        $this->assertTrue($category->products->contains($product));
        $this->assertTrue($product->variants->contains($variant));
        $this->assertTrue($variant->inventoryLevels->contains($level));
        $this->assertTrue(
            $variant->inventoryMovements->contains($movement),
        );
        $this->assertTrue($variant->salesOrderItems->contains($item));

        $this->assertTrue(
            $location->inventoryLevels->contains($level),
        );
        $this->assertTrue(
            $location->inventoryMovements->contains($movement),
        );
        $this->assertTrue($location->salesOrders->contains($order));

        $this->assertTrue($order->items->contains($item));
        $this->assertTrue(
            $order->inventoryMovements->contains($movement),
        );
        $this->assertTrue(
            $item->inventoryMovements->contains($movement),
        );
    }
}
