import { createRouter, createWebHistory } from "vue-router"

import Home from "../views/home.vue"
import Jadwal from "../views/jadwalKuliah/index.vue"
import KRS from "../views/krs/index.vue"
import Surat from "../views/surat/index.vue"
import Keuangan from "../views/keuangan/index.vue";
import Editprofile from "../views/editprofile/index.vue";

const routes = [
  {
    path: "/",
    name: "home",
    component: Home
  },
  {
    path: "/jadwal-kuliah",
    name: "jadwal",
    component: Jadwal
  },
  {
    path: "/krs",
    name: "krs",
    component: KRS
  },
  {
    path: "/surat",
    name: "surat",
    component: Surat
  },
  {
    path: "/keuangan",
    name: "keuangan",
    component: Keuangan,
  },
  {
    path: "/editprofile",
    name: "editprofile",
    component: Editprofile,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router