# Przykładowy moduł PrestaShop 8+

Użyj tego repozytorium jako punktu startowego do tworzenia swojego modułu. Znajdziesz w nim: 

- pliki i foldery zorganizowane zgodnie z DDD (oraz wymaganiami PrestaShop)
- gotowe klasy do instalacji hooków, tabów, tabel itp.
- kontener DI + podstawowa konfiguracja
- skonfigurowany PHPStan
- gotowy kontroler i serwis ustawień modułu

## Pierwsze użycie (przy pomocy skryptu PowerShell)

1. Dodaj do swojego systemu skrypt PowerShell do tworzenia modułu PrestaShop 8+ [Instrukcja](https://wiki.arkonsoft.pl/prestashop/modules/create-module-script.html)
1. Wejdź do folderu `modules` w projekcie PrestaShop.
1. Uruchom komendę `ps-module-create` i postępuj zgodnie z instrukcją.
1. Podmień logo, jeżeli tworzysz moduł dla Klienta.

## Pierwsze użycie (ręcznie)

1. Sklonuj to repozytorium do folderu `modules` w projekcie PrestaShop. 
```bash
git clone https://github.com/Arkonsoft/PS-Example-Module-8.git ./twojanazwamodulu
```
1. Podmień w całym folderze wystąpienia "arkonexample" oraz "ArkonExample" (**ważne**: wyszukuj z uwzględnieniem wielkości liter) na odpowiedniki zgodnie z nazwą Twojego modułu.
1. Podmień nazwę głównego pliku modułu arkonexample.php.
1. Podmień nazwę pliku controllers/admin/AdminArkonExampleSettingsController.php na Admin{TWOJA_NAZWA_MODUŁU}SettingsController.php
1. Uruchom komendę `composer install`.
1. Podmień logo, jeżeli tworzysz moduł dla Klienta.

## Struktura modułu

### Foldery

```bash
.
├── controllers
│   └── admin                     # folder na legacy kontrolery back-office
├── src                           # zawiera główny kod modułu
│   ├── Application               # zawiera klasy kontrolerów, DTO, niektóre serwisy itp. działające na styku Domeny i Infrastruktury
│   │   └── Controller           # zawiera właściwe implementacje kontrolerów
│   ├── Domain                    # zawiera kod związany z logiką biznesową modułu (np. obiekty, interfejsy, serwisy domenowe)
│   └── Infrastructure           # zawiera klasy repozytoriów, presenterów, formatterów, serwisów itp., które implementują logike domenową w kontekście PrestaShop
│       ├── Bootstrap            # zawiera kod inicjalizujący moduł, np. Instalator
│       ├── Form                 # zawiera kod dot. formularzy w module
│       └── Service              # zawiera kod serwisów, np. Settings
└── tests                        # folder zawierający testy i konfigurację PHPUnit & PHPStan
```

### Dlaczego DDD?

Standardowe podejście z podziałem plików na kontrolery, serwisy, repozytoria itp. nie zawsze jest wystarczające, ponieważ tworzy silne powiązania między warstwami, co utrudnia wprowadzanie zmian i testowanie. W przypadku modułów na PrestaShop bardzo często mamy do czynienia z modyfikacjami założeń, które niestety równie często prowadzą do potrzeby przepisywania niemal całego modułu. Użycie DDD (choć początkowo może wydawać się skomplikowane) pozwala na znacznie elastyczniejsze podejście do zmian w modułach, zwiększa czytelność kodu i umożliwia lepszą integrację z innymi systemami. 

**Przykład:**
Encja ProductLabel znajduje się w warstwie Domain i tam zdefiniowana jest jej struktura oraz logika. 
Repozytorium ProductLabelRepository znajduje się w warstwie Infrastructure i zawiera implementację techniczną pobierania danych o encji ProductLabel z bazy danych. 
Serwis ProductLabelService znajduje się w warstwie Infrastructure, korzysta z repozytorium i zawiera logikę dotyczącą wyświetlania etykiet na określonych Hookach (np. na ekranie produktu).

W takim układzie, jeżeli pojawi się potrzeba dodawania informacji o etykietach produktów w feedzie Google (coś, czego całkowicie nie przewidywaliśmy), modyfikacja sprowadza się niemal wyłącznie do dodania nowej metody w serwisie ProductLabelService lub ew. utworzeniu nowego serwisu. Encja i repozytorium nie były ściśle związane z konkretnym use-case'em, więc można je bezproblemowo używać w innych miejscach modułu (do zastosowań, których nie przewidywaliśmy).

## Konfiguracja

1. Ustaw nazwę i opis modułu w głównym pliku modułu. Nie stosuj translacji jeżeli moduł jest przeznaczony tylko dla polskojęzycznego Klienta (translacje w konstruktorze dorzucają kilka ms do czasu ładowania strony, a finalnie bardzo rzadko są przydatne w back-office).
2. Sprawdź jakie pozycje w menu back-office chcesz dodać i wprowadź modyfikacje w `src\Infrastructure\Bootstrap\Install\TabInstaller.php`. Domyślnie dodany zostanie ukryty tab z ustawieniami modułu. Jeżeli chcesz, aby link był widoczny, zmień parametr $shouldBeVisibleInMenu w `installTab` na `true`.
3. Sprawdź jakie hooki są potrzebne do Twojego modułu i wprowadź modyfikacje w `src\Infrastructure\Bootstrap\HookInstaller.php`.
4. Sprawdź czy moduł powinien stworzyć własne foldery i wprowadź modyfikacje w `src\Infrastructure\Bootstrap\DirectoryInstaller.php`.
5. Sprawdź czy moduł powinien stworzyć jakieś tabele w bazie danych i wprowadź modyfikacje w `src\Infrastructure\Bootstrap\DbInstaller.php`. DbInstaller przyjmuje obiekty typu `DbInstallerCommandInterface`, które zawierają nazwę zapytania oraz samo zapytanie w formie SQL.

### Przykładowa klasa komendy do instalacji tabeli w bazie danych

```php
class CreateMyExampleTableCommand implements DbInstallerCommandInterface
{
    public function getName(): string
    {
        return 'CreateMyExampleTable';
    }

    public function getSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . ArkonExampleEntity::$definition['table'] . '` (
            `' . ArkonExampleEntity::$definition['primary'] . '` int(11) NOT NULL AUTO_INCREMENT,
            `content` text NOT NULL,
            `position` int(11) NOT NULL DEFAULT 1,
            `active` tinyint(1) NOT NULL DEFAULT 1,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`' . ArkonExampleEntity::$definition['primary'] . '`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
    }
}
```

Aby podłączyć komendę do instalacji, należy dodać ją do tablicy `$installCommands` w klasie `DbInstaller`. Możesz ją przekazać w konstruktorze za pomocą DI.

```php
class DbInstaller implements InstallerInterface
{
    public function __construct(
        private \Db $db,
        private CreateMyExampleTableCommand $createMyExampleTableCommand,
    ) {
    }

    public function install(): bool
    {
        $installCommands = [
            $this->createMyExampleTableCommand,
        ];

        foreach ($installCommands as $command) {
            $this->executeCommand($command);
        }

        return true;
    }
```

Analogicznie należy dodać komendy do deinstalacji.

Takie podejście wymaga dość dużej ilości kodu (1 klasa na akcję instalacji/deinstalacji każdej tabeli), ale pozwala na znacznie większą kontrolę nad instalacją i deinstalacją modułu.

### Ustawienia modułu

Moduł posiada gotowe kontrolery i serwisy ustawień. 

Aby dodać nowe pola ustawień modułu należy:
1. Zdefiniowac nowe pola w pliku `src\Infrastructure\Form\Settings\SettingsFormDictionary.php`
2. Dodać nowe gettery i settery w serwisie `Settings` w pliku `src\Infrastructure\Service\Settings.php`
3. Dodać pola w formacie HelperForm'a `src\Application\Controller\Admin\SettingsController.php`. Obsługa zapisu i odczytu znajduje się w klasie nadrzędnej tego kontrolera więc nie musisz się tym całkowicie przejmować. Pola same się zapiszą i odczytają do formularza.