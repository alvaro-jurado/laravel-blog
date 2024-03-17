<div>
    <div class="flex flex-row justify-between mb-4">
        <div class="flex-1">
            <x-input wire:model="search" type="text" class="w-full" name="search"
                placeholder="Buscar nombre / email..." />
        </div>
        <div class="flex items-center">


            @if (Auth::user()->hasRole('user'))
            <button type="button" wire:click="openModal"
                class="bg-gray-50 ml-4 border shadow border-gray-300 text-gray-900 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 font-medium rounded-lg text-sm py-2 px-4 text-center inline-flex items-center transition-colors duration-300 ease-in-out">
                <span class="material-symbols-outlined mr-3">person_add</span>
                Crear usuario
            </button>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha de Registro</th>

                    @if (Auth::user()->hasRole('user'))
                    <th></th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                <tr class="shadow">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->isNotEmpty() ? $user->roles->first()->name : 'Sin Rol' }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>


                    <td>
                        <button type="button" wire:click="editUser({{ $user->id }})"
                            class="text-black-600 material-symbols-outlined hover:text-red-700 transition duration-200 ease-in-out">
                            edit
                        </button>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-500">No se encontraron usuarios.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen">
        <x-slot name="title">
            Crear Usuario
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="createUser">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model="newUser.name" id="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 sm:text-sm">
                    @error('newUser.name') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="newUser.email" id="email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 sm:text-sm">
                    @error('newUser.email') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" wire:model="newUser.password" id="password"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 sm:text-sm">
                    @error('newUser.password') <span class="error">{{ $message }}</span> @enderror
                </div>
            </form>
        </x-slot>

        <x-slot name="footer" class="flex items-center justify-end mt-4">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="createUser" wire:loading.attr="disabled">
                Crear
            </x-button>
        </x-slot>
    </x-dialog-modal>


    <x-dialog-modal wire:model="isEditModalOpen">
        <x-slot name="title">
            Asignar Equipo a {{ $selectedUser->name ?? '' }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <label for="selectedUser-name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" wire:model="name" id="selectedUser-name" name="selectedUser-name"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="selectedUser-email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" id="selectedUser-email" name="selectedUser-email"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="user_status" class="block text-sm font-medium text-gray-700">Estado del Usuario</label>
                <select wire:model="status" id="user_status" name="user_status"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                    <option value="active">Activo</option>
                    <option value="inactive">Inactivo</option>
                </select>
            </div>


            <div class="mb-4">
                <label for="spatie_role" class="block text-sm font-medium text-gray-700">Rol</label>
                <select wire:model.defer="selectedSpatieRole" id="spatie_role" name="spatie_role"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                    <option value="">Seleccionar Rol</option>
                    @foreach ($spatieRoles as $spatieRole)
                    @if (Auth::user()->hasRole('Cliente') && $spatieRole->name === 'Administrador')
                    @else
                    <option value="{{ $spatieRole->name }}">{{ $spatieRole->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>


        </x-slot>

        <x-slot name="footer" class="flex items-center justify-end mt-4">
            <x-secondary-button wire:click="closeEditModal" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="saveUser" wire:loading.attr="disabled">
                Asignar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>