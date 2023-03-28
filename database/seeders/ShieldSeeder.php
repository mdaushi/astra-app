<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        $rolesWithPermissions = '[{"name":"admin","guard_name":"web","permissions":["view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_user","view_any_user","delete_any_user","page_MyProfile","view_pegawai","view_any_pegawai","create_pegawai","update_pegawai","delete_pegawai","delete_any_pegawai","page_ViewLog","view_pengajuan::perjalanan::dinas","view_any_pengajuan::perjalanan::dinas","create_pengajuan::perjalanan::dinas","update_pengajuan::perjalanan::dinas","restore_pengajuan::perjalanan::dinas","restore_any_pengajuan::perjalanan::dinas","replicate_pengajuan::perjalanan::dinas","reorder_pengajuan::perjalanan::dinas","delete_pengajuan::perjalanan::dinas","delete_any_pengajuan::perjalanan::dinas","force_delete_pengajuan::perjalanan::dinas","force_delete_any_pengajuan::perjalanan::dinas","view_plan::pengajuan::perjalanan::dinas","view_any_plan::pengajuan::perjalanan::dinas","create_plan::pengajuan::perjalanan::dinas","update_plan::pengajuan::perjalanan::dinas","restore_plan::pengajuan::perjalanan::dinas","restore_any_plan::pengajuan::perjalanan::dinas","replicate_plan::pengajuan::perjalanan::dinas","reorder_plan::pengajuan::perjalanan::dinas","delete_plan::pengajuan::perjalanan::dinas","delete_any_plan::pengajuan::perjalanan::dinas","force_delete_plan::pengajuan::perjalanan::dinas","force_delete_any_plan::pengajuan::perjalanan::dinas","view_golongan","view_any_golongan","create_golongan","update_golongan","restore_golongan","restore_any_golongan","replicate_golongan","reorder_golongan","delete_golongan","delete_any_golongan","force_delete_golongan","force_delete_any_golongan","view_jabatan","view_any_jabatan","create_jabatan","update_jabatan","restore_jabatan","restore_any_jabatan","replicate_jabatan","reorder_jabatan","delete_jabatan","delete_any_jabatan","force_delete_jabatan","force_delete_any_jabatan"]},{"name":"user","guard_name":"web","permissions":["page_MyProfile","view_pengajuan::perjalanan::dinas","view_any_pengajuan::perjalanan::dinas","create_pengajuan::perjalanan::dinas","update_pengajuan::perjalanan::dinas","restore_pengajuan::perjalanan::dinas","restore_any_pengajuan::perjalanan::dinas","replicate_pengajuan::perjalanan::dinas","reorder_pengajuan::perjalanan::dinas","delete_pengajuan::perjalanan::dinas","delete_any_pengajuan::perjalanan::dinas","force_delete_pengajuan::perjalanan::dinas","force_delete_any_pengajuan::perjalanan::dinas","view_plan::pengajuan::perjalanan::dinas","view_any_plan::pengajuan::perjalanan::dinas","create_plan::pengajuan::perjalanan::dinas","update_plan::pengajuan::perjalanan::dinas","restore_plan::pengajuan::perjalanan::dinas","restore_any_plan::pengajuan::perjalanan::dinas","replicate_plan::pengajuan::perjalanan::dinas","reorder_plan::pengajuan::perjalanan::dinas","delete_plan::pengajuan::perjalanan::dinas","delete_any_plan::pengajuan::perjalanan::dinas","force_delete_plan::pengajuan::perjalanan::dinas","force_delete_any_plan::pengajuan::perjalanan::dinas"]},{"name":"GA","guard_name":"web","permissions":["view_pegawai","view_any_pegawai","create_pegawai","update_pegawai","delete_pegawai","delete_any_pegawai","view_pengajuan::perjalanan::dinas","view_any_pengajuan::perjalanan::dinas","create_pengajuan::perjalanan::dinas","update_pengajuan::perjalanan::dinas","restore_pengajuan::perjalanan::dinas","restore_any_pengajuan::perjalanan::dinas","replicate_pengajuan::perjalanan::dinas","reorder_pengajuan::perjalanan::dinas","delete_pengajuan::perjalanan::dinas","delete_any_pengajuan::perjalanan::dinas","force_delete_pengajuan::perjalanan::dinas","force_delete_any_pengajuan::perjalanan::dinas","view_plan::pengajuan::perjalanan::dinas","view_any_plan::pengajuan::perjalanan::dinas","create_plan::pengajuan::perjalanan::dinas","update_plan::pengajuan::perjalanan::dinas","restore_plan::pengajuan::perjalanan::dinas","restore_any_plan::pengajuan::perjalanan::dinas","replicate_plan::pengajuan::perjalanan::dinas","reorder_plan::pengajuan::perjalanan::dinas","delete_plan::pengajuan::perjalanan::dinas","delete_any_plan::pengajuan::perjalanan::dinas","force_delete_plan::pengajuan::perjalanan::dinas","force_delete_any_plan::pengajuan::perjalanan::dinas"]},{"name":"chief","guard_name":"web","permissions":["view_pegawai","view_any_pegawai","create_pegawai","update_pegawai","delete_pegawai","delete_any_pegawai","view_pengajuan::perjalanan::dinas","view_any_pengajuan::perjalanan::dinas","create_pengajuan::perjalanan::dinas","update_pengajuan::perjalanan::dinas","restore_pengajuan::perjalanan::dinas","restore_any_pengajuan::perjalanan::dinas","replicate_pengajuan::perjalanan::dinas","reorder_pengajuan::perjalanan::dinas","delete_pengajuan::perjalanan::dinas","delete_any_pengajuan::perjalanan::dinas","force_delete_pengajuan::perjalanan::dinas","force_delete_any_pengajuan::perjalanan::dinas","view_plan::pengajuan::perjalanan::dinas","view_any_plan::pengajuan::perjalanan::dinas","create_plan::pengajuan::perjalanan::dinas","update_plan::pengajuan::perjalanan::dinas","restore_plan::pengajuan::perjalanan::dinas","restore_any_plan::pengajuan::perjalanan::dinas","replicate_plan::pengajuan::perjalanan::dinas","reorder_plan::pengajuan::perjalanan::dinas","delete_plan::pengajuan::perjalanan::dinas","delete_any_plan::pengajuan::perjalanan::dinas","force_delete_plan::pengajuan::perjalanan::dinas","force_delete_any_plan::pengajuan::perjalanan::dinas"]},{"name":"hrd","guard_name":"web","permissions":["view_pegawai","view_any_pegawai","create_pegawai","update_pegawai","delete_pegawai","delete_any_pegawai","view_pengajuan::perjalanan::dinas","view_any_pengajuan::perjalanan::dinas","create_pengajuan::perjalanan::dinas","update_pengajuan::perjalanan::dinas","restore_pengajuan::perjalanan::dinas","restore_any_pengajuan::perjalanan::dinas","replicate_pengajuan::perjalanan::dinas","reorder_pengajuan::perjalanan::dinas","delete_pengajuan::perjalanan::dinas","delete_any_pengajuan::perjalanan::dinas","force_delete_pengajuan::perjalanan::dinas","force_delete_any_pengajuan::perjalanan::dinas","view_plan::pengajuan::perjalanan::dinas","view_any_plan::pengajuan::perjalanan::dinas","create_plan::pengajuan::perjalanan::dinas","update_plan::pengajuan::perjalanan::dinas","restore_plan::pengajuan::perjalanan::dinas","restore_any_plan::pengajuan::perjalanan::dinas","replicate_plan::pengajuan::perjalanan::dinas","reorder_plan::pengajuan::perjalanan::dinas","delete_plan::pengajuan::perjalanan::dinas","delete_any_plan::pengajuan::perjalanan::dinas","force_delete_plan::pengajuan::perjalanan::dinas","force_delete_any_plan::pengajuan::perjalanan::dinas"]}]';
        $directPermissions = '{"8":{"name":"create_user","guard_name":"web"},"9":{"name":"update_user","guard_name":"web"},"10":{"name":"delete_user","guard_name":"web"},"20":{"name":"view_rekening","guard_name":"web"},"21":{"name":"view_any_rekening","guard_name":"web"},"22":{"name":"create_rekening","guard_name":"web"},"23":{"name":"update_rekening","guard_name":"web"},"24":{"name":"restore_rekening","guard_name":"web"},"25":{"name":"restore_any_rekening","guard_name":"web"},"26":{"name":"replicate_rekening","guard_name":"web"},"27":{"name":"reorder_rekening","guard_name":"web"},"28":{"name":"delete_rekening","guard_name":"web"},"29":{"name":"delete_any_rekening","guard_name":"web"},"30":{"name":"force_delete_rekening","guard_name":"web"},"31":{"name":"force_delete_any_rekening","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions,true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {

                $role = Role::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (! blank($rolePlusPermission['permissions'])) {

                    $role->givePermissionTo($rolePlusPermission['permissions']);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions,true))) {

            foreach($permissions as $permission) {

                if (Permission::whereName($permission)->doesntExist()) {
                    Permission::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
