Bienvenido al Reto ACTON
{{$usuario->name}} {{$usuario->last_name}}
Ya puedes iniciar sesión en Acton:
correo: {{$usuario->email}}
@if($usuario->pass!='')
    contraseña: {{$usuario->pass}}

    NOTA: Recuerda que tu contraseña se escribe con mayusculas, si deseas asignar una nueva contraseña, lo podrás hacer en la seccion "Mi cuenta"
@endif
Ingresa aquí : "{{env("APP_URL")."/login"}}"