<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\InventoryLevel;
use App\Models\InventoryMovement;
use App\Models\Organization;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\Reservation;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StockLocation;
use App\Models\Venue;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class PhaseFourScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now()->startOfMinute();

        $group = $this->organization('venueops-leisure-group');
        $green = $this->organization('green-valley-golf-club');
        $rotterdam = $this->organization('rotterdam-padel-centre');
        $serenity = $this->organization('serenity-wellness');

        $groupVenue = $this->venue('venueops-training-centre');
        $greenVenue = $this->venue('green-valley-main-venue');
        $rotterdamVenue = $this->venue('rotterdam-padel-hall');
        $serenityVenue = $this->venue('serenity-spa');

        $golfCategory = $this->category(
            $green,
            'Golf Shop',
            'golf-shop',
        );
        $padelCategory = $this->category(
            $rotterdam,
            'Padel Shop',
            'padel-shop',
        );
        $wellnessCategory = $this->category(
            $serenity,
            'Wellness Retail',
            'wellness-retail',
        );
        $trainingCategory = $this->category(
            $group,
            'Training Supplies',
            'training-supplies',
        );

        ProductCategory::factory()
            ->for($green)
            ->archived()
            ->create([
                'name' => 'Clearance',
                'slug' => 'clearance',
                'description' => 'Archived empty category',
            ]);

        $golfBalls = $this->product(
            $green,
            $golfCategory,
            'GOLF-BALLS',
            'Golf Balls',
        );
        $golfGlove = $this->product(
            $green,
            $golfCategory,
            'GOLF-GLOVE',
            'Golf Glove',
        );
        $golfTowel = $this->product(
            $green,
            $golfCategory,
            'GOLF-TOWEL',
            'Golf Towel',
        );

        Product::factory()
            ->for($green)
            ->archived()
            ->create([
                'product_category_id' => $golfCategory->id,
                'code' => 'LEGACY-HOLDER',
                'name' => 'Legacy Scorecard Holder',
                'product_type' => 'physical',
                'is_stock_tracked' => true,
            ]);

        $padelBalls = $this->product(
            $rotterdam,
            $padelCategory,
            'PADEL-BALLS',
            'Padel Balls',
        );
        $padelGrip = $this->product(
            $rotterdam,
            $padelCategory,
            'PADEL-GRIP',
            'Padel Grip',
        );
        $massageOil = $this->product(
            $serenity,
            $wellnessCategory,
            'MASSAGE-OIL',
            'Massage Oil',
        );

        $giftCard = Product::factory()
            ->for($serenity)
            ->active()
            ->digital()
            ->create([
                'product_category_id' => $wellnessCategory->id,
                'code' => 'GIFT-CARD',
                'name' => 'Gift Card',
                'tax_rate' => 0,
            ]);

        $notebook = $this->product(
            $group,
            $trainingCategory,
            'TRAINING-NOTEBOOK',
            'Training Notebook',
        );

        $ballsVariant = $this->variant(
            $golfBalls,
            'GV-BALL-12',
            'Dozen',
            30,
            ['pack_size' => 12],
        );
        $gloveMedium = $this->variant(
            $golfGlove,
            'GV-GLOVE-M',
            'Left Medium',
            25,
            ['hand' => 'left', 'size' => 'M'],
        );
        $gloveLarge = $this->variant(
            $golfGlove,
            'GV-GLOVE-L',
            'Left Large',
            25,
            ['hand' => 'left', 'size' => 'L'],
        );
        $towelBlue = $this->variant(
            $golfTowel,
            'GV-TOWEL-BLUE',
            'Blue',
            20,
            ['color' => 'blue'],
        );
        $padelBallVariant = $this->variant(
            $padelBalls,
            'RP-BALL-3',
            'Tube of 3',
            12,
            ['pack_size' => 3],
        );
        $gripBlack = $this->variant(
            $padelGrip,
            'RP-GRIP-BLK',
            'Black',
            8,
            ['color' => 'black'],
        );
        $oilVariant = $this->variant(
            $massageOil,
            'SW-OIL-250',
            '250 ml',
            18,
            ['volume_ml' => 250],
        );
        $giftVariant = $this->variant(
            $giftCard,
            'SW-GIFT-50',
            'EUR 50',
            50,
            ['value' => 50],
        );
        $notebookVariant = $this->variant(
            $notebook,
            'VO-NOTE-A5',
            'A5',
            10,
            ['size' => 'A5'],
        );

        $greenProShop = $this->location(
            $green,
            $greenVenue,
            'GV-PRO',
            'Green Valley Pro Shop',
            'retail',
        );
        $greenBackroom = $this->location(
            $green,
            $greenVenue,
            'GV-BACK',
            'Green Valley Backroom',
            'warehouse',
        );
        $padelDesk = $this->location(
            $rotterdam,
            $rotterdamVenue,
            'RP-DESK',
            'Rotterdam Padel Desk',
            'retail',
        );
        $serenityReception = $this->location(
            $serenity,
            $serenityVenue,
            'SW-RECEPTION',
            'Serenity Reception',
            'retail',
        );
        $groupStorage = $this->location(
            $group,
            $groupVenue,
            'VO-STORAGE',
            'VenueOps Storage',
            'warehouse',
        );

        $this->level($ballsVariant, $greenProShop, 20, 4, 8);
        $this->level($ballsVariant, $greenBackroom, 40, 0, 10);
        $this->level($gloveMedium, $greenProShop, 3, 1, 5);
        $this->level($gloveLarge, $greenProShop, 0, 0, 3);
        $this->level($padelBallVariant, $padelDesk, 12, 4, 5);
        $this->level($gripBlack, $padelDesk, 1, 0, 4);
        $this->level($oilVariant, $serenityReception, 6, 1, 2);
        $this->level($notebookVariant, $groupStorage, 25, 0, 5);

        $pascal = $this->customer('GV-0001');
        $greenGuest = $this->customer('GV-0002');
        $blocked = $this->customer('GV-0003');
        $multiGreen = $this->customer('GV-0004');
        $multiRotterdam = $this->customer('RP-0001');
        $noMembership = $this->customer('RP-0002');
        $serenityCustomer = $this->customer('SW-0001');

        $gvOrderOne = $this->order(
            $green,
            $greenVenue,
            $greenProShop,
            $pascal,
            'GV-SO-0001',
            'fulfilled',
            85,
            5,
            0,
            80,
            $now->subDays(3),
            $this->reservation('GV-RES-0001'),
        );
        $gvOrderOneBalls = $this->item(
            $gvOrderOne,
            $ballsVariant,
            2,
            30,
            5,
            0,
            55,
            'fulfilled',
        );
        $gvOrderOneGlove = $this->item(
            $gvOrderOne,
            $gloveMedium,
            1,
            25,
            0,
            0,
            25,
            'fulfilled',
        );

        $gvOrderTwo = $this->order(
            $green,
            $greenVenue,
            $greenProShop,
            $greenGuest,
            'GV-SO-0002',
            'pending',
            20,
            0,
            0,
            20,
            $now->subDay(),
        );
        $this->item(
            $gvOrderTwo,
            $towelBlue,
            1,
            20,
            0,
            0,
            20,
        );

        $gvCancelled = $this->order(
            $green,
            $greenVenue,
            $greenProShop,
            $blocked,
            'GV-SO-0003',
            'cancelled',
            25,
            0,
            0,
            25,
            $now->subDays(2),
        );
        $this->item(
            $gvCancelled,
            $gloveLarge,
            1,
            25,
            0,
            0,
            25,
            'cancelled',
        );

        $gvOrderFour = $this->order(
            $green,
            $greenVenue,
            $greenProShop,
            $pascal,
            'GV-SO-0004',
            'fulfilled',
            30,
            0,
            0,
            30,
            $now->subHours(12),
        );
        $gvOrderFourBalls = $this->item(
            $gvOrderFour,
            $ballsVariant,
            1,
            30,
            0,
            0,
            30,
            'fulfilled',
        );

        $rpOrderOne = $this->order(
            $rotterdam,
            $rotterdamVenue,
            $padelDesk,
            $multiRotterdam,
            'RP-SO-0001',
            'fulfilled',
            40,
            0,
            0,
            40,
            $now->subDays(4),
            $this->reservation('RP-RES-0001'),
        );
        $rpOrderOneBalls = $this->item(
            $rpOrderOne,
            $padelBallVariant,
            2,
            12,
            0,
            0,
            24,
            'fulfilled',
        );
        $rpOrderOneGrip = $this->item(
            $rpOrderOne,
            $gripBlack,
            2,
            8,
            0,
            0,
            16,
            'fulfilled',
        );

        $rpRefunded = $this->order(
            $rotterdam,
            $rotterdamVenue,
            $padelDesk,
            $noMembership,
            'RP-SO-0002',
            'refunded',
            12,
            0,
            0,
            12,
            $now->subDays(2),
        );
        $rpRefundedBalls = $this->item(
            $rpRefunded,
            $padelBallVariant,
            1,
            12,
            0,
            0,
            12,
            'refunded',
        );

        $swOrder = $this->order(
            $serenity,
            $serenityVenue,
            $serenityReception,
            $serenityCustomer,
            'SW-SO-0001',
            'paid',
            86,
            0,
            0,
            90,
            $now->subDays(2),
            $this->reservation('SW-RES-0001'),
        );
        $swOilItem = $this->item(
            $swOrder,
            $oilVariant,
            2,
            18,
            0,
            0,
            36,
        );
        $this->item(
            $swOrder,
            $giftVariant,
            1,
            50,
            0,
            0,
            50,
        );

        $this->order(
            $group,
            $groupVenue,
            $groupStorage,
            null,
            'VO-SO-0001',
            'draft',
            0,
            0,
            0,
            0,
            null,
        );

        $this->movement(
            $ballsVariant,
            $greenProShop,
            'opening',
            30,
            30,
            $now->subMonths(4),
        );
        $this->movement(
            $ballsVariant,
            $greenProShop,
            'sale',
            -2,
            28,
            $now->subDays(3),
            $gvOrderOneBalls,
        );
        $this->movement(
            $ballsVariant,
            $greenProShop,
            'sale',
            -1,
            27,
            $now->subHours(12),
            $gvOrderFourBalls,
        );
        $this->movement(
            $ballsVariant,
            $greenProShop,
            'adjustment',
            -7,
            20,
            $now->subHour(),
        );

        $this->movement(
            $ballsVariant,
            $greenBackroom,
            'opening',
            50,
            50,
            $now->subMonths(4),
        );
        $this->movement(
            $ballsVariant,
            $greenBackroom,
            'transfer_out',
            -10,
            40,
            $now->subMonth(),
        );

        $this->movement(
            $gloveMedium,
            $greenProShop,
            'opening',
            5,
            5,
            $now->subMonths(3),
        );
        $this->movement(
            $gloveMedium,
            $greenProShop,
            'sale',
            -1,
            4,
            $now->subDays(3),
            $gvOrderOneGlove,
        );
        $this->movement(
            $gloveMedium,
            $greenProShop,
            'adjustment',
            -1,
            3,
            $now->subDay(),
        );

        $this->movement(
            $gloveLarge,
            $greenProShop,
            'opening',
            4,
            4,
            $now->subMonths(3),
        );
        $this->movement(
            $gloveLarge,
            $greenProShop,
            'sale',
            -4,
            0,
            $now->subMonth(),
        );

        $this->movement(
            $padelBallVariant,
            $padelDesk,
            'opening',
            20,
            20,
            $now->subMonths(3),
        );
        $this->movement(
            $padelBallVariant,
            $padelDesk,
            'sale',
            -2,
            18,
            $now->subDays(4),
            $rpOrderOneBalls,
        );
        $this->movement(
            $padelBallVariant,
            $padelDesk,
            'return',
            1,
            19,
            $now->subDays(2),
            $rpRefundedBalls,
        );
        $this->movement(
            $padelBallVariant,
            $padelDesk,
            'adjustment',
            -7,
            12,
            $now->subDay(),
        );

        $this->movement(
            $gripBlack,
            $padelDesk,
            'opening',
            5,
            5,
            $now->subMonths(3),
        );
        $this->movement(
            $gripBlack,
            $padelDesk,
            'sale',
            -2,
            3,
            $now->subDays(4),
            $rpOrderOneGrip,
        );
        $this->movement(
            $gripBlack,
            $padelDesk,
            'adjustment',
            -1,
            2,
            $now->subDay(),
        );

        $this->movement(
            $oilVariant,
            $serenityReception,
            'opening',
            10,
            10,
            $now->subMonths(2),
        );
        $this->movement(
            $oilVariant,
            $serenityReception,
            'sale',
            -2,
            8,
            $now->subDays(2),
            $swOilItem,
        );
        $this->movement(
            $oilVariant,
            $serenityReception,
            'adjustment',
            -2,
            6,
            $now->subDay(),
        );

        $this->movement(
            $notebookVariant,
            $groupStorage,
            'opening',
            20,
            20,
            $now->subMonths(2),
        );
        $this->movement(
            $notebookVariant,
            $groupStorage,
            'purchase',
            10,
            30,
            $now->subMonth(),
        );
        $this->movement(
            $notebookVariant,
            $groupStorage,
            'adjustment',
            -5,
            25,
            $now->subDay(),
        );
    }

    private function organization(string $slug): Organization
    {
        return Organization::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    private function venue(string $slug): Venue
    {
        return Venue::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    private function customer(string $number): Customer
    {
        return Customer::query()
            ->where('customer_number', $number)
            ->firstOrFail();
    }

    private function reservation(string $reference): Reservation
    {
        return Reservation::query()
            ->where('reference_number', $reference)
            ->firstOrFail();
    }

    private function category(
        Organization $organization,
        string $name,
        string $slug,
    ): ProductCategory {
        return ProductCategory::factory()
            ->for($organization)
            ->create([
                'name' => $name,
                'slug' => $slug,
                'status' => 'active',
            ]);
    }

    private function product(
        Organization $organization,
        ProductCategory $category,
        string $code,
        string $name,
    ): Product {
        return Product::factory()
            ->for($organization)
            ->active()
            ->create([
                'product_category_id' => $category->id,
                'code' => $code,
                'name' => $name,
                'product_type' => 'physical',
                'is_stock_tracked' => true,
            ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    private function variant(
        Product $product,
        string $sku,
        string $name,
        float|int $price,
        array $attributes,
    ): ProductVariant {
        return ProductVariant::factory()
            ->for($product)
            ->create([
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'cost_price' => round($price * 0.55, 2),
                'attributes' => $attributes,
            ]);
    }

    private function location(
        Organization $organization,
        Venue $venue,
        string $code,
        string $name,
        string $type,
    ): StockLocation {
        return StockLocation::factory()
            ->for($organization)
            ->create([
                'venue_id' => $venue->id,
                'code' => $code,
                'name' => $name,
                'type' => $type,
                'status' => 'active',
            ]);
    }

    private function level(
        ProductVariant $variant,
        StockLocation $location,
        int $onHand,
        int $reserved,
        int $reorderPoint,
    ): InventoryLevel {
        return InventoryLevel::factory()
            ->forVariantAtLocation($variant, $location)
            ->create([
                'quantity_on_hand' => $onHand,
                'quantity_reserved' => $reserved,
                'reorder_point' => $reorderPoint,
            ]);
    }

    private function order(
        Organization $organization,
        Venue $venue,
        StockLocation $location,
        ?Customer $customer,
        string $number,
        string $status,
        float|int $subtotal,
        float|int $discount,
        float|int $tax,
        float|int $total,
        ?CarbonImmutable $orderedAt,
        ?Reservation $reservation = null,
    ): SalesOrder {
        return SalesOrder::factory()
            ->create([
                'organization_id' => $organization->id,
                'venue_id' => $venue->id,
                'customer_id' => $customer?->id,
                'reservation_id' => $reservation?->id,
                'stock_location_id' => $location->id,
                'order_number' => $number,
                'status' => $status,
                'ordered_at' => $orderedAt,
                'fulfilled_at' => $status === 'fulfilled'
                    ? $orderedAt?->addHour()
                    : null,
                'cancelled_at' => $status === 'cancelled'
                    ? $orderedAt?->addHour()
                    : null,
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'tax_total' => $tax,
                'total' => $total,
            ]);
    }

    private function item(
        SalesOrder $order,
        ProductVariant $variant,
        int $quantity,
        float|int $unitPrice,
        float|int $discount,
        float|int $tax,
        float|int $lineTotal,
        string $status = 'ordered',
    ): SalesOrderItem {
        return SalesOrderItem::factory()
            ->forOrderAndVariant($order, $variant)
            ->create([
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_total' => $discount,
                'tax_total' => $tax,
                'line_total' => $lineTotal,
                'status' => $status,
            ]);
    }

    private function movement(
        ProductVariant $variant,
        StockLocation $location,
        string $type,
        int $quantity,
        int $quantityAfter,
        CarbonImmutable $occurredAt,
        ?SalesOrderItem $orderItem = null,
    ): InventoryMovement {
        return InventoryMovement::factory()
            ->forVariantAtLocation($variant, $location)
            ->create([
                'sales_order_id' => $orderItem?->sales_order_id,
                'sales_order_item_id' => $orderItem?->id,
                'type' => $type,
                'quantity' => $quantity,
                'quantity_after' => $quantityAfter,
                'occurred_at' => $occurredAt,
            ]);
    }
}
