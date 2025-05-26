<x-filament::page>
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <form wire:submit.prevent="createEmployee" enctype="multipart/form-data">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Ism</label>
                    <input wire:model.defer="first_name" type="text" id="first_name" name="first_name" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900" />
                </div>
                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Familiya</label>
                    <input wire:model.defer="last_name" type="text" id="last_name" name="last_name" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900" />
                </div>
                <!-- Position -->
                <div>
                    <label for="position_id" class="block text-sm font-medium text-gray-700 mb-1">Lavozim</label>
                    <select wire:model.defer="position_id" id="position_id" name="position_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900">
                        <option value="">Tanlang</option>
                        @foreach($this->getPositionOptions() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Holati</label>
                    <select wire:model.defer="status" id="status" name="status" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900">
                        <option value="active">Faol</option>
                        <option value="inactive">Faol emas</option>
                    </select>
                </div>
                <!-- Branch -->
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Filial</label>
                    <select wire:model.defer="branch_id" id="branch_id" name="branch_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900">
                        <option value="">Tanlang</option>
                        @foreach($this->getBranchOptions() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select wire:model.defer="role_id" id="role_id" name="role_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900">
                        <option value="">Tanlang</option>
                        @foreach($this->getRoleOptions() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Bo'lim</label>
                    <select wire:model.defer="department_id" id="department_id" name="department_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900">
                        <option value="">Tanlang</option>
                        @foreach($this->getDepartmentOptions() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input wire:model.defer="email" type="email" id="email" name="email" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900" />
                </div>
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input wire:model.defer="phone" type="tel" id="phone" name="phone" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900" />
                </div>
                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Rasm</label>
                    <input wire:model="photo" type="file" id="photo" name="photo" accept="image/*"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900 bg-white" />
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-x-3 mt-8">
                <a href="{{ \App\Filament\Pages\HR\Employees::getUrl() }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 transition">
                    Bekor qilish
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Xodimni qo'shish
                </button>
            </div>
        </form>
    </div>
</x-filament::page>