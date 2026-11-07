# PROGRESS

Mapped to prompt sections 4–10.

## Group A — Social Commerce Core
- [x] Channel connections (mock adapters + simulator page/command)
- [x] Unified Inbox models + Filament resources
- [x] Chat-to-Order + AI extraction (hub or mock)
- [x] AI assistant hook via laravel-ai-hub

## Group B — Orders & Fulfillment
- [x] Orders lifecycle + totals service
- [x] Customers
- [x] Products / variants / categories (universal slug)
- [x] Inventory stock items/movements
- [x] Purchases (suppliers + PO tables)
- [x] Packing module flag + shipment flow
- [x] Courier mocks (Steadfast/Pathao)
- [x] Returns model

## Group C — Payments & Money
- [x] Payments COD/manual + gateway stubs
- [x] Accounting chart + journal posting helper
- [x] Cash drawers
- [x] Plans / subscriptions tables + Platform UI

## Group D — Growth (lite)
- [x] CRM leads
- [x] Campaigns
- [x] Coupons
- [x] Tickets
- [x] Reviews
- [x] Storefront lite

## Group E — People (lite)
- [x] HR employees + attendance logs
- [x] Attendance hub integration hook
- [x] Document templates + unicode-pdf invoice view

## Group F — Platform
- [x] Dashboard widgets
- [x] Notifications log table
- [x] Sanctum API orders
- [x] Localization EN+BN lang files

## Panels & site
- [x] Platform / Business / Agent / Rider panels
- [x] Marketing pages (Home, Features, Modules, Pricing, How it works, Live Demo, Integrations, Blog, Contact, Legal)
- [x] Module Center UI
- [x] demo:seed + channel:simulate

## Quality
- [x] Feature tests: tenant isolation, module toggle, order totals
- [x] README / MODULES / ARCHITECTURE / DECISIONS / PROGRESS
- [ ] Full Pest browser suite / Larastan CI (scaffold deferred)
- [ ] 80+ report catalog (lite dashboards instead)
