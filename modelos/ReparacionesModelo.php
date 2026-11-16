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



}