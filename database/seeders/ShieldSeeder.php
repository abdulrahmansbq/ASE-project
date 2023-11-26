<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
class ShieldSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_bill","view_any_bill","create_bill","update_bill","restore_bill","restore_any_bill","replicate_bill","reorder_bill","delete_bill","delete_any_bill","force_delete_bill","force_delete_any_bill","view_drug","view_any_drug","create_drug","update_drug","restore_drug","restore_any_drug","replicate_drug","reorder_drug","delete_drug","delete_any_drug","force_delete_drug","force_delete_any_drug","view_patient","view_any_patient","create_patient","update_patient","restore_patient","restore_any_patient","replicate_patient","reorder_patient","delete_patient","delete_any_patient","force_delete_patient","force_delete_any_patient","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_service","view_any_service","create_service","update_service","restore_service","restore_any_service","replicate_service","reorder_service","delete_service","delete_any_service","force_delete_service","force_delete_any_service","widget_TemperatureOverview","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","widget_UpdatedAccountWidget"]},{"name":"Cashir","guard_name":"web","permissions":["view_bill","view_any_bill","create_bill","update_bill","restore_bill","restore_any_bill","replicate_bill","reorder_bill","delete_bill","delete_any_bill","force_delete_bill","force_delete_any_bill","view_patient","view_any_patient","create_patient","update_patient","restore_patient","restore_any_patient","replicate_patient","reorder_patient","delete_patient","delete_any_patient","force_delete_patient","force_delete_any_patient","view_service","view_any_service","create_service","update_service","restore_service","restore_any_service","replicate_service","reorder_service","delete_service","delete_any_service","force_delete_service","force_delete_any_service","widget_UpdatedAccountWidget"]},{"name":"Pharmacist","guard_name":"web","permissions":["view_drug","view_any_drug","create_drug","update_drug","restore_drug","restore_any_drug","replicate_drug","reorder_drug","delete_drug","delete_any_drug","force_delete_drug","force_delete_any_drug","view_patient","view_any_patient","create_patient","update_patient","restore_patient","restore_any_patient","replicate_patient","reorder_patient","delete_patient","delete_any_patient","force_delete_patient","force_delete_any_patient","view_service","view_any_service","create_service","update_service","restore_service","restore_any_service","replicate_service","reorder_service","delete_service","delete_any_service","force_delete_service","force_delete_any_service","widget_TemperatureOverview","widget_UpdatedAccountWidget"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions,true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = Utils::getRoleModel()::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (! blank($rolePlusPermission['permissions'])) {

                    $permissionModels = collect();

                    collect($rolePlusPermission['permissions'])
                        ->each(function ($permission) use($permissionModels) {
                            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                                'name' => $permission,
                                'guard_name' => 'web'
                            ]));
                        });
                    $role->syncPermissions($permissionModels);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions,true))) {

            foreach($permissions as $permission) {

                if (Utils::getPermissionModel()::whereName($permission)->doesntExist()) {
                    Utils::getPermissionModel()::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
