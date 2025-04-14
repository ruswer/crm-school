<!-- filepath: /resources/views/vendor/filament/components/sidebar.blade.php -->
<div {{ $attributes->class(['filament-sidebar']) }}>
    <div class="filament-sidebar-items">
        @foreach (Filament\Facades\Filament::getNavigation() as $group => $items)
            @if ($group !== 'Students') <!-- "Students" guruhidan tashqari barcha bo'limlar -->
                <div class="filament-sidebar-group">
                    <h3 class="filament-sidebar-group-label">{{ $group }}</h3>
                    @foreach ($items as $item)
                        {!! $item->render() !!}
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
    <div class="filament-sidebar-extra">
        <!-- Students uchun dropdown -->
        <div class="dropdown">
            <button class="dropdown-toggle">
                <span class="icon heroicon-o-academic-cap"></span> <!-- Dropdown uchun ikonka -->
                Students
            </button>
            <div class="dropdown-menu">
                @foreach (Filament\Facades\Filament::getNavigation()['Students'] as $item)
                    {!! $item->render() !!}
                @endforeach
            </div>
        </div>
    </div>
</div>

