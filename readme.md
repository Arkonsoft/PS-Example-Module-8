# Przykładowy moduł PrestaShop 8+

## Spis treści

1. [Wstęp](#wstep)
1. [Instalacja](#instalacja)
    1. [Taby](#taby)
    2. [Tabele](#tabele)
1. [Konfiguracja](#konfiguracja)
1. [Użycie](#uzycie)
1. [Dodatkowe informacje](#dodatkowe-informacje)

## Wstęp

## Instalacja

### Taby

#### Co zrobić, aby Tab nie był widoczny w menu?

Wejdź do pliku `src/Infrastructure/Bootstrap/Install/TabInstaller.php` i w metodzie `install()` zmień wartość `active` na `false`.

```php
public function install(): bool
{
    // ...
    $tab->active = false;
    // ...
}
```




