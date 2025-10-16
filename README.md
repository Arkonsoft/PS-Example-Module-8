# Przykładowy moduł PrestaShop 8+

Użyj tego repozytorium jako punktu startowego do tworzenia swojego modułu. Znajdziesz w nim: 

- pliki i foldery zorganizowane zgodnie z DDD (oraz wymaganiami PrestaShop)
- gotowe klasy do instalacji hooków, tabów, tabel itp.
- kontener DI + podstawowa konfiguracja
- skonfigurowany PHPStan
- gotowy kontroler i serwis ustawień modułu

## Spis treści

- [Pierwsze użycie (przy pomocy skryptu)](#pierwsze-użycie-przy-pomocy-skryptu)
- [Pierwsze użycie (ręcznie)](#pierwsze-użycie-ręcznie)
- [Przed rozpoczęciem pracy](#przed-rozpoczęciem-pracy)
- [Struktura modułu](#struktura-modułu)
- [Konfiguracja](#konfiguracja)
- [Testowanie i jakość kodu](#testowanie-i-jakość-kodu)
- [Przykładowe pliki SQL do instalacji tabel](#przykładowe-pliki-sql-do-instalacji-tabel)
- [Ustawienia modułu](#ustawienia-modułu)
- [Gotowe prompty dla AI](#gotowe-prompty-dla-ai)
- [Dlaczego DDD?](#dlaczego-ddd)

## Pierwsze użycie (przy pomocy skryptu)

TODO: Opis skryptu

## Pierwsze użycie (ręcznie)

1. Sklonuj to repozytorium do folderu `modules` w projekcie PrestaShop. 
```bash
git clone https://github.com/Arkonsoft/PS-Example-Module-8.git ./twojanazwamodulu
```
2. Podmień w całym folderze wystąpienia "arkonexample" oraz "ArkonExample" (**ważne**: wyszukuj z uwzględnieniem wielkości liter) na odpowiedniki zgodnie z nazwą Twojego modułu.
3. Podmień nazwę głównego pliku modułu arkonexample.php.
4. Podmień nazwę pliku controllers/admin/AdminArkonExampleSettingsController.php na Admin{TWOJA_NAZWA_MODUŁU}SettingsController.php
5. Uruchom komendę `composer install`.
6. Podmień logo, jeżeli tworzysz moduł dla Klienta.

## Przed rozpoczęciem pracy

Przed rozpoczęciem pracy z modułem, upewnij się, że wykonałeś następujące kroki:

### 1. Pliki SQL
Pliki `sql/install.sql` i `sql/uninstall.sql` są **zakomentowane jako przykład**. Należy je odkomentować i dostosować do potrzeb Twojego modułu.

### 2. Serwis Settings
Serwis `Settings` w `src/Settings/Infrastructure/Service/Settings.php` zawiera tylko przykładową metodę `getExampleTextFieldName()`. Należy dodać własne gettery i settery dla wszystkich pól zdefiniowanych w `SettingsFormDictionary`.

### 3. Tłumaczenia
Plik `translations/pl.php` jest pusty. Należy dodać tłumaczenia jeśli są potrzebne dla Twojego modułu.

## Struktura modułu

### Foldery

```bash
├── controllers
│   └── admin                    # folder na legacy kontrolery back-office
│       └── AdminArkonExampleSettingsController.php
├── src                          # zawiera główny kod modułu
│   ├── Settings                 # funkcjonalność ustawień modułu
│   │   ├── Application          # zawiera klasy kontrolerów działające na styku Domeny i Infrastruktury
│   │   │   └── Controller       # zawiera właściwe implementacje kontrolerów
│   │   │       └── Admin        # kontrolery administracyjne
│   │   │           └── SettingsAdminController.php
│   │   ├── Domain               # zawiera kod związany z logiką biznesową ustawień
│   │   │   └── ValueObject      # obiekty wartości, np. słowniki formularzy
│   │   │       └── SettingsFormDictionary.php
│   │   └── Infrastructure       # zawiera implementacje serwisów ustawień
│   │       └── Service          # zawiera kod serwisów, np. Settings
│   │           └── Settings.php
│   └── Shared                   # wspólne komponenty modułu
│       └── Infrastructure       # zawiera klasy inicjalizujące moduł
│           └── Bootstrap        # zawiera kod inicjalizujący moduł, np. Instalator
│               └── Install      # klasy instalacyjne
│                   ├── DbInstaller.php
│                   ├── DirectoryInstaller.php
│                   ├── HookInstaller.php
│                   ├── Installer.php
│                   ├── InstallerInterface.php
│                   └── TabInstaller.php
├── sql                          # pliki SQL do instalacji/deinstalacji
│   ├── install.sql
│   └── uninstall.sql
├── translations                 # pliki tłumaczeń
│   └── pl.php
└── tests                        # folder zawierający testy i konfigurację PHPUnit & PHPStan
    ├── bootstrap.php
    └── index.php
```

## Konfiguracja

1. Ustaw nazwę i opis modułu w głównym pliku modułu. Nie stosuj translacji jeżeli moduł jest przeznaczony tylko dla polskojęzycznego Klienta (translacje w konstruktorze dorzucają kilka ms do czasu ładowania strony, a finalnie bardzo rzadko są przydatne w back-office).
2. Sprawdź jakie pozycje w menu back-office chcesz dodać i wprowadź modyfikacje w `src\Shared\Infrastructure\Bootstrap\Install\TabInstaller.php`. Domyślnie dodany zostanie ukryty tab z ustawieniami modułu. Jeżeli chcesz, aby link był widoczny, zmień parametr $shouldBeVisibleInMenu w `installTab` na `true`.
3. Sprawdź jakie hooki są potrzebne do Twojego modułu i wprowadź modyfikacje w `src\Shared\Infrastructure\Bootstrap\Install\HookInstaller.php`.
4. Sprawdź czy moduł powinien stworzyć własne foldery i wprowadź modyfikacje w `src\Shared\Infrastructure\Bootstrap\Install\DirectoryInstaller.php`.
5. Sprawdź czy moduł powinien stworzyć jakieś tabele w bazie danych i wprowadź modyfikacje w plikach `sql/install.sql` i `sql/uninstall.sql`. DbInstaller automatycznie wykonuje te pliki podczas instalacji i deinstalacji modułu.

## Testowanie i jakość kodu

### PHPStan

Moduł jest skonfigurowany do pracy z PHPStan na poziomie 8. Aby uruchomić analizę statyczną:

```bash
composer analyse
```

### Wymagania do uruchomienia PHPStan

**Ważne**: PHPStan wymaga obecności folderu `install-dev` w głównym katalogu PrestaShop.

#### Skąd pobrać folder `install-dev`:

1. Przejdź do oficjalnego repozytorium PrestaShop: https://github.com/PrestaShop/PrestaShop
2. Pobierz odpowiedni tag dla używanej wersji PrestaShop (np. `8.1.0`)
3. Skopiuj folder `install-dev` z pobranego repozytorium do głównego katalogu Twojego PrestaShop

Przykład:
```bash
# Pobierz tag 8.1.0
git clone --depth 1 --branch 8.1.0 https://github.com/PrestaShop/PrestaShop.git temp-prestashop

# Skopiuj folder install-dev
cp -r temp-prestashop/install-dev /path/to/your/prestashop/

# Usuń tymczasowy folder
rm -rf temp-prestashop
```

## Przykładowe pliki SQL do instalacji tabel w bazie danych

**sql/install.sql:**
```sql
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_arkonexample_entity` (
    `id_entity` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `position` int(10) unsigned NOT NULL DEFAULT 0,
    `active` tinyint(1) NOT NULL DEFAULT 1,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_entity`)
) ENGINE=_MYSQL_ENGINE_ DEFAULT CHARSET=utf8;

-- Dodaj więcej tabel według potrzeb
-- CREATE TABLE IF NOT EXISTS `_DB_PREFIX_arkonexample_another_table` (
--     `id` int(11) NOT NULL AUTO_INCREMENT,
--     `name` varchar(255) NOT NULL,
--     PRIMARY KEY (`id`)
-- ) ENGINE=_MYSQL_ENGINE_ DEFAULT CHARSET=utf8;
```

**sql/uninstall.sql:**
```sql
DROP TABLE IF EXISTS `_DB_PREFIX_arkonexample_entity`;
-- DROP TABLE IF EXISTS `_DB_PREFIX_arkonexample_another_table`;
```

**Jak to działa:**
DbInstaller automatycznie wykonuje pliki SQL podczas instalacji i deinstalacji modułu. Używa PrestaShop SqlLoader, który obsługuje:
- Zastępowanie zmiennych `_DB_PREFIX_` i `_MYSQL_ENGINE_`
- Wykonywanie wielu zapytań SQL w jednym pliku
- Obsługę błędów z szczegółowymi komunikatami

**Zalety tego podejścia:**
- Prostsze w utrzymaniu - wszystkie zapytania SQL w jednym miejscu
- Łatwiejsze debugowanie - można testować zapytania bezpośrednio w bazie danych
- Mniej kodu PHP - nie trzeba tworzyć osobnych klas dla każdej tabeli
- Lepsze wsparcie dla złożonych zapytań SQL

## Ustawienia modułu

Moduł posiada gotowe kontrolery i serwisy ustawień. 

Aby dodać nowe pola ustawień modułu należy:
1. Zdefiniowac nowe pola w pliku `src\Settings\Domain\ValueObject\SettingsFormDictionary.php`
2. Dodać nowe gettery i settery w serwisie `Settings` w pliku `src\Settings\Infrastructure\Service\Settings.php`
3. Dodać pola w formacie HelperForm'a `src\Settings\Application\Controller\Admin\SettingsAdminController.php`. Obsługa zapisu i odczytu znajduje się w klasie nadrzędnej tego kontrolera więc nie musisz się tym całkowicie przejmować. Pola same się zapiszą i odczytają do formularza.

W pliku SettingsAdminController znajdziesz przykładową implementację metody `prepareOptions` w której możesz modyfikować pola formularza.

```php
    /**
     * @return void
     */
    public function prepareOptions()
    {
        $settings = $this->module->moduleContainer->get(Settings::class);

        $form = [
            'form' => [
                'tabs' => [
                    'global' => 'Globalne',
                    // 'other1' => 'Inne 1',
                    // 'other2' => 'Inne 2',
                ],
                'legend' => [
                    'title' => 'Ustawienia',
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'label' => 'Przykładowe pole tekstowe',
                        'type' => 'text',
                        'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_TEXT_FIELD->value),
                        'desc' => 'To jest przykładowy opis pola',
                        'lang' => true,
                        'tab' => 'global',
                    ],
                    [
                        'label' => 'Przykładowy przełącznik',
                        'type' => 'switch',
                        'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_SWITCHER->value),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_SWITCHER->value) . '_on',
                                'value' => 1,
                                'label' => 'Włączony',
                            ],
                            [
                                'id' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_SWITCHER->value) . '_off',
                                'value' => 0,
                                'label' => 'Wyłączony',
                            ],
                        ],
                        'tab' => 'global',
                    ],
                    // [
                    //     'label' => 'Przykładowe pole tekstowe 1',
                    //     'type' => 'text',
                    //     'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_TEXT_FIELD_1),
                    //     'desc' => 'To jest przykładowy opis pola',
                    //     'lang' => true,
                    //     'tab' => 'other1',
                    // ],
                    // [
                    //     'label' => 'Przykładowe pole tekstowe 2',
                    //     'type' => 'text',
                    //     'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_TEXT_FIELD_2)
                    //     'desc' => 'To jest przykładowy opis pola',
                    //     'lang' => true,
                    //     'tab' => 'other2',
                    // ],
                ],
                'submit' => [
                    'title' => 'Zapisz',
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $this->forms[] = $form;
    }
```

## Gotowe prompty dla AI

### Dodawanie nowych pól do ustawień modułu

**Prompt dla Cursor/AI:**

```
Dodaj nowe pole ustawień do modułu PrestaShop. Pole powinno być typu [TYP_POLA] o nazwie [NAZWA_POLA] z opisem "[OPIS_POLA]".

Wykonaj następujące kroki:
1. Dodaj nową stałą do enum SettingsFormDictionary w pliku src/Settings/Domain/ValueObject/SettingsFormDictionary.php
2. Dodaj getter i setter w serwisie Settings w pliku src/Settings/Infrastructure/Service/Settings.php
3. Dodaj pole do formularza w kontrolerze SettingsAdminController w pliku src/Settings/Application/Controller/Admin/SettingsAdminController.php w metodzie prepareOptions()

Zastąp [TYP_POLA] jednym z: text, switch, textarea, select, file
Zastąp [NAZWA_POLA] nazwą pola (np. "my_custom_field")
Zastąp [OPIS_POLA] opisem pola (np. "To jest moje niestandardowe pole")

Używaj standardów nazewnictwa z projektu - camelCase dla metod, UPPER_SNAKE_CASE dla stałych.
```

**Przykład użycia:**
```
Dodaj nowe pole ustawień do modułu PrestaShop. Pole powinno być typu switch o nazwie "enable_notifications" z opisem "Włącz powiadomienia email".

Wykonaj następujące kroki:
1. Dodaj nową stałą do enum SettingsFormDictionary w pliku src/Settings/Domain/ValueObject/SettingsFormDictionary.php
2. Dodaj getter i setter w serwisie Settings w pliku src/Settings/Infrastructure/Service/Settings.php
3. Dodaj pole do formularza w kontrolerze SettingsAdminController w pliku src/Settings/Application/Controller/Admin/SettingsAdminController.php w metodzie prepareOptions()

Używaj standardów nazewnictwa z projektu - camelCase dla metod, UPPER_SNAKE_CASE dla stałych.
```

## Dlaczego DDD?

Standardowe podejście z podziałem plików na kontrolery, serwisy, repozytoria itp. nie zawsze jest wystarczające, ponieważ tworzy silne powiązania między warstwami, co utrudnia wprowadzanie zmian i testowanie. W przypadku modułów na PrestaShop bardzo często mamy do czynienia z modyfikacjami założeń, które niestety równie często prowadzą do potrzeby przepisywania niemal całego modułu. Użycie DDD (choć początkowo może wydawać się skomplikowane) pozwala na znacznie elastyczniejsze podejście do zmian w modułach, zwiększa czytelność kodu i umożliwia lepszą integrację z innymi systemami. 

**Struktura w tym module:**
Moduł jest zorganizowany wokół **bounded contexts** (kontekstów ograniczonych) - każda funkcjonalność ma swój własny kontekst z pełną implementacją warstw DDD. Na przykład funkcjonalność ustawień modułu (`src/Settings/`) zawiera:

- **Domain** (`src/Settings/Domain/`): Logika biznesowa ustawień, obiekty wartości jak `SettingsFormDictionary`
- **Application** (`src/Settings/Application/`): Kontrolery i przypadki użycia, np. `SettingsAdminController`
- **Infrastructure** (`src/Settings/Infrastructure/`): Implementacje serwisów, np. `Settings` service

**Przykład z ustawieniami:**
Obiekt wartości `SettingsFormDictionary` znajduje się w warstwie Domain i definiuje strukturę formularza ustawień. 
Serwis `Settings` w warstwie Infrastructure implementuje logikę zapisywania i odczytywania ustawień z bazy danych.
Kontroler `SettingsAdminController` w warstwie Application orkiestruje interakcję między warstwami i obsługuje żądania HTTP.

**Wspólne komponenty:**
Funkcjonalności wspólne dla całego modułu (jak instalacja, hooki, tabele) znajdują się w `src/Shared/Infrastructure/Bootstrap/Install/`. To pozwala na ponowne użycie kodu instalacyjnego bez duplikowania go w każdym bounded context.

W takim układzie, jeżeli pojawi się potrzeba dodania nowej funkcjonalności (np. zarządzania produktami), można utworzyć nowy bounded context `src/Products/` z własną implementacją warstw DDD, zachowując spójność architektoniczną i możliwość niezależnego rozwoju każdej funkcjonalności.