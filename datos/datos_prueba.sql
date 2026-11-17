-- Datos de prueba para la base de datos `taller mecanico`.
-- Importar despues de crear el esquema de datos/taller_mecanico.sql.

SET NAMES utf8mb4;
START TRANSACTION;

INSERT INTO `cliente` (`id`, `nombre`, `apellidos`, `telefono`, `correo`) VALUES
(1, 'Lucia', 'Garcia Martin', '600123456', 'lucia.garcia@example.com'),
(2, 'Carlos', 'Rodriguez Lopez', '611234567', 'carlos.rodriguez@example.com'),
(3, 'Marta', 'Fernandez Ruiz', '622345678', 'marta.fernandez@example.com'),
(4, 'Javier', 'Sanchez Perez', '633456789', 'javier.sanchez@example.com'),
(5, 'Elena', 'Lopez Moreno', '644567890', 'elena.lopez@example.com'),
(6, 'Pablo', 'Martin Romero', '655678901', 'pablo.martin@example.com'),
(7, 'Sara', 'Diaz Torres', '666789012', 'sara.diaz@example.com'),
(8, 'Diego', 'Alvarez Gil', '677890123', 'diego.alvarez@example.com');

INSERT INTO `mecanicos` (`id`, `nombre`, `especialidad`) VALUES
(1, 'Antonio Perez Gomez', 'mecánica general'),
(2, 'Beatriz Molina Ruiz', 'electromecanico'),
(3, 'Raul Sanchez Diaz', 'chapa y pintura'),
(4, 'Carmen Navarro Lopez', 'mecánica general');

INSERT INTO `piezas` (`id`, `referencia`, `nombre`, `precio`) VALUES
(1, 'ACE-5W30-01', 'Aceite sintetico 5W30 (litro)', 12.50),
(2, 'FIL-ACE-01', 'Filtro de aceite', 9.90),
(3, 'FIL-AIR-01', 'Filtro de aire', 18.75),
(4, 'PAS-FRE-01', 'Pastillas de freno delanteras', 54.90),
(5, 'BAT-12V-01', 'Bateria 12V 70Ah', 109.00),
(6, 'BOM-AGU-01', 'Bomba de agua', 78.50),
(7, 'COR-DIS-01', 'Kit de distribucion', 165.00),
(8, 'ALT-01', 'Alternador', 189.95),
(9, 'RET-ESP-01', 'Retrovisor exterior', 72.00),
(10, 'PAR-DEL-01', 'Paragolpes delantero', 245.00),
(11, 'BUJ-ENC-01', 'Juego de bujias', 32.00),
(12, 'NEU-205-01', 'Neumatico 205/55 R16', 89.90);

INSERT INTO `vehiculo` (`id`, `matricula`, `marca`, `modelo`, `anio`, `id_cliente`) VALUES
(1, '1234 BCD', 'Seat', 'Leon', 2018, 1),
(2, '2345 FGH', 'Toyota', 'Corolla', 2021, 2),
(3, '3456 JKL', 'Renault', 'Clio', 2015, 3),
(4, '4567 MNP', 'Volkswagen', 'Golf', 2019, 4),
(5, '5678 RST', 'Ford', 'Focus', 2016, 5),
(6, '6789 VWX', 'Peugeot', '308', 2020, 6),
(7, '7890 BCF', 'Citroen', 'C3', 2014, 7),
(8, '8901 DFG', 'Hyundai', 'i30', 2022, 8),
(9, '9012 HJK', 'Opel', 'Corsa', 2017, 1),
(10, '0123 LMN', 'Dacia', 'Sandero', 2023, 3);

INSERT INTO `reparacion`
  (`id`, `id_vehiculo`, `id_mecanico`, `fecha_entrega`, `descripcion`, `mano_obra`, `fecha_salida`)
VALUES
(1, 1, 1, '2026-10-02 09:00:00', 'Revision periodica y cambio de aceite', 45.00, '2026-10-02 12:30:00'),
(2, 2, 2, '2026-10-04 10:15:00', 'La bateria se descarga y el motor no arranca', 55.00, '2026-10-04 15:00:00'),
(3, 3, 1, '2026-10-07 08:30:00', 'Ruido al frenar y desgaste de pastillas', 65.00, '2026-10-07 13:45:00'),
(4, 4, 4, '2026-10-10 09:20:00', 'Sustitucion del kit de distribucion', 180.00, '2026-10-10 17:00:00'),
(5, 5, 3, '2026-10-12 11:00:00', 'Retrovisor derecho roto', 40.00, '2026-10-12 14:30:00'),
(6, 6, 2, '2026-10-15 08:45:00', 'Testigo de carga encendido en el cuadro', 75.00, '2026-10-16 16:00:00'),
(7, 7, 3, '2026-11-10 09:00:00', 'Danos en el paragolpes delantero', 210.00, NULL),
(8, 8, 2, '2026-11-11 10:30:00', 'Perdida de refrigerante en el circuito', 120.00, NULL),
(9, 9, 4, '2026-11-12 08:15:00', 'Revision periodica, filtros y bujias', 60.00, '2026-11-12 13:00:00'),
(10, 10, 1, '2026-11-13 09:30:00', 'Vibracion y desgaste irregular de neumaticos', 50.00, NULL);

INSERT INTO `piezas_reparacion` (`id`, `id_reparacion`, `id_pieza`, `unidades`) VALUES
(1, 1, 1, 5),
(2, 1, 2, 1),
(3, 2, 5, 1),
(4, 3, 4, 1),
(5, 4, 7, 1),
(6, 4, 6, 1),
(7, 5, 9, 1),
(8, 6, 8, 1),
(9, 7, 10, 1),
(10, 8, 6, 1),
(11, 9, 2, 1),
(12, 9, 3, 1),
(13, 9, 11, 1),
(14, 10, 12, 2),
(15, 10, 3, 1);

COMMIT;
