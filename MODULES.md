# MODULES

Each module lives under `app/Modules/<Name>/module.json`.

| Key | Name | Group | Core | Dependencies |
|---|---|---|---|---|
| core_auth | Auth & Users | core | yes | — |
| core_settings | Settings | core | yes | — |
| core_modules | Module Center | core | yes | — |
| core_notifications | Notifications | core | yes | — |
| core_audit | Audit Log | core | yes | — |
| channels | Channel Connections | A | no | — |
| inbox | Unified Inbox | A | no | channels |
| chat_to_order | Chat-to-Order | A | no | inbox, orders, catalog |
| ai_assistant | AI Assistant | A | no | — |
| orders | Order Management | B | no | customers, catalog |
| customers | Customers | B | no | — |
| catalog | Product Catalog | B | no | — |
| inventory | Inventory | B | no | catalog |
| purchases | Purchases | B | no | inventory |
| packing | Packing & Dispatch | B | no | orders, inventory |
| couriers | Courier & Delivery | B | no | orders |
| returns | Returns & Refunds | B | no | orders |
| payments | Payments | C | no | orders |
| accounting | Accounting | C | no | payments |
| cash | Cash Management | C | no | payments |
| billing | Platform Billing | C | no | — |
| crm | CRM Pipeline | D | no | customers |
| campaigns | Marketing Campaigns | D | no | customers |
| coupons | Coupons & Offers | D | no | orders |
| tickets | Help Desk | D | no | customers |
| reviews | Reviews | D | no | orders |
| storefront | Storefront Lite | D | no | catalog, orders |
| hr | HR & Attendance | E | no | — |
| documents | Document Templates | E | no | — |
| dashboards | Dashboards | F | no | — |
| reports | Reports Center | F | no | dashboards |
| integrations | Integrations Hub | F | no | — |
| multi_branch | Multi-Branch | core | no | — |

## Toggle behavior

Table `business_modules` stores per-business (optional per-branch) enablement + JSON settings.

When disabled: Filament resources using `canViewAny()` hide; `EnsureModuleEnabled` / API checks return 403; Module Center warns on dependency conflicts.

## Presets

Configured in `config/modules.php`: `fashion`, `gadgets`, `food`.
