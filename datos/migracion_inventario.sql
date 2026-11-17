-- Ejecutar una sola vez en una base ya creada para añadir el control de stock.
ALTER TABLE piezas
  ADD COLUMN stock INT NOT NULL DEFAULT 0,
  ADD COLUMN stock_minimo INT NOT NULL DEFAULT 4;
