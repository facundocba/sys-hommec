<?php

class Professional
{
    private $db;
    private $table = 'profesionales';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los profesionales con filtros y paginación
     */
    public function getAll($filters = [], $limit = null, $offset = null)
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Filtro por búsqueda (nombre, especialidad, email)
        if (!empty($filters['search'])) {
            $query .= " AND (nombre LIKE ? OR especialidad LIKE ? OR email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtro por especialidad
        if (!empty($filters['especialidad'])) {
            $query .= " AND especialidad = ?";
            $params[] = $filters['especialidad'];
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND estado = ?";
            $params[] = $filters['estado'];
        }

        // Ordenamiento
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
     * Contar profesionales con filtros
     */
    public function count($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (nombre LIKE ? OR especialidad LIKE ? OR email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['especialidad'])) {
            $query .= " AND especialidad = ?";
            $params[] = $filters['especialidad'];
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
     * Obtener profesional por ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nuevo profesional
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (nombre, especialidad, telefono, email, estado)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['nombre'],
            $data['especialidad'],
            $data['telefono'],
            $data['email'],
            $data['estado']
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar profesional
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nombre = ?, especialidad = ?, telefono = ?, email = ?, estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['nombre'],
            $data['especialidad'],
            $data['telefono'],
            $data['email'],
            $data['estado'],
            $id
        ]);
    }

    /**
     * Eliminar (desactivar) profesional
     */
    public function delete($id)
    {
        $query = "UPDATE {$this->table} SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Verificar si el email ya existe
     */
    public function emailExists($email, $excludeId = null)
    {
        if (empty($email)) {
            return false;
        }

        $query = "SELECT id FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }

    /**
     * Obtener estadísticas del profesional
     */
    public function getStats($id)
    {
        $query = "SELECT
                    (SELECT COUNT(*) FROM pacientes WHERE id_profesional = ? AND paciente_recurrente = 0) as pacientes_finalizados,
                    (SELECT COUNT(*) FROM pacientes WHERE id_profesional = ? AND paciente_recurrente = 1) as pacientes_activos,
                    (SELECT COALESCE(SUM(valor_profesional), 0) FROM pacientes WHERE id_profesional = ?) as total_generado";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id, $id, $id]);

        return $stmt->fetch();
    }

    /**
     * Obtener todas las especialidades únicas
     */
    public function getEspecialidades()
    {
        $query = "SELECT DISTINCT especialidad FROM {$this->table}
                  WHERE especialidad IS NOT NULL AND especialidad != ''
                  ORDER BY especialidad ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
