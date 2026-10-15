-- =========================================================================
-- Dummy podaci (20 redova po tabeli) - PostgreSQL
-- Lozinka za SVE korisnike: password123
-- Pokrenuti na PRAZNOJ bazi, nakon setup.sql.
-- =========================================================================
BEGIN;

-- ----------------------------- USERS -------------------------------------
INSERT INTO users (id, username, email, password, first_name, age, gender, city, bio, looking_for) VALUES
(1,  'marko',   'marko@example.com',   '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Marko',   25, 'Muški',  'Beograd',    'Volim sport i putovanja', 'Vezu'),
(2,  'jelena',  'jelena@example.com',  '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Jelena',  23, 'Ženski', 'Novi Sad',   'Ljubitelj knjiga i kafe', 'Vezu'),
(3,  'nikola',  'nikola@example.com',  '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Nikola',  28, 'Muški',  'Niš',        'Programer i gamer',       'Prijateljstvo'),
(4,  'ana',     'ana@example.com',     '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Ana',     22, 'Ženski', 'Kragujevac', 'Volim muziku',            'Zabavu'),
(5,  'stefan',  'stefan@example.com',  '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Stefan',  30, 'Muški',  'Subotica',   'Planinarenje',            'Vezu'),
(6,  'milica',  'milica@example.com',  '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Milica',  26, 'Ženski', 'Beograd',    'Joga i priroda',          'Vezu'),
(7,  'petar',   'petar@example.com',   '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Petar',   24, 'Muški',  'Novi Sad',   'Fudbal',                  'Prijateljstvo'),
(8,  'jovana',  'jovana@example.com',  '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Jovana',  27, 'Ženski', 'Niš',        'Slikanje',                'Zabavu'),
(9,  'luka',    'luka@example.com',    '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Luka',    29, 'Muški',  'Beograd',    'Putovanja',               'Vezu'),
(10, 'teodora', 'teodora@example.com', '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Teodora', 21, 'Ženski', 'Kragujevac', 'Ples',                    'Prijateljstvo'),
(11, 'vuk',     'vuk@example.com',     '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Vuk',     31, 'Muški',  'Subotica',   'Trčanje',                 'Vezu'),
(12, 'sara',    'sara@example.com',    '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Sara',    24, 'Ženski', 'Beograd',    'Kuvanje',                 'Vezu'),
(13, 'filip',   'filip@example.com',   '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Filip',   26, 'Muški',  'Niš',        'Gitara',                  'Zabavu'),
(14, 'nina',    'nina@example.com',    '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Nina',    28, 'Ženski', 'Novi Sad',   'Crtanje',                 'Vezu'),
(15, 'dusan',   'dusan@example.com',   '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Dušan',   33, 'Muški',  'Beograd',    'Biciklizam',              'Prijateljstvo'),
(16, 'ema',     'ema@example.com',     '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Ema',     22, 'Ženski', 'Kragujevac', 'Filmovi',                 'Zabavu'),
(17, 'ognjen',  'ognjen@example.com',  '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Ognjen',  27, 'Muški',  'Subotica',   'Košarka',                 'Vezu'),
(18, 'hana',    'hana@example.com',    '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Hana',    25, 'Ženski', 'Beograd',    'Fotografija',             'Vezu'),
(19, 'pavle',   'pavle@example.com',   '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Pavle',   34, 'Muški',  'Niš',        'Ribolov',                 'Prijateljstvo'),
(20, 'ivana',   'ivana@example.com',   '$2y$10$0RlJaOBCDiQSBVbaArWzjO0Vtl1ip764huwLPlAzCkuFsr7mU6pG2', 'Ivana',   23, 'Ženski', 'Novi Sad',   'Putovanja',               'Vezu');
SELECT setval(pg_get_serial_sequence('users', 'id'), (SELECT MAX(id) FROM users));

-- ----------------------------- LIKES -------------------------------------
INSERT INTO likes (id, user_id, liked_user_id, action) VALUES
(1,  1,  2,  'like'),
(2,  2,  1,  'like'),
(3,  1,  4,  'dislike'),
(4,  3,  2,  'like'),
(5,  4,  1,  'like'),
(6,  5,  6,  'like'),
(7,  6,  5,  'like'),
(8,  5,  12, 'dislike'),
(9,  7,  8,  'like'),
(10, 8,  7,  'like'),
(11, 9,  10, 'like'),
(12, 10, 9,  'like'),
(13, 11, 12, 'like'),
(14, 12, 11, 'like'),
(15, 13, 14, 'dislike'),
(16, 14, 13, 'like'),
(17, 15, 16, 'like'),
(18, 16, 15, 'like'),
(19, 17, 18, 'like'),
(20, 18, 17, 'like');
SELECT setval(pg_get_serial_sequence('likes', 'id'), (SELECT MAX(id) FROM likes));

-- ------------------------- PROFILES_VIEWED -------------------------------
INSERT INTO profiles_viewed (id, user_id, viewed_user_id) VALUES
(1,  1,  2),  (2,  1,  3),  (3,  1,  4),  (4,  2,  1),  (5,  2,  3),
(6,  3,  1),  (7,  3,  4),  (8,  4,  2),  (9,  5,  6),  (10, 6,  5),
(11, 7,  9),  (12, 8,  10), (13, 9,  7),  (14, 10, 8),  (15, 11, 13),
(16, 12, 14), (17, 13, 11), (18, 14, 12), (19, 15, 17), (20, 16, 18);
SELECT setval(pg_get_serial_sequence('profiles_viewed', 'id'), (SELECT MAX(id) FROM profiles_viewed));

-- ----------------------------- MATCHES -----------------------------------
INSERT INTO matches (id, user_id_1, user_id_2) VALUES
(1,  1,  2),  (2,  1,  3),  (3,  1,  4),  (4,  2,  5),  (5,  2,  6),
(6,  3,  7),  (7,  3,  8),  (8,  4,  9),  (9,  4,  10), (10, 5,  11),
(11, 5,  12), (12, 6,  13), (13, 6,  14), (14, 7,  15), (15, 7,  16),
(16, 8,  17), (17, 8,  18), (18, 9,  19), (19, 9,  20), (20, 10, 11);
SELECT setval(pg_get_serial_sequence('matches', 'id'), (SELECT MAX(id) FROM matches));

-- ----------------------------- MESSAGES ----------------------------------
INSERT INTO messages (id, match_id, sender_id, receiver_id, message, is_read) VALUES
(1,  1,  1,  2,  'Zdravo! Kako si?',                  TRUE),
(2,  1,  2,  1,  'Cao, dobro sam, hvala!',            TRUE),
(3,  2,  1,  3,  'Hej, vidim da volis programiranje', FALSE),
(4,  3,  4,  1,  'Pozdrav!',                          FALSE),
(5,  4,  2,  5,  'Cao, kako ide planinarenje?',       TRUE),
(6,  5,  6,  2,  'Zdravo!',                           FALSE),
(7,  6,  3,  7,  'Hej, igras fudbal?',                TRUE),
(8,  7,  8,  3,  'Cao!',                              FALSE),
(9,  8,  4,  9,  'Pozdrav, volis putovanja?',         FALSE),
(10, 9,  10, 4,  'Hej!',                              TRUE),
(11, 10, 5,  11, 'Zdravo!',                           FALSE),
(12, 11, 12, 5,  'Cao, kako si?',                     TRUE),
(13, 12, 6,  13, 'Hej!',                              FALSE),
(14, 13, 14, 6,  'Pozdrav!',                          FALSE),
(15, 14, 7,  15, 'Cao!',                              TRUE),
(16, 15, 16, 7,  'Zdravo!',                           FALSE),
(17, 16, 8,  17, 'Hej!',                              FALSE),
(18, 17, 18, 8,  'Pozdrav!',                          TRUE),
(19, 18, 9,  19, 'Cao!',                              FALSE),
(20, 19, 20, 9,  'Zdravo!',                           TRUE);
SELECT setval(pg_get_serial_sequence('messages', 'id'), (SELECT MAX(id) FROM messages));

-- ----------------------------- SESSIONS ----------------------------------
-- Napomena: ovo su dummy sesije; realne se prave pri prijavi korisnika.
INSERT INTO sessions (id, data, last_access) VALUES
('dummy_sess_0001','user_id|i:1;username|s:5:"marko";',   EXTRACT(EPOCH FROM now())::int - 60),
('dummy_sess_0002','user_id|i:2;username|s:6:"jelena";',  EXTRACT(EPOCH FROM now())::int - 120),
('dummy_sess_0003','user_id|i:3;username|s:6:"nikola";',  EXTRACT(EPOCH FROM now())::int - 180),
('dummy_sess_0004','user_id|i:4;username|s:3:"ana";',     EXTRACT(EPOCH FROM now())::int - 240),
('dummy_sess_0005','user_id|i:5;username|s:6:"stefan";',  EXTRACT(EPOCH FROM now())::int - 300),
('dummy_sess_0006','user_id|i:6;username|s:6:"milica";',  EXTRACT(EPOCH FROM now())::int - 360),
('dummy_sess_0007','user_id|i:7;username|s:5:"petar";',   EXTRACT(EPOCH FROM now())::int - 420),
('dummy_sess_0008','user_id|i:8;username|s:6:"jovana";',  EXTRACT(EPOCH FROM now())::int - 480),
('dummy_sess_0009','user_id|i:9;username|s:4:"luka";',    EXTRACT(EPOCH FROM now())::int - 540),
('dummy_sess_0010','user_id|i:10;username|s:7:"teodora";',EXTRACT(EPOCH FROM now())::int - 600),
('dummy_sess_0011','user_id|i:11;username|s:3:"vuk";',    EXTRACT(EPOCH FROM now())::int - 660),
('dummy_sess_0012','user_id|i:12;username|s:4:"sara";',   EXTRACT(EPOCH FROM now())::int - 720),
('dummy_sess_0013','user_id|i:13;username|s:5:"filip";',  EXTRACT(EPOCH FROM now())::int - 780),
('dummy_sess_0014','user_id|i:14;username|s:4:"nina";',   EXTRACT(EPOCH FROM now())::int - 840),
('dummy_sess_0015','user_id|i:15;username|s:5:"dusan";',  EXTRACT(EPOCH FROM now())::int - 900),
('dummy_sess_0016','user_id|i:16;username|s:3:"ema";',    EXTRACT(EPOCH FROM now())::int - 960),
('dummy_sess_0017','user_id|i:17;username|s:6:"ognjen";', EXTRACT(EPOCH FROM now())::int - 1020),
('dummy_sess_0018','user_id|i:18;username|s:4:"hana";',   EXTRACT(EPOCH FROM now())::int - 1080),
('dummy_sess_0019','user_id|i:19;username|s:5:"pavle";',  EXTRACT(EPOCH FROM now())::int - 1140),
('dummy_sess_0020','user_id|i:20;username|s:5:"ivana";',  EXTRACT(EPOCH FROM now())::int - 1200);

COMMIT;
