<?php
// Devuelve la conexión con la base de datos del taller
function conectar(): PDO
{
    try {
        return new PDO(
            'mysql:host=localhost;dbname=taller;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        exit('No se ha podido conectar con la base de datos.');
    }
}