# QueryAutism — Phase 4 Blueprint

## Products, inventory and sales orders

Phase Four adds catalog, stock and retail-order data.

## Tables

1. `product_categories`
2. `products`
3. `product_variants`
4. `stock_locations`
5. `inventory_levels`
6. `sales_orders`
7. `sales_order_items`
8. `inventory_movements`

## Relationship map

```text
Organization
├── hasMany ProductCategories
├── hasMany Products
├── hasMany StockLocations
└── hasMany SalesOrders

Venue
├── hasMany StockLocations
└── hasMany SalesOrders

Customer
└── hasMany SalesOrders

Reservation
└── hasMany SalesOrders

ProductCategory
├── belongsTo Organization
├── belongsTo parent ProductCategory
├── hasMany child ProductCategories
└── hasMany Products

Product
├── belongsTo Organization
├── belongsTo ProductCategory
└── hasMany ProductVariants

ProductVariant
├── belongsTo Product
├── hasMany InventoryLevels
├── hasMany InventoryMovements
└── hasMany SalesOrderItems

StockLocation
├── belongsTo Organization
├── belongsTo Venue
├── hasMany InventoryLevels
├── hasMany InventoryMovements
└── hasMany SalesOrders

SalesOrder
├── belongsTo Organization
├── belongsTo Venue
├── belongsTo Customer
├── belongsTo Reservation
├── belongsTo StockLocation
├── hasMany SalesOrderItems
└── hasMany InventoryMovements
```

## Deterministic scenarios

The Phase Four seeder includes:

- active and archived categories and products;
- a product without variants;
- a stock-tracked variant without an inventory level;
- inventory at multiple locations;
- low-stock and out-of-stock variants;
- a stored inventory level that differs from movement history;
- completed, pending, cancelled and refunded orders;
- one customer with multiple orders;
- an order without items;
- an order total that differs from item totals;
- sales, returns, purchases, transfers and adjustments.

## Planned query coverage

QRY-096 through QRY-120 can cover:

- active products and variants;
- products without variants;
- stock-tracked variants without inventory;
- available quantity per location;
- low-stock and out-of-stock detection;
- inventory movement timelines;
- running inventory balances;
- inventory reconciliation;
- orders without items;
- order-total reconciliation;
- best-selling variants and products;
- revenue by product, venue and organization;
- customer purchase history;
- inventory and sales health reports.
