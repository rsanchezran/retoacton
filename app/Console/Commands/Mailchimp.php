<?php


namespace App\Console\Commands;


use App\Contacto;
use Illuminate\Console\Command;

class Mailchimp extends Command
{

    protected $signature = 'mailchimp';
    protected $description = 'Enviar correo a contactos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
//        $etapa1 = $this->getListas("etapa1");
//        $this->enviarMiembro();
//        $id =  $this->createCampana();
//        $this->sendCampana($id);
    }

    public function getLista($etapa){
        $auth = base64_encode( 'user:'.env('MAILCHIMP_KEY',''));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('MAILCHIMP_URL','').'/lists/');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $listas = json_decode(curl_exec($ch));
        foreach ($listas->lists as $lista){
            if($lista->name == $etapa){
                return $lista->id;
            }
        }
    }

    public function enviarMiembros($lista, $miembros){
        $apikey = env('MAILCHIMP_KEY','');
        $auth = base64_encode( 'user:'.$apikey );
        $data = new \stdClass();
        $data->members = collect();
        foreach ($miembros as $miembro){
            $member = new \stdClass();
            $member->email_address = $miembro->email;
            $member->status = 'subscribed';
            $member->merge_fields = ['FNAME' => $miembro->nombres,
                'LNAME' => $miembro->apellidos];
            $data->members->push($member);
        }
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,env('MAILCHIMP_URL','')."/lists/$lista");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $result = json_decode(curl_exec($ch));
    }

    public function enviarMiembro($lista, $email, $nombres, $apellidos){
        $apikey = env('MAILCHIMP_KEY','');
        $auth = base64_encode( 'user:'.$apikey );
        $data = array(
            'apikey' => $apikey,
            'email_address' => $email,
            'status'=> 'subscribed',
            'merge_fields'=> array(
                'FNAME' => $nombres,
                'LNAME' => $apellidos
            )
        );
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,env('MAILCHIMP_URL','')."/lists/$lista/members");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $result = json_decode(curl_exec($ch));
        $contacto = Contacto::where('email',$email)->first();
        $contacto->unique_id = $result->unique_email_id;
        $contacto->save();
    }

    public function quitarMiembro($lista, $id){
        $apikey = env('MAILCHIMP_KEY','');
        $auth = base64_encode( 'user:'.$apikey );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('MAILCHIMP_URL','')."/lists/$lista/members/$id");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
    }

    public function getCampanas() {
        $apikey = env('MAILCHIMP_KEY','');
        $auth = base64_encode( 'user:'.$apikey );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('MAILCHIMP_URL','').'/campaigns');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $campanas  = json_decode(curl_exec($ch));
        foreach ($campanas as $campana) {
            return $campana->id;
        }
    }

    public function createCampana($campana){
        $apikey = env('MAILCHIMP_KEY','');
        $auth = base64_encode( 'user:'.$apikey );
        $data = array(
            'apikey' => $apikey,
            'type' => 'regular',
        );
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('MAILCHIMP_URL','')."/campaigns/$campana/actions/replicate");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        $result = json_decode(curl_exec($ch));
        return $result->id;
    }

    public function sendCampana($campana){
        $apikey = env('MAILCHIMP_KEY','');
        $auth = base64_encode( 'user:'.$apikey );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('MAILCHIMP_URL','')."/campaigns/$campana/actions/send");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = json_decode(curl_exec($ch));
    }

    public function enviarCorreo($miembros, $workflow, $queue){
        $apikey = env('MAILCHIMP_KEY','');
        $auth = base64_encode( 'user:'.$apikey );
        foreach ($miembros as $miembro){
            $data = array(
                'email_address' => $miembro->email,
            );
            $json_data = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('MAILCHIMP_URL','')."/automations/$workflow/emails/$queue/queue");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                'Authorization: Basic '.$auth));
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            $result = json_decode(curl_exec($ch));
        }
    }
}
