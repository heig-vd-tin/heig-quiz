import Vue from "vue"
import VueRouter from "vue-router"

import Activities from "./components/Activities"
import NewQuestion from "./components/NewQuestion"
import Progress from "./components/Progress"
import Questions from "./components/Questions"
import Quiz from "./components/Quiz"
import Quizzes from "./components/Quizzes"
import Results from "./components/Results"
import Dashboard from "./layouts/dashboard"

const routes = [
  {
    path: "/",
    component: Dashboard,
    children: [
      {
        path: "/",
        name: "home",
        meta: {
          title: "Activités",
          icon: 'dice-6',
        },
        component: Activities
      },
      {
        path: "/activities",
        name: "activities",
        meta: {
          title: "Activités",
          icon: 'dice-6',
          navbar: true,
        },
        component: Activities
      },
      {
        path: "/quizzes",
        name: "quizzes",
        meta: {
          title: "Quizzes",
          icon: 'trophy',
          navbar: true,
        },
        component: Quizzes
      },
      {
        path: "/questions",
        name: "questions",
        meta: {
          title: "Questions",
          icon: 'puzzle'
        },
        component: Questions
      },
      {
        path: "/activities/:activity_id/results",
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
        path: "/activities/:activity_id/questions/:question_id",
        name: "quiz",
        component: Quiz,
        meta: { title: "Quiz", icon: 'easel' },
        props: route => ({
          activity_id: parseInt(route.params.activity_id),
          question_id: parseInt(route.params.question_id)
        })
      },
      {
        path: "/activities/:activity_id/progression",
        name: "progression",
        meta: { title: "Suivi de l'activité", teacher: true },
        component: Progress,
        props: route => ({
          activity_id: parseInt(route.params.activity_id)
        })
      },
      {
        path: "/questions/create",
        name: "new-question",
        meta: { title: "Nouvelle question", teacher: true },
        component: NewQuestion
      },
      {
        path: "/sandbox",
        name: "sandbox",
        meta: {
          title: "Bac à sable",
          icon: 'bucket',
          teacher: true,
          navbar: true
        },
        component: () => import("./components/sandbox/Sandbox")
      },
      {
        path: "/createquiz",
        name: "createquiz",
        meta: {
          title: "Create Quiz",
          icon: 'bucket',
          teacher: true,
          navbar: true
        },
        component: () => import("./components/class/CreateQuiz")
      },
      {
        path: "/updatequiz",
        name: "updatequiz",
        meta: {
          title: "Update Quiz",
          icon: 'bucket',
          teacher: true,
          navbar: true
        },
        component: () => import("./components/class/UpdateQuiz")
      },
      {
        path: "/createroster",
        name: "createroster",
        meta: {
          title: "Create Roster",
          icon: 'bucket',
          teacher: true,
          navbar: true
        },
        component: () => import("./components/class/CreateRoster")
      },
      {
        path: "/rosters",
        name: "rosters",
        meta: {
          title: "Roster",
          icon: 'bucket',
          teacher: true,
          navbar: true
        },
        component: () => import("./components/class/Class")
      },
      {
        path: "/help",
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

Vue.use(VueRouter)

export default router;
