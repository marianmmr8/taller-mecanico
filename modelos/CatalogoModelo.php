<?php
// Modelo: las listas para rellenar los desplegables
class CatalogoModelo
{
    public function __construct(private PDO $pdo) {}

    public function vehiculos(): array
    {
        // TODO 1: id, matricula, marca y modelo de cada vehículo, con el nombre
        //         y los apellidos de su cliente; ordenados por marca y modelo
        return $this->pdo->query('SELECT vehiculo.id, vehiculo.marca, vehiculo.matricula,vehiculo.modelo,cliente.nombre,cliente.apellidos
FROM vehiculo
JOIN cliente ON cliente.id = vehiculo.id_cliente
ORDER BY vehiculo.marca, vehiculo.modelo')
->fetchAll();
    }

    public function mecanicos(): array
    {
        // TODO 2: id, nombre y especialidad de cada mecánico, ordenados por nombre
         return $this->pdo->query('SELECT id, nombre, especialidad
FROM mecanicos
ORDER BY nombre')->fetchAll();

    }

    public function piezas(): array
    {
        return $this->pdo->query('SELECT id, referencia, nombre, precio, stock, stock_minimo
FROM piezas
ORDER BY nombre')->fetchAll();
    }
}