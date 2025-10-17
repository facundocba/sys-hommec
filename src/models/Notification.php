<?php

class Notification
{
    private $db;
    private $table = 'notificaciones';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las notificaciones de un usuario
     */
    public function getByUser($idUsuario, $filters = [])
    {
        $query = "SELECT
                    n.*,
                    p.nombre_completo as paciente_nombre
                  FROM {$this->table} n
                  LEFT JOIN pacientes p ON n.id_paciente = p.id
                  WHERE (n.id_usuario_destino = ? OR n.id_usuario_destino IS NULL)";

        $params = [$idUsuario];

        // Filtro por leída/no leída
        if (isset($filters['leida']) && $filters['leida'] !== '') {
            $query .= " AND n.leida = ?";
            $params[] = (int)$filters['leida'];
        }

        // Filtro por tipo
        if (!empty($filters['tipo'])) {
            $query .= " AND n.tipo = ?";
            $params[] = $filters['tipo'];
        }

        $query .= " ORDER BY n.fecha_creacion DESC";

        // Límite
        if (!empty($filters['limit'])) {
            $query .= " LIMIT ?";
            $params[] = (int)$filters['limit'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener notificación por ID
     */
    public function getById($id)
    {
        $query = "SELECT
                    n.*,
                    p.nombre_completo as paciente_nombre
                  FROM {$this->table} n
                  LEFT JOIN pacientes p ON n.id_paciente = p.id
                  WHERE n.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nueva notificación
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (tipo, titulo, mensaje, id_usuario_destino, id_paciente, id_archivo)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['tipo'],
            $data['titulo'],
            $data['mensaje'],
            $data['id_usuario_destino'] ?? null,
            $data['id_paciente'] ?? null,
            $data['id_archivo'] ?? null
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Marcar como leída
     */
    public function markAsRead($id)
    {
        $query = "UPDATE {$this->table}
                  SET leida = 1, fecha_leida = NOW()
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Marcar todas como leídas para un usuario
     */
    public function markAllAsRead($idUsuario)
    {
        $query = "UPDATE {$this->table}
                  SET leida = 1, fecha_leida = NOW()
                  WHERE (id_usuario_destino = ? OR id_usuario_destino IS NULL)
                    AND leida = 0";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$idUsuario]);
    }

    /**
     * Eliminar notificación
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Contar notificaciones no leídas de un usuario
     */
    public function countUnread($idUsuario)
    {
        $query = "SELECT COUNT(*) as total
                  FROM {$this->table}
                  WHERE (id_usuario_destino = ? OR id_usuario_destino IS NULL)
                    AND leida = 0";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$idUsuario]);
        $row = $stmt->fetch();

        return (int)$row['total'];
    }

    /**
     * Obtener notificaciones recientes (últimas 5)
     */
    public function getRecent($idUsuario, $limit = 5)
    {
        return $this->getByUser($idUsuario, ['limit' => $limit]);
    }

    /**
     * Crear notificación de archivo subido
     */
    public function createFileUploadNotification($idPaciente, $idArchivo, $nombreArchivo, $idUsuarioSubio)
    {
        return $this->create([
            'tipo' => 'archivo_subido',
            'titulo' => 'Nuevo archivo subido',
            'mensaje' => "Se ha subido un nuevo archivo: {$nombreArchivo}",
            'id_usuario_destino' => null, // Todos los usuarios
            'id_paciente' => $idPaciente,
            'id_archivo' => $idArchivo
        ]);
    }

    /**
     * Crear notificación de prestación próxima a vencer
     */
    public function createExpiringServiceNotification($idPaciente, $idPrestacion, $nombrePaciente, $diasRestantes)
    {
        return $this->create([
            'tipo' => 'paciente_vencimiento',
            'titulo' => 'Prestación próxima a vencer',
            'mensaje' => "La prestación del paciente {$nombrePaciente} vence en {$diasRestantes} días",
            'id_usuario_destino' => null, // Todos los usuarios
            'id_paciente' => $idPaciente,
            'id_archivo' => null
        ]);
    }

    /**
     * Crear notificación de sistema
     */
    public function createSystemNotification($titulo, $mensaje, $idUsuarioDestino = null)
    {
        return $this->create([
            'tipo' => 'sistema',
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'id_usuario_destino' => $idUsuarioDestino,
            'id_paciente' => null,
            'id_archivo' => null
        ]);
    }
}
