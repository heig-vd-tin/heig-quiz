# What to do?

- [ ] Teacher's correction
  - [ ] Show statistics for the whole class
  - [ ] Do not show good/bad

- [.] New Question form
  - [x] Parse markdown extract frontmatter
  - [.] Preview question in realtime
  - [ ] Validate frontmatter
  - [ ] Button to insert default scaffolding for different questions
- [ ] New Quiz form
  - [ ] Select questions from list add to a new Quiz
- [ ] Validation 
  - [ ] Markdown before SQL
  - [ ] JSON before SQL
- [ ] Code question
- [ ] Server event to finish the activity
- [ ] Bugs
  - [ ] Result 0 is false ?
- [ ] Anestetics
  - [ ] Color count down if < 30 seconds
  - [ ] Footer HEIG-VD (with logos)
  - [ ] Dynamic Breadcrumb
- [ ] Deployment
  - [ ] Register new Shibboleth service
  - [ ] Get VM and DNS from IT

## Open questions

- [ ] Singular or Plural for API routes?
- [ ] Student can pass questions?

## Refactoring

- [ ] Dynamic navigation bar
- [ ] Emit events from models
- [ ] Rename Keywords -> Tags
- [ ] Global frontend storage 
  - [ ] Register vueex or whatever needed
  - [ ] Create interface to get (activities, ... ...)
- [ ] Global Channels manager
- [ ] Add tests
  
## New features

- [ ] Activity Type (quiz, exercise, exam)
- [ ] Code Question Type
- [ ] Give Bundle Hash to Student
- [ ] Question pool (table of all questions)
- [ ] Create quiz from questions (difficulty, keywords, number of questions, duration)
- [ ] Force Stop Quiz (add stopped_at in activities)
- [ ] Limited time per question, the question is seen once by the student
- [ ] Estimated time for question to be answered, get an estimated quiz time
- [ ] Separate activities from questions pool
- [ ] Git Sync

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
