import { createRouter, createWebHistory } from "vue-router"

import HomeView from "../components/HelloWorld.vue"
import JadwalView from "../components/jadwalKuliah.vue"
import AlertDetails from "../components/alertDetails.vue"
import notificationJadwal from "../components/notificationJadwal.vue"

const routes = [
  {
    path: "/",
    name: "home",
    component: HomeView
  },
  {
    path: "/jadwal-kuliah",
    name: "jadwal",
    component: JadwalView
    },
  {
    path: "/alert",
    name: "alertDetails",
    component: AlertDetails
  },
  {
    path: "/notification",
    name: "notification",
    component: notificationJadwal
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
