# MatchMe - Tinder-like Dating Aplikacija

## 📋 Opis projekta

MatchMe je PHP aplikacija koja simulira funkcionalnost Tinder-a. Korisnici se mogu registrovati, prijavljati, pregledati profile drugih korisnika, i lajkovati/diskard-ovati profile.

## ✨ Implemented Features

Projekat pokriva sve zahtevane aspekte:

### 1. **Forme i njihova obrada (7 poena)**
- Registracijska forma sa validacijom
- Login forma
- Forma za uređivanje profila
- AJAX obrada like/dislike akcija
- Server-side validacija svih input podataka

### 2. **Zaštita od XSS napada (7 poena)**
- `sanitize_input()` funkcija čisti sve user input-e
- `htmlspecialchars()` sa ENT_QUOTES za bezbednu pretragu
- Korišćenje prepared statements u svim SQL upitima
- `safe_output()` funkcija za siguran prikaz podataka
- Zaštita CSRF tokena je moguća sa `session_id()`

### 3. **Rad sa cookie-jima i sesijom (8 poena)**
- Session management sa `$_SESSION` globalnom promenljivom
- Cookie-ji sa rokom važenja (30 dana) za user_id i username
- Automatski logout sa brisanjem session-a i cookie-ja
- Session validacija na svim zaštićenim stranama
- Session timeout moguć sa `session_set_cookie_params()`

### 4. **Rad sa MySQL bazom podataka (8 poena)**
- Tri tabele: users, likes, profiles_viewed
- Operacije: INSERT, SELECT, UPDATE
- Foreign keys za relacije između tabela
- Prepared statements za sve SQL upite
- Transakcije i error handling

## 🗂️ Struktura fajlova

```
.
├── index.php           # Početna stranica
├── register.php        # Registracija korisnika
├── login.php          # Prijava korisnika
├── dashboard.php      # Pregled profila
├── profile.php        # Moj profil
├── logout.php         # Odjava
├── db_config.php      # Konfiguracija baze
├── functions.php      # Helper funkcije
└── setup.sql          # SQL za kreiranje baze
```

## 🚀 Instalacija i pokretanje

### 1. Preuzmite fajlove
- Stavite sve fajlove u `htdocs` folder (ako koristiš XAMPP) ili `/var/www/html` (ako koristiš Linux)

### 2. Kreiraj bazu podataka
- Otvori phpMyAdmin (http://localhost/phpmyadmin)
- Kreiraj novu bazu `tinder_db`
- Kopira sadržaj iz `setup.sql` i izvrši u SQL tabu

Ili direktno u terminalu:
```bash
mysql -u root -p < setup.sql
```

### 3. Konfiguruj konekciju
- Otvori `db_config.php`
- Promeni `$db_user`, `$db_password` i `$db_name` ako je potrebno

### 4. Pokreni aplikaciju
- Otvori browser i idi na `http://localhost/Veb%20programiranje/Projekat/`

## 👤 Test korisnici

Možeš kreirati test korisnike kroz registraciju, ili uneti direktno u bazu:

```sql
INSERT INTO users (username, email, password, first_name, age, gender, city, bio, looking_for) 
VALUES 
('testuser1', 'test1@example.com', '$2y$10$...', 'John', 25, 'Muški', 'Beograd', 'Volim putovanja', 'Vezu'),
('testuser2', 'test2@example.com', '$2y$10$...', 'Jane', 23, 'Ženski', 'Novi Sad', 'Ljubitelj knjiga', 'Vezu');
```

Za lozinku, koristi `password_hash('password123', PASSWORD_DEFAULT)` u PHP-u.

## 🔒 Sigurnost

- Sve lozinke su heširane sa `password_hash()` i `PASSWORD_DEFAULT`
- Svi user input-i su čišćeni od XSS napada
- Prepared statements защite od SQL injekcije
- Session-i su aktivni tokom pregledanja aplikacije

## 🛠️ Technologies

- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript (minimal za aktivacije formi)

## 📝 Napomene

- CSS je uključen za bolji UX, ali se ne boduje
- Rad sa fajlovima nije implementiran (kako je traženo)
- Sve vrednosti se филтректно sanitizovane pre nego što se prikazuju

## 🎯 Kako funkcioniše aplikacija

1. **Nelogovani korisnik** -> index.php landing stranica
2. **Registracija** -> register.php (validacija i čuvanje u bazi)
3. **Login** -> login.php (password_verify, postavljanje session-a i cookie-ja)
4. **Dashboard** -> dashboard.php (pregled profila, like/dislike)
5. **Profil** -> profile.php (uređivanje svojih informacija)
6. **Logout** -> logout.php (brisanje session-a i cookie-ja)

## 📞 Podrška

Ako imaš pitanja ili problema, proveri:
- Da li je MySQL server pokrenut
- Da li su kredencijali tačni u `db_config.php`
- Lokaciju fajlova (trebali bi biti dostupni preko http://localhost)

---

**Autori:**
- Projekat kreiran za predmet Veb programiranje
- Datum: Februar 2026
