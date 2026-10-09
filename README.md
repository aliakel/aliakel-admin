# AliAkel Admin

**AliAkel Admin** (`aliakel/aliakel-admin`) is a modern Laravel admin panel builder — grids, forms, trees, and widgets with a Tailwind-based UI, RTL support, and a clean developer API.

Built and maintained by **Eng. Ali Akel**.

---

## Features

- Model grids with filters, export, batch actions, and inline editing
- Form builder with rich fields (including richtext, subcards, multi-column layouts)
- Tree menus, auth, roles & permissions
- Widgets (box, form, tabs, info boxes, and more)
- Feather icons, Flatpickr dates, Quill rich text
- Full RTL layout support

## Requirements

- PHP `>= 8.0`
- Laravel `>= 10`

## Installation

```bash
composer require aliakel/aliakel-admin
```

Publish config and assets:

```bash
php artisan vendor:publish --provider="AliAkel\Admin\AdminServiceProvider"
```

Install admin tables and default admin user:

```bash
php artisan admin:install
```

Open `/admin` in the browser (default path is configurable in `config/admin.php`).

### Local path package (development)

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "/path/to/aliakel-admin",
      "options": { "symlink": true }
    }
  ],
  "require": {
    "aliakel/aliakel-admin": "dev-main"
  }
}
```

## Namespace

```php
use AliAkel\Admin\Facades\Admin;
use AliAkel\Admin\Form;
use AliAkel\Admin\Grid;
use AliAkel\Admin\Layout\Content;
```

## Assets (CSS)

Frontend styles are built with Tailwind:

```bash
npm install
npm run build:css
```

Output: `resources/assets/laravel-admin/admin.css`

## Config

Main settings live in `config/admin.php`:

- Panel name & logo
- Route prefix & middleware
- Auth guard & models
- Upload disk
- Theme / accent colors
- Locale & RTL

## Author

| | |
|---|---|
| **Name** | Eng. Ali Akel |
| **Email** | [aliakel1991@gmail.com](mailto:aliakel1991@gmail.com) |
| **Phone** | [+963 936 301 412](tel:+963936301412) |
| **Package** | `aliakel/aliakel-admin` |

## License

MIT
