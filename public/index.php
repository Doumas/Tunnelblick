<?php
session_start(); // Session starten, bevor die App geladen wird

// App initialisieren
$app = require __DIR__ . '/../src/Bootstrap.php';

// App ausführen
$app->run();
