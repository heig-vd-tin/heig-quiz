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
import Dashboard from "./layouts/dashboard"

import ActivitiesTeacher from "./components/ActivitiesTeacher"
import ActivitiesStudent from "./components/ActivitiesStudent"

import store from "./store"

const routes = [
  {
    path: "/quiz",
    component: Dashboard,
    children: [
      {
        path: "/quiz/home",
        name: "home",
        meta: {
          title: "Activités",
          icon: 'dice-6',
        },
        component: Activities
      },
      {
        path: "/quiz/activities",
        name: "activities",
        meta: {
          title: "Activités",
          icon: 'dice-6',
          navbar: true,
        },
        component: Activities
      },
      {
        path: "/quiz/quizzes",
        name: "quizzes",
        meta: {
          title: "Quizzes",
          icon: 'trophy',
          navbar: true,
        },
        component: Quizzes
      },
      {
        path: "/quiz/questions",
        name: "questions",
        meta: {
          title: "Questions",
          icon: 'puzzle'
        },
        component: Questions
      },
      {
        path: "/quiz/activities/:activity_id/results",
        name: "results",
        component: Results,
        meta: {
          title: "Résultats",
          icon: 'awards'
        },
        props: route => ({
          activity_id: parseInt(route.params.activity_id)
        })
      },
      {
        path: "/quiz/activities/:activity_id/questions/:question_id",
        name: "quiz",
        component: Quiz,
        meta: { title: "Quiz", icon: 'easel' },
        props: route => ({
          activity_id: parseInt(route.params.activity_id),
          question_id: parseInt(route.params.question_id)
        })
      },
      {
        path: "/quiz/activities/:activity_id/progression",
        name: "progression",
        meta: { title: "Suivi de l'activité", teacher: true },
        component: Progress,
        props: route => ({
          activity_id: parseInt(route.params.activity_id)
        })
      },
      {
        path: "/quiz/questions/create",
        name: "new-question",
        meta: { title: "Nouvelle question", teacher: true },
        component: NewQuestion
      },
      {
        path: "/quiz/sandbox",
        name: "sandbox",
        meta: {
          title: "Bac à sable",
          icon: 'bucket',
          teacher: true,
          navbar: true
        },
        component: () => import(/* webpackChunkName: "sandbox" */ "./components/sandbox/Sandbox")
      },
      {
        path: "/quiz/help",
        name: "help",
        meta: {
          title: "Informations",
          icon: 'question-diamond',
        },
        component: () => import(/* webpackChunkName: "help" */ "./components/Help")
      },
    ]
  },
];

const router = new VueRouter({
  history: true,
  base: "/",
  mode: "history",
  routes
});

router.beforeEach((to, from, next) => {
  next()
})

router.beforeResolve((to, from, next) => {
  next();
});

router.afterEach((to, from) => {
});

export default router;
