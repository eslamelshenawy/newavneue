<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'a@b.c',
            'password' => bcrypt('123'),
            'phone' => '0123456789',
            'agent_type_id' => '1',
            'user_id' => '1',
            'image'=>'image.ico',
        ]);

        DB::table('settings')->insert([
            'logo' => 'logo.png',
            'title' => 'hub',
            'admin_path' => 'admin',
            'theme' => 'skin-blue',
        ]);

        DB::table('agent_types')->insert([
            'name' => 'admin',
            'description' => 'admin',
            'user_id' => 0,
        ]);
        
        DB::table('locations')->insert([
            'en_name' => 'Egypt',
            'ar_name' => 'مصر',
            'lat'=>'26.8206',
            'lng'=>'30.8025',
            'zoom'=>'8',
            'parent_id' => '0',
        ]);
    }
}
