<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 21/10/19
 * Time: 08:54 AM
 */

namespace App\Listeners;


use App\Events\ProcesarVideoEvent;
use App\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcesarVideoEventListener implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(ProcesarVideoEvent $event)
    {
        $storage = storage_path('app');
        if (!Storage::disk('local')->exists($event->destino)) {
            Storage::disk('local')->makeDirectory($event->destino);
        }
        $archivo = "$storage/$event->origen/$event->ruta";
        $extension = explode(".", $archivo)[1];
        if ($extension == 'zip') {
            $zip = new \ZipArchive();
            $zip->open($archivo);
            $zip->extractTo("$storage/$event->origen");
            Storage::disk('local')->delete("/$event->origen/$event->ruta");
            $archivos = Storage::disk('local')->files("$event->origen");
            foreach ($archivos as $arch) {
                if (strpos($arch, " ")!==false){
                    Storage::disk('local')->move($arch, str_replace(' ', '_', $arch));
                }
                $origen = str_replace(" ", "_", $arch);
                $destino = explode('/', $origen);
                $destino = $destino[count($destino) - 1];
                exec("HandBrakeCLI -i " . storage_path("app/$origen") . " -o $storage/$event->destino/$destino -B 160 -e x264 -q 20");
                Storage::disk('local')->delete($origen);
            }
        } else {
            exec("HandBrakeCLI -i $storage/$event->origen/$event->ruta -o $storage/$event->destino/$event->ruta -B 160 -e x264 -q 20");
            Storage::disk('local')->delete("$event->origen/$event->ruta");
        }
    }
}
