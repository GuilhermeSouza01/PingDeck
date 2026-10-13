---
title: Action Classes
tags:
  - laravel
  - arquitetura
  - casos-de-uso
projeto: PingDeck
---

## O que é

Uma **Action** é uma classe de responsabilidade única que executa uma operação de domínio ("criar uma API", "cancelar assinatura"). Normalmente expõe um único método `handle()` e é resolvida por injeção de dependência. Não é um service genérico com vários métodos: cada Action faz **uma** coisa.

## Quando usar

- Quando a lógica de escrita tem regras que não cabem no controller/componente.
- Quando a mesma operação é usada em HTTP, jobs, comandos de console e testes.
- Para manter o componente Livewire "magro": ele só orquestra (valida, pega o usuário, delega).

## Exemplo mínimo

```php
namespace App\Actions\Api;

use App\Models\Api;
use App\Models\User;

class CreateApi
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(User $user, array $attributes): Api
    {
        return $user->apis()->create($attributes);
    }
}
```

## Uso no componente

```php
public function createApi(CreateApi $action): void
{
    $this->form->validate();

    $user = Auth::user();

    if (! $user instanceof User) {
        abort(403);
    }

    $action->handle($user, $this->form->attributes());

    $this->creatingApi = false;
    $this->success(__('API created successfully.'));
}
```

O Livewire injeta a dependência automaticamente no método chamado por `wire:submit`/`call`.

## Armadilhas

- Não use `Auth::user()` **dentro** da action: receba o usuário como parâmetro (facilita teste e reuso).
- Nunca deixe o `user_id` vir dos atributos de entrada — derive da relation (`$user->apis()->create()`), que sobrescreve qualquer `user_id` recebido.
- Evite transformar a Action num "god service" com N métodos. Uma operação por classe.

## Relacionado

[[form-objects]] · [[livewire-componentes]] · [[livewire-test]] · [[eloquent-relacionamentos]]

## No projeto

`app/Actions/Api/CreateApi.php`, chamada em `app/Livewire/Api/Index.php`.
