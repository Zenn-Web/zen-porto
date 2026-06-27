<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'category',
        'year',
        'github_url',
        'tech_stack',
        'flow_description',
        'live_demo_url',
    ];

    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
        ];
    }

    public function getTechStackBadgesAttribute(): array
    {
        if (empty($this->tech_stack)) {
            return [];
        }

        $badgeColors = [
            'Laravel' => '#FF2D20',
            'PHP' => '#777BB4',
            'JavaScript' => '#F7DF1E',
            'TypeScript' => '#3178C6',
            'React' => '#61DAFB',
            'Vue.js' => '#4FC08D',
            'Node.js' => '#339933',
            'Bootstrap' => '#7952B3',
            'Tailwind CSS' => '#06B6D4',
            'CSS' => '#1572B6',
            'HTML' => '#E34F26',
            'MySQL' => '#4479A1',
            'Python' => '#3776AB',
            'Docker' => '#2496ED',
            'Git' => '#F05032',
            'Figma' => '#F24E1E',
            'jQuery' => '#0769AD',
            'SASS' => '#CC6699',
            'Alpine.js' => '#8BC0D0',
            'Livewire' => '#FB70A9',
        ];

        return array_map(fn($tech) => [
            'name' => $tech,
            'color' => $badgeColors[$tech] ?? '#6B7280',
        ], $this->tech_stack);
    }
}
