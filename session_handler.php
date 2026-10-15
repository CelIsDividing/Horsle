<?php

// DB-based session handler (PDO / PostgreSQL).
// Sesije se cuvaju u tabeli `sessions`, pa ostaju sacuvane
// i nakon restarta/redeploy-a (ephemeral filesystem na Render-u).

class PdoSessionHandler implements SessionHandlerInterface
{
    private PDO $conn;
    private int $maxLifetime;

    public function __construct(PDO $conn, int $maxLifetime = 1440)
    {
        $this->conn = $conn;
        $this->maxLifetime = $maxLifetime;
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $stmt = $this->conn->prepare("SELECT data FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? (string)$row['data'] : '';
    }

    public function write(string $id, string $data): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO sessions (id, data, last_access) VALUES (?, ?, ?)
             ON CONFLICT (id) DO UPDATE SET data = EXCLUDED.data, last_access = EXCLUDED.last_access"
        );

        return $stmt->execute([$id, $data, time()]);
    }

    public function destroy(string $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM sessions WHERE id = ?");

        return $stmt->execute([$id]);
    }

    public function gc(int $max_lifetime): int|false
    {
        $stmt = $this->conn->prepare("DELETE FROM sessions WHERE last_access < ?");
        $stmt->execute([time() - $max_lifetime]);

        return $stmt->rowCount();
    }
}
