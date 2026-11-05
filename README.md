# Evidence firemního nářadí

Jednoduchá aplikace v **Laravelu** pro evidenci firemního nářadí. Každé zařízení má jedinečné
evidenční číslo, stav a historii servisních zásahů.

## Funkce

- CRUD zařízení (seznam, přidání, detail, úprava, smazání)
- Filtr seznamu podle stavu: **Aktivní** (active), **V servisu** (service), **Vyřazeno** (retired)
- Detail zařízení se servisní historií
- Přidání (a smazání) servisního záznamu přímo z detailu zařízení
- Součet servisních nákladů pro každé zařízení (v detailu i v seznamu)
- Barevně i textově rozlišený stav (zelená / oranžová / šedá)
- Volitelné rozšíření: **export servisní historie do CSV**

## Datový model

| Tabulka | Sloupce |
|---|---|
| `equipment` | `id`, `name`, `inventory_number` (unique), `status`, `purchased_at`, timestamps |
| `service_records` | `id`, `equipment_id` (FK → equipment, cascade delete), `description`, `serviced_at`, `cost` decimal(10,2), timestamps |

Vztah: `Equipment` **1 : N** `ServiceRecord`.

## Validace

- Evidenční číslo je povinné a **jedinečné** (pravidlo `unique` + unikátní index v DB).
- Stav musí být z povoleného seznamu `active`, `service`, `retired` (`Rule::in`).
- Datum nákupu i servisu musí být ve formátu `RRRR-MM-DD`, ne v budoucnosti a ne před rokem 1900.
- Cena je **nezáporné desetinné číslo** s max. 2 desetinnými místy.

## Důležité soubory

- `database/migrations/2026_11_05_*` – migrace tabulek
- `app/Models/Equipment.php`, `app/Models/ServiceRecord.php` – modely a vztahy
- `database/seeders/DatabaseSeeder.php` – testovací data
- `app/Http/Controllers/EquipmentController.php` – resource controller + export CSV
- `app/Http/Controllers/ServiceRecordController.php` – servisní záznamy
- `routes/web.php` – routy
- `resources/views/` – šablony (Blade)
- `tests/Feature/EquipmentTest.php` – testy

## Spuštění

Potřeba: PHP 8.3+, Composer, rozšíření `pdo_sqlite`.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite        # databáze SQLite
php artisan migrate --seed            # tabulky + testovací data
php artisan serve                     # http://127.0.0.1:8000
```

Testy: `php artisan test`
