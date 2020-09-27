import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

import Activities from "./components/Activities"
import Quizzes from "./components/Quizzes"

import Quiz from "./components/QuizComponent"
import Progress from "./components/ProgressComponent"
import Sandbox from "./components/Sandbox"

const routes = [
  {
    path: "/quiz/home",
    name: "Activités",
    component: Activities
  },
  {
    path: "/quiz/activities",
    name: "Activités",
    component: Activities
  },
  {
    path: "/quiz/quizzes",
    name: "Quizzes",
    component: Quizzes
  },
  {
    path: "/quiz/questions/:id",
    name: "Quiz",
    component: Quiz,
    props: true
  },
  {
    path: "/quiz/progress",
    name: "Progression",
    component: Progress
  },
  {
    path: "/quiz/sandbox",
    name: "Sandbox",
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
