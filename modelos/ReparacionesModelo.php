<?php
// Modelo: todo el SQL de las reparaciones está aquí, y solo aquí
class ReparacionModelo
{
    public function __construct(private PDO $pdo) {}

    // Todas las reparaciones, de la más reciente a la más antigua
    public function todas(string $estado = 'abiertas'): array
    {
        // TODO 1: devuelve todas las filas de una consulta que una reparaciones,
        //         vehiculos y mecanicos, con estas columnas: id, fecha_entrada,
        //         fecha_salida, descripcion, matricula, marca, modelo //         y el nombre del mecánico con el alias «mecanico»


        $sql = 'SELECT reparacion.id, reparacion.fecha_entrega, reparacion.fecha_salida,
        reparacion.descripcion, vehiculo.matricula, vehiculo.marca,
        vehiculo.modelo, mecanicos.nombre AS mecanico
        FROM reparacion
        JOIN vehiculo ON reparacion.id_vehiculo = vehiculo.id
        JOIN mecanicos ON reparacion.id_mecanico = mecanicos.id';

        if ($estado === 'abiertas') {
            $sql .= ' WHERE reparacion.fecha_salida IS NULL';
        } elseif ($estado === 'finalizadas') {
            $sql .= ' WHERE reparacion.fecha_salida IS NOT NULL';
        } elseif ($estado !== 'todas') {
            throw new InvalidArgumentException('Estado de reparación no válido.');
        }

        $sql .= ' ORDER BY reparacion.fecha_entrega DESC';

$consulta = $this->pdo->query($sql);
return $consulta->fetchAll();
    }
        // Una reparación con su vehículo, su cliente y su mecánico; null si no existe
    public function buscar(int $id): ?array
    {
        // TODO 1: consulta preparada que una reparaciones, vehiculos, clientes y mecanicos.
        //         Columnas: id, fecha_entrada, fecha_salida, descripcion, mano_obra,
        //         matricula, marca, modelo, telefono y, con alias,
        //         cliente_nombre, cliente_apellidos y mecanico.
        //         Devuelve la fila, o null si no hay ninguna

        $consulta = $this->pdo->prepare(
        'SELECT reparacion.id, reparacion.fecha_entrega, reparacion.fecha_salida,
        reparacion.descripcion, reparacion.mano_obra, reparacion.horas_mano_obra,
        vehiculo.matricula, vehiculo.modelo,
        vehiculo.marca, cliente.nombre AS cliente_nombre,
        cliente.apellidos AS cliente_apellido, cliente.telefono,
        mecanicos.nombre AS mecanico
        FROM reparacion
        JOIN vehiculo ON vehiculo.id = reparacion.id_vehiculo
        JOIN cliente ON cliente.id = vehiculo.id_cliente
        JOIN mecanicos ON mecanicos.id = reparacion.id_mecanico
        WHERE reparacion.id = ?'
        );
        $consulta->execute([$id]);
        $fila = $consulta->fetch();
        return $fila ?: null;

    }


    // Las piezas usadas en una reparación, con el importe de cada línea
    public function piezas(int $id): array
    {
        // TODO 2: consulta preparada que una reparacion_piezas y piezas.
        //         Columnas: referencia, nombre, cantidad, precio
        //         e importe (cantidad por precio), ordenadas por nombre

        $consulta = $this->pdo->prepare(
        'SELECT piezas.referencia, piezas.nombre, pr.unidades, piezas.precio,
        pr.unidades * piezas.precio AS importe
        FROM piezas_reparacion AS pr
        JOIN piezas ON piezas.id = pr.id_pieza
        WHERE pr.id_reparacion = ?
        ORDER BY piezas.nombre'
);

$consulta->execute([$id]);
return $consulta->fetchAll();
    }

    public function anadirPieza(int $reparacionId, int $piezaId, int $unidades): void
    {
        $actualizar = $this->pdo->prepare(
            'UPDATE piezas_reparacion
            SET unidades = unidades + ?
            WHERE id_reparacion = ? AND id_pieza = ?'
        );
        $actualizar->execute([$unidades, $reparacionId, $piezaId]);

        if ($actualizar->rowCount() === 0) {
            $insertar = $this->pdo->prepare(
                'INSERT INTO piezas_reparacion (id_reparacion, id_pieza, unidades)
                VALUES (?, ?, ?)'
            );
            $insertar->execute([$reparacionId, $piezaId, $unidades]);
        }
    }

    public function cerrar(int $id): bool
    {
        $consulta = $this->pdo->prepare(
            'UPDATE reparacion
            SET fecha_salida = CURDATE()
            WHERE id = ? AND fecha_salida IS NULL'
        );
        $consulta->execute([$id]);

        return $consulta->rowCount() > 0;
    }

    public function anadirManoObra(int $id, float $horas, float $tarifaHora): bool
    {
        $consulta = $this->pdo->prepare(
            'UPDATE reparacion
            SET horas_mano_obra = horas_mano_obra + ?,
                mano_obra = mano_obra + (? * ?)
            WHERE id = ? AND fecha_salida IS NULL'
        );
        $consulta->execute([$horas, $horas, $tarifaHora, $id]);

        return $consulta->rowCount() > 0;
    }

    // Abre una reparación con la fecha de hoy y devuelve su id
    public function crear(int $vehiculoId, int $mecanicoId, string $descripcion, float $manoObra): int
    {
        // TODO 3: INSERT preparado; la fecha de entrada la pone MySQL con CURDATE().
        //         Devuelve el id nuevo con lastInsertId(), convertido a entero

        $insertar = $this->pdo->prepare('INSERT INTO reparacion
        (id_vehiculo, id_mecanico, descripcion, mano_obra)
VALUES (?,?,?,?)');
        
        $insertar->execute([$vehiculoId, $mecanicoId, $descripcion, $manoObra]);

        return(int) $this->pdo->lastInsertId();
    }


}