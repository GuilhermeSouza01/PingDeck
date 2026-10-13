# Create API — notas de aprendizagem (Livewire)

Diário dos passos para implementar o fluxo "Create API" no PingDeck, com o conceito
por trás de cada decisão. Cada passo corresponde a um commit.

## Passo 1: Aumentar o limite de memória do PHPStan

**Conceito:** PHPStan é uma ferramenta de análise estática: ela lê o código sem
executá-lo e tenta provar que os tipos batem. Para isso ele mantém toda a árvore
de tipos na memória. Por padrão, muitos `php.ini` têm `memory_limit=128M`, e a
análise em paralelo pode estourar esse limite e "crashar" o processo antes de
reportar qualquer erro. `--memory-limit=-1` diz ao PHPStan para ignorar o limite
(sem limite) só durante a análise.

**O que mudou:**
- `composer.json` — o script `types:check` passou de `phpstan analyse` para
  `phpstan analyse --memory-limit=-1`. É o script que o `composer test` e o CI
  executam, então agora a checagem roda de forma determinística.

**Por que assim:** colocar a flag no script do Composer (em vez de só rodar
manualmente) garante que todo mundo — você, o `composer test` e o GitHub Actions —
use a mesma configuração. A alternativa descartada foi editar o `php.ini` da
máquina: isso resolveria só localmente e não viajaria com o repositório.

**Armadilha comum:** subir o `memory_limit` globalmente no `php.ini` e achar que
resolveu. Aí o CI (ou outro dev) continua quebrando, porque a configuração não
está versionada. A flag no script do Composer é explícita e portátil.

**Pergunta de entrevista:** "O que PHPStan/Larastan faz e por que ele pode estourar
a memória?" Resposta: é análise estática em nível de tipos rodando em paralelo
sobre todo o diretório `app/`; cada worker carrega a AST e os tipos, então projetos
maiores usam mais memória. Aumentar o limite (ou dividir em baseline/ignores)
evita o crash sem desligar a checagem.

## Passo 2: O Livewire Form object (`ApiForm`)

**Conceito:** um "Form object" no Livewire é uma classe que agrupa os campos de um
formulário num único objeto, em vez de espalhar várias propriedades públicas pelo
componente. Ele guarda os valores (`$form->name`), as regras (`rules()`) e valida a
si mesmo (`$form->validate()`). O componente apenas declara `public ApiForm $form;`.

**O que mudou:**
- `app/Livewire/Forms/ApiForm.php` — nova classe `ApiForm extends Livewire\Form`
  com as 7 propriedades tipadas (`name`, `url`, `method`, `expected_status`,
  `timeout`, `description`, `is_active`), o método `rules()` (required/url/enum e
  intervalos coerentes com as colunas da migration) e `attributes()` que devolve só
  os campos do modelo — **sem `user_id`**.

**Por que assim:** um objeto dedicado mantém o componente enxuto e reaproveitável
(dá para reusar o mesmo `ApiForm` numa tela de edição futura). A alternativa
descartada foi declarar as 7 propriedades e um array `$rules` direto no `Index`:
isso incha o componente e duplica regras quando surgir o formulário de edição.

**Armadilha comum:** esquecer que, no Form object, as chaves de `rules()` **não**
levam prefixo (`'name'`, e não `'form.name'`) — o Livewire prefixa automaticamente
na hora de exibir os erros como `form.name`. E nunca colocar `user_id` no form:
se estivesse lá, viraria um campo que o usuário poderia manipular.

**Pergunta de entrevista:** "Por que usar um Form object em vez de propriedades
soltas no componente Livewire?" Resposta: encapsulamento e reuso — dados, regras e
validação ficam juntos e testáveis isoladamente; o componente vira um coordenador
e evita duplicar regras entre criar e editar.

## Passo 3: A action `CreateApi` (e a relação `User::apis()`)

**Conceito:** uma "action" é uma classe com uma única responsabilidade: executar um
caso de uso (aqui, "criar um Api"). Em vez de o componente Livewire montar o
`Api::create([...])` na mão, ele delega para `CreateApi`, que recebe o `User` e os
atributos validados. Usamos a relação Eloquent `$user->apis()->create($attributes)`:
chamar `create` numa relação HasMany preenche sozinho a foreign key (`user_id`) a
partir do usuário-pai.

**O que mudou:**
- `app/Actions/Api/CreateApi.php` — método `handle(User $user, array $attributes): Api`
  que salva via `$user->apis()->create(...)`.
- `app/Models/User.php` — nova relação `apis(): HasMany` (não existia) com generics
  para o PHPStan.

**Por que assim:** receber o `User` explicitamente faz o `user_id` nunca vir do
request e deixa a action testável sem depender do facade `Auth`. A alternativa
descartada era `Api::create([...$data, 'user_id' => auth()->id()])` dentro do
componente: mistura HTTP com persistência, dificulta o teste e deixa o `user_id`
no mesmo array que os dados do usuário (mais fácil vazar um override por engano).

**Armadilha comum:** achar que basta esconder o `user_id` do formulário. A defesa
real é o código não ler `user_id` de input em lugar nenhum — e a relação HasMany
ainda sobrescreve qualquer `user_id` que viesse nos atributos (segunda camada).

**Pergunta de entrevista:** "Como garantir que um registro pertence ao usuário
certo?" Resposta: nunca confiando em `user_id` vindo do request; derivando da
identidade autenticada (`$user->apis()->create(...)` ou `auth()->id()`) e, para
leitura, usando policy/global scope.
