<?php

require_once __DIR__ . '/../../config/database.php';

class Frequency
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las frecuencias activas
     */
    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM frecuencias
            WHERE estado = 'activo'
            ORDER BY orden ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Obtener frecuencia por ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM frecuencias
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Obtener sesiones por mes de una frecuencia
     */
    public function getSessionsPerMonth($frecuenciaId, $sessionesPersonalizadas = null)
    {
        if (empty($frecuenciaId)) {
            return 4; // Default
        }

        $frecuencia = $this->getById($frecuenciaId);

        if (!$frecuencia) {
            return 4; // Default
        }

        // Si es frecuencia personalizada (id = 9), usar sesiones_personalizadas
        if ($frecuencia['id'] == 9 && $sessionesPersonalizadas !== null) {
            return intval($sessionesPersonalizadas);
        }

        return intval($frecuencia['sesiones_por_mes']);
    }

    /**
     * Crear nueva frecuencia
     */
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO frecuencias (nombre, sesiones_por_mes, descripcion, orden, estado)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['nombre'],
            $data['sesiones_por_mes'],
            $data['descripcion'] ?? null,
            $data['orden'] ?? 0,
            $data['estado'] ?? 'activo'
        ]);
    }

    /**
     * Actualizar frecuencia
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE frecuencias
            SET nombre = ?,
                sesiones_por_mes = ?,
                descripcion = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['nombre'],
            $data['sesiones_por_mes'],
            $data['descripcion'] ?? null,
            $data['orden'] ?? 0,
            $data['estado'] ?? 'activo',
            $id
        ]);
    }

    /**
     * Eliminar (desactivar) frecuencia
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE frecuencias
            SET estado = 'inactivo'
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
