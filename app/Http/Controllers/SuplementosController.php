<?php

namespace App\Http\Controllers;

use App\Code\Suplementos;
use App\Kits;
use App\Suplemento;
use App\UsuarioKit;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SuplementosController extends Controller
{

    public function index(){
        $this->authorize('configurar.suplementos');
        $kitsSuplementos = $this->getKitsFormato();

        return view('configuracion.suplementos',['kits' => $kitsSuplementos]);
    }

    public function save(Request $request)
    {
        $this->authorize('configurar.suplementos');
        $this->validate($request, [
            'suplementos.*.*.porcion' => 'required|max:255',
            'suplementos.*.*.suplemento' => 'required|max:255'
        ],[
            'suplementos.*.*.porcion.required' => 'El campo porción es obligatorio',
            'suplementos.*.*.suplemento.required' => 'El campo suplemento es obligatorio',
            'suplementos.*.*.porcion.max' => 'Debe tener máximo 255 caracteres',
            'suplementos.*.*.suplemento.max' => 'Debe tener máximo 255 caracteres'
        ]);

        \DB::beginTransaction();
        if($request->eliminados != null) { //elimina los suplementos necesarios
            $viejosKits = collect($request->eliminados)->groupBy('kit');
            foreach ($viejosKits as $key => $oldSuplementos) {
                Suplemento::whereIn('id', $oldSuplementos->pluck('id'))->delete();
            }
        }
        $kits = Kits::all()->keyBy('descripcion');
        $eliminadosHash = clone collect($request->eliminados)->groupBy('descripcion');
        $suplementosDB = Suplemento::all()->keyBy('id');

        foreach ($request->suplementos as $key => $suplementos){//accion con suplementos nuevos, modificados y eliminados
            $nuevosSuplementos = clone collect($suplementos)->filter(function ($supl){
                return $supl['id'] == '';
            });

            //comprobar que haiga nuevos suplementos
            if (count($nuevosSuplementos) != 0 || ($eliminadosHash->get($key)!=null && count($eliminadosHash->get($key))!=0 )) {
                $oldKit = $kits->get($key);
                $nuevokit = new Kits();     //crear nuevo kit
                $nuevokit->descripcion = $key;
                $nuevokit->objetivo = $oldKit->objetivo;
                $nuevokit->genero = $oldKit->genero;
                $nuevokit->save();
                $nuevokit = $nuevokit->id; //nuevo id del kit para sustituir el viejo

                foreach ($nuevosSuplementos as $suplemento) {   //agrega los suplementos nuevos
                    $nuevoSuplemento = new Suplemento();
                    $nuevoSuplemento->suplemento = $suplemento['suplemento'];
                    $nuevoSuplemento->porcion = $suplemento['porcion'];
                    $nuevoSuplemento->kit_id = $nuevokit;
                    $nuevoSuplemento->save();
                }

                if ($oldKit != null) { //actualiza los datos de las tablas relacionadas y borrar el kit viejo
                    Suplemento::where('kit_id', $oldKit->id)//agregar nuevos kit_id a los suplementos
                    ->update(['kit_id' => $nuevokit]);
                    UsuarioKit::where('kit_id', $oldKit->id)//agregar nuevos kit_id a usuario_kit
                    ->update(['kit_id' => $nuevokit]);
                    Kits::find($oldKit->id)->delete();
                }
            }

            //Revisa cambios en los suplementos que ya tienen id desde la vista
            $cambiosSuplementos = clone collect($suplementos)->filter(
                function ($vistaSupl)use($suplementosDB){ //checa que almenos alguno de los dos cambios cambio
                    $suplemento = $suplementosDB->get($vistaSupl['id']);

                    if($suplemento != null) {//checa que algun dato sea diferente
                        return ($vistaSupl['porcion'] != $suplemento['porcion']
                                || $vistaSupl['suplemento'] != $suplemento['suplemento']);
                    }
                    return false;
                });

            foreach ($cambiosSuplementos as $cambio) { //haz los cambios necesarios en los suplementos que cambiaron
                $cambioSupl = Suplemento::find($cambio['id']);
                $cambioSupl->suplemento = $cambio['suplemento'];
                $cambioSupl->porcion = $cambio['porcion'];
                $cambioSupl->save();
            }
        }
        \DB::commit();

        $kitsSuplementos = $this->getKitsFormato(); //traer todos los kits con suplementos

        return response()->json(['status' => 'ok', 'kitsDB' => $kitsSuplementos]) ;

    }

    public function getKitsFormato(){
        $kits = Kits::with(['KitsSuplementos' => function($kit) { //busca los kits que tengan suplementos
            $kit->select('id', 'suplemento', 'porcion');
        }])->get();

        $kitsSuplementos = $kits->groupBy('descripcion'); //agrupar por HS0, HS1..., MS1
        $kitsSuplementos = $kitsSuplementos->map(function ($kit){ //agrega a cada suplemeto el id del kit
            $kit[0]->kitsSuplementos->id_kit = $kit[0]->id;
            return $kit[0]->kitsSuplementos;
        });

        return $kitsSuplementos;
    }

}
