<?php
/**
 * User Model
 * MedFlow - Sistema Administrativo Médico
 */

class User {
    private $db;
    private $table = 'usuarios';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE email = ? AND estado = 'activo'
            LIMIT 1
        ");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     */
    public function findById($id, $anyStatus = false) {
        if ($anyStatus) {
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table}
                WHERE id = ?
                LIMIT 1
            ");
        } else {
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table}
                WHERE id = ? AND estado = 'activo'
                LIMIT 1
            ");
        }
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials($email, $password) {
        $user = $this->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    /**
     * Create new user
     */
    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} (nombre, email, password_hash, rol, estado)
                VALUES (?, ?, ?, ?, ?)
            ");

            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

            $stmt->execute([
                $data['nombre'],
                $data['email'],
                $passwordHash,
                $data['rol'] ?? 'coordinador',
                $data['estado'] ?? 'activo'
            ]);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            error_log("User Create Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update user
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $values = [];

            if (isset($data['nombre'])) {
                $fields[] = "nombre = ?";
                $values[] = $data['nombre'];
            }

            if (isset($data['email'])) {
                $fields[] = "email = ?";
                $values[] = $data['email'];
            }

            if (isset($data['rol'])) {
                $fields[] = "rol = ?";
                $values[] = $data['rol'];
            }

            if (isset($data['estado'])) {
                $fields[] = "estado = ?";
                $values[] = $data['estado'];
            }

            if (empty($fields)) {
                return false;
            }

            $values[] = $id;

            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute($values);

        } catch (PDOException $e) {
            error_log("User Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update password
     */
    public function updatePassword($id, $newPassword) {
        try {
            $stmt = $this->db->prepare("
                UPDATE {$this->table}
                SET password_hash = ?
                WHERE id = ?
            ");

            $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
            return $stmt->execute([$passwordHash, $id]);

        } catch (PDOException $e) {
            error_log("Password Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update last access
     */
    public function updateLastAccess($id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE {$this->table}
                SET ultimo_acceso = NOW()
                WHERE id = ?
            ");
            return $stmt->execute([$id]);

        } catch (PDOException $e) {
            error_log("Last Access Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all users
     */
    public function getAll($filters = []) {
        $sql = "SELECT id, nombre, email, rol, estado, fecha_creacion, ultimo_acceso
                FROM {$this->table} WHERE 1=1";

        $params = [];

        if (!empty($filters['rol'])) {
            $sql .= " AND rol = ?";
            $params[] = $filters['rol'];
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND estado = ?";
            $params[] = $filters['estado'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (nombre LIKE ? OR email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Delete user (soft delete)
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE {$this->table}
                SET estado = 'inactivo'
                WHERE id = ?
            ");
            return $stmt->execute([$id]);

        } catch (PDOException $e) {
            error_log("User Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Permanently delete user (hard delete)
     */
    public function permanentDelete($id) {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM {$this->table}
                WHERE id = ?
            ");
            return $stmt->execute([$id]);

        } catch (PDOException $e) {
            error_log("User Permanent Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get users count by role
     */
    public function countByRole() {
        $stmt = $this->db->prepare("
            SELECT rol, COUNT(*) as total
            FROM {$this->table}
            WHERE estado = 'activo'
            GROUP BY rol
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
