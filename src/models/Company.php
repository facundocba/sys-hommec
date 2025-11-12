<?php

class Company
{
    private $db;
    private $table = 'empresas';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las empresas con filtros y paginación
     */
    public function getAll($filters = [], $limit = null, $offset = null)
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Filtro por búsqueda (nombre, email)
        if (!empty($filters['search'])) {
            $query .= " AND (nombre LIKE ? OR email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
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
     * Contar empresas con filtros
     */
    public function count($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (nombre LIKE ? OR email LIKE ?)";
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
     * Obtener empresa por ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nueva empresa
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (nombre, email, telefono, direccion, estado)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            $data['estado']
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar empresa
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nombre = ?, email = ?, telefono = ?, direccion = ?, estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            $data['estado'],
            $id
        ]);
    }

    /**
     * Verificar si la empresa tiene registros relacionados
     */
    public function hasRelatedRecords($id)
    {
        $relations = [];

        // Verificar pacientes
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pacientes WHERE id_empresa = ?");
        $stmt->execute([$id]);
        $pacientesCount = $stmt->fetch()['total'];
        if ($pacientesCount > 0) {
            $relations['pacientes'] = $pacientesCount;
        }

        // Verificar prestaciones_pacientes
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM prestaciones_pacientes WHERE id_empresa = ?");
        $stmt->execute([$id]);
        $prestacionesCount = $stmt->fetch()['total'];
        if ($prestacionesCount > 0) {
            $relations['prestaciones_pacientes'] = $prestacionesCount;
        }

        // Verificar prestaciones_empresas (si la tabla existe)
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM prestaciones_empresas WHERE id_empresa = ?");
            $stmt->execute([$id]);
            $prestacionesEmpresaCount = $stmt->fetch()['total'];
            if ($prestacionesEmpresaCount > 0) {
                $relations['prestaciones_empresas'] = $prestacionesEmpresaCount;
            }
        } catch (PDOException $e) {
            // La tabla no existe, ignorar
        }

        return $relations;
    }

    /**
     * Eliminar empresa (hard delete)
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Verificar si el email ya existe
     */
    public function emailExists($email, $excludeId = null)
    {
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
     * Obtener estadísticas de la empresa
     */
    public function getStats($id)
    {
        $query = "SELECT
                    (SELECT COUNT(*) FROM pacientes WHERE id_empresa = ? AND paciente_recurrente = 0) as pacientes_finalizados,
                    (SELECT COUNT(*) FROM pacientes WHERE id_empresa = ? AND paciente_recurrente = 1) as pacientes_activos,
                    (SELECT COALESCE(SUM(valor_empresa), 0) FROM pacientes WHERE id_empresa = ?) as total_facturado";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id, $id, $id]);

        return $stmt->fetch();
    }

    /**
     * Obtener empresas asociadas a un paciente (a través de sus prestaciones)
     */
    public function getByPaciente($idPaciente)
    {
        $query = "SELECT DISTINCT e.*
                  FROM {$this->table} e
                  INNER JOIN prestaciones_pacientes pp ON pp.id_empresa = e.id
                  WHERE pp.id_paciente = ? AND e.estado = 'activo'";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$idPaciente]);

        return $stmt->fetchAll();
    }
}
