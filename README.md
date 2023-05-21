## Aplikacja

Aplikacja używa laravela 10 oraz PHP 8.1, a do autoryzacji API użyty
został [Sanctum](https://laravel.com/docs/10.x/sanctum).

Pozwala ona na wylistowanie/filtrowanie produktów oraz
dodawanie/aktualizację/usuwanie produktów oraz ich cen. Produkty i ceny używają osobnych tabel, jeden produkt może mieć
wiele cen.

Seeder tworzy domyślnie konto użytkownika, które można użyć do autoryzacji, jak i również produkty i ich ceny. Również
wszystkie tabelki są tworzone przez migracje.

| Email            | Password |
|------------------|----------|
| test@example.com | password |

Aplikacja nie używa klas Serwisów/Akcji ze względu na to, że zapytania czy zapis/aktualizacja danych w prawie każdym
przypadku są długości 1 linijki, aczkolwiek jest to rzecz którą warto by było rozważyć w przypadku dalszego rozwoju
aplikacji.

Zawarte są również feature testy obejmujące każdy API endpoint, wraz ze sprawdzeniem poprawności filtrów/sortowania.

## Endpointy

| METHOD    | URI                                    | CONTROLLER                     |
|-----------|----------------------------------------|--------------------------------|
| POST      | /api/login                             | AuthorizationController@login  |
| GET/HEAD  | /api/products                          | ProductController@index        |
| POST      | /api/products                          | ProductController@store        |
| GET/HEAD  | /api/products/{product}                | ProductController@show         |
| PUT/PATCH | /api/products/{product}                | ProductController@update       |
| DELETE    | /api/products/{product}                | ProductController@destroy      |
| POST      | /api/products/{product}/prices         | ProductPriceController@store   |
| GET/HEAD  | /api/products/{product}/prices/{price} | ProductPriceController@show    |
| PUT/PATCH | /api/products/{product}/prices/{price} | ProductPriceController@update  |
| DELETE    | /api/products/{product}/prices/{price} | ProductPriceController@destroy |

## Instalacja
Jedyne co jest potrzebne po `composer install`, to skonfigurowanie `.env`, aby miało połączenie z bazą danych, a następnie uruchomienie migracji (opcjonalnie z flagą `--seed`)
