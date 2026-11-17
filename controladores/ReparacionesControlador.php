<?php
// Controlador: recibe la petición, usa los modelos y elige la vista.
// No contiene SQL ni HTML.

use const Dom\INDEX_SIZE_ERR;

class ReparacionesControlador
{
    public function __construct(private ReparacionModelo $reparaciones , private CatalogoModelo $catalogo) {}

    // index.php?accion=listado
    public function listado(): void
    {
        $estado = $_GET['estado'] ?? 'abiertas';
        $error = '';
        $estadosValidos = ['abiertas', 'finalizadas', 'todas'];
        if (!is_string($estado) || !in_array($estado, $estadosValidos, true)) {
            $estado = 'abiertas';
            $error = 'El filtro no es válido. Se muestran las reparaciones en el taller.';
        }

        $this->mostrar('listado', [
            'titulo' => 'Reparaciones',
            'reparaciones' => $this->reparaciones->todas($estado),
            'estado' => $estado,
            'error' => $error,
        ]);
    }




        // index.php?accion=ver&id=3
    public function ver(): void
    {
        // TODO 3: lee el id de la dirección como número entero

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // TODO 4: pide la reparación al modelo; si no hay id o no existe,
        //         llama a $this->noEncontrada() y termina con return

        $reparacion = $id ? $this->reparaciones->buscar($id) : null;
        if(!$reparacion){
            $this->noEncontrada();
            return;
        }

        // TODO 5: pide sus piezas y calcula el total:
        //         mano de obra más la suma de los importes

        $piezas = $this->reparaciones->piezas($id);
        $total = $reparacion['mano_obra'] + array_sum(array_column($piezas, 'importe'));


        // TODO 6: muestra la vista «ficha» con cuatro datos: titulo
        //         («Reparación n.º 3»), reparacion, piezas y total

       // Avisos que llegan en la dirección: …&msg=creada
        $avisos = [
            'creada' => 'Reparación abierta.',
            'pieza' => 'Pieza añadida a la reparación.',
            'salida' => 'Salida anotada.',
            'mano_obra' => 'Mano de obra añadida.',
        ];
        $errores = [
            'metodo' => 'La petición debe enviarse mediante POST.',
            'cerrada' => 'No se puede modificar una reparación finalizada.',
            'pieza' => 'Selecciona una pieza válida.',
            'unidades' => 'La cantidad debe ser un número entero de 1 o más.',
            'guardar' => 'No se ha podido añadir la pieza.',
            'horas' => 'Las horas deben ser un número mayor que 0.',
            'tarifa' => 'La tarifa por hora debe ser un número igual o mayor que 0.',
            'mano_obra' => 'No se ha podido añadir la mano de obra.',
            'factura_abierta' => 'La factura estará disponible cuando se cierre la reparación.',
        ];

        $this->mostrar('ficha', [
            'titulo' => 'Reparación n.º ' . $reparacion['id'],
            'reparacion' => $reparacion,
            'piezas' => $piezas,
            'total' => $total,
            'aviso' => $avisos[$_GET['msg'] ?? ''] ?? '',
            'error' => $errores[$_GET['error'] ?? ''] ?? '',
            'catalogoPiezas' => $reparacion['fecha_salida'] === null ? $this->catalogo->piezas() : [],
        ]);
    }

    public function anadirPieza(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $reparacion = $id ? $this->reparaciones->buscar($id) : null;
        if (!$reparacion) {
            $this->noEncontrada();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=metodo');
        }

        if ($reparacion['fecha_salida'] !== null) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=cerrada');
        }

        $piezaId = filter_input(INPUT_POST, 'pieza_id', FILTER_VALIDATE_INT);
        if ($piezaId === false || $piezaId === null || $piezaId < 1) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=pieza');
        }

        $piezasDisponibles = array_map(
            'intval',
            array_column($this->catalogo->piezas(), 'id')
        );
        if (!in_array($piezaId, $piezasDisponibles, true)) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=pieza');
        }

        $unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
        if ($unidades === false || $unidades === null || $unidades < 1) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=unidades');
        }

        try {
            $this->reparaciones->anadirPieza($id, $piezaId, $unidades);
        } catch (PDOException $e) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=guardar');
        }

        $this->redirigir('index.php?accion=ver&id=' . $id . '&msg=pieza');
    }

    public function cerrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('index.php?accion=listado');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id < 1 || !$this->reparaciones->cerrar($id)) {
            $this->redirigir('index.php?accion=listado');
        }

        $this->redirigir('index.php?accion=ver&id=' . $id . '&msg=salida');
    }

    public function anadirManoObra(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('index.php?accion=listado');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id < 1) {
            $this->redirigir('index.php?accion=listado');
        }

        $reparacion = $this->reparaciones->buscar($id);
        if (!$reparacion) {
            $this->noEncontrada();
            return;
        }

        if ($reparacion['fecha_salida'] !== null) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=cerrada');
        }

        $horas = filter_input(INPUT_POST, 'horas', FILTER_VALIDATE_FLOAT);
        if ($horas === false || $horas === null || $horas <= 0) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=horas');
        }

        $tarifaHora = filter_input(INPUT_POST, 'tarifa_hora', FILTER_VALIDATE_FLOAT);
        if ($tarifaHora === false || $tarifaHora === null || $tarifaHora < 0) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=tarifa');
        }

        try {
            if (!$this->reparaciones->anadirManoObra($id, $horas, $tarifaHora)) {
                $this->redirigir('index.php?accion=ver&id=' . $id . '&error=cerrada');
            }
        } catch (PDOException $e) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=mano_obra');
        }

        $this->redirigir('index.php?accion=ver&id=' . $id . '&msg=mano_obra');
    }

    public function factura(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $reparacion = $id ? $this->reparaciones->buscar($id) : null;
        if (!$reparacion) {
            $this->noEncontrada();
            return;
        }

        if ($reparacion['fecha_salida'] === null) {
            $this->redirigir('index.php?accion=ver&id=' . $id . '&error=factura_abierta');
        }

        $piezas = $this->reparaciones->piezas($id);
        $base = (float) $reparacion['mano_obra']
            + array_sum(array_column($piezas, 'importe'));
        $iva = round($base * 0.21, 2);

        $this->mostrar('factura', [
            'titulo' => 'Factura · reparación ' . $id,
            'numeroFactura' => 'NX-' . date('Y') . '-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT),
            'reparacion' => $reparacion,
            'piezas' => $piezas,
            'base' => $base,
            'iva' => $iva,
            'total' => $base + $iva,
        ]);
    }



    // Cualquier acción que no exista
    public function noEncontrada(): void
    {
        // TODO 2: responde con el código 404 y muestra la vista «no-encontrada»
        //         con el título «Página no encontrada»
     http_response_code(404);
    $this->mostrar('no-encontrada',
     [
    'titulo'=> 'Página no encontrada'
     ]);


    }
        // index.php?accion=nueva

      public function nueva(): void
    {
        $this->formulario([], []);
    }

    // index.php?accion=guardar (POST)
    public function guardar(): void
    {
        // TODO 4: si la petición no es POST, redirige a index.php?accion=nueva
          if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->redirigir('index.php?accion=nueva');
          }
        // TODO 5: lee vehiculo_id y mecanico_id como enteros, descripcion sin espacios
        //         a los lados y mano_obra como número decimal (FILTER_VALIDATE_FLOAT)

        $vehiculoId = filter_input(INPUT_POST, 'vehiculo_id', FILTER_VALIDATE_INT);
        $mecanicoId = filter_input(INPUT_POST, 'mecanico_id', FILTER_VALIDATE_INT);
        $descripcion = trim($_POST['descripcion'] ?? ' ');
        $manoObra = filter_input(INPUT_POST, 'mano_obra', FILTER_VALIDATE_FLOAT);


        // TODO 6: guarda en el array $errores un mensaje por cada dato incorrecto:
        //         «Elige un vehículo.», «Elige un mecánico.»,
        //         «Describe la avería (200 caracteres como máximo).»,
        //         «La mano de obra debe ser un número igual o mayor que 0.»

        $errores = [];
        if(!$vehiculoId) {
            $errores[] = 'Elige un vehiculo';
        }
        if (!$mecanicoId){
            $errores[] ='Elige un mecanico';
        }
        if($descripcion === '' || mb_strlen($descripcion) > 250 ){
            $errores [] = ' Describe la avería';
        }

        if($manoObra === false || $manoObra === null || $manoObra < 0){
            $errores[] = ' La mano de obra de ser un número igual o mayor que 0';
        }
        
        // TODO 7: si no hay errores, crea la reparación con el modelo y redirige
        //         a su ficha con &msg=creada. Si el modelo lanza una PDOException,
        //         añade el error «No se ha podido guardar la reparación.»

        if(!$errores){
            try{
                $id = $this->reparaciones->crear($vehiculoId, $mecanicoId, $descripcion, $manoObra);
                $this->redirigir('index.php?accion=ver&id=' . $id . '&msg=creada');
            }catch (PDOException $e){
                $errores[] = ' No se ha podido guardar la reparación';
            }
        }

         $this->formulario($errores,[
            'vehiculo_id' => $vehiculoId,
            'mecanico_id' => $mecanicoId,
            'descripcion' => $descripcion,
            'mano_obra' => $_POST['mano_obra'] ?? '',
         ]);



        // TODO 8: si has llegado aquí, hay errores: vuelve a mostrar el formulario
        //         con $errores y con los valores que escribió el usuario
    }

    // Muestra el formulario de nueva reparación, vacío o con errores
    private function formulario(array $errores, array $valores): void
    {
        $this->mostrar('formulario', [
            'titulo' => 'Nueva reparación',
            'vehiculos' => $this->catalogo->vehiculos(),
            'mecanicos' => $this->catalogo->mecanicos(),
            'errores' => $errores,
            'valores' => $valores,
        ]);
    }

    private function redirigir(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    // Carga una vista. extract() convierte ['titulo' => 'Reparaciones']
    // en la variable $titulo, y así con cada dato
    private function mostrar(string $vista, array $datos): void
    {
        extract($datos);
        require __DIR__ . '/../vistas/' . $vista . '.php';
    }






}