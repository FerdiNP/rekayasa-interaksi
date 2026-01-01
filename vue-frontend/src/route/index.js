import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";

import Login from "../views/login/index.vue";
import Profile from "../views/profile/index.vue";
import EditProfile from "../views/editprofile/index.vue";
import Jadwal from "../views/jadwalKuliah/index.vue";
import KRS from "../views/krs/index.vue";
import Keuangan from "../views/keuangan/index.vue";

import SuratLayout from "../views/surat/HalamanSurat.vue";
import SuratCuti from "../views/surat/Cuti.vue";
import SuratAktifKembali from "../views/surat/AktifKembali.vue";
import SuratPengajuan from "../views/surat/PengajuanSurat.vue";

const routes = [
  {
    path: "/",
    redirect: "/login",
  },

  {
    path: "/login",
    name: "login",
    component: Login,
    meta: { guest: true },
  },

  {
    path: "/profile",
    name: "profile",
    component: Profile,
    meta: { requiresAuth: true },
  },
  {
    path: "/edit-profile",
    name: "editprofile",
    component: EditProfile,
    meta: { requiresAuth: true },
  },
  {
    path: "/jadwal-kuliah",
    name: "jadwal",
    component: Jadwal,
    meta: { requiresAuth: true },
  },
  {
    path: "/krs",
    name: "krs",
    component: KRS,
    meta: { requiresAuth: true },
  },
  {
    path: "/keuangan",
    name: "keuangan",
    component: Keuangan,
    meta: { requiresAuth: true },
  },

  {
    path: "/surat",
    component: SuratLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "cuti",
        name: "surat.cuti",
        component: SuratCuti,
      },
      {
        path: "aktif-kembali",
        name: "surat.aktif",
        component: SuratAktifKembali,
      },
      {
        path: "pengajuan",
        name: "surat.pengajuan",
        component: SuratPengajuan,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to) => {
  const auth = useAuthStore();

  if (to.meta.requiresAuth && !auth.isLogin) {
    return "/login";
  }

  if (to.meta.guest && auth.isLogin) {
    return "/profile";
  }
});

export default router;
