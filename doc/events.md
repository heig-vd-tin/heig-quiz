# Events and channel managements

## Channels

```yaml
- application:
  - UserLogin: Someone has logged in
  - UserLogout: Someone has logged out
- activity:
  - ActivityCreated: New activity created
  - ActivityStarted: An activity has started
  - ActivityStopped: An activity has stopped
  - activity.{id}:
    - QuestionAnswered: A question has been answered
    - QuizFinished: Someone finished his quiz
    - ActivityStopped:
    - ActivityStarted:
- quiz:
```

