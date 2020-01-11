<?php

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
        $now = \Carbon\Carbon::now();
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'contacto@tresfactorial.com',
            'password' => bcrypt('secret'),
            'referencia' => '00000',
            'pagado' => true,
            'rol' => \App\Code\RolUsuario::ADMIN,
            'created_at'=>$now
        ]);

        $this->call(PreguntasSeeder::class);

        for ($i=1; $i<=env('DIAS'); $i++){
            DB::table('dias')->insert([
                'dia' => $i,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach (\App\Code\Suplementos::suplementos as $suplemento){
            $descripcion = \App\Code\Suplementos::getObjGen($suplemento);
            DB::table('kits')->insert([
                'descripcion' => $suplemento,
                'genero' => $descripcion->genero,
                'objetivo' => $descripcion->objetivo,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
