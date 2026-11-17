<?php

class AdministracionModelo
{
    public function __construct(private PDO $pdo) {}

    public function facturasEntre(string $desde, string $hasta): array
    {
        $consulta = $this->pdo->prepare(
            'SELECT r.id, r.fecha_salida, r.mano_obra,
                    v.matricula, c.nombre AS cliente_nombre, c.apellidos AS cliente_apellidos,
                    COALESCE(lineas.importe_piezas, 0) AS importe_piezas
            FROM reparacion AS r
            JOIN vehiculo AS v ON v.id = r.id_vehiculo
            JOIN cliente AS c ON c.id = v.id_cliente
            LEFT JOIN (
                SELECT pr.id_reparacion, SUM(pr.unidades * p.precio) AS importe_piezas
                FROM piezas_reparacion AS pr
                JOIN piezas AS p ON p.id = pr.id_pieza
                GROUP BY pr.id_reparacion
            ) AS lineas ON lineas.id_reparacion = r.id
            WHERE r.fecha_salida >= ? AND r.fecha_salida < DATE_ADD(?, INTERVAL 1 DAY)
            ORDER BY r.fecha_salida DESC, r.id DESC'
        );
        $consulta->execute([$desde . ' 00:00:00', $hasta . ' 00:00:00']);

        return $consulta->fetchAll();
    }

    public function inventario(): array
    {
        return $this->pdo->query(
            'SELECT id, referencia, nombre, precio, stock, stock_minimo
            FROM piezas
            ORDER BY nombre'
        )->fetchAll();
    }

    public function reponer(int $piezaId, int $unidades, int $stockMinimo): bool
    {
        $consulta = $this->pdo->prepare(
            'UPDATE piezas
            SET stock = stock + ?, stock_minimo = ?
            WHERE id = ?'
        );
        $consulta->execute([$unidades, $stockMinimo, $piezaId]);

        return $consulta->rowCount() > 0;
    }
}
