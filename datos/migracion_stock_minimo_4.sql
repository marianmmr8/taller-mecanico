-- Ejecutar una sola vez si el control de stock ya estaba instalado.
ALTER TABLE piezas
  MODIFY COLUMN stock_minimo INT NOT NULL DEFAULT 4;

UPDATE piezas
SET stock_minimo = 4;
