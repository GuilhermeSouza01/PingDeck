---
title: Policies (Laravel)
tags:
  - laravel
  - autorizacao
  - seguranca
projeto: PingDeck
---

## O que é

Uma **Policy** é uma classe que concentra as regras de autorização de um model. Cada método recebe o usuário (e o recurso) e devolve `true`/`false`. O Laravel descobre a policy por convenção: `App\Models\Api` → `App\Policies\ApiPolicy`, sem precisar registrar.

## Quando usar

- Quando a autorização depende do **recurso específico** (ex.: "esse usuário é dono desta API?").
- Para acesso a um registro único (show/update/delete). Listagens costumam ser resolvidas por escopo de query (ver [[tenant-scoping]]).

## Exemplo mínimo

```php
namespace App\Policies;

use App\Models\Api;
use App\Models\User;

class ApiPolicy
{
    public function view(User $user, Api $api): bool
    {
        return (int) $api->user_id === (int) $user->id;
    }
}
```

## Uso no componente

```php
use Illuminate\Support\Facades\Gate;

public function mount(Api $api): void
{
    Gate::authorize('view', $api);

    $this->api = $api;
}
```

Se negado, `Gate::authorize` lança `AuthorizationException` → HTTP 403. Em Laravel 11+ a policy **não precisa ser registrada** (auto-discovery por convenção de nome).

## Armadilhas

- Fugir da convenção de nome/local (ex.: `MyApiPolicy` para `Api`) faz o auto-discovery falhar.
- Guest é barrado antes (middleware `auth`); métodos `view(User $user, ...)` assumem usuário autenticado.
- Policy resolve o **indivíduo**; ela não filtra o **coletivo** — a listagem precisa de escopo de query.
- 403 revela que o recurso existe; para ocultar, use 404 (ex.: escopando a busca ao usuário).

## Relacionado

[[tenant-scoping]] · [[route-model-binding]] · [[action-classes]] · [[livewire-componentes]]

## No projeto

`app/Policies/ApiPolicy.php`, autorizada em `app/Livewire/Api/Show.php`.
