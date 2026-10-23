<?php
// search bar component
use Livewire\Component;

new class extends Component {
    public string $term = '';

    /**
     * dispatch event
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function updatedTerm(): void
    {
        $this->dispatch('update-search', term: $this->term);
    }
};
?>

<div>
    <x-input icon="magnifying-glass" placeholder="Search" wire:model.live.debounce.250ms="term" />
</div>
