<?php
// Funciones de ayuda para las vistas

// Escapa un texto antes de escribirlo en el HTML
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '');
}

// 2026-11-03  →  03/11/2026
function fecha(string $iso): string
{
    return date('d/m/Y', strtotime($iso));
}

// 145  →  145,00 €
function euros($cantidad): string
{
    return number_format((float) $cantidad, 2, ',', '.') . ' €';
}