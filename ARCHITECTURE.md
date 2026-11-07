# ARCHITECTURE

```mermaid
flowchart TB
  subgraph Public
    M[Marketing site Blade]
    S[Storefront lite]
  end

  subgraph Panels
    P[Platform Filament /platform]
    B[Business Filament /app]
    A[Agent Filament /agent]
    R[Rider Filament /rider]
  end

  subgraph Core
    T[TenantContext + scopes]
    MR[ModuleRegistry]
    API[Sanctum API]
  end

  subgraph Domain
    CH[Channels + Inbox]
    OR[Orders + Catalog + Inventory]
    FF[Couriers + Payments + Accounting]
    GR[CRM + Campaigns + HR]
  end

  subgraph Packages
    AI[laravel-ai-hub]
    PDF[laravel-unicode-pdf]
    SLUG[laravel-universal-slug]
    ATT[laravel-attendance-hub]
    CAT[laravel-ai-catalog]
    SPEED[drutogoti]
  end

  M --> B
  S --> OR
  P --> MR
  B --> MR
  B --> Domain
  A --> CH
  A --> OR
  R --> FF
  Domain --> T
  CH --> AI
  OR --> PDF
  OR --> SLUG
  GR --> ATT
  Public --> SPEED
```

## Tenancy

- `businesses` are tenants; `branches` are optional sub-scopes.
- Tenant models use `BelongsToBusiness` / `BelongsToBranch` traits.
- `SetTenantContext` middleware sets context from the authenticated user.

## Module system

- Manifests in `app/Modules/*/module.json`.
- Runtime enablement via `business_modules`.
- Module Center page for owners; Platform panel manages tenants/plans/CMS.

## Integration adapters

Interfaces under `app/Core/Contracts`:

- `ChannelAdapterInterface` → Mock Meta / WhatsApp
- `CourierAdapterInterface` → Mock Steadfast / Pathao
- `PaymentGatewayManager` → COD/manual live; other gateways stubbed

## Events / queues

Queue driver defaults to `database`. Slow work (AI, PDF, courier sync) should be dispatched as jobs; demo paths also run synchronously for simplicity.
