# Przykładowy moduł PrestaShop 8+

## Instalacja

1. Utwórz folder z modułem na Twoim lokalnym środowisku i wejdź do niego terminalem.
2. Przy pomocy komendy `git clone https://github.com/Arkonsoft/PS-Example-Module-8 .` pobierz zawartość repozytorium do folderu.
3. Podmień w całym folderze wystąpienia "arkonexample" oraz "ArkonExample" (**ważne**: wyszukuj z uwzględnieniem wielkości liter) na odpowiedniki zgodnie z nazwą Twojego modułu.
4. Podmień nazwę głównego pliku modułu arkonexample.php.
5. Podmień nazwę pliku controllers/admin/AdminArkonExampleSettingsController.php na Admin{TWOJA_NAZWA_MODUŁU}SettingsController.php
6. W pliku `src/Infrastructure/Bootstrap/Install/TabInstaller.php` możesz zarządzać tym, gdzie podłączy się link do konfiguracji modułu.
7. W pliku `src/Infrastructure/Bootstrap/Install/HookInstaller.php` możesz zarejestrować hooki modułu.
8. Podmień logo (jeżeli robisz moduł dla Klienta)
9. Uruchom komendę `composer install`.
