import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

import Activities from "./components/Activities"
import NewQuestion from "./components/NewQuestion"
import Progress from "./components/Progress"
import Questions from "./components/Questions"
import Quiz from "./components/Quiz"
import Quizzes from "./components/Quizzes"
import Results from "./components/Results"
import Sandbox from "./components/sandbox/Sandbox"

const routes = [
  {
    path: "/quiz/home",
    name: "home",
    component: Activities
  },
  {
    path: "/quiz/activities",
    name: "activities",
    component: Activities
  },
  {
    path: "/quiz/quizzes",
    name: "quizzes",
    component: Quizzes
  },
  {
    path: "/quiz/questions",
    name: "questions",
    component: Questions
  },
  {
    path: "/quiz/activities/:activity_id/results",
    name: "results",
    component: Results,
    props: route => ({
      activity_id: parseInt(route.params.activity_id)
    })
  },
  {
    path: "/quiz/activities/:activity_id/questions/:question_id",
    name: "quiz",
    component: Quiz,
    props: route => ({
      activity_id: parseInt(route.params.activity_id),
      question_id: parseInt(route.params.question_id)
    })
  },
  {
    path: "/quiz/activities/:activity_id/progression",
    name: "progression",
    component: Progress,
    props: route => ({
      activity_id: parseInt(route.params.activity_id)
    })
  },
  {
    path: "/quiz/questions/create",
    name: "new-question",
    component: NewQuestion
  },
  {
    path: "/quiz/sandbox",
    name: "sandbox",
    component: Sandbox
  }
];

const router = new VueRouter({
  history: true,
  base: "/",
  mode: "history",
  routes
});

router.beforeResolve((to, from, next) => {
  next();
});

router.afterEach((to, from) => {
});

export default router;
