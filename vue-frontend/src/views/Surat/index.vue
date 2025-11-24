<template>
  <div class="root">
    <div class="phone-frame">
      <main class="content">
        <div class="section-title">Layanan Mandiri</div>

        <section class="identity">
          <div class="row">
            <span class="label">NIM</span>
            <span class="value">{{ mahasiswa.nim }}</span>
          </div>
          <div class="row">
            <span class="label">Nama</span>
            <span class="value">{{ mahasiswa.nama }}</span>
          </div>
          <div class="row">
            <span class="label">Fakultas</span>
            <span class="value">{{ mahasiswa.fakultas }}</span>
          </div>
          <div class="row">
            <span class="label">Program Studi</span>
            <span class="value">{{ mahasiswa.prodi }}</span>
          </div>
          <div class="row">
            <span class="label">Status</span>
            <span class="value">{{ mahasiswa.status }}</span>
          </div>
        </section>

        <section class="card layanan-list">
          <button class="layanan-item">Cuti</button>
          <button class="layanan-item">Aktif Kembali</button>
        </section>
      </main>

      <button class="fab">💬</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const mahasiswa = ref({
  nim: "202210370311272",
  nama: "Gemilang Rizmart Samodra",
  fakultas: "Fakultas Teknik",
  prodi: "Informatika",
  status: "Aktif",
});
</script>

<style scoped>
.root {
  align-items: center;
  padding: 16px;
  width: 100%;
}

.phone-frame {
  width: 100%;
  background: #ffffff;
  border-radius: 12px;
  overflow: hidden;
  padding-bottom: 80px;
}

.content {
  padding: 16px 16px 72px;
}

.section-title {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #111;
}

.identity {
  margin-bottom: 16px;
}

.row {
  display: flex;
  margin-bottom: 6px;
}

.label {
  width: 110px;
  font-size: 12px;
  color: #666;
}

.value {
  font-size: 12px;
  color: #111;
}

.card {
  border-radius: 8px;
  border: 1px solid #e5e5e5;
  background: #ffffff;
  overflow: hidden;
}

.layanan-list {
  display: flex;
  flex-direction: column;
}

.layanan-item {
  text-align: left;
  padding: 10px 12px;
  background: #ffffff;
  border: none;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px;
  cursor: pointer;
}

.layanan-item:last-child {
  border-bottom: none;
}

.fab {
  position: absolute;
  right: 16px;
  bottom: 16px;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  background: #f44336;
  color: #fff;
  font-size: 20px;
}
</style>
