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

        <div class="account account-trigger" @click="toggleDropdown">
          <span class="account-name">{{ user?.nama_lengkap }}</span>
          <span class="arrow" :class="{ rotate: dropdownOpen }">▼</span>

          <ul v-if="dropdownOpen" class="dropdown">
            <li @click="viewProfile">
              <span class="dropdown-icon">👤</span>
              <span>Profil</span>
            </li>
            <li @click="logout">
              <span class="dropdown-icon">🚪</span>
              <span>Logout</span>
            </li>
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
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "./stores/auth";
import FloatingChat from "./components/FloatingChat.vue";

const handleClickOutside = (e) => {
  if (!e.target.closest(".account")) {
    dropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});

const auth = useAuthStore();

const logout = async () => {
  try {
    await api.post("/logout");
  } catch (error) {
  } finally {
    auth.logout();
    localStorage.removeItem("token");
    router.push("/login");
  }
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

/* ACCOUNT BUTTON */
.account{
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  background: #ffffff;
  border-radius: 999px;
  cursor: pointer;
  user-select: none;
  position: relative;

  /* shadow lembut */
  box-shadow:
    0 4px 12px rgba(15, 23, 42, 0.08),
    0 1px 2px rgba(15, 23, 42, 0.04);

  transition: box-shadow .2s ease, transform .2s ease;
}

.account:hover{
  box-shadow:
    0 8px 20px rgba(15, 23, 42, 0.12),
    0 2px 4px rgba(15, 23, 42, 0.06);
  transform: translateY(-1px);
}

.account-name{
  font-size: 14px;
  font-weight: 600;
  color: #374151;
}

/* DROPDOWN */
.dropdown{
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  width: 180px;
  background: #ffffff;
  border-radius: 14px;
  padding: 6px;
  list-style: none;

  /* NO BORDER, ONLY SHADOW */
  box-shadow:
    0 16px 40px rgba(15, 23, 42, 0.14),
    0 4px 12px rgba(15, 23, 42, 0.08);

  animation: dropdownFade .18s ease-out;
  z-index: 50;
}

/* DROPDOWN ITEM */
.dropdown li{
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  font-size: 14px;
  color: #374151;
  cursor: pointer;
  transition: background .15s ease, transform .15s ease;
}

.dropdown li:hover{
  background: #f1f5f9;
  transform: translateX(2px);
}

/* ICON */
.dropdown-icon{
  font-size: 16px;
}

/* ANIMATION */
@keyframes dropdownFade{
  from{
    opacity: 0;
    transform: translateY(-6px) scale(.98);
  }
  to{
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

</style>
