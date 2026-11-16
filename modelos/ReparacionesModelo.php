<?php
// Modelo: todo el SQL de las reparaciones está aquí, y solo aquí
class ReparacionModelo
{
    public function __construct(private PDO $pdo) {}

    // Todas las reparaciones, de la más reciente a la más antigua
    public function todas(): array
    {
        // TODO 1: devuelve todas las filas de una consulta que una reparaciones,
        //         vehiculos y mecanicos, con estas columnas: id, fecha_entrada,
        //         fecha_salida, descripcion, matricula, marca, modelo //         y el nombre del mecánico con el alias «mecanico»


        $sql ="select reparacion.id, reparacion.fecha_entrega , reparacion.fecha_salida,reparacion.descripcion, 
        vehiculo.matricula, vehiculo.marca, vehiculo.modelo, mecanicos.nombre as mecanico
        FROM reparacion, vehiculo, mecanicos 
        WHERE reparacion.id_vehiculo = vehiculo.id 
        AND reparacion.id_mecanico = mecanicos.id 
        ORDER BY reparacion.fecha_entrega DESC";

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
        'SELECT reparacion.id, reparacion.fecha_entrega, reparacion.fecha_salida, reparacion.descripcion, reparacion.mano_obra, 
        vehiculo.matricula, vehiculo.modelo, vehiculo.marca, cliente.nombre as cliente_nombre, cliente.apellidos as cliente_apellido, 
        cliente.telefono, mecanicos.nombre as mecanico FROM reparacion JOIN vehiculo 
        ON vehiculo.id = reparacion.id_vehiculo 
        JOIN cliente ON cliente.id = vehiculo.id_cliente 
        JOIN mecanicos 
        ON mecanicos.id = reparacion.id_mecanico 
        WHERE reparacion.id = ?;'
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
        'SELECT piezas.referencia, piezas.nombre, piezas_reparacion.unidades, piezas.precio,piezas_reparacion.unidades * piezas.precio as importe
FROM piezas_reparacion
JOIN piezas ON piezas.id = piezas_reparacion.id_reparacion
WHERE piezas_reparacion.id_reparacion = ?
ORDER BY piezas.nombre;'
);

$consulta->execute([$id]);
return $consulta->fetchAll();
    }



}