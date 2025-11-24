<template>
  <div class="page">
    <section class="login-box">

      <!-- LOGO UMM -->
      <img
        src="/mnt/data/watermark-logo-umm.jpg"
        class="logo"
        alt="Logo UMM"
      />

      <!-- TEKS UMM -->
      <h1 class="brand">UMM</h1>
      <p class="brand-sub">u m m . a c . i d</p>

      <p class="title">Informasi Mahasiswa</p>
      <p class="subtitle">Universitas Muhammadiyah Malang</p>

      <!-- FORM -->
      <form class="form" @submit.prevent="handleSubmit">
        <input
          v-model="form.nim"
          type="text"
          placeholder="NIM"
          class="input"
        />

        <input
          v-model="form.pic"
          type="password"
          placeholder="PIC"
          class="input"
        />

        <button type="submit" class="btn-login">Login</button>
      </form>

      <!-- FOOTER -->
      <p class="footer">
        Tetap rahasiakan PIC anda meskipun dari teman terdekat, pastikan log
        out setelah transaksi, tetap jaga kerahasiaan identitas pribadi anda
        (KTM, KTP, Kartu Keluarga) dari pihak yang menghubungi dan
        mengatasnamakan UMM. Segera lapor ke BAA-AIK UMM jika terjadi
        penyalahgunaan.
      </p>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  nim: '',
  pic: ''
})

const handleSubmit = () => {
  console.log('Login clicked')
}
</script>

<style scoped>
/* Background putih */
.page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  padding: 40px 16px;
  background: #ffffff;
}

/* Kotak login */
.login-box {
  width: 100%;
  max-width: 350px;
  text-align: center;
  padding-top: 40px; /* 🔥 SEMUA ISI TURUN KE BAWAH */
}

/* LOGO */
.logo {
  width: 85px;
  margin: 0 auto 12px;
}

/* TEKS UMM MERAH */
.brand {
  margin: 0;
  font-size: 46px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: #c62828;
  font-family: "Arial Black", "Impact", sans-serif;
  line-height: 0.9;
}

/* u m m . a c . i d – tebal + jarak lebar */
.brand-sub {
  margin: 4px 0 14px;
  font-size: 13px;
  letter-spacing: 0.45em;
  font-weight: 700;
  color: #222;
  text-transform: lowercase;
}

/* JUDUL */
.title {
  margin: 0;
  font-size: 14px;
  font-weight: 500;
  color: #3f51b5;
}

.subtitle {
  margin: 2px 0 22px;
  font-size: 12px;
  color: #3f51b5;
}

/* FORM */
.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.input {
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  background: #fafafa;
}

.input:focus {
  border-color: #9fa8da;
  box-shadow: 0 0 0 1px rgba(159, 168, 218, 0.3);
}

/* LOGIN BUTTON */
.btn-login {
  margin-top: 10px;
  padding: 10px 12px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  color: white;
  cursor: pointer;
  background: linear-gradient(180deg, #6e7fdc, #5a68b8);
}

.btn-login:hover {
  filter: brightness(1.05);
}

/* FOOTER */
.footer {
  margin-top: 24px;
  font-size: 10px;
  color: #999;
  line-height: 1.4;
}
</style>
