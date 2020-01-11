<?php
use Illuminate\Database\Seeder;

class PreguntasSeeder extends Seeder{

    public function run (){
        DB::table('preguntas')->insert([
            'pregunta' => 'Peso en Kg',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Estatura en cm',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Sexo',
            'multiple' => 0,
            'opciones' => '["Mujer", "Hombre"]',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Tipo de trabajo',
            'multiple' => 0,
            'opciones' => '["Mi trabajo es de oficina, muy relajado","Mi trabajo es de oficina con mucho estrés","Mi trabajo requiere esfuerzo físico","Me dedico al hogar"]',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Horas de trabajo al día',
            'multiple' => 0,
            'opciones' => '["Menos de 8hrs", "Más de 8hrs"]',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Actividad física',
            'multiple' => 0,
            'opciones' => '["Me ejercito diariamente", "Me ejercito menos de 4 veces por semana","Nunca realizo actividad física, apenas empezaré"]',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Malos hábitos',
            'multiple' => true,
            'opciones' => '["Fumo", "Tomo alcohol muy seguido","Consumo mucho azúcar","Tomo poca agua","Duermo muy poco (menos de 5hrs)"]',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Tipo de físico',
            'multiple' => 0,
            'opciones' => '["Soy delgado con niveles de grasa bajo", "Soy delgado pero acumulo mucha grasa abdominal", "Soy robusto y tiendo a acumular grasa rápidamente","Padezco de obesidad"]',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'Objetivo fitness',
            'multiple' => 0,
            'opciones' => '["Aumentar masa muscular","Subir de peso","Bajar de peso","Bajar porcentaje de grasa y tonificar"]',
        ]);

        DB::table('preguntas')->insert([
            'pregunta' => 'No quiero en mi dieta',
            'multiple' => 1,
            'opciones' => '["Pollo","Pescado","Pavo","Atún","Queso","Res", "Cerdo","Huevo","Salmón"]',
        ]);
        DB::table('preguntas')->insert([
            'pregunta' => 'No quiero en mi dieta',
            'multiple' => 1,
            'opciones' => '["Arroz","Amaranto","Avas","Espárragos","Apio","Cebolla", "Pasta","Frijol","Lentejas","Ejotes","Ajo","Pan de centeno"]',
        ]);
        DB::table('preguntas')->insert([
            'pregunta' => 'No quiero en mi dieta',
            'multiple' => 1,
            'opciones' => '["Manzana","Mandarina","Durazno","Sandía","Papaya","Pera", "Uvas","Higo","Naranja","Kiwi"]',
        ]);
    }
}