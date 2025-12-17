🎮 MiniGry – Platforma z grami online
📌 Opis projektu

MiniGry to webowa platforma z systemem rejestracji i logowania użytkowników, umożliwiająca
granie w proste gry oraz zdobywanie punktów.
Projekt został napisany w języku PHP, uruchamiany na XAMPP i wykorzystuje bazę danych MySQL.

Po zalogowaniu użytkownik widzi stronę główną z listą dostępnych gier, swoim wynikiem punktowym,
profilem oraz tabelą rankingową najlepszych graczy.

Projekt został zrealizowany zespołowo w celu nauki współpracy z wykorzystaniem GitHub
oraz narzędzi do zarządzania projektem.

🎯 Cele projektu
1.Implementacja systemu rejestracji i logowania użytkowników
2.Stworzenie platformy z mini-grami i punktacją
3.Nauka pracy zespołowej w środowisku GitHub
4.Praca z:
*GitHub Issues
*Branchami
*Pull Requestami
5.Stworzenie funkcjonalnego prototypu aplikacji webowej

🛠 Wykorzystane technologie
1.Frontend: HTML, CSS, JavaScript
2.Backend: PHP
3.Baza danych: MySQL (phpMyAdmin)
4.Serwer: XAMPP (Apache)
5.Kontrola wersji: Git & GitHub

👥 Role w zespole
🔹 Lider Projektu ( Bohdan Melnychuk )
1.Utworzenie i zarządzanie repozytorium na GitHubie
2.Organizacja zadań przy użyciu GitHub Issues
3.Nadzór nad pull requestami oraz przegląd kodu

🔹 Front-End Developer / UI Designer ( Roman Vykeryk )
1.Projektowanie interfejsu użytkownika (UX/UI)
2.Implementacja widoków stron i gier
3.Stylizacja i spójność wizualna aplikacji

🔹 Back-End Developer ( Artem Semenko )
1.Implementacja rejestracji i logowania użytkowników
2.Obsługa bazy danych (użytkownicy, wyniki, punkty)
3.Zapisywanie i przeliczanie wyników gier

🔹 Tester / DevOps (opcjonalnie) ( Roman Vykeryk, Bohdan Melnychuk, Artem Semenko )
1.Testowanie funkcjonalności aplikacji
2.Sprawdzanie poprawności naliczania punktów
3.Kontrola jakości kodu

🎮 Dostępne gry
       Gra                                	Opis	                                               Punkty
❌⭕ Kółko i Krzyżyk	            Gra przeciwko komputerowi             	               Punkty za wygraną
🐍 Wąż	                          Klasyczna gra Snake	                                   Punkty za wynik
🎯 Kliknij Cele	                  Gra na refleks i celność	                             Punkty za celność
🖱 Clicker	                      Klikanie jak najszybciej przez określony czas	         Punkty za liczbę kliknięć
👤 Funkcjonalności użytkownika
✔ Rejestracja i logowanie
✔ Wyświetlanie łącznej liczby punktów
✔ Dostęp do gier
✔ Naliczanie punktów za każdą grę
✔ Profil użytkownika ze statystykami
✔ Historia ostatnich gier
✔ Ranking najlepszych graczy

📊 Profil użytkownika
W profilu użytkownika wyświetlane są:
1.nazwa użytkownika
2.data rejestracji
3.łączny wynik punktowy
4.statystyki dla każdej gry:
*liczba rozegranych gier
*najlepszy wynik
*średni wynik
5.lista ostatnio rozegranych gier

🏆 Ranking
1.Ranking przedstawia użytkowników z największą liczbą punktów
2.Dane pobierane są z bazy danych
3.Ranking aktualizowany jest automatycznie po zakończeniu gry

📂 Struktura projektu (przykładowa)
/minigry
│── index.php
│── login.php
│── register.php
│── profile.php
│── ranking.php
│
├── games/
│   ├── snake.php
│   ├── tic_tac_toe.php
│   ├── clicker.php
│   └── target_game.php
│
├── css/
├── js/
├── db/
│   └── database.sql
│
└── README.md

🚀 Jak uruchomić projekt
1.Zainstalować XAMPP
2.Skopiować folder projektu do:
xampp/htdocs/
3.Uruchomić Apache oraz MySQL
4.Zaimportować bazę danych przez phpMyAdmin
5.Otworzyć w przeglądarce:
http://localhost/minigry

✅ Efekt końcowy
1.Funkcjonalna platforma webowa z grami
2.System rejestracji i logowania użytkowników
3.Profil użytkownika ze statystykami
4.Tabela rankingowa
5.Historia pracy zespołu na GitHubie
6.Dokumentacja projektu

🎓 Dlaczego projekt spełnia wymagania zadania
✔ praca zespołowa
✔ wykorzystanie GitHub
✔ Issues i Pull Requesty
✔ działający prototyp aplikacji
✔ czytelny podział ról
✔ kompletna dokumentacja
