<?php

namespace Cnrp\ModelCRUD\Components;

use Livewire\Component;
use Illuminate\Validation\ValidationException;

class ModelCRUD extends Component
{
    public $modelName;
    public $modelClass;
    public $items = [];
    public $fields = [];
    public $showModal = false;
    public $modalTitle;
    public $editItemId = null;
    public $deleteItemId = null;

    // Dynamic public properties
    public $name;
    public $email;
    public $description;
    public $start_date;
    public $end_date;
    public $status;

    public function mount($modelName, array $fields, $modalTitle)
    {
        $this->modelName = $modelName;
        $this->modelClass = $this->resolveModelClass($modelName);
        $this->fields = $fields;
        $this->modalTitle = $modalTitle;
        $this->loadItems();
    }

    private function resolveModelClass($modelName)
    {
        $modelClass = "App\\Models\\" . str_replace('/', '\\', $modelName);
        if (!class_exists($modelClass)) {
            throw new \Exception("Model class {$modelClass} does not exist.");
        }
        return $modelClass;
    }

    public function loadItems()
    {
        $this->items = $this->modelClass::all()->toArray();
    }

    public function toggleItemStatus($itemId)
    {
        $item = $this->modelClass::findOrFail($itemId);
        $newStatus = $item->status === 'completed' ? 'pending' : 'completed';
        $item->update([
            'status' => $newStatus,
            'completed_at' => $newStatus === 'completed' ? now() : null,
        ]);
        $this->loadItems();
        $message = $newStatus === 'completed' ? 'Item marked as completed successfully.' : 'Item marked as pending successfully.';
        $this->dispatch('flashMessage', ['message' => $message]);
    }

    public function openCreateItemModal()
    {
        $this->resetAttributes();
        $this->setDefaultValues();
        $this->editItemId = null;
        $this->showModal = true;
    }

    public function openEditItemModal($itemId)
    {
        $item = $this->modelClass::findOrFail($itemId);
        $this->fill($item->toArray());
        $this->editItemId = $itemId;
        $this->showModal = true;
    }

    public function openDeleteItemModal($itemId)
    {
        $this->deleteItemId = $itemId;
        $this->dispatch('open-modal', 'delete-item-modal');
    }

    public function createOrUpdateItem()
    {
        try {
            $validatedData = $this->validate($this->getValidationRules());

            // Remove empty strings for nullable fields
            $validatedData = array_map(function ($value) {
                return $value === '' ? null : $value;
            }, $validatedData);

            if ($this->editItemId) {
                $item = $this->modelClass::findOrFail($this->editItemId);
                $item->update($validatedData);
                $message = 'Item updated successfully.';
            } else {
                $this->modelClass::create($validatedData);
                $message = 'Item created successfully.';
            }

            $this->loadItems();
            $this->resetAttributes();
            $this->editItemId = null;
            $this->dispatch('flashMessage', message: $message);
            $this->showModal = false;
        } catch (ValidationException $e) {
            $this->dispatch('flashMessage', message: 'Validation failed. Please check the form for errors.', type: 'error');
            $this->setErrorBag($e->validator->getMessageBag());
        } catch (\Exception $e) {
            $this->dispatch('flashMessage', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteItem()
    {
        try {
            $item = $this->modelClass::findOrFail($this->deleteItemId);
            $item->delete();
            $this->loadItems();
            $this->dispatch('flashMessage', ['message' => 'Item deleted successfully.']);
            $this->deleteItemId = null;
            $this->dispatch('close-modal', 'delete-item-modal');
        } catch (\Exception $e) {
            $this->dispatch('flashMessage', ['message' => 'An error occurred: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    private function resetAttributes()
    {
        foreach ($this->fields as $field => $details) {
            $this->$field = null;
        }
    }

    private function setDefaultValues()
    {
        foreach ($this->fields as $field => $details) {
            if ($details['type'] === 'select' && isset($details['options'])) {
                $this->$field = array_key_first($details['options']);
            }
        }
    }

    private function getValidationRules()
    {
        $rules = [];
        foreach ($this->fields as $field => $details) {
            if (isset($details['rules'])) {
                $rules[$field] = $details['rules'];
            }
        }
        return $rules;
    }

    public function render()
    {
        return view('livewire.model-crud');
    }
}
