<?php

namespace App\Console\Commands;

use App\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcesarVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesar_video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $procesando = Job::all()->first();
            if ($procesando == null) {
                $directorios = Storage::disk('local')->directories("ejercicios");
                foreach ($directorios as $directorio) {
                    $archivos = Storage::disk('local')->files($directorio);
                    foreach ($archivos as $arch){
                        $extension = explode('.',$arch);
                        $extension = $extension[count($extension)-1];
                        if ($extension == 'mp4'){
                            $procesando = new Job();
                            $procesando->nombre = $arch;
                            $procesando->save();
                            if (strpos($arch, " ")!==false){
                                Storage::disk('local')->move($arch, str_replace(' ', '_', $arch));
                            }
                            $destino = explode('/', $directorio);
                            $destino = $destino[count($destino)-1];
                            $origen = str_replace(" ", "_", $arch);
                            $origen = explode('/', $origen);
                            $origen = $origen[count($origen) - 1];
                            exec("HandBrakeCLI -i ".storage_path("app"). "/$arch -o ".storage_path("app")."/optimized/$destino/$origen -B 160 -e x264 -q 20");
                            Job::query()->delete();
                        }
                        Storage::disk('local')->delete($arch);
                    }
                }
            }
        }catch (\Exception $e){
            Job::query()->delete();
        }
    }
}
