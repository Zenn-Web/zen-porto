<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Company Website Development – Eyegil.com',
                'slug' => 'company-website-eyegil',
                'description' => 'Membangun tulang punggung digital yang meningkatkan efisiensi operasional perusahaan hingga 40% melalui sistem terintegrasi.',
                'image' => 'img/eyegilv2.porto.png',
                'category' => 'UI/UX • DIGITAL BUSINESS • Enterprise Solutions Provider • Custom Software Development Services',
                'year' => '2025',
                'github_url' => null,
                'tech_stack' => ['Laravel', 'Bootstrap', 'JavaScript', 'MySQL'],
                'flow_description' => 'Proyek ini dimulai dengan analisis kebutuhan klien untuk membangun platform digital??. Proses dilanjutkan dengan perancangan UI/UX menggunakan Figma, pengembangan frontend dengan Laravel Blade dan Bootstrap, serta integrasi backend untuk manajemen konten dan layanan pelanggan.',
                'live_demo_url' => 'https://eyegil.com',
            ],
            [
                'title' => 'UMKM Business Management – TUMBUH',
                'slug' => 'umkm-business-tumbuh',
                'description' => 'Eksperimen AI yang mengakselerasi pertumbuhan UMKM melalui pengolahan data dan laporan keuangan otomatis.',
                'image' => 'img/UMKM-tumbuh.porto.png',
                'category' => 'UI/UX • DEVELOPMENT • Personal Growth Platform • AI Growth Partner',
                'year' => '2026',
                'github_url' => 'https://github.com/Zenn-Web/tumbuh-app',
                'tech_stack' => ['Next.js', 'TypeScript', 'Tailwind CSS', 'Python'],
                'flow_description' => 'Aplikasi ini dikembangkan menggunakan Next.js untuk frontend dan Python untuk backend AI. Fitur utama meliputi dashboard otomatis, laporan keuangan real-time, dan rekomendasi bisnis berbasis AI. Proses development meliputi riset pengguna, pembuatan prototipe, pengembangan agile, dan testing.',
                'live_demo_url' => 'https://tumbuh-app-web.vercel.app',
            ],
            [
                'title' => 'Training Platform – AMAZAIN',
                'slug' => 'training-platform-amazain',
                'description' => 'Platform pendidikan digital yang didesain untuk mempercepat penguasaan skill strategis di era digital.',
                'image' => 'img/Amazaincompro.porto.png',
                'category' => 'UI/UX • DEVELOPMENT • Training Consultant • Human Capital Development',
                'year' => '2026',
                'github_url' => null,
                'tech_stack' => ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'MySQL'],
                'flow_description' => 'Platform ini dibangun dengan arsitektur modular yang memungkinkan manajemen kursus, pengguna, dan sertifikasi. Fitur utama meliputi sistem enrollment, pembelajaran interaktif, tracking progress, dan integrasi payment gateway. Dikembangkan dengan metode agile Scrum.',
                'live_demo_url' => 'https://amazaintraining.com/',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        $this->command->info('Projects seeded successfully!');
    }
}
