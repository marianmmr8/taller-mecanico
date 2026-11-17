<?php

class AdministracionControlador
{
    public function __construct(private AdministracionModelo $administracion) {}

    public function inicio(): void
    {
        $this->mostrar('administracion', [
            'titulo' => 'Administración',
        ]);
    }

    public function ventas(): void
    {
        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');
        $error = '';

        if (!$this->fechaValida($desde) || !$this->fechaValida($hasta)) {
            $desde = date('Y-m-01');
            $hasta = date('Y-m-d');
            $error = 'Selecciona un intervalo de fechas válido.';
        } elseif ($desde > $hasta) {
            $desde = date('Y-m-01');
            $hasta = date('Y-m-d');
            $error = 'La fecha inicial no puede ser posterior a la fecha final.';
        }

        $facturas = [];
        $baseTotal = 0.0;
        $ivaTotal = 0.0;
        $total = 0.0;
        if ($error === '') {
            foreach ($this->administracion->facturasEntre($desde, $hasta) as $factura) {
                $base = round((float) $factura['mano_obra'] + (float) $factura['importe_piezas'], 2);
                $iva = round($base * 0.21, 2);
                $factura['numero'] = 'NX-' . date('Y', strtotime($factura['fecha_salida']))
                    . '-' . str_pad((string) $factura['id'], 6, '0', STR_PAD_LEFT);
                $factura['base'] = $base;
                $factura['iva'] = $iva;
                $factura['total'] = $base + $iva;
                $facturas[] = $factura;
                $baseTotal += $base;
                $ivaTotal += $iva;
                $total += $factura['total'];
            }
        }

        $this->mostrar('administracion-ventas', [
            'titulo' => 'Ventas y facturas',
            'desde' => $desde,
            'hasta' => $hasta,
            'facturas' => $facturas,
            'baseTotal' => $baseTotal,
            'ivaTotal' => $ivaTotal,
            'total' => $total,
            'error' => $error,
        ]);
    }

    public function inventario(): void
    {
        $avisos = [
            'repuesto' => 'Reposición registrada.',
        ];
        $errores = [
            'metodo' => 'La reposición debe enviarse mediante POST.',
            'pieza' => 'Selecciona una pieza válida.',
            'unidades' => 'La reposición debe ser de una o más unidades.',
            'minimo' => 'El stock mínimo no puede ser negativo.',
            'guardar' => 'No se ha podido registrar la reposición.',
        ];

        $this->mostrar('administracion-inventario', [
            'titulo' => 'Piezas y existencias',
            'piezas' => $this->administracion->inventario(),
            'aviso' => $avisos[$_GET['msg'] ?? ''] ?? '',
            'error' => $errores[$_GET['error'] ?? ''] ?? '',
        ]);
    }

    public function reponer(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('index.php?seccion=administracion&accion=inventario&error=metodo');
        }

        $piezaId = filter_input(INPUT_POST, 'pieza_id', FILTER_VALIDATE_INT);
        $unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
        $stockMinimo = filter_input(INPUT_POST, 'stock_minimo', FILTER_VALIDATE_INT);

        if ($piezaId === false || $piezaId === null || $piezaId < 1) {
            $this->redirigir('index.php?seccion=administracion&accion=inventario&error=pieza');
        }
        if ($unidades === false || $unidades === null || $unidades < 1) {
            $this->redirigir('index.php?seccion=administracion&accion=inventario&error=unidades');
        }
        if ($stockMinimo === false || $stockMinimo === null || $stockMinimo < 0) {
            $this->redirigir('index.php?seccion=administracion&accion=inventario&error=minimo');
        }

        try {
            if (!$this->administracion->reponer($piezaId, $unidades, $stockMinimo)) {
                $this->redirigir('index.php?seccion=administracion&accion=inventario&error=pieza');
            }
        } catch (PDOException $e) {
            $this->redirigir('index.php?seccion=administracion&accion=inventario&error=guardar');
        }

        $this->redirigir('index.php?seccion=administracion&accion=inventario&msg=repuesto');
    }

    public function noEncontrada(): void
    {
        http_response_code(404);
        $this->mostrar('no-encontrada', [
            'titulo' => 'Página no encontrada',
        ]);
    }

    private function fechaValida($fecha): bool
    {
        if (!is_string($fecha)) {
            return false;
        }

        $fechaFormateada = DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);
        return $fechaFormateada !== false && $fechaFormateada->format('Y-m-d') === $fecha;
    }

    private function redirigir(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    private function mostrar(string $vista, array $datos): void
    {
        extract($datos);
        require __DIR__ . '/../vistas/' . $vista . '.php';
    }
}
