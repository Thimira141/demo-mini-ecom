<?php

use Livewire\Component;

new class extends Component {
    public array $selected = [];

    /**
     * dispatch event
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function updatedSelected()
    {
        $this->dispatch('update-category-filter', selected: $this->selected);
    }
};
?>

<div>
    <x-checkbox.group wire:model.live="selected" :options="\App\Models\Category::all(['id', 'name'])" select="label:name|value:id" />
</div>
