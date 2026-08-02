<?php

namespace Database\Seeders;

use App\Models\PortfolioProject;
use Illuminate\Database\Seeder;

class PortfolioProjectSeeder extends Seeder
{
    /**
     * Seed the marketing portfolio carousel projects.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Comprobar',
                'badges' => ['React Native', 'Firebase', 'Redux', 'Thunk', 'Bootstrap', 'XD'],
                'description' => 'App para el seguimiento de diabetes: cuestionarios, registro de datos y detección temprana de riesgos.',
                'image' => '/images/proj_1.webp',
                'secondary_image' => null,
                'sort_order' => 0,
            ],
            [
                'title' => 'Show Travelers',
                'badges' => ['React Native', 'PHP', 'Bootstrap', 'Redux', 'Google Map', 'Sagas'],
                'description' => 'App móvil para viajeros con alertas útiles y propuestas de visitas turísticas durante el viaje.',
                'image' => '/images/proj_2.webp',
                'secondary_image' => null,
                'sort_order' => 1,
            ],
            [
                'title' => 'Sprint',
                'badges' => ['React Native', 'Laravel', 'Barcode', 'React JS', 'Bootstrap', 'Redux', 'Sagas', 'Expo'],
                'description' => 'Ecosistema web + apps iOS/Android para gestionar la logística de paquetería de punta a punta.',
                'image' => '/images/proj_4.webp',
                'secondary_image' => '/images/proj_4_1.webp',
                'sort_order' => 2,
            ],
            [
                'title' => 'Prego',
                'badges' => ['React Native', 'Laravel', 'React JS', 'Bootstrap', 'Redux', 'Sagas', 'Landing Page', 'Expo', 'Figma'],
                'description' => 'Marketplace que conecta profesionales con pedidos de trabajo, de forma simple y rápida.',
                'image' => '/images/proj_5_1.webp',
                'secondary_image' => '/images/proj_5_2.webp',
                'sort_order' => 3,
            ],
            [
                'title' => 'Moveler',
                'badges' => ['React Native', 'Laravel', 'React JS', 'Redux', 'Bootstrap', 'Sagas', 'Expo', 'Figma'],
                'description' => 'CMS web que alimenta una app móvil con contenidos y administración centralizada.',
                'image' => '/images/proj_6_1.webp',
                'secondary_image' => '/images/proj_6_2.webp',
                'sort_order' => 4,
            ],
            [
                'title' => 'Estoker',
                'badges' => ['Laravel', 'React JS', 'Bootstrap', 'Figma'],
                'description' => 'Control de stock y productos por Excel o interfaz gráfica, pensado para la operación diaria.',
                'image' => '/images/proj_7.webp',
                'secondary_image' => null,
                'sort_order' => 5,
            ],
            [
                'title' => 'Seccoplac',
                'badges' => ['Laravel', 'React JS', 'Bootstrap', 'Landing Page'],
                'description' => 'Sitio corporativo para captar clientes y franquiciados, con catálogo de productos y chatbot.',
                'image' => '/images/proj_3.webp',
                'secondary_image' => null,
                'sort_order' => 6,
            ],
        ];

        foreach ($projects as $project) {
            PortfolioProject::updateOrCreate(
                ['title' => $project['title']],
                array_merge($project, ['is_active' => true])
            );
        }
    }
}
