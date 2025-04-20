
<x-filament::page>
    <form wire:submit.prevent="saveRole">
        {{ $this->form }}

        <div class="flex justify-between mt-4">
            <div>
                @if($selectedRole)
                    <x-filament::button
                        type="button"
                        color="danger"
                        wire:click="deleteRole"
                        wire:confirm="Rostdan ham bu rolni o'chirmoqchimisiz?"
                    >
                        Rolni o'chirish
                    </x-filament::button>
                @endif
            </div>

            <div class="flex space-x-3">
                <x-filament::button
                    type="button"
                    color="secondary"
                    wire:click="$refresh"
                >
                    Bekor qilish
                </x-filament::button>

                <x-filament::button
                    type="submit"
                >
                    Saqlash
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament::page>