<?php

namespace App\Filament\Pages\Students;

use App\Models\Student;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class StudentProfilePage extends Page
{
    public Student $record;    

    public string $activeTab = 'profile';

    protected static ?string $slug = 'students/{record}/profile';
    protected static string $view = 'filament.pages.students.student-profile';
    protected static bool $shouldRegisterNavigation = false;

    public function mount(Student $record): void
    {
        $this->record = Student::with([
            'branch',
            'groups',
            'status',
            'courses',
            'studyLanguages',
            'knowledgeLevel',
            'studyDays',
            'parents',
            'marketingSource'
        ])->findOrFail($record->id);
    }

    public function getTitle(): string
    {
        return "O'quvchi haqida axborot";
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
        return parent::getUrl($parameters, $isAbsolute, $panel, $tenant);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
    public function setActiveTab($tab): void
    {
        $this->activeTab = $tab;
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
            'activeTab' => $this->activeTab,
        ];
    }
}