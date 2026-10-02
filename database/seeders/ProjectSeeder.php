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
                'title' => 'Company Website Development - Eyegil.com',
                'title_en' => 'Company Website Development - Eyegil.com',
                'slug' => 'company-website-eyegil',
                'description' => 'Berkontribusi dalam membangun tulang punggung digital yang meningkatkan efisiensi operasional perusahaan hingga 40% melalui sistem terintegrasi.',
                'description_en' => 'Contributed to building a digital backbone that increases company operational efficiency by up to 40% through integrated systems.',
                'image' => 'img/eyegilv2.porto.png',
                'category' => 'Enterprise Solutions Provider • Custom Software Development',
                'category_en' => 'UI/UX • DIGITAL BUSINESS • Enterprise Solutions Provider • Custom Software Development Services',
                'year' => '2025',
                'github_url' => null,
                'tech_stack' => ['Laravel', 'Bootstrap', 'JavaScript', 'MySQL'],
                'flow_description' => 'Proyek ini dimulai dengan analisis kebutuhan klien untuk membangun platform digital. Proses dilanjutkan dengan perancangan UI/UX menggunakan Figma, pengembangan frontend dengan Laravel Blade dan Bootstrap, serta integrasi backend untuk manajemen konten dan layanan pelanggan.',
                'flow_description_en' => 'This project began with client needs analysis to build a digital platform. The process continued with UI/UX design using Figma, frontend development with Laravel Blade and Bootstrap, and backend integration for content management and customer services.',
                'live_demo_url' => 'https://eyegil.com',
            ],
            [
                'title' => 'UMKM Business Management - TUMBUH',
                'title_en' => 'MSME Business Management - TUMBUH',
                'slug' => 'umkm-business-tumbuh',
                'description' => 'Berkontribusi dalam eksperimen AI yang mengakselerasi pertumbuhan UMKM melalui pengolahan data dan laporan keuangan otomatis.',
                'description_en' => 'Contributed to an AI experiment that accelerates MSME growth through automated data processing and financial reporting.',
                'image' => 'img/UMKM-tumbuh.porto.png',
                'category' => 'Personal Growth Platform • AI Growth Partner',
                'category_en' => 'UI/UX • DEVELOPMENT • Personal Growth Platform • AI Growth Partner',
                'year' => '2026',
                'github_url' => 'https://github.com/h8naf1/UMKM-AI-REACT',
                'tech_stack' => ['React', 'TypeScript', 'Tailwind CSS', 'Python'],
                'flow_description' => 'Aplikasi ini dikembangkan menggunakan React.js untuk frontend dan Python untuk backend AI. Fitur utama meliputi dashboard otomatis, laporan keuangan real-time, dan rekomendasi bisnis berbasis AI. Proses development meliputi riset pengguna, pembuatan prototipe, pengembangan agile, dan testing.',
                'flow_description_en' => 'This application was developed using React.js for the frontend and Python for the AI backend. Key features include an automated dashboard, real-time financial reports, and AI-based business recommendations. The development process included user research, prototyping, agile development, and testing.',
                'live_demo_url' => 'https://tumbuh-app-web.vercel.app',
            ],
            [
                'title' => 'Training Platform - AMAZAIN',
                'title_en' => 'Training Platform - AMAZAIN',
                'slug' => 'training-platform-amazain',
                'description' => 'Berkontribusi dalam pengembangan platform pendidikan digital yang didesain untuk mempercepat penguasaan skill strategis di era digital.',
                'description_en' => 'Contributed to developing a digital education platform designed to accelerate the mastery of strategic skills in the digital era.',
                'image' => 'img/Amazaincompro.porto.png',
                'category' => 'Training Consultant • Human Capital Development',
                'category_en' => 'UI/UX • DEVELOPMENT • Training Consultant • Human Capital Development',
                'year' => '2026',
                'github_url' => null,
                'tech_stack' => ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'MySQL'],
                'flow_description' => 'Platform ini dibangun dengan arsitektur modular yang memungkinkan manajemen kursus, pengguna, dan sertifikasi. Fitur utama meliputi sistem enrollment, pembelajaran interaktif, tracking progress, dan integrasi payment gateway. Dikembangkan dengan metode agile Scrum.',
                'flow_description_en' => 'This platform was built with a modular architecture that enables course, user, and certification management. Key features include an enrollment system, interactive learning, progress tracking, and payment gateway integration. Developed using the agile Scrum method.',
                'live_demo_url' => 'https://amazaintraining.com/',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        $this->command->info('Projects seeded successfully!');
    }
}
