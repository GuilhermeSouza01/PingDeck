---
title: Escopo por tenant (query via relação)
tags:
  - laravel
  - eloquent
  - seguranca
  - multitenancy
projeto: PingDeck
---

## O que é

Escopar por tenant é garantir que uma consulta só enxergue os registros do **dono** (aqui, o usuário autenticado). No PingDeck, em vez de `Api::query()`, usamos a relação do usuário: `$user->apis()`. A relação já injeta o `where('user_id', ...)`, então o filtro de dono vira parte da query.

## Quando usar

- Toda listagem ou busca que deve retornar apenas dados do usuário logado.
- Junto de uma **Policy** para acesso a um registro único (ver [[policy]]): a query resolve o *coletivo*, a policy resolve o *individual*.

## Exemplo mínimo

```php
public function render(): View
{
    return view('livewire.api.index', [
        'apis' => $this->user()->apis()->paginate(5),
    ]);
}

private function user(): User
{
    $user = Auth::user();

    if (! $user instanceof User) {
        abort(403);
    }

    return $user;
}
```

## Uso no componente

No `App\Livewire\Api\Index`, `render()` monta a listagem a partir de `$this->user()->apis()` (com busca/filtros e `with('latestCheck')` encadeados), e `createApi()` usa o mesmo helper `user()`.

## Armadilhas

- Usar `Api::query()`/`Api::find()` "cru" vaza dados de outros usuários. Parta sempre da relação do dono.
- Nunca aceitar `user_id` da entrada: derive do autenticado (ver [[action-classes]]).
- `Auth::user()` pode ser `null`; cheque o tipo antes de usar (o helper centraliza isso).
- Escopo de query **não** substitui autorização em ações sobre um registro específico — para isso, use uma Policy.

## Relacionado

[[policy]] · [[action-classes]] · [[eloquent-relacionamentos]] · [[livewire-pagination]]

## No projeto

`app/Livewire/Api/Index.php`.
