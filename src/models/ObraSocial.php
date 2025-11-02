<?php

class ObraSocial
{
    private $db;
    private $table = 'obras_sociales';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las obras sociales con filtros y paginación
     */
    public function getAll($filters = [], $limit = null, $offset = null)
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Filtro por búsqueda
        if (!empty($filters['search'])) {
            $query .= " AND (nombre LIKE ? OR sigla LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND estado = ?";
            $params[] = $filters['estado'];
        }

        $query .= " ORDER BY nombre ASC";

        // Paginación
        if ($limit !== null) {
            $query .= " LIMIT ?";
            $params[] = (int)$limit;

            if ($offset !== null) {
                $query .= " OFFSET ?";
                $params[] = (int)$offset;
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Contar obras sociales con filtros
     */
    public function count($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (nombre LIKE ? OR sigla LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['estado'])) {
            $query .= " AND estado = ?";
            $params[] = $filters['estado'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch();

        return (int)$row['total'];
    }

    /**
     * Obtener obra social por ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nueva obra social
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (nombre, sigla, telefono, email, direccion, observaciones, estado)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['nombre'],
            $data['sigla'],
            $data['telefono'],
            $data['email'],
            $data['direccion'],
            $data['observaciones'],
            $data['estado']
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar obra social
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nombre = ?, sigla = ?, telefono = ?, email = ?,
                      direccion = ?, observaciones = ?, estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['nombre'],
            $data['sigla'],
            $data['telefono'],
            $data['email'],
            $data['direccion'],
            $data['observaciones'],
            $data['estado'],
            $id
        ]);
    }

    /**
     * Eliminar obra social (cambiar estado)
     */
    public function delete($id)
    {
        $query = "UPDATE {$this->table} SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Verificar si el nombre ya existe
     */
    public function nameExists($nombre, $excludeId = null)
    {
        $query = "SELECT id FROM {$this->table} WHERE nombre = ?";
        $params = [$nombre];

        if ($excludeId !== null) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }

    /**
     * Obtener estadísticas de uso
     */
    public function getStats($id)
    {
        $query = "SELECT
                    COUNT(p.id) as total_pacientes,
                    COUNT(CASE WHEN p.estado = 'activo' THEN 1 END) as pacientes_activos
                  FROM pacientes p
                  WHERE p.id_obra_social = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }
}
