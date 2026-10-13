---
title: wire:model.live e debounce
tags:
  - livewire
  - blade
  - reatividade
projeto: PingDeck
---

## O que é

`wire:model` é o binding entre um input e uma propriedade Livewire. Por padrão é **deferred**: o valor só vai ao servidor no próximo request (ex.: submit). O modificador `.live` envia a cada evento; `.debounce.300ms` espera o usuário parar por 300ms antes de enviar — evitando um request por tecla.

## Quando usar

- `.live.debounce.Xms` para **busca** e **filtros** reativos.
- `wire:model` puro (deferred) para formulários em que só o submit importa.

## Exemplo mínimo

```blade
<x-mary-input
    placeholder="Search..."
    wire:model.live.debounce.300ms="search"
    clearable
/>
```

```php
public string $search = '';

public function updatedSearch(): void
{
    $this->resetPage();
}
```

## Uso no componente

No `Index`, a toolbar usa `wire:model.live.debounce.300ms="search"` e `wire:model.live` nos `<x-mary-select>` de método/status; cada hook `updated*()` reseta a paginação.

## Armadilhas

- Abusar de `.live` em todos os campos: cada mudança vira um request. Use só onde há reatividade real.
- Sem `debounce`, digitar "stripe" dispara um request por tecla.
- Em `<select>`, `wire:model.live` é aceitável (eventos raros); `.live.debounce` também funciona.

## Relacionado

[[livewire-componentes]] · [[livewire-pagination]] · [[maryui-select]]

## No projeto

`resources/views/livewire/api/index.blade.php` e `app/Livewire/Api/Index.php`.
