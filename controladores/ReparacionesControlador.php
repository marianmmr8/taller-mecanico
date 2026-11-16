<?php
// Controlador: recibe la petición, usa los modelos y elige la vista.
// No contiene SQL ni HTML.

use const Dom\INDEX_SIZE_ERR;

class ReparacionesControlador
{
    public function __construct(private ReparacionModelo $reparaciones) {}

    // index.php?accion=listado
    public function listado(): void
    {
        // TODO 1: pide las reparaciones al modelo y muestra la vista «listado»
        //         con dos datos: 'titulo' => 'Reparaciones' y 'reparaciones'

        $this->mostrar ('listado', [
            'titulo' => "Reparaciones ",
            'reparaciones' => $this->reparaciones->todas(),
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

        $this->mostrar('ficha',[
        'titulo' => 'Reparacion nº' . $reparacion['id'],
        'reparacion' => $reparacion,
        'piezas' => $piezas,
        'total' => $total
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

    // Carga una vista. extract() convierte ['titulo' => 'Reparaciones']
    // en la variable $titulo, y así con cada dato
    private function mostrar(string $vista, array $datos): void
    {
        extract($datos);
        require __DIR__ . '/../vistas/' . $vista . '.php';
    }






}