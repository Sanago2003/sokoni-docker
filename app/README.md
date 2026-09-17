# Sokoni — fixed normalized PHP/MySQL build

This package is aligned with the normalized Sokoni database structure: `users` uses `role_id` and location foreign keys, while `listings` uses `listing_type_id`, `crop_id`, `unit_id`, `region_id`, and `status_id`.

## XAMPP on Ubuntu
1. Put the `Sokoni_fixed` folder at `/opt/lampp/htdocs/sokoni`.
2. Keep your existing `sokoni` database if it already contains the normalized tables and data. Do **not** import `schema.sql` into that existing database unless you intentionally want to recreate it.
3. `config.php` is already set for XAMPP's default `root` account with an empty password.
4. Open `http://localhost/sokoni/`.

## If creating a new database
Import `schema.sql` through phpMyAdmin or:
`/opt/lampp/bin/mysql -u root < schema.sql`

## Main fixes
- All PHP listing queries use normalized foreign keys and lookup tables.
- Registration writes `role_id` and `region_id` instead of the old text `region` column.
- Login checks account status and updates last login.
- Post listing validates lookup and location foreign keys.
- My listings and listing actions use `status_id`.
- Marketplace filters use normalized crop/region/type IDs.
- Added reusable CSRF/auth/helper functions and JSON APIs.
