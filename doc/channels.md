# Qu'est-ce que chaque vue à besoin ?

```yaml
- Home:
  - states:
    roster_id: selected from the top tabs
    user_id: connected user, sent from the backend, injected as a blade directive into Vue
  - data-sources:
    activities: list of activities
    quizzes: list of quizzes
- Quiz:
  - states:
    user_id: connected user
    activity_id: which activity is concerned
    current_question_id: which is the current question
    questions: raw data got from the backend
    state:
        - waiting: Quiz hasn't started yet. Waiting room.
        - running: Quiz is running.
        - pause: Quiz is in pause.
        - finished: Quiz is finished.
```

## Evènements et Channels

```yaml
channels:
  activity.{id}: # Use a presence channel !
    StudentJoinedActivity(student_id): A new student joined the activity
    StudentLeftActivity(student_id): A student left the activity
    ActivityStarted(activity_id):
    ActivityPaused(activity_id):
    ActivityStopped(activity_id): Someone's stopped the activity
    ActivityFinished(activity_id): End of time, the activity finished
    QuestionAnswered(question_id): Someone gave a new answer to a question
  student.{id}:
    ActivityCreated(activity_id):
  user.{id}:
    UserJoined(user_id):
    UserLeaved(user_id):
    RosterSelected(user_id):
    ActivityCreated(activity_id):
    QuestionAdded(question_id, user_id):
    QuizAdded(quiz_id, user_id):
```