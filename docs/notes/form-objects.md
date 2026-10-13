---
title: Form Objects (Livewire)
tags:
  - livewire
  - laravel
  - formularios
projeto: PingDeck
---

## O que é

`Livewire\Form` é uma classe que agrupa o **estado** de um formulário e as **regras de validação**, tirando esse peso de dentro do componente. Você a declara como propriedade pública (`public ApiForm $form;`) e acessa os campos como `$form->name`. Nos inputs, o binding usa o prefixo do objeto: `wire:model="form.name"`.

## Quando usar

- Formulários com vários campos (>~4) ou que reaparecem em mais de um componente (create/update).
- Quando você quer mover `rules()`/`attributes()` do componente para uma classe testável e reutilizável.

Para 1–2 campos simples, propriedades soltas no componente já bastam.

## Exemplo mínimo

```php
namespace App\Livewire\Forms;

use Livewire\Form;

class ApiForm extends Form
{
    public string $name = '';

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:255']];
    }
}
```

## Uso no componente

```php
use App\Livewire\Forms\ApiForm;

class Index extends Component
{
    public ApiForm $form;

    public function createApi(CreateApi $action): void
    {
        $this->form->validate();

        // ...
        $action->handle($user, $this->form->attributes());
    }
}
```

```blade
<x-mary-input label="Name" wire:model="form.name" required />
```

## Armadilhas

- A propriedade precisa ser `public`, senão o Livewire não hidrata o objeto.
- O `wire:model` precisa do prefixo (`form.name`); sem ele o campo não é bindado.
- Toda propriedade do Form deve ter **valor default** para o `reset()` funcionar corretamente.
- As chaves de `rules()` são sem prefixo (`name`), mas o erro exposto ao front tem prefixo (`form.name`).

## Relacionado

[[maryui-modal]] · [[action-classes]] · [[livewire-componentes]] · [[validacao-laravel]]

## No projeto

`app/Livewire/Forms/ApiForm.php`, usado em `app/Livewire/Api/Index.php`.
