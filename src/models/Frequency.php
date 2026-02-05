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
     * Obtener horas por mes a partir de horas semanales
     * Usa 4.33 semanas promedio por mes
     */
    public function getHoursPerMonth($horasSemana)
    {
        if (empty($horasSemana) || !is_numeric($horasSemana)) {
            return 0;
        }

        return round($horasSemana * 4.33, 2);
    }

    /**
     * Formatear frecuencia para mostrar según el modo
     * @param array $prestacion - datos de la prestación
     * @return string - texto formateado de la frecuencia
     */
    public function formatFrecuencia($prestacion)
    {
        $modo = $prestacion['modo_frecuencia'] ?? 'sesiones';

        if ($modo === 'horas') {
            $horas = $prestacion['horas_semana'] ?? 0;
            $diasSemana = $prestacion['dias_semana'] ?? null;

            $texto = $horas . ' hs/semana';

            // Si hay distribución por días, mostrar cuáles
            if ($diasSemana) {
                $dias = is_string($diasSemana) ? json_decode($diasSemana, true) : $diasSemana;
                if (is_array($dias)) {
                    $diasActivos = [];
                    $nombresDias = ['lun' => 'Lun', 'mar' => 'Mar', 'mie' => 'Mié', 'jue' => 'Jue', 'vie' => 'Vie', 'sab' => 'Sáb', 'dom' => 'Dom'];
                    foreach ($dias as $dia => $horasDia) {
                        if ($horasDia > 0) {
                            $diasActivos[] = $nombresDias[$dia] ?? $dia;
                        }
                    }
                    if (!empty($diasActivos)) {
                        $texto .= ' (' . implode(', ', $diasActivos) . ')';
                    }
                }
            }

            return $texto;
        } else {
            // Modo sesiones
            if (!empty($prestacion['frecuencia_nombre'])) {
                $texto = $prestacion['frecuencia_nombre'];
                if ($prestacion['id_frecuencia'] == 9 && !empty($prestacion['sesiones_personalizadas'])) {
                    $texto .= ' (' . $prestacion['sesiones_personalizadas'] . ' sesiones/mes)';
                } elseif (!empty($prestacion['frecuencia_sesiones'])) {
                    $texto .= ' (' . $prestacion['frecuencia_sesiones'] . ' sesiones/mes)';
                }
                return $texto;
            }
            return $prestacion['frecuencia_servicio'] ?? 'No especificada';
        }
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
