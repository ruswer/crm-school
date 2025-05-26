{{-- Asosiy profil ma'lumotlari (qo'shimcha) --}}
<div class="border dark:border-gray-700 rounded-lg overflow-hidden">
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Asosiy ma'lumotlar</h3>
        </div>
        {{-- Email --}}
        <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</div>
            <div class="col-span-2 text-sm text-gray-900 dark:text-gray-100">{{ $staff->email ?? '-' }}</div>
        </div>
        {{-- Telefon --}}
        <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefon:</div>
            <div class="col-span-2 text-sm text-gray-900 dark:text-gray-100">{{ $staff->phone ?? '-' }}</div>
        </div>
        {{-- Jinsi --}}
        <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Jinsi:</div>
            {{-- TODO: Staff modelida 'gender' atributi borligiga ishonch hosil qiling --}}
            <div class="col-span-2 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($staff->gender ?? '-') }}</div>
        </div>
        {{-- Tug'ilgan kuni --}}
        <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tug'ilgan kuni:</div>
            {{-- TODO: Staff modelida 'birth_date' atributi borligiga ishonch hosil qiling --}}
            <div class="col-span-2 text-sm text-gray-900 dark:text-gray-100">{{ $staff->birth_date ? \Carbon\Carbon::parse($staff->birth_date)->format('d.m.Y') : '-' }}</div>
        </div>
        {{-- Ish staji --}}
        <div class="grid grid-cols-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Ish staji:</div>
            {{-- TODO: Staff modelida 'hired_date' atributi borligiga ishonch hosil qiling --}}
            <div class="col-span-2 text-sm text-gray-900 dark:text-gray-100">
                @if($staff->created_at)
                    @php
                        $diff = $staff->created_at->diff(now());
                        $experienceParts = [];
                        if ($diff->y > 0) $experienceParts[] = $diff->y . ' yil';
                        if ($diff->m > 0) $experienceParts[] = $diff->m . ' oy';
                        $experience = !empty($experienceParts) ? implode(' ', $experienceParts) : '0 oy';
                    @endphp
                    {{ $experience }}
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>
</div>