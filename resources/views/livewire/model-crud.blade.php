<div class="flex flex-col gap-4" x-data>
    <livewire:flash-message />

    <h2>{{ $modalTitle }}</h2>

    <!-- Modal component for creating/updating items -->
    <x-modal name="create-item-modal" :title="$modalTitle">
        <div>
            <form>
                @foreach ($fields as $field => $details)
                    <div>
                        <label for="{{ $field }}">{{ $details['label'] }}:</label>
                        @if ($details['type'] === 'select')
                            <select id="{{ $field }}" wire:model="{{ $field }}">
                                @foreach ($details['options'] as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @elseif ($details['type'] === 'textarea')
                            <textarea id="{{ $field }}" wire:model="{{ $field }}"></textarea>
                        @else
                            <input type="{{ $details['type'] }}" id="{{ $field }}" wire:model="{{ $field }}">
                        @endif
                        @error($field)
                            <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </form>
        </div>

        <x-slot name="footer">
            <button @click="$dispatch('close-modal')" class="mr-2 modal-button">Cancel</button>
            <button wire:click="createOrUpdateItem" class="modal-confirm-button">{{ $editItemId ? 'Update' : 'Create' }} Item</button>
        </x-slot>
    </x-modal>

    <!-- Modal component for deleting items -->
    <x-modal name="delete-item-modal" title="Delete Item">
        <div>
            <p>Are you sure you want to delete this item?</p>
        </div>

        <x-slot name="footer">
            <button @click="$dispatch('close-modal')" class="mr-2 modal-button">Cancel</button>
            <button wire:click="deleteItem" class="modal-confirm-button">Delete</button>
        </x-slot>
    </x-modal>

    <!-- Existing Items Table -->
    <table class="min-w-full">
        <thead>
            <tr>
                @foreach ($fields as $field => $details)
                    <th>{{ $details['label'] }}</th>
                @endforeach
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                @foreach ($fields as $field => $details)
                    <td>{{ $item[$field] }}</td>
                @endforeach
                <td class="actions">
                    <button class="edit" wire:click="openEditItemModal('{{ $item['id'] }}')" @click="$dispatch('open-modal', 'create-item-modal')">Edit</button>
                    <button class="view" wire:click="openEditItemModal('{{ $item['id'] }}')" @click="$dispatch('open-modal', 'create-item-modal')">View</button>
                    <button class="delete" wire:click="openDeleteItemModal('{{ $item['id'] }}')" @click="$dispatch('open-modal', 'delete-item-modal')">Delete</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($fields) + 1 }}">No items found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Button to open the modal for creating a new item -->
    <button @click="$dispatch('open-modal', 'create-item-modal')" wire:click="openCreateItemModal" class="mb-4 ml-auto modal-button w-fit">Create New Item</button>

</div>
