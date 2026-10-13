---
title: Modal com MaryUI
tags:
  - livewire
  - maryui
  - blade
  - ui
projeto: PingDeck
---

## O que é

No MaryUI, `<x-mary-modal wire:model="propriedadeBooleana">` é um modal controlado pelo Livewire: a propriedade booleana define aberto/fechado. Dentro dele cabe um `<x-mary-form wire:submit="metodo">` com inputs (`x-mary-input`, `x-mary-select`, `x-mary-toggle`, `x-mary-textarea`) cujo `wire:model` aponta para um Form Object.

## Quando usar

- CRUD em que o formulário é **secundário** à tela e não vale uma página própria (ex.: criar um item a partir de uma listagem).
- Para formulários longos ou que precisam de URL própria, prefira uma página/componente dedicada.

## Exemplo mínimo

```blade
<x-mary-button label="Criar" wire:click="openCreateModal" />

<x-mary-modal wire:model="creatingApi" title="Create API" separator>
    <x-mary-form wire:submit="createApi" no-separator>
        <x-mary-input label="Name" wire:model="form.name" required />

        <x-slot:actions>
            <x-mary-button label="Cancel" @click="$wire.creatingApi = false" />
            <x-mary-button label="Create" class="btn-primary" type="submit" />
        </x-slot:actions>
    </x-mary-form>
</x-mary-modal>
```

## Uso no componente

```php
public bool $creatingApi = false;

public function openCreateModal(): void
{
    $this->form->reset();
    $this->resetValidation();

    $this->creatingApi = true;
}
```

No sucesso, o componente faz `$this->creatingApi = false;` para fechar o modal.

## Armadilhas

- Erros de validação só aparecem inline se o `wire:model` do input casar com a chave do erro (`form.name`); use o prefixo do Form Object.
- O modal **não** fecha sozinho: feche explicitamente no caminho de sucesso; em erro de validação ele deve continuar aberto.
- O botão de cancelar é Alpine controlando a propriedade Livewire: use `@click="$wire.prop = false"`.

## Relacionado

[[form-objects]] · [[maryui-select]] · [[livewire-componentes]] · [[wire-model-live-debounce]]

## No projeto

`resources/views/livewire/api/index.blade.php` (componente `app/Livewire/Api/Index.php`).
