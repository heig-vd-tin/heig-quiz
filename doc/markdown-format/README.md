# How to easily store quizzes on GitHub?

C'est avantageux de pouvoir sauver un ensemble de quiz sous GitHub sans devoir intéragir avec une interface graphique et une base de donnée. Les révision des quiz sont contrôlées, plusieurs contributeurs peuvent travailler en parallèle et le clou (du spectacle), il est facilement possible de voir la différence entre différentes version de quiz ou de questions.

Il est néanmoins nécessaire d'assurer une synchronisation entre le référentiel GitHub et le système de Quiz.

Alternativement, il n'est pas raisonnable de devoir reparser l'entier des quiz en temps réel, une base de donnée s'impose sachant qu'une fois qu'un quiz a été passé par un étudiant, il n'est plus possible de modifier cette version.

## Synchronisation GitHub <-> Quiz

Une table de synchronisation avec GitHub permet de lier des questions écrites en Markdown avec le système de quiz

- id           5474
- blob         77fc36a3dc571a81ebd251a98b158b53c05fdccd
- remote       github.com/heig-tin-info/quizzes
- question_id  32

À chaque évènement de push sur le référentiel Git, un trigger démarre une synchronisation.

1. Tous les `blobs` du `tree` qui n'existent pas dans dans la base
de données sont importés,
2. Les `blobs` qui ont été modifiés sont mis à jour.

## Format Markdown

Les questions sont saisies en Markdown avec une `frontmatter` en `YAML`.

### Champs communs

```yaml
difficulty: ['easy', 'medium', 'hard', 'insane']
type: ['code', 'multiple-choice', 'short-answer, 'fill-in-the-gaps']
activities: # If nothing all activities types are allowed
  - quiz # Eligible for a quiz
  - exercise # Eligible for an exercise (free access for students)
  - exam # Eligible for exams
keywords:
  - keyword1
  - keyword2
  - ...
```

```markdown
# Title

Question...

## Explanation

Only visible once the activity is finished

## Hint

Depending on the activity type a hint can be given to the student
```

### Short Answer

Une unique réponse est demandée à l'étudiant dans un champ texte libre.

```yaml
lines: 1 # Number of lines of the textfield (default 1)
width: 40 # Width in characters (default 40)
validation:
  pattern: regex # Regular expression
  contains: text # The answer must contain it
  trim: true # The answer is trimmed (default true)
```

### Multiple choices

Question à choix multiple. Les titres Markdown `## [A-Z]` sont identifiés comme des propositions possibles et doivent être groupés à la suite après le titre de la question.

```markdown
# Title

Question

## A

Answer A

## B

Answer B
```

La configuration spécifique est la suivante :

```yaml
allow-shuffle: true # Propositions can be shuffled
answers: [A, B] # Multiple answers possible
```

Alternativement il peut y avoir plusieurs couples de réponses:

```yaml
answers:
  - [C, D]
  - [A, B]
```

### Fill In The Gaps

L'étudiant est invité soit à saisir des réponses dans le texte via menu déroulant, soit à entrer du texte directement.

```markdown
# Title

A `arrow` is denoted as a composition in `uml`.
```

La configuration est la suivante :

```yaml
validation:
  - diamond plain
  - UML
gaps:
  arrow:
    - plain arrow
    - diamond plain
    - diamond transparant
  uml:
    - SOLID
    - UML
    - BPMN
```

### Code

L'utilisateur est invité à saisir du code dans une fenêtre. Il dispose de boutons additionnels : `build`, `build & execute`

````markdown

Question followed by a unique code block

```c
#include <print.h>

void main() {
    printk('hello, world!')
}
```
````

La configuration :

```yaml
language: c # Default is c
allow-execute: true # Allow the student to execute his code
allow-build: true # Allow the student to build the code and see if there is any warning
validation:
  stdout:
    pattern:
    contains:
  stderr:
  exit-status:
args:
  - -n
  - 42
stdin: Hello
```
