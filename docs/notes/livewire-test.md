---
title: Livewire::test (Testes de componente)
tags:
  - livewire
  - testes
  - phpunit
projeto: PingDeck
---

## O que é

`Livewire::test(Component::class)` sobe o componente **em memória** (sem navegador) e devolve um `Testable` com helpers: `set()` (dispara os hooks `updated*`), `call()` (invoca métodos públicos), `assertSee`, `assertSet`, `assertHasErrors`, `assertHasNoErrors`, `assertRedirect`. Roda dentro de um teste PHPUnit com `RefreshDatabase`.

## Quando usar

- Para testar comportamento de um componente Livewire: validação, mudança de estado, efeitos (banco, redirect).
- Em conjunto com testes HTTP (`$this->get(...)`) para rota/middleware.

## Exemplo mínimo

```php
public function test_api_can_be_created(): void
{
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('openCreateModal')
        ->set('form.name', 'Stripe API')
        ->set('form.url', 'https://api.stripe.com/health')
        ->call('createApi')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('apis', [
        'name' => 'Stripe API',
        'user_id' => $user->id,
    ]);
}
```

## Uso no componente

`tests/Feature/Api/IndexTest.php` cobre criação, validação, busca/filtros, paginação e ownership do `App\Livewire\Api\Index`.

## Armadilhas

- `set()` dispara os hooks `updated*` — útil para testar filtros reais, mas pode causar renders inesperados.
- Erros de Form Object têm prefixo: use `assertHasErrors('form.name')`.
- Continua sendo necessário `RefreshDatabase` para o banco nascer limpo a cada teste.
- Prefira asserts sobre o **comportamento observável** (estado, banco) a detalhes internos.

## Relacionado

[[form-objects]] · [[action-classes]] · [[livewire-componentes]] · [[phpunit-testes]]

## No projeto

`tests/Feature/Api/IndexTest.php`.
