# URL Shortener

Laravel application for creating short links, tracking visits, and managing links in a personal dashboard powered by Filament.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database named `url_shortener`, then configure `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://url-shortener.test
```

```bash
php artisan migrate
npm install
npm run build
```

## Usage

1. Register at `/register` or sign in at `/login`.
2. Open the dashboard at `/admin`.
3. Create a link with an original URL.
4. Share the generated short URL (`/{code}`).
5. View click history and totals in the link details page.

## Tests

```bash
php artisan test
```

## Troubleshooting

### `proc_open(): CreateProcess failed` warning

This can appear when Composer installed packages from Git source and Apache cannot find `git` in PATH. Remove vendor metadata folders:

```bash
# PowerShell
Get-ChildItem vendor -Directory -Recurse -Force -Filter ".git" | Remove-Item -Recurse -Force
```

Or add `C:\laragon\bin\git\cmd` to the system PATH and restart Laragon.

## Architecture

- `Controller` → `Service` → `Repository`
- Enums for domain states and migration column values
- Strategy pattern for short-code generation
- Repository interfaces bound in `AppServiceProvider`
