# Activités

Une activité représente un évènement associé à un **quiz** auquel peuvent participer les étudiants faisant partie de la classe à laquelle l'activité est liée.

## Status

Une activité peut être dans plusieurs états :

`idle` 
    L'activité (nouvellement crée) est en attente. Elle n'est pas visible par les étudiants.

`opened`
    L'activité est ouverte à la participation. Elle apparaît chez les étudiants qui peuvent alors joindre l'activité

`started`
    L'activité a démarré les étudiants répondent aux questions, le compte à rebourd a démarré

`finished`
    L'activité s'est terminée. Les étudiants ne peuvent plus répondre aux questions mais peuvent consulter leurs résultats et la correction du quiz.

`hidden`
    Une activité terminée peut-être cachée aux étudiants. 

## Actions

Les actions possibles sur les activités sont : 

- `create` Crée une nouvelle activité ;
- `open` Si l'activité est `idle` ;
- `start` Si l'activité est ouverte ;
- `stop` Si l'activité est en cours ;
- `hide` Si l'activité est terminée ;
- `show` Si l'activité est cachée ;
- `delete` Uniquement si l'activité est `idle`.

