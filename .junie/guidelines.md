### CM Gordijnconfectie API — Development Guidelines

This document captures project-specific conventions and verified commands to speed up onboarding and future development. It is written for experienced Laravel/PHP developers and focuses on nuances in this codebase.

---

### Build and Configuration

- Stack
  - PHP: ^8.4
  - Laravel: ^12
  - Key packages: `encore/base-kit-laravel`, `spatie/laravel-permission`, `spatie/laravel-query-builder`, `laravel/sanctum`.

- Install
    - Composer install: `composer install`
    - Environment: `cp .env.example .env` (adjust DB/MAIL/etc.)
    - App key: `php artisan key:generate`
    - Database:
        - Local development uses MySQL via Laravel Herd.
        - Configure your database credentials in `.env` (Herd typically provides a MySQL instance).
        - Run migrations: `php artisan migrate`

- Dev workflow
    - Serve: Laravel Herd manages the local server automatically (or use `herd open` to launch).
    - Queues: `php artisan queue:listen --tries=1`
    - Logs (pail): `php artisan pail --timeout=0`

- BaseKit integration
  - Config: `config/base-kit.php` defines the user model/resource, request classes, role enum, and the permission matrix used by the permissions sync command.
  - Archive feature is enabled and mapped for specific models under `base-kit.archive.types`. Only models implementing `Encore\BaseKit\Interfaces\Archivable` and using the `Archivable` trait should be listed there.

- Permissions
  - Extend the permission matrix in `config/base-kit.php` → key `permissions`.
  - Sync to DB: `php artisan permissions:sync`

---

### Testing

- Philosophy
  - Only test happy flows and critical alternate paths such as custom errors and validation failures.
  - 100% coverage is not required; prioritize behavior that protects business value and integration boundaries.

- Framework/tooling
  - PHPUnit 11 + Pest 3 are installed; `artisan test`/`composer test` runs the entire suite.
  - Test root paths are `tests/Unit` and `tests/Feature` (see `phpunit.xml`).
  - Laravel testing helpers, database refresh, factories, and Sanctum auth are used heavily.

- Running tests
  - Standard: `composer test`
  - Or: `php artisan test`
  - Lint and static checks: `composer lint` (parallel-lint, phpstan, dump-check, pint test)

- Test data and auth helpers
  - Use Database factories and `RefreshDatabase` where integration matters. Example (Pest):
    ```php
    uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

    beforeEach(function () {
        $this->seed([
            \Database\Seeders\RolesAndPermissionsSeeder::class,
            \Database\Seeders\UserSeeder::class,
        ]);
    });

    test('Get all customers', function () {
        $this->signInUsingRole(\Encore\BaseKit\Enums\UserRole::ADMIN);
        // ... create models/factories ...
        $response = $this->getJson('/api/customers');
        $response->assertOk()->assertJsonCount(3, 'data');
    });
    ```
  - `Tests\TestCase` provides:
    - `signIn(?User $user = null): User` using Sanctum
    - `signInUsingRole(UserRole $role): void`
    - `signOut(): void`

- Adding tests
  - Unit tests: Prefer pure function/class behavior, minimal framework bootstrapping.
  - Feature/API tests: Seed roles/users as shown above; act as a user via `signInUsingRole`; exercise controllers through HTTP JSON endpoints; assert status + JSON payload.
  - Typical structure:
    - `tests/Unit/FooTest.php` (PHPUnit class) or Pest-style files
    - `tests/Feature/BarTest.php` (Pest preferred here for API flows)

- Demo test example (verified)
  - We validated the pipeline by adding a trivial unit test, running the suite, then removing it. The command `composer test` executed successfully with all tests passing. You can scaffold a new unit test like:
    ```php
    <?php
    namespace Tests\Unit;

    use Tests\TestCase;

    class DemoTest extends TestCase
    {
        public function test_truth(): void
        {
            $this->assertTrue(true);
        }
    }
    ```
  - Run with `composer test`. Remove the file when done if it was only for demonstration.

---

### Controller and Resource Conventions

- RESTful controllers
  - Use `AuthorizesRequests` and `authorizeResource(Model::class, 'route_param')` to wire policies.
  - Index endpoints must support pagination and expose totals in `meta` as needed.
  - Sorting and filtering: use `spatie/laravel-query-builder` for `filter[]`, `sort`, and similar query parameters.
  - Return `Resource` or `Resource::collection()` from controllers; do not return models directly.

- Example (pattern used by `GoodReceiptController@index`)
  ```php
  public function index(Request $request, GoodReceiptRepository $repository): AnonymousResourceCollection
  {
      $queryBuilder = $repository->indexQuery($request);

      $totalRecords = (clone $queryBuilder)->count();
      $results = $queryBuilder->paginate(
          $request->integer('itemsPerPage', 10),
          page: $request->integer('page')
      );

      return GoodReceiptResource::collection($results)->additional([
          'meta' => [
              'totals' => ['total_records' => $totalRecords],
          ],
      ]);
  }
  ```

- Eager loading
  - Eager load only when needed for the response or to avoid N+1s. Keep relations explicit in `Resource` or in `.load([...])` just before returning.

- Requests/validation
  - NEW FormRequests: In this repository, requests live under `App\Http\Requests` (e.g., `StoreDeliveryNoteRequest`, `UpdateGoodReceiptRequest`). Place new request classes there and reference them in controllers.
  - Note: Some specifications may suggest placing FormRequests under `App\Http\Resources`; this project intentionally keeps FormRequests under `App\Http\Requests` to align with Laravel conventions and existing code.

- Policies/guards
  - Guard controllers via policies; wire with `authorizeResource` and explicit `authorize` calls for one-off actions.
  - Keep permission slugs synchronized with `config/base-kit.php` and run `php artisan permissions:sync` after changes.

---

### Model Conventions

- Soft deletes
  - Use `Illuminate\Database\Eloquent\SoftDeletes` where logical (most user-editable entities). Ensure migrations add `softDeletes()`.

- Searchable
  - Use `Encore\BaseKit\Traits\Searchable` and implement `searchable(): array` with logical fields; these are used for a simple multi-column like-based search across index endpoints.
  - Example:
    ```php
    use Encore\BaseKit\Traits\Searchable;

    class GoodReceipt extends Model
    {
        use Searchable;

        public function searchable(): array
        {
            return ['article_brand', 'article_name', 'article_color'];
        }
    }
    ```

- Docblocks and strict typing
  - Add concise docblocks to public methods with `@param` types, `@return` types, and a short summary. Always add explicit return types and enable strict typing in new files when reasonable.

- Properties
  - Keep `@property`/`@property-read` annotations in models up to date when adding attributes or relations to assist static analysis (Larastan/phpstan).

---

### Permissions and Roles

- Add new domain permissions under the `permissions` key in `config/base-kit.php` using the `[resource] => [actions...]` format.
- After editing, run `php artisan permissions:sync`.
- Role setup is handled in `app/Console/Commands/PermissionsSync.php` (overrides BaseKit’s command). Update this when changing role capability groupings.

---

### Coding Style and Quality Gates

- Code style: Laravel Pint
  - Check: `composer checkcs` or `composer lint`
  - Fix: `composer fix`

- Static analysis: Larastan (`composer phpstan`)
- Linting: `composer parallellint`
- Debug artifact checks: `composer dumpcheck` (guards against stray dumps)

---

### When adding endpoints

- Controller
  - Follow REST semantics: `index`, `show`, `store`, `update`, `destroy` (+ domain actions as needed, e.g., `match`, `detach`).
  - Validate with dedicated `FormRequest` per action (`StoreXRequest`, `UpdateXRequest`).
  - Use Query Builder for filtering/sorting in `index`.
  - Return a `Resource`/`ResourceCollection` and add minimal `meta` including totals when relevant.

- Resource
  - Keep `toArray()` minimal and explicit; map only the fields needed by the client.

- Authorization
  - Map controller to policy via `authorizeResource`; use fine-grained `authorize()` where needed.

- Permissions
  - Ensure the required permission slugs are present in `config/base-kit.php` and synced.

---

### Verified Commands Quick Sheet

- Setup (Herd + MySQL)
    - `composer install`
    - `cp .env.example .env`
    - `php artisan key:generate`
    - Configure MySQL credentials in `.env` (Herd provides MySQL by default)
    - `php artisan migrate`

- Run
    - Herd manages the server (access via Herd dashboard or `herd open`)
    - `php artisan queue:listen --tries=1`

- Tests
  - `composer test` (verified)
  - `composer lint` (parallel-lint + phpstan + dump-check + pint)

- Permissions
  - `php artisan permissions:sync`

---

### Notes

- Keep SOLID principles in mind when introducing services and repositories (e.g., `App\Services\OrderMatcher`, `App\Repositories\...`). Prefer injecting abstractions in controllers.
- Pagination defaults and query param names (`itemsPerPage`, `page`) are used consistently; reuse them for new endpoints.
- PDFs and Exports are generated via `barryvdh/laravel-dompdf` and `maatwebsite/excel`; prefer generating files in dedicated service classes and keep controllers thin.
