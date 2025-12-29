<template>
  <div class="login-page">
    <div class="login-wrapper">
      <img :src="logoUmm" alt="UMM Logo" class="logo" />

      <p class="title">Informasi Mahasiswa</p>
      <p class="subtitle">Universitas Muhammadiyah Malang</p>

      <form class="form" @submit.prevent="handleSubmit">
        <input v-model="form.nim" type="text" placeholder="NIM" class="input" />

        <input
          v-model="form.pic"
          type="password"
          placeholder="PIC"
          class="input"
        />

        <p v-if="error" class="error">{{ error }}</p>

        <button class="btn-login" :disabled="loading">
          {{ loading ? "Loading..." : "Log in" }}
        </button>
      </form>

      <!-- <div class="links">
        <a href="#">» Login Orang Tua</a>
        <a href="#">» Panduan Reset Password</a>
        <a href="#">» Login Password?</a>
      </div> -->

      <div class="info">
        <h4>Informasi</h4>
        <p>
          Assalamualaikum Wr. Wb.<br /><br />
          Selamat Datang di Aplikasi Portal Mahasiswa Universitas Muhammadiyah
          Malang.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/auth";
import api from "../../api";
import logoUmm from "../../assets/Logo_UMM_Color.png";

const router = useRouter();
const auth = useAuthStore();

const form = ref({ nim: "", pic: "" });
const error = ref("");
const loading = ref(false);

const handleSubmit = async () => {
  error.value = "";
  loading.value = true;

  try {
    const res = await api.post("/login", {
      nim: form.value.nim,
      pin: form.value.pic,
    });

    auth.setAuth(res.data.user, res.data.token);

    const jadwal = await api.get(`/jadwal/${form.value.nim}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
        Accept: "application/json",
      },
    });

    localStorage.setItem("jadwal", JSON.stringify(jadwal.data.data));
    router.push("/profile");
  } catch (e) {
    error.value = e.response?.data?.message || "Server error";
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-page {
  width: 100%;
  display: flex;
  justify-content: center;
  background: #ffffff;
}

.login-wrapper {
  width: 100%;
  max-width: 380px;
  text-align: center;
  padding: 24px;
}

.error {
  color: #d32f2f;
  font-size: 12px;
  margin-bottom: 6px;
}

.logo {
  width: 100%;
  margin: 0 auto 10px;
}

.brand {
  font-size: 46px;
  font-weight: 900;
  letter-spacing: 0.14em;
  margin: 0;
  color: #c62828;
}

.brand-sub {
  margin: 4px 0 16px;
  font-size: 13px;
  letter-spacing: 0.45em;
  font-weight: 700;
}

.title {
  font-size: 14px;
  font-weight: 600;
  color: #3f51b5;
}

.subtitle {
  font-size: 12px;
  margin-bottom: 24px;
  color: #3f51b5;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.input {
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.btn-login {
  margin-top: 8px;
  padding: 10px;
  background: #5c6bc0;
  border: none;
  border-radius: 4px;
  color: white;
  font-size: 14px;
  cursor: pointer;
}

.btn-login:hover {
  background: #3f51b5;
}

.links {
  margin: 14px 0;
  text-align: left;
  font-size: 12px;
}

.links a {
  display: block;
  color: #3f51b5;
  text-decoration: none;
  margin-bottom: 4px;
}

.info {
  border-top: 1px solid #ddd;
  margin-top: 16px;
  padding-top: 10px;
  text-align: left;
}

.info h4 {
  font-size: 13px;
  margin-bottom: 6px;
  color: #3f51b5;
}

.info p {
  font-size: 12px;
  color: #555;
  line-height: 1.5;
}

@media (min-width: 768px) {
  .login-page {
    align-items: center;
  }
}
</style>
