---
title: Paginação no Livewire (resetPage)
tags:
  - livewire
  - paginacao
  - eloquent
projeto: PingDeck
---

## O que é

O trait `Livewire\WithPagination` integra o `paginate()` do Eloquent com o Livewire. O estado da página vive em `$this->paginators` e `resetPage()` volta para a página 1. Métodos como `gotoPage`, `nextPage` e `previousPage` ficam disponíveis para a view.

## Quando usar

- Listagens paginadas que também têm busca/filtros: sempre que o conjunto de resultados muda, volte para a página 1.

## Exemplo mínimo

```php
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('livewire.api.index', [
            'apis' => Api::query()->paginate(5),
        ]);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
}
```

```blade
<x-mary-table :rows="$apis" with-pagination />
```

## Uso no componente

No `Index`, `resetPage()` é chamado nos hooks `updatedSearch`, `updatedStatus` e `updatedMethod`, e também após criar uma API.

## Armadilhas

- Filtrar **depois** do `paginate()` (na coleção) quebra a contagem e mostra resultados errados. Monte o filtro na query.
- Esquecer `resetPage()`: o usuário pode ficar numa página inexistente (ex.: estava na 4 e o filtro deixa 1 página).
- `with-pagination` (MaryUI) exige que `$apis` seja um paginator, não uma collection.

## Relacionado

[[wire-model-live-debounce]] · [[maryui-table]] · [[livewire-componentes]] · [[eloquent-query-builder]]

## No projeto

`app/Livewire/Api/Index.php`.
