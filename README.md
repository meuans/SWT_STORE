## Asian Store API (PHP 8.2)

Simple PHP API that proxies TheMealDB and exposes meals as store items with pricing and filters.

### Features
- PHP 8.2+, Composer autoloading
- `.env` configuration via `vlucas/phpdotenv`
- HTTP client via `guzzlehttp/guzzle`
- Minimal router and controllers
- JSON responses, pagination, and filters

### Setup
1. Install PHP 8.2+ and Composer.
2. From project root, install dependencies:
   ```bash
   composer install
   ```
3. Create `.env` (auto-copies from `.env.example` after autoload dump). Adjust values if needed.

### Run (Built-in server)
```bash
composer start
```
This serves on `http://localhost:8000` by default.

### Endpoints
- `GET /health` → basic status
- `GET /api/items` → list store items from TheMealDB
  - Query params:
    - `area` (string): cuisine/area, e.g. `Chinese`, `Japanese`, `Thai`, `Indian`
    - `q` (string): name contains filter
    - `minPrice` (int cents): minimum price in cents (e.g. `500`)
    - `maxPrice` (int cents): maximum price in cents
    - `page` (int): page number (default 1)
    - `perPage` (int): items per page (default 12, max 50)


    ### Example requests

```bash
# GET with filters
curl "http://localhost:8000/api/items?area=Japanese&q=soup&minPrice=800&maxPrice=2000&page=1&perPage=6"

# POST example (demo)
curl -X POST http://localhost:8000/api/items \
     -H "Content-Type: application/json" \
     -d '{"name": "Test Meal", "area": "Thai"}'

# PUT example (demo)
curl -X PUT http://localhost:8000/api/items/123 \
     -H "Content-Type: application/json" \
     -d '{"name": "Updated Meal"}'


### Example
```bash
curl "http://localhost:8000/api/items?area=Japanese&q=soup&minPrice=800&maxPrice=2000&page=1&perPage=6"
```

### Notes
- Data source: `https://www.themealdb.com/api.php` (public)
- Prices are synthetic and deterministic based on meal ID.


