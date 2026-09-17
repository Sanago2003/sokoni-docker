<?php
declare(strict_types=1);

$CROPS = $pdo->query('SELECT name FROM crops ORDER BY name')->fetchAll(PDO::FETCH_COLUMN);
$REGIONS = $pdo->query('SELECT name FROM regions ORDER BY name')->fetchAll(PDO::FETCH_COLUMN);
$UNITS = $pdo->query('SELECT name FROM units ORDER BY name')->fetchAll(PDO::FETCH_COLUMN);
