<!-- filepath: /Applications/MAMP/htdocs/crm-project/resources/views/filament/widgets/statistics-widget.blade.php -->
<x-filament::widget>
    <div>
        <h2>Statistics</h2>
        <ul>
            <li>Total Students: {{ $this->studentsCount }}</li>
            <li>Total Courses: {{ $this->coursesCount }}</li>
            <li>Total Enrollments: {{ $this->enrollmentsCount }}</li>
        </ul>
    </div>
</x-filament::widget>