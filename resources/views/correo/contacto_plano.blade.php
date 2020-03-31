@if($contacto->etapa==1)
    Hola {{$contacto->nombres}}.
    Vi que te intereso mi reto acton de 8 semanas y me tomé la libertad de mandarte este correo para platicarte un poco más.
    Probablemente ya has intentado algunas veces transformar tu cuerpo sin obtener el resultado que deseas y eso quizá te haya hecho creer que mejorar  tu físico es muy dificil pero dejame decirte no es así. Regalame 5 minutos de tu tiempo para poder explicarte porque es tan fácil cambiar nuestro cuerpo en este reto.
@elseif($contacto->etapa==2)
    Hola {{$contacto->nombres}}. Que tal.
    Dando seguimiento al correo que te envie ayer.
    Te quiero platicar el lema que tenemos en ingeniería
     Lo que no se puede medir no se puede mejorar
    Y estoy totalmente de acuerdo.
    Por esa razón diseñe este simulador para que puedas ver el avance exacto que tendrias en el primer mes de llevar a cabo el reto acton, te gustaría ver tu resultado del primer mes
@else
    Hola {{$contacto->nombres}}.
    Estuve pensando las posibles razones de porque aun no has decidido unirte al reto Y se me vienen a la mente varias opciones, quiza te estes preguntando:
    Me dará buenos resultados
    Será una buena opción invertir mi dinero en este programa
    Serà fácil llevarlo
    Realmente puedo generar dinero aquí desde el primer día
    Recibiré el seguimento adecuado
    Bueno, te invito a ver el siguiente video para que veas que la respuesta a todas estas preguntas es SI!
    Decidimos mi equipo y yo que ya no habrá más descuentos por ahora pero me interesa tenerte en mi equipo, porque sé que realmente tienes interés y se que realmente te puede servir mucho este reto, si te quedas a ver el video hasta el final, podemos hacer solo por hoy el último descuento. Te parece
@endif
{{env("APP_URL")."/etapa".($contacto->etapa-1)."/$contacto->id"}}
