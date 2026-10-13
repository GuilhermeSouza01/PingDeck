---
title: Route Model Binding
tags:
  - laravel
  - rotas
  - eloquent
projeto: PingDeck
---

## O que é

Route model binding converte um parâmetro da rota em um model Eloquent automaticamente. Se o parâmetro se chama `{api}` e o argumento é tipado como `Api`, o Laravel resolve `Api::where('id', ...)->firstOrFail()` e injeta. Se não achar, devolve **404**.

## Quando usar

- Sempre que uma rota recebe o id (ou slug) de um recurso e você precisa do model já carregado.

## Exemplo mínimo

```php
// routes/web.php
Route::get('/apis/{api}', Show::class)->name('apis.show');
```

```php
public function mount(Api $api): void
{
    // $api já é o model carregado (ou 404)
    $this->api = $api;
}
```

## Uso no componente

No full-page component `App\Livewire\Api\Show`, `mount(Api $api)` recebe o model via binding implícito; em seguida autorizamos com a policy. O nome do parâmetro na rota (`api`) precisa casar com o nome do argumento.

## Armadilhas

- Nome do parâmetro diferente do argumento (rota `{id}` com `mount(Api $api)`) — o binding **não** acontece e um model vazio pode ser injetado. Os nomes têm que casar.
- O binding busca pelo route key (padrão `id`); para slug, sobrescreva `getRouteKeyName()`.
- Binding resolve o model, mas **não** autoriza: combine com [[policy]].

## Relacionado

[[policy]] · [[tenant-scoping]] · [[livewire-componentes]]

## No projeto

`routes/web.php` e `app/Livewire/Api/Show.php`.
