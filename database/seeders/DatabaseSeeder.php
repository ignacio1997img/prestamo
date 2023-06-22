<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(DataRowsTableSeeder::class);
        // $this->call(DataTypesTableSeeder::class);
        // $this->call(MenuItemsTableSeeder::class);

        $this->call(VoyagerDatabaseSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(MenuItemsTableSeeder::class);
        $this->call(DataTypesTableSeeder::class);
        $this->call(DataRowsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(PeopleTableSeeder::class);
    }
}
