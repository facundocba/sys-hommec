<?php

class Patient
{
    private $db;
    private $table = 'pacientes';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los pacientes con filtros
     */
    public function getAll($filters = [], $limit = null, $offset = null)
    {
        $query = "SELECT
                    p.*,
                    COALESCE(prof_pp.nombre, prof.nombre) as profesional_nombre,
                    COALESCE(prof_pp.especialidad, prof.especialidad) as profesional_especialidad,
                    COALESCE(emp_pp.nombre, emp.nombre) as empresa_nombre,
                    COALESCE(tp.nombre, prest.nombre) as prestacion_nombre,
                    os.nombre as obra_social_nombre,
                    os.sigla as obra_social_sigla,
                    prov.nombre as provincia_nombre
                  FROM {$this->table} p
                  LEFT JOIN profesionales prof ON p.id_profesional = prof.id
                  LEFT JOIN empresas emp ON p.id_empresa = emp.id
                  LEFT JOIN prestaciones prest ON p.id_prestacion = prest.id
                  LEFT JOIN obras_sociales os ON p.id_obra_social = os.id
                  LEFT JOIN provincias prov ON p.id_provincia = prov.id
                  LEFT JOIN (
                      SELECT pp.id_paciente,
                             pp.id_profesional,
                             pp.id_empresa,
                             pp.id_tipo_prestacion,
                             ROW_NUMBER() OVER (PARTITION BY pp.id_paciente ORDER BY pp.fecha_inicio DESC) as rn
                      FROM prestaciones_pacientes pp
                      WHERE pp.estado = 'activo'
                  ) pp_active ON pp_active.id_paciente = p.id AND pp_active.rn = 1
                  LEFT JOIN profesionales prof_pp ON pp_active.id_profesional = prof_pp.id
                  LEFT JOIN empresas emp_pp ON pp_active.id_empresa = emp_pp.id
                  LEFT JOIN tipos_prestacion tp ON pp_active.id_tipo_prestacion = tp.id
                  WHERE 1=1";
        $params = [];

        // Filtro por búsqueda (nombre, DNI)
        if (!empty($filters['search'])) {
            $query .= " AND (p.nombre_completo LIKE ? OR p.dni LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtro por profesional
        if (!empty($filters['profesional'])) {
            $query .= " AND (p.id_profesional = ? OR pp_active.id_profesional = ?)";
            $params[] = $filters['profesional'];
            $params[] = $filters['profesional'];
        }

        // Filtro por empresa
        if (!empty($filters['empresa'])) {
            $query .= " AND (p.id_empresa = ? OR pp_active.id_empresa = ?)";
            $params[] = $filters['empresa'];
            $params[] = $filters['empresa'];
        }

        // Filtro por prestación
        if (!empty($filters['prestacion'])) {
            $query .= " AND (p.id_prestacion = ? OR pp_active.id_tipo_prestacion = ?)";
            $params[] = $filters['prestacion'];
            $params[] = $filters['prestacion'];
        }

        // Filtro por obra social
        if (!empty($filters['obra_social'])) {
            $query .= " AND p.id_obra_social = ?";
            $params[] = $filters['obra_social'];
        }

        // Filtro por provincia
        if (!empty($filters['provincia'])) {
            $query .= " AND p.id_provincia = ?";
            $params[] = $filters['provincia'];
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND p.estado = ?";
            $params[] = $filters['estado'];
        }

        // Filtro por paciente recurrente
        if (isset($filters['recurrente']) && $filters['recurrente'] !== '') {
            $query .= " AND p.paciente_recurrente = ?";
            $params[] = (int)$filters['recurrente'];
        }

        // Ordenamiento por fecha de finalización (próximos a vencer primero)
        $query .= " ORDER BY
                    CASE
                        WHEN p.paciente_recurrente = 1 THEN 0
                        ELSE 1
                    END,
                    p.fecha_finalizacion ASC,
                    p.fecha_ingreso DESC";

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
     * Contar pacientes con filtros
     */
    public function count($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} p WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (p.nombre_completo LIKE ? OR p.dni LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['profesional'])) {
            $query .= " AND p.id_profesional = ?";
            $params[] = $filters['profesional'];
        }

        if (!empty($filters['empresa'])) {
            $query .= " AND p.id_empresa = ?";
            $params[] = $filters['empresa'];
        }

        if (!empty($filters['prestacion'])) {
            $query .= " AND p.id_prestacion = ?";
            $params[] = $filters['prestacion'];
        }

        if (!empty($filters['obra_social'])) {
            $query .= " AND p.id_obra_social = ?";
            $params[] = $filters['obra_social'];
        }

        if (!empty($filters['estado'])) {
            $query .= " AND p.estado = ?";
            $params[] = $filters['estado'];
        }

        if (isset($filters['recurrente']) && $filters['recurrente'] !== '') {
            $query .= " AND p.paciente_recurrente = ?";
            $params[] = (int)$filters['recurrente'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch();

        return (int)$row['total'];
    }

    /**
     * Obtener paciente por ID con relaciones
     */
    public function getById($id)
    {
        $query = "SELECT
                    p.*,
                    COALESCE(prof_pp.nombre, prof.nombre) as profesional_nombre,
                    COALESCE(prof_pp.especialidad, prof.especialidad) as profesional_especialidad,
                    COALESCE(emp_pp.nombre, emp.nombre) as empresa_nombre,
                    COALESCE(tp.nombre, prest.nombre) as prestacion_nombre,
                    os.nombre as obra_social_nombre,
                    os.sigla as obra_social_sigla,
                    prov.nombre as provincia_nombre
                  FROM {$this->table} p
                  LEFT JOIN profesionales prof ON p.id_profesional = prof.id
                  LEFT JOIN empresas emp ON p.id_empresa = emp.id
                  LEFT JOIN prestaciones prest ON p.id_prestacion = prest.id
                  LEFT JOIN obras_sociales os ON p.id_obra_social = os.id
                  LEFT JOIN provincias prov ON p.id_provincia = prov.id
                  LEFT JOIN (
                      SELECT pp.id_paciente,
                             pp.id_profesional,
                             pp.id_empresa,
                             pp.id_tipo_prestacion,
                             ROW_NUMBER() OVER (PARTITION BY pp.id_paciente ORDER BY pp.fecha_inicio DESC) as rn
                      FROM prestaciones_pacientes pp
                      WHERE pp.estado = 'activo'
                  ) pp_active ON pp_active.id_paciente = p.id AND pp_active.rn = 1
                  LEFT JOIN profesionales prof_pp ON pp_active.id_profesional = prof_pp.id
                  LEFT JOIN empresas emp_pp ON pp_active.id_empresa = emp_pp.id
                  LEFT JOIN tipos_prestacion tp ON pp_active.id_tipo_prestacion = tp.id
                  WHERE p.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nuevo paciente
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (nombre_completo, dni, id_provincia, localidad, id_obra_social, frecuencia_servicio,
                   id_profesional, id_empresa, id_prestacion, fecha_ingreso, fecha_finalizacion,
                   paciente_recurrente, observaciones, valor_profesional, valor_empresa, estado)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['nombre_completo'],
            $data['dni'],
            $data['id_provincia'] ?? null,
            $data['localidad'],
            $data['id_obra_social'],
            $data['frecuencia_servicio'],
            $data['id_profesional'],
            $data['id_empresa'],
            $data['id_prestacion'],
            $data['fecha_ingreso'],
            $data['fecha_finalizacion'],
            $data['paciente_recurrente'],
            $data['observaciones'],
            $data['valor_profesional'],
            $data['valor_empresa'],
            $data['estado']
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar paciente
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nombre_completo = ?, dni = ?, id_provincia = ?, localidad = ?, id_obra_social = ?,
                      frecuencia_servicio = ?, id_profesional = ?, id_empresa = ?,
                      id_prestacion = ?, fecha_ingreso = ?, fecha_finalizacion = ?,
                      paciente_recurrente = ?, observaciones = ?, valor_profesional = ?,
                      valor_empresa = ?, estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['nombre_completo'],
            $data['dni'],
            $data['id_provincia'] ?? null,
            $data['localidad'],
            $data['id_obra_social'],
            $data['frecuencia_servicio'],
            $data['id_profesional'],
            $data['id_empresa'],
            $data['id_prestacion'],
            $data['fecha_ingreso'],
            $data['fecha_finalizacion'],
            $data['paciente_recurrente'],
            $data['observaciones'],
            $data['valor_profesional'],
            $data['valor_empresa'],
            $data['estado'],
            $id
        ]);
    }

    /**
     * Finalizar paciente (cambiar estado a finalizado) - Soft Delete
     */
    public function finalize($id)
    {
        $query = "UPDATE {$this->table} SET estado = 'finalizado' WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Eliminar paciente (cambiar estado a finalizado) - Mantener compatibilidad
     * @deprecated Use finalize() instead
     */
    public function delete($id)
    {
        return $this->finalize($id);
    }

    /**
     * Eliminar paciente permanentemente - Hard Delete
     * ADVERTENCIA: Esta acción es irreversible
     */
    public function deletePermanently($id)
    {
        try {
            // Iniciar transacción
            $this->db->beginTransaction();

            // Eliminar archivos asociados
            $queryFiles = "DELETE FROM archivos_paciente WHERE id_paciente = ?";
            $stmtFiles = $this->db->prepare($queryFiles);
            $stmtFiles->execute([$id]);

            // Eliminar prestaciones asociadas
            $queryPrestaciones = "DELETE FROM prestaciones_pacientes WHERE id_paciente = ?";
            $stmtPrestaciones = $this->db->prepare($queryPrestaciones);
            $stmtPrestaciones->execute([$id]);

            // Eliminar notificaciones asociadas
            $queryNotifications = "DELETE FROM notificaciones WHERE id_paciente = ?";
            $stmtNotifications = $this->db->prepare($queryNotifications);
            $stmtNotifications->execute([$id]);

            // Finalmente eliminar el paciente
            $queryPatient = "DELETE FROM {$this->table} WHERE id = ?";
            $stmtPatient = $this->db->prepare($queryPatient);
            $stmtPatient->execute([$id]);

            // Commit de la transacción
            $this->db->commit();

            return true;
        } catch (Exception $e) {
            // Rollback en caso de error
            $this->db->rollBack();
            error_log("Error al eliminar paciente permanentemente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener pacientes próximos a vencer (alerta)
     */
    public function getExpiringPatients($days = 7)
    {
        $query = "SELECT
                    p.*,
                    prof.nombre as profesional_nombre,
                    emp.nombre as empresa_nombre,
                    prest.nombre as prestacion_nombre,
                    DATEDIFF(p.fecha_finalizacion, CURDATE()) as dias_restantes
                  FROM {$this->table} p
                  LEFT JOIN profesionales prof ON p.id_profesional = prof.id
                  LEFT JOIN empresas emp ON p.id_empresa = emp.id
                  LEFT JOIN prestaciones prest ON p.id_prestacion = prest.id
                  WHERE p.paciente_recurrente = 0
                    AND p.estado = 'activo'
                    AND p.fecha_finalizacion IS NOT NULL
                    AND DATEDIFF(p.fecha_finalizacion, CURDATE()) <= ?
                    AND DATEDIFF(p.fecha_finalizacion, CURDATE()) >= 0
                  ORDER BY p.fecha_finalizacion ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$days]);

        return $stmt->fetchAll();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getStats()
    {
        $query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN paciente_recurrente = 1 THEN 1 ELSE 0 END) as recurrentes,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN estado = 'finalizado' THEN 1 ELSE 0 END) as finalizados,
                    COALESCE(SUM(valor_profesional), 0) as total_profesionales,
                    COALESCE(SUM(valor_empresa), 0) as total_empresas
                  FROM {$this->table}";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch();
    }
}
