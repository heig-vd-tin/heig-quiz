import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

import Activities from "./components/ActivitiesComponent.vue"
import Quiz from "./components/QuizComponent.vue"
import Progress from "./components/ProgressComponent.vue"
import Sandbox from "./components/Sandbox.vue"

const routes = [
  {
    path: "/quiz/home",
    name: "Activities",
    component: Activities
  },
  {
    path: "/quiz/questions",
    name: "Quiz",
    component: Quiz
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
