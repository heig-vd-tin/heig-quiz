# What to do?

- [x] Tables
- [x] Migrations
- [x] Seeds
- [x] Factories
- [x] Models
- [x] Base API
- [x] Base Controller to get data
- [x] Get student questions in order
- [x] Actions
  - [x] Show/Hide Activity
  - [x] Start/Stop Activity
  - [x] Create Activity
  - [x] Answer Question
- [x] Setup Event System (with Pusher?)
- [x] View Home Teacher
  - [x] Show Rosters Tabs
  - [x] Create Activity from Quiz
- [x] View Home Student
- [x] View Question
  - [x] Short Answer
  - [x] Multiple Choices
- [x] Redis/Pusher Events
  - [x] New activity  
  - [x] Activity started
- [x] laravel-websockets to use on deployment
- [x] Generate api_token on Shibboleth auth
- [x] Deploy on chevallier.io
- [ ] View Correction
  - [ ] Display answered field and correction
    - [ ] For Short Answer
      - [ ] Validation bad on the fields with correction in red under
    - [ ] For Code
      - [ ] Additional textfield with syntax coloring and answer
    - [ ] For fill-in-the-gaps
      - [ ] Validation bad on the fields with correction in red under
    - [ ] For Multiple choices
      - [ ] Highlight good answer
      - [ ] Color current answer green or red
- [ ] Redis/Pusher Events
  - [ ] Activity finished
  - [ ] New answer given
- [ ] View Progress 
  - [ ] Listen to notifications
  - [ ] Two sliders : show names, show answers
- [ ] Questions display
  - [ ] Types
    - [ ] Fill-in-the-gaps
    - [ ] ShortAnswer
    - [ ] Code
- [ ] Dynamic Breadcrumb
- [ ] New Question form
  - [ ] Parse markdown extract frontmatter
  - [ ] Validate frontmatter
  - [ ] Button to insert scaffolding for different questions
  - [ ] Preview question in realtime
- [ ] New Quiz form
  - [ ] Select questions from list add to a new Quiz
- [ ] Copy tables questions, quiz... 
- [ ] Git Sync
  - [ ] Add table sync
- [ ] Deployment
  - [ ] On chevallier.io
  - [ ] Register new Shibboleth service
  - [ ] Get VM and DNS from IT

## Open questions

- [ ] Singular or Plural for API routes?
- [ ] Student can pass questions?

## New features

- [ ] Activity Type (quiz, exercise, exam)
- [ ] Code Question Type
- [ ] Give Bundle Hash to Student
- [ ] Question pool (table of all questions)
- [ ] Create quiz from questions (difficulty, keywords, number of questions, duration)
- [ ] Force Stop Quiz (add stopped_at in activities)
- [ ] Limited time per question, the question is seen once by the student
- [ ] Estimated time for question to be answered, get an estimated quiz time

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
