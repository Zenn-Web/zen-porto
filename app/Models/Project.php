<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'description',
        'description_en',
        'image',
        'category',
        'category_en',
        'year',
        'github_url',
        'tech_stack',
        'flow_description',
        'flow_description_en',
        'live_demo_url',
    ];

    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
        ];
    }

    /**
     * Plain-text display value for a short project field (e.g. "title", "category_en").
     *
     * Legacy rows store presentation markup such as "&nbsp;&bull;&nbsp;" or stray
     * Windows-1252 bullet/dash bytes; both are normalised to real Unicode characters.
     * The result is plain text and must always be output escaped ({{ }}), never raw.
     */
    public function displayText(string $field, ?string $fallback = null): string
    {
        $value = $this->getAttribute($field)
            ?? ($fallback !== null ? $this->getAttribute($fallback) : null)
            ?? '';

        $value = (string) $value;

        // Only remap legacy bytes when the text is not valid UTF-8; in valid UTF-8
        // these bytes are continuation bytes of characters such as "Ö" or "×".
        if (! mb_check_encoding($value, 'UTF-8')) {
            $value = str_replace([chr(149), chr(150), chr(151)], ["\u{2022}", "\u{2013}", "\u{2013}"], $value);
        }

        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // --- Locale-aware accessors ---

    public function getTranslatedTitleAttribute(): string
    {
        if (app()->getLocale() === 'en' && $this->title_en) {
            return $this->title_en;
        }

        return $this->title;
    }

    public function getTranslatedDescriptionAttribute(): string
    {
        if (app()->getLocale() === 'en' && $this->description_en) {
            return $this->description_en;
        }

        return $this->description ?? '';
    }

    public function getTranslatedCategoryAttribute(): string
    {
        if (app()->getLocale() === 'en' && $this->category_en) {
            return $this->category_en;
        }

        return $this->category ?? '';
    }

    public function getTranslatedFlowDescriptionAttribute(): string
    {
        if (app()->getLocale() === 'en' && $this->flow_description_en) {
            return $this->flow_description_en;
        }

        return $this->flow_description ?? '';
    }

    // --- Existing accessor ---

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

        return array_map(fn ($tech) => [
            'name' => $tech,
            'color' => $badgeColors[$tech] ?? '#6B7280',
        ], $this->tech_stack);
    }
}
