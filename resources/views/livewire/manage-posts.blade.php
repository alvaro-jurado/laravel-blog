<div>
    @foreach($posts as $post)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-4">
            <div class="px-4 py-5 sm:px-6">
                <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
                <p class="text-sm text-gray-500">{{ $post->content }}</p>
            </div>
            <div class="px-4 py-3 sm:px-6">
                <button wire:click="edit({{ $post->id }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest  focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                    Editar
                </button>
                <button wire:click="confirmPostDeletion({{ $post->id }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-red-600 hover:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition ease-in-out duration-150 ml-2">
                    Eliminar
                </button>
            </div>
        </div>
    @endforeach

    <!-- Jetstream Modals -->
    <x-dialog-modal wire:model="isConfirmDeleteModalOpen">
        <x-slot name="title">
            {{ __('Confirmar Eliminación') }}
        </x-slot>

        <x-slot name="content">
            {{ __('¿Estás seguro de que deseas eliminar este post?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isConfirmDeleteModalOpen', false)">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button wire:click="delete">
                {{ __('Eliminar') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="isEditModalOpen" id="editModal">
        <x-slot name="title">
            {{ __('Editar Post') }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label for="title" value="{{ __('Título') }}" />
                <x-input id="title" type="text" class="mt-1 block w-full" wire:model.defer="title" />
                <x-input-error for="title" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-label for="content" value="{{ __('Contenido') }}" />
                <textarea id="content" class="form-textarea mt-1 block w-full" wire:model.defer="content" rows="6"></textarea>
                <x-input-error for="content" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isEditModalOpen', false)">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-button wire:click="update">
                {{ __('Guardar Cambios') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
