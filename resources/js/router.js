import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

import Activities from "./components/ActivitiesComponent.vue"
//import Quiz from "./components/QuizComponent.vue"

const routes = [
  {
    path: "/",
    name: "Activities",
    component: Activities
  },
  /*{
    path: "/quiz",
    name: "Quiz",
    component: Quiz
  }*/
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