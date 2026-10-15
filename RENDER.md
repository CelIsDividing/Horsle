# Render + Aiven (PostgreSQL)

Jedan besplatni Aiven PostgreSQL servis moze da drzi baze za sve projekte
(Postgres podrzava vise baza na jednom serveru). Svaki projekat koristi svoju bazu.

## Aiven (jednom, za sve projekte)

1. Aiven Console -> Create service -> PostgreSQL -> plan **Free** -> region Frankfurt.
2. Overview -> **Connection information**: zapisi Host, Port, User (`avnadmin`),
   Password i default bazu (`defaultdb`). Skini `ca.pem` ako ga klijent zahteva.
3. Overview -> **Allowed IP addresses** -> dodaj `0.0.0.0/0`
   (Render izlazne IP adrese su dinamicke).

## Po projektu

1. Napravi bazu za projekat. U Aiven konzoli (Query editor ili preko klijenta)
   pokreni: `CREATE DATABASE projekat1_db;`
2. Konektuj se na **tu** bazu (pgAdmin / `psql`) i pokreni `setup.sql`
   (Postgres nema `USE`, pa se prvo bira baza pri konekciji).
3. Push repozitorijum na GitHub.
4. Na Render-u: New -> Blueprint i izaberi repo (`render.yaml` se procita sam).
5. Popuni env varijable:

   | Key | Value |
   | --- | --- |
   | `DB_HOST` | Aiven host |
   | `DB_PORT` | Aiven port |
   | `DB_USER` | `avnadmin` |
   | `DB_PASSWORD` | Aiven password |
   | `DB_NAME` | ime baze iz koraka 1 (npr. `projekat1_db`) |
   | `DB_SSLMODE` | `require` |

6. Deploy i otvori `onrender.com` URL.

## Napomene

- Deljeni limiti: 1 GB storage i ogranicen broj konekcija za sve projekte.
- Servis se gasi kada nema aktivnosti; prvi zahtev posle toga traje ~30s.
- Sesije se cuvaju u bazi, pa korisnici ostaju ulogovani i posle restarta.
- Aplikacija koristi PDO (`pdo_pgsql`), pa ista baza radi i lokalno i na Render-u.
