<?php
// GestorReservas.php

class GestorReservas {
    private $pdo;

    public function __construct($dbConnection) {
        $this->pdo = $dbConnection;
    }

    public function procesar($fecha, $hora, $personas, $ubicacion) {

        $this->validarAnticipacion($fecha, $hora);

        $this->validarHorarioRestaurante($fecha, $hora);

        $mesasLibres = $this->buscarMesasDisponibles($fecha, $hora, $ubicacion);

        $mesasAAsignar = $this->seleccionarMesasParaReserva($mesasLibres, $personas);

        if (empty($mesasAAsignar)) {
            throw new Exception("No hay disponibilidad suficiente en la ubicación $ubicacion para $personas personas.");
        }

        return $this->guardarReserva($fecha, $hora, $personas, $mesasAAsignar);
    }

    private function validarAnticipacion($fecha, $hora) {
        $ahora = new DateTime();
        $reserva = new DateTime("$fecha $hora");
        $diff = $ahora->diff($reserva);
        $minutos = ($diff->days * 1440) + ($diff->h * 60) + $diff->i;

        if ($reserva < $ahora || ($diff->invert == 0 && $minutos < 15)) {
            throw new Exception("La reserva debe realizarse con al menos 15 minutos de antelación.");
        }
    }

    private function validarHorarioRestaurante($fecha, $hora) {
        $dt = new DateTime("$fecha $hora");
        $diaSemana = $dt->format('N');
        $h = (int)$dt->format('H');

        if ($diaSemana >= 1 && $diaSemana <= 5) {
            if ($h < 10 || $h >= 24) throw new Exception("Cerrado. Horario L-V: 10:00 a 00:00.");
        } 
        elseif ($diaSemana == 6) {
            if (!($h >= 22 || $h < 2)) throw new Exception("Cerrado. Horario Sábados: 22:00 a 02:00.");
        } 
        elseif ($diaSemana == 7) {
            if ($h < 12 || $h >= 16) throw new Exception("Cerrado. Horario Domingos: 12:00 a 16:00.");
        }
    }

    private function buscarMesasDisponibles($fecha, $hora, $ubicacion) {
        $sql = "SELECT id, capacidad FROM mesas 
                WHERE ubicacion = ? 
                AND id NOT IN (
                    SELECT rm.mesa_id FROM reserva_mesa rm
                    JOIN reservas r ON r.id = rm.reserva_id
                    WHERE r.fecha = ? 
                    AND r.hora BETWEEN SUBTIME(?, '01:59:00') AND ADDTIME(?, '01:59:00')
                )
                ORDER BY capacidad DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ubicacion, $fecha, $hora, $hora]);
        return $stmt->fetchAll();
    }

    private function seleccionarMesasParaReserva($mesas, $personas) {
        $seleccionadas = [];
        $acumulado = 0;
        foreach ($mesas as $mesa) {
            $seleccionadas[] = $mesa['id'];
            $acumulado += $mesa['capacidad'];
            if ($acumulado >= $personas) return $seleccionadas;
            if (count($seleccionadas) >= 3) break; 
        }
        return []; 
    }

    private function guardarReserva($fecha, $hora, $personas, $mesasIds) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO reservas (fecha, hora, cantidad_personas) VALUES (?, ?, ?)");
            $stmt->execute([$fecha, $hora, $personas]);
            $reservaId = $this->pdo->lastInsertId();

            $stmtMesa = $this->pdo->prepare("INSERT INTO reserva_mesa (reserva_id, mesa_id) VALUES (?, ?)");
            foreach ($mesasIds as $id) {
                $stmtMesa->execute([$reservaId, $id]);
            }

            $this->pdo->commit();
            return $reservaId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}