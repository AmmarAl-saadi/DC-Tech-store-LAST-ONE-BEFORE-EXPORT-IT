<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

try {
    $role = Role::where('name', 'superadmin')->first();
    
    $p1 = Permission::firstOrCreate(['name' => 'quotation.view'], [
        'group_name' => 'quotation',
        'guard_name' => 'admin'
    ]);
    
    $p2 = Permission::firstOrCreate(['name' => 'quotation.delete'], [
        'group_name' => 'quotation',
        'guard_name' => 'admin'
    ]);
    
    if ($role) {
        $role->givePermissionTo($p1);
        $role->givePermissionTo($p2);
        echo "Permissions granted successfully.\n";
    } else {
        echo "Superadmin role not found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
