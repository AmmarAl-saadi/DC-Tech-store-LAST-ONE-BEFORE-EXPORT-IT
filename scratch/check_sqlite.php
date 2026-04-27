<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $res = $db->query("SELECT count(*) FROM products");
    echo "Products in SQLite: " . $res->fetchColumn() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
