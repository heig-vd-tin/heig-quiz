import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

import Activities from "./components/ActivitiesComponent.vue"
import Quiz from "./components/QuizComponent.vue"
import Progress from "./components/ProgressComponent.vue"

const routes = [
  {
    path: "/",
    name: "Activities",
    component: Activities
  },
  {
    path: "/quiz",
    name: "Quiz",
    component: Quiz
  },
  {
    path: "/progress",
    name: "Progression",
    component: Progress
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
