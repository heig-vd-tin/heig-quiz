# What to do?

- [x] Tables
- [x] Migrations
- [x] Seeds
- [x] Factories
- [x] Models
- [x] Base API
- [x] Base Controller to get data
- [ ] Get student questions in order
- [.] Actions
  - [x] Show/Hide Activity
  - [x] Start/Stop Activity
  - [x] Create Activity
  - [x] Answer Question
- [x] Setup Event System (with Pusher?)
- [ ] View Home Teacher
  - [x] Show Rosters Tabs
  - [ ] Create Activity from Quiz
- [ ] View Home Student
- [ ] View Progress
- [x] View Question
  - [x] Short Answer
  - [ ] Fill-In-The-Gaps
  - [x] Multiple Choices
- [x] Redis/Pusher Events
  - [ ] New activity
  - [ ] Activity finished
  - [ ] Activity started
  - [ ] New answer given
- [ ] View Correction
- [ ] Git Sync
- [x] laravel-websockets to use on deployment
- [x] Generate api_token on Shibboleth auth
- [x] Deploy on chevallier.io

- [ ] Dynamic Breadcrumb Home > Quiz 23

## Open questions

- [ ] Singular or Plural for api routes?
- [ ] API request message for JSON output -> Exception JSON?
- [ ] Student can pass questions?
- [ ] Question pool?

## New features

- [ ] Activity Type (quiz, exercise, exam)
- [ ] Code Question Type
- [ ] Give Bundle Hash to Student
- [ ] Question pool (table of all questions)
- [ ] Create quiz from questions (difficulty, keywords, number of questions, duration)
- [ ] Force Stop Quiz (add stopped_at in activities)
- [ ] Limited time per question, the question is seen once by the student

## Discussions

### Events

A channel per entity:

- activity
  - ActivityCreated
- activity.{id}
  - ActivityStarted
  - ActivityEnded
  - ActivityJoined
  - QuestionAnswered
  - QuizFinished
- roster
  - NewActivitiesAvailable
- quiz
  - QuizCreated
