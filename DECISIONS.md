# DECISIONS

Professional defaults chosen while implementing Social Commerce Order Manager without blocking questions.

1. **Primary admin UI is Filament 3.** `imrandevbd/laravel-admin-panel` is installed and available for future headless/admin experiments, but its full competing panel is **not** registered to avoid dual-panel auth/nav conflicts. Documented usage: package remains a dependency; Filament panels own UX.
2. **Module path:** `app/Modules/<PascalCase>/module.json` with runtime registry `App\Core\Services\ModuleRegistry`.
3. **Tenancy:** single-database shared schema with `business_id` (+ optional `branch_id`) and `TenantContext` global scopes + policies.
4. **Money:** integer minor units (paisa) in BDT by default.
5. **IDs:** auto-increment PKs + ULID public identifiers on key entities; `public_token` on orders.
6. **External APIs:** Meta/WhatsApp, Steadfast/Pathao, and paid payment gateways are **mock/stub adapters** in demo and local. Real credentials can later swap implementations behind the same interfaces.
7. **AI:** `laravel-ai-hub` when keys exist; otherwise deterministic Bangla/English mock extraction.
8. **Demo volume:** realistic but SQLite-friendly sample sizes (≈25 products, 40 customers, 30 orders per business) instead of hundreds of thousands of rows.
9. **Panels:** `/platform`, `/app` (business), `/agent`, `/rider`. Legacy `/admin` Filament provider removed.
10. **Attendance:** `AttendanceIntegrationService` hooks for `laravel-attendance-hub`; demo uses manual punches, no live devices.
11. **Public site:** Blade + Tailwind CDN + Alpine for zero Node dependency in demo environments.
12. **Spatie permission teams:** disabled; business scoping handled via `business_id` on users/models.
13. **HR attendance table** is `hr_attendance_logs` to avoid colliding with `laravel-attendance-hub`'s `attendance_logs` migration.
