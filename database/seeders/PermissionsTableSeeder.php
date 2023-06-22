<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {

        \DB::table('permissions')->delete();

        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'table_name' => 'admin',
        ]);

        $keys = [
            // 'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');


        Permission::generateFor('category_garments');
        Permission::generateFor('brand_garments');
        Permission::generateFor('model_garments');
        Permission::generateFor('articles');


        
        $keys = [
            'browse_vaults',
            'add_vaults',
            'open_vaults',
            'movements_vaults',
            'close_vaults',
            'print_vaults',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'vaults',
            ]);
        }

        // para crear prestamos varios
        $keys = [
            'browse_loans',
            'add_loans',
            'read_loans',
            'delete_loans',

            'successLoan_loans',//para que el gerente apruebe el prestamo
            'deliverMoney_loans', //para quye entregen el dinero al beneficiario


            'addMoneyDaily_loans',//para agregar o pagar el prestamo diario

        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'loans',
            ]);
        }



        // para reportes en general GERENTE Y ADMINISTRADOR
        $keys = [
            'browse_printdailyCollection',
            'browse_printloanAll',


            'browse_printdailyList', //para imprimir la lista diaria de cobro por dias y rutas
            'browse_printloanListLate',

            'browse_printloanCollection', //reportes para el cajero y el cobrador en moto
            'browse_printloanDelivered' //reportes para obtener los prestamos diarios entregados o en fecha
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'reports_gerente',
            ]);
        }


        

        // _________________________________________________________


        //  Rutas

        $keys = [
            'browse_routes',
            'add_routes',
            'edit_routes',
            'read_routes',
            'collector_routes',
            
            'browse_routesloanexchange'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'routes',
            ]);
        }


        

        // cajeros
        $keys = [
            'browse_cashiers',
            'add_cashiers',
            'read_cashiers',
            // 'open_cashiers',
            // 'movements_cashiers',
            // 'close_vaults',
            // 'print_vaults',
            
        ];
        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'cashiers',
            ]);
        }


        // poople
        $keys = [
            'browse_people',
            'add_people',
            'edit_people',
            'read_people',
            'delete_people',
            'sponsor_people',            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'people',
            ]);
        }


        $keys = [
            'browse_user',
            'add_user',
            'edit_user',
            'status_user',          
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'user',
            ]);
        }

        // #################################################################################################
        // ###########################################   PRENDARIO   #######################################
        // #################################################################################################

        $keys = [
            'browse_garments',
            'add_garments',
            'read_garments',
            'delete_garments'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'garments',
            ]);
        }
    }
}
