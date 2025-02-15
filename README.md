# kostka-server

## Jest to przepisany stary projekt na nową wersję frameworka CodeIgniter 4.
Przepisywanie nie jest dokończone.

## Główne cechy projektu:
- użycie modelu MVC

- architektura: dostęp bezpośredni przez http (z wykorzystaniem osadzania przez iframe, aby nie ingerować w strukturę stron hostujących)

- dane: lista forów, użytkowników, możliwych rzutów, wykonanych rzutów

- dane złożone:
  - fora przypisane do użytkownika,
  - rzuty przypisane do użytkownika i forum

- funkcjonalność: listowanie rzutów w obrebie forów, listowanie forów, przypisywanie forów, rejestracja i logowanie

- rzuty kostką zawierają:
  - tytuł, opis,
  - nieograniczona ilość rzutów w ramach jednego rozdania
  - możliwość łączenia rzutów,
  - predefiniowane rodzaje kostek + dowolność w wyborze ścian kostek
  - zabezpieczanie legalności rzutu poprzez przypisywanie go do konkretnego numeru posta na konkretnym forum, w chwili wykonywania żądania losu.
  
- osobny projekt ze skryptem JS, wyświetlającym w ładny sposób pobierane dane i dopasowujący je do stylu forum
