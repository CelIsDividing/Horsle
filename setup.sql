-- PostgreSQL sema (Render + Aiven deployment)
--
-- Postgres nema `USE` i ne dozvoljava CREATE DATABASE unutar iste konekcije/transakcije.
-- Bazu prvo napravi u Aiven konzoli (ili sa `CREATE DATABASE ime_baze;` na defaultdb),
-- pa se konektuj na tu bazu i pokreni ovaj fajl.
-- Vrednost DB_NAME env varijable na Render-u mora da odgovara imenu te baze.

-- Tabela korisnika
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    age INTEGER NOT NULL,
    bio TEXT,
    gender VARCHAR(20),
    photo_url VARCHAR(255),
    city VARCHAR(50),
    looking_for VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela lajkova/diskarda
CREATE TABLE IF NOT EXISTS likes (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    liked_user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    action VARCHAR(10) NOT NULL, -- 'like' ili 'dislike'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_like UNIQUE (user_id, liked_user_id)
);

-- Tabela poslednje posećenih profila (za logovanje)
CREATE TABLE IF NOT EXISTS profiles_viewed (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    viewed_user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela za mutual matches (dopisivanje)
CREATE TABLE IF NOT EXISTS matches (
    id SERIAL PRIMARY KEY,
    user_id_1 INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    user_id_2 INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_match UNIQUE (user_id_1, user_id_2)
);

-- Tabela za sesije (DB-based, prezivljava restart/redeploy)
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) NOT NULL PRIMARY KEY,
    data TEXT NOT NULL,
    last_access INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_sessions_last_access ON sessions (last_access);

-- Tabela za poruke
CREATE TABLE IF NOT EXISTS messages (
    id SERIAL PRIMARY KEY,
    match_id INTEGER NOT NULL REFERENCES matches(id) ON DELETE CASCADE,
    sender_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    receiver_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
