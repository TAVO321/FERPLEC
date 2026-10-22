<?php

namespace Database\Seeders;

use App\Models\Tip;
use Illuminate\Database\Seeder;

class TipSeeder extends Seeder
{
    public function run(): void
    {
        $tips = [
            [
                'title' => 'Código de colores eléctricos según NB 777',
                'slug' => 'codigo-colores-electricos-nb-777',
                'category' => 'Electricidad',
                'excerpt' => 'Guía práctica para identificar conductores por su color.',
                'content' => "El código de colores permite identificar la función de cada conductor.\n\nEs fundamental verificar la normativa vigente en cada proyecto y seguir las indicaciones del proyectista o norma aplicable.",
                'read_time' => 4,
                'is_featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Cómo seleccionar tuberías PVC para desagüe',
                'slug' => 'seleccionar-tuberias-pvc-desague',
                'category' => 'Plomería',
                'excerpt' => 'Recomendaciones para elegir el diámetro y tipo adecuado.',
                'content' => "La selección depende del caudal, longitud y tipo de desagüe.\n\nSiempre considere las especificaciones técnicas del fabricante y las normas aplicables.",
                'read_time' => 3,
                'is_featured' => false,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Cómo elegir el cable adecuado según la carga',
                'slug' => 'elegir-cable-segun-carga',
                'category' => 'Electricidad',
                'excerpt' => 'Secciones, material y calibre: así evitas recalentamiento y caídas de tensión.',
                'content' => "Un cable con sección insuficiente se calienta, pierde aislamiento y termina fallando.\n\nPara cargas domésticas lo más común es cobre flexible o rígido de 2,5 mm² en circuitos de iluminación y 4 mm² o 6 mm² en tomas de corriente. Ante dudas, consulta con un electricista.\n\nNo conectes cargas de potencia sin calcular la carga total del circuito.",
                'read_time' => 5,
                'is_featured' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Errores comunes al instalar tuberías PVC sanitaria',
                'slug' => 'errores-comunes-instalacion-pvc-sanitaria',
                'category' => 'Plomería',
                'excerpt' => 'Pendientes, sin escuadrar y sin testeado: los tres fallos que más se repiten.',
                'content' => "Los tres errores más frecuentes son dejar la tubería sin pendiente, descuadrar los empalmes y no hacer la prueba de estanqueidad antes de tapar.\n\nUsa cuñas o nivel para dar una pendiente mínima de 1 a 2 %, y sella las uniones con el solvente correspondiente dejando el tiempo de secado indicado por el fabricante.\n\nTrabaja con guantes y ventilación adecuada: los vapores de los solventes son irritantes.",
                'read_time' => 6,
                'is_featured' => false,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Herramientas básicas para un kit doméstico',
                'slug' => 'herramientas-basicas-kit-domestico',
                'category' => 'Ferretería',
                'excerpt' => 'Lo mínimo indispensable para reparar y mantener tu casa.',
                'content' => "Un buen kit doméstico incluye: destornilladores de punta plana y de estrella, alicates de corte y de punta, llave adjustable, flexómetro, martillo unipunta y un juego de llaves Allen.\n\nBuy en punta y punta plana, en medidas de 6 a 12, con mango acolchado. El flexómetro de 5 m es más cómodo que uno de 3 m dentro de un armario.\n\nInvierte en una bolsa o caja de herramientas para mantener todo ordenado.",
                'read_time' => 5,
                'is_featured' => false,
                'published_at' => now()->subDays(13),
            ],
            [
                'title' => 'Clavos, tornillos y anclajes: cuál usar en cada caso',
                'slug' => 'clavos-tornillos-anclajes-cual-usar',
                'category' => 'Construcción',
                'excerpt' => 'Fijación en madera, mampostería o metal: qué tornillo elegir.',
                'content' => "En madera, el tornillo autoperforante es cómodo y rápido. En mampostería necesitas taladro con punta de widia o anclaje químico. En metal, tornillo metricado y roscaExtractor.\n\nEl anclaje químico es la opción más firme para cargas pesadas en concreto, pero respeta los tiempos de curado indicados por el fabricante.\n\nUn largo de tornilloadequate es más importante que el calibre: debe penetrar al menos 2,5 cm en la base.",
                'read_time' => 6,
                'is_featured' => false,
                'published_at' => now()->subDays(16),
            ],
            [
                'title' => 'Seguridad eléctrica en el taller y en casa',
                'slug' => 'seguridad-electrica-taller-casa',
                'category' => 'Electricidad',
                'excerpt' => 'Antes de tocar un enchufe: corte la energía y verifica que está muerto.',
                'content' => "Antes de intervenir cualquier instalación, corta la energía en el tablero y verifica la ausencia de tensión con un comprobador, no solo con el interruptor.\n\nTrabaja con herramientas aisladas, con el mango seco y sinExtensions. No toques partes metálicas descubiertas.\n\nAnte cualquier duda, llama a un electricista certificado. En FERPLEC te asesoramos en materiales, pero la instalación queda a cargo del profesional.",
                'read_time' => 4,
                'is_featured' => false,
                'published_at' => now()->subDays(19),
            ],
            [
                'title' => 'Mantenimiento preventivo de la instalación sanitaria',
                'slug' => 'mantenimiento-preventivo-instalacion-sanitaria',
                'category' => 'Plomería',
                'excerpt' => 'Revisiones de 15 minutos que evitan la mayoría de las fugas.',
                'content' => "Revisa periódicamente los empalmes visibles, las llaves de paso y los sifones. Una gotera en la base del inodoro o un sifón que gotea suelen resolverse con un ajuste simple.\n\nLimpia los desagües con métodos caseros: no uses soda cáustica, deteriora las juntas de PVC.\n\nCierra las llaves de paso cuando no las uses y revisa el tanque de la cisterna: una válvula de llenado defectuosa consume agua sin que lo notes.",
                'read_time' => 5,
                'is_featured' => false,
                'published_at' => now()->subDays(22),
            ],
        ];

        foreach ($tips as $tip) {
            Tip::updateOrCreate(
                ['slug' => $tip['slug']],
                array_merge($tip, ['is_published' => true])
            );
        }
    }
}
