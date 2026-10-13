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
