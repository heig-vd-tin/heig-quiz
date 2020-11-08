# What to do?

- [ ] Centralize join/leave channels
- [ ] Popover on activity to see who's there, who's missing
- [ ] Connected status on realtime results (circle green/yellow/red)
- [ ] Remove all Axios but in store
- [ ] Show Quiz result (chart on popper on result button) and avg score
- [ ] Quick access question number (question 1..10 button)
- [ ] Do not show good/bad on teacher's correction
- [ ] Code type question
- [ ] Generate new certificates for quiz.chevallier.io
- [ ] Migrate to quiz.chevallier.io, migrate repository to heig-vd-tin
- [ ] Migrate to Vue 3.0
- [ ] Separate question type :
  - [ ] PHP Validator file
  - [ ] Vue file
  - [ ] Schema file
- [ ] New Quiz form
  - [ ] Select questions from list add to a new Quiz
- [ ] Validation 
  - [ ] Markdown before SQL
  - [ ] JSON before SQL
- [ ] Code question
- [ ] Server event to finish the activity
- [ ] Bugs
  - [ ] Result 0 is false ?
- [ ] Deployment
  - [ ] Register new Shibboleth service
  - [ ] Get VM and DNS from IT
- [ ] "Passer" button
- [.] New Question form
  - [.] Preview question in realtime
  - [ ] Validate frontmatter
  - [ ] Button to insert default scaffolding for different questions
- [ ] Once everybody has finished a quiz : the result is showed (stop waiting)
  
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
