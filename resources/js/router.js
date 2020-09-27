import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

import Activities from "./components/Activities"
import Quizzes from "./components/Quizzes"
import Results from "./components/Results"
import Quiz from "./components/Quiz"

import Progress from "./components/ProgressComponent"
import Sandbox from "./components/Sandbox"

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
    path: "/quiz/results",
    name: "results",
    component: Results
  },
  {
    path: "/quiz/questions/:id",
    name: "quiz",
    component: Quiz,
    props: true
  },
  {
    path: "/quiz/progress",
    name: "progress",
    component: Progress
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
