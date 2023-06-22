<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Administrador Root',
            ])->save();
        }

        // $role = Role::firstOrNew(['name' => 'user']);
        // if (!$role->exists) {
        //     $role->fill([
        //         'display_name' => __('voyager::seeders.roles.user'),
        //     ])->save();
        // }

        $role = Role::firstOrNew(['name' => 'gerente']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Gerente'])->save();
        }

        $role = Role::firstOrNew(['name' => 'administrador']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Administrador'])->save();
        }


        $role = Role::firstOrNew(['name' => 'cajeros']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Cajero (a)'])->save();
        }

        $role = Role::firstOrNew(['name' => 'cobrador']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Cobradores en Moto'])->save();
        }



        $role = Role::firstOrNew(['name' => 'prenda']);
        if (!$role->exists) {
            $role->fill(['display_name' => 'Prendario'])->save();
        }
    }
}
