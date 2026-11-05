# Evidence techniky

Školní projekt v Laravelu - evidence firemního nářadí a jeho servisu.

Co to umí:
- přidat / upravit / smazat zařízení
- filtrovat podle stavu (aktivní, v servisu, vyřazeno)
- u každého zařízení servisní historie a součet nákladů
- export servisu do CSV

## Spuštění

```
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Testy: `php artisan test`
