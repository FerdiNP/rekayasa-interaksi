<template>
  <div class="container">
    <aside
      :class="['sidebar', { open: isSidebarOpen }]"
      v-if="!$route.meta.guest"
    >
      <div class="logo">
        <img src="../src/assets/UMM_Logo.svg" alt="UMM Logo" />
      </div>

      <ul class="menu">
        <li>
          <router-link to="/profile"
            ><div class="icon icon-1"></div>
            Profil</router-link
          >
        </li>
        <li>
          <router-link to="#">
            <span class="icon icon-2"></span>
            <p>Hasil Studi</p>
          </router-link>
        </li>
        <li>
          <router-link to="/krs">
            <span class="icon icon-3"></span>
            <p>KRS</p>
          </router-link>
        </li>
        <li>
          <router-link to="/jadwal-kuliah">
            <span class="icon icon-4"></span>
            <p>Jadwal Kuliah</p>
          </router-link>
        </li>
        <li>
          <router-link to="/keuangan"
            ><span class="icon icon-5"></span>
            <p>Keuangan</p>
          </router-link>
        </li>
        <li>
          <router-link to="/surat">
            <span class="icon icon-6"></span>
            <p>Layanan Mandiri</p>
          </router-link>
        </li>
      </ul>
    </aside>

    <main :class="{ full: $route.meta.guest || !isSidebarOpen }">
      <header class="header" v-if="!$route.meta.guest">
        <button
          :class="['menu-toggle', { geser: !isSidebarOpen }]"
          @click="toggleSidebar"
        >
          ☰
        </button>

        <div class="account" @click="toggleDropdown">
          <span>{{ user?.nama_lengkap }}</span>
          <span class="arrow" :class="{ rotate: dropdownOpen }">▼</span>

          <ul v-if="dropdownOpen" class="dropdown">
            <li @click="viewProfile">View Profile</li>
            <li @click="logout">Logout</li>
          </ul>
        </div>
      </header>

      <div class="content">
        <router-view />
      </div>
    </main>

    <FloatingChat v-if="!$route.meta.guest" />
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "./stores/auth";
import api from "./api";
import FloatingChat from "./components/FloatingChat.vue";

const auth = useAuthStore();

const logout = async () => {
  await api.post("/logout");
  auth.logout();
  router.push("/login");
};
const user = computed(() => auth.user);
const router = useRouter();
const isSidebarOpen = ref(true);
const dropdownOpen = ref(false);

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}

function toggleDropdown() {
  dropdownOpen.value = !dropdownOpen.value;
}

function viewProfile() {
  router.push("/profile");
}
</script>

<style scoped>
.container {
  padding: 0;
}

.sidebar {
  width: 100px;
  background: #253953;
  color: white;
  padding: 20px 10px;
  position: fixed;
  left: -230px;
  top: 0;
  bottom: 0;
  transition: 0.3s;
  z-index: 10;
}

.sidebar.open {
  left: 0;
}

p {
  text-align: center;
  margin: 0;
}

.logo img {
  width: 80%;
  margin-bottom: 30px;
}

.menu {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.menu a {
  display: flex;
  color: white;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
}

.icon {
  width: 32px;
  height: 32px;
  display: inline-block;
}

.icon-1 {
  background-image: url("../src/assets/profil.svg");
  background-size: cover;
}

.icon-2 {
  background-image: url("../src/assets/hasil_studi.svg");
  background-size: cover;
}

.icon-3 {
  background-image: url("../src/assets/KRS.svg");
  background-size: cover;
}

.icon-4 {
  background-image: url("../src/assets/jadwal_kuliah.svg");
  background-size: cover;
}

.icon-5 {
  background-image: url("../src/assets/keuangan.svg");
  background-size: cover;
}

.icon-6 {
  background-image: url("../src/assets/layanan.svg");
  background-size: cover;
}

.menu {
  margin-top: 10px;
  list-style: none;
  padding: 0;
}

.menu li {
  padding: 16px 12px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.menu li:hover {
  background: #1c2d44;
  border-radius: 8px;
}

.menu a {
  color: white;
  text-decoration: none;
}

main {
  margin-left: 100px;
  width: calc(100% - 100px);
  transition: 0.3s;
}

main.full {
  margin-left: 0;
  width: 100%;
}

.header {
  height: 60px;
  background: #f8f8f8;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
}

.menu-toggle {
  background: transparent;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

.menu-toggle.geser {
  left: 0;
}

.account {
  display: flex;
  background-color: #fff;
  align-items: center;
  justify-content: center;
  border: 1px solid gray;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  user-select: none;
  position: relative;
  height: auto;
  width: auto;
  padding: 0 10px;
  box-shadow: 0 1px 1px rgba(15, 23, 42, 0.45);
}

.arrow {
  margin-left: 6px;
  transition: 0.3s;
}
.rotate {
  transform: rotate(180deg);
}

.dropdown {
  position: absolute;
  top: 35px;
  right: 0;
  width: 150px;
  background: #fff;
  list-style: none;
  padding: 8px 0;
  border-radius: 8px;
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.15);
  z-index: 1;
}

.dropdown li {
  padding: 10px;
}
.dropdown li:hover {
  background: #eee;
}

.content {
  padding: 20px;
  width: 100%;
}

@media (min-width: 576px) {
  .container,
  .container-sm {
    max-width: 100%;
    padding: 0;
  }
}
</style>
