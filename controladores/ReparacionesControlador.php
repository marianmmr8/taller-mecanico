<?php
// Controlador: recibe la petición, usa los modelos y elige la vista.
// No contiene SQL ni HTML.
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