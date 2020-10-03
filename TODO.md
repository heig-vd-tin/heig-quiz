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
- [x] Questions display
  - [x] Types
    - [x] Fill-in-the-gaps
    - [x] ShortAnswer
- [x] View Correction
  - [x] Display answered field and correction
    - [x] For Short Answer
      - [x] Validation bad on the fields with correction in red under
    - [x] For fill-in-the-gaps
      - [x] Validation bad on the fields with correction in red under
    - [x] For Multiple choices
      - [x] Highlight good answer
      - [x] Color current answer green or red
- [ ] Quiz
  - [ ] Next / Previous
  - [ ] Submit answer
  - [ ] Color count down if < 30 seconds
  - [ ] Finished panel
  - [ ] Event New Answer Given
- [ ] Teacher's correction
  - [ ] Show statistics for the whole class
  - [ ] Do not show good/bad
- [ ] Server event to finish the activity
- [.] Answer questions
  - [x] Waiting room
  - [x] Display countdown on activities teacher
  - [x] Start Quiz
  - [.] Answer questions
    - [x] Short answer
    - [ ] Multiple-choice
    - [ ] Fill-in-the-gaps
- [ ] Redis/Pusher Events
  - [ ] Activity finished
  - [ ] New answer given
- [ ] View Progress 
  - [ ] Listen to notifications
  - [ ] Two sliders : show names, show answers
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
- [ ] Cross origin
- [ ] Validation 
  - [ ] Markdown before SQL
  - [ ] JSON before SQL
- [ ] Rename Keywords -> Tags
- [ ] Code question
- [ ] Footer HEIG-VD (with logos)

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
