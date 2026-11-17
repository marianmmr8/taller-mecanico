# Nexo Motor · Gestión de taller

Aplicación web sencilla para llevar las reparaciones de un taller mecánico. Permite consultar los trabajos en curso, registrar piezas y horas de mano de obra, cerrar reparaciones y preparar una factura imprimible.

## Funciones

- Listado de reparaciones con filtro por estado: en el taller, finalizadas o todas. El listado inicial muestra las que siguen abiertas.
- Ficha de reparación con vehículo, cliente, mecánico, avería, piezas y costes.
- Alta de reparaciones seleccionando vehículo y mecánico.
- Registro de piezas. Si se añade de nuevo una pieza a la misma reparación, se suman las unidades existentes.
- Registro de mano de obra adicional mediante horas y tarifa por hora. El coste se suma al importe de mano de obra ya guardado; las reparaciones cerradas no se pueden modificar.
- Cierre de la reparación con fecha de salida.
- Factura imprimible, disponible al cerrar la reparación, con base imponible e IVA del 21 %.

## Requisitos

- PHP 8.2 o posterior con las extensiones `PDO` y `pdo_mysql`.
- MySQL o MariaDB.
- Para desarrollo local en Windows: XAMPP con Apache y MySQL activos.

## Instalación con XAMPP

1. Coloca el proyecto en `C:\xampp\htdocs\taller-mecanico`.
2. Inicia Apache y MySQL desde el panel de XAMPP.
3. En phpMyAdmin, crea una base de datos vacía, por ejemplo `taller_mecanico`.
4. Selecciona esa base de datos e importa `datos/taller_mecanico.sql` para crear las tablas.
5. Si quieres cargar registros de ejemplo, importa después `datos/datos_prueba.sql`.
6. Si aún no tienes `config/config.php`, copia `config/config_example.php` a esa ruta y completa los datos de conexión:

   ```php
   <?php
   $servidor = "localhost";
   $nombreBase = "taller_mecanico";
   $usuario = "root";
   $password = "";
   ```

   Ajusta usuario, contraseña y nombre de base de datos a tu instalación local. No sobrescribas un `config/config.php` que ya tenga tu configuración correcta ni publiques sus credenciales.

7. Abre `http://localhost/taller-mecanico/`.

> Importa el esquema solo en una base vacía. La importación de datos de ejemplo también requiere tablas sin registros con esos mismos identificadores.

## Uso habitual

1. En **Reparaciones**, filtra por estado y abre una reparación para consultar su ficha.
2. En **Nueva reparación**, elige vehículo y mecánico, describe la avería e introduce el importe inicial de mano de obra en euros.
3. Mientras siga abierta, desde la ficha puedes añadir piezas y horas adicionales. Para cada registro de mano de obra se introduce la tarifa por hora; el importe calculado se acumula al coste inicial.
4. Pulsa **Registrar salida** cuando se entregue el vehículo. Desde ese momento no se pueden añadir piezas ni mano de obra.
5. En la ficha cerrada, selecciona **Imprimir factura**. El botón de impresión desaparece al imprimir.

## Rutas principales

El punto de entrada es `index.php`; la acción se selecciona con el parámetro `accion`.

| Acción | Uso |
| --- | --- |
| `listado` | Listado y filtro de reparaciones |
| `ver&id=ID` | Ficha de una reparación |
| `nueva` | Formulario para abrir una reparación |
| `guardar` | Guardar el formulario de alta mediante `POST` |
| `anadirPieza&id=ID` | Añadir una pieza mediante `POST` |
| `anadirManoObra` | Añadir horas y tarifa mediante `POST` |
| `cerrar` | Anotar la salida mediante `POST` |
| `factura&id=ID` | Factura imprimible de una reparación cerrada |

Si no se indica una acción, se abre el listado.

## Estructura del proyecto

- `index.php`: carga la aplicación y despacha las acciones.
- `controladores/ReparacionesControlador.php`: valida las peticiones y decide qué vista mostrar.
- `modelos/`: consultas y operaciones sobre reparaciones y catálogos.
- `vistas/`: páginas y componentes HTML.
- `config/`: conexión PDO y configuración local.
- `datos/`: esquema de la base de datos y registros de ejemplo.
- `estilos.css`: estilos y reglas de impresión.

## Datos y cálculos

- `reparacion.mano_obra` contiene el coste de mano de obra en euros, incluido el importe inicial y los incrementos posteriores.
- `reparacion.horas_mano_obra` acumula las horas añadidas después de abrir la reparación.
- `piezas_reparacion` relaciona reparaciones y piezas. La pareja `(id_reparacion, id_pieza)` es única; la clave primaria `id` se conserva.
- La factura calcula el subtotal sumando mano de obra y piezas, aplica un IVA fijo del 21 % y muestra el total.

La factura es una plantilla básica: el proyecto no almacena datos fiscales del taller ni una numeración fiscal independiente.
