import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
const Game = () => import("../views/Game");
const List = () => import("../views/List");
const Infor = () => import("../views/Infor");
Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    redirect: "/home",
  },
  {
    path: "/home",
    name: "Home",
    component: Home,
  },
  {
    path: "/game",
    name: "Game",
    component: Game,
  },
  {
    path: "/list",
    name: "List",
    component: List,
  }, {
    path: "/infor",
    name: "Infor",
    component: Infor,
  },
];

const router = new VueRouter({
  mode: "hash",
  base: process.env.BASE_URL,
  routes,
});

export default router;
