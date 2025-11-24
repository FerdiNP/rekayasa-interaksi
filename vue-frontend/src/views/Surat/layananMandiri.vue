<template>
  <div class="page">
    <main class="main">
      <h1 class="page-title">{{ pageTitle }}</h1>

      <section class="identity">
        <div class="row">
          <span class="label">NIM</span
          ><span class="value">{{ mahasiswa.nim }}</span>
        </div>
        <div class="row">
          <span class="label">Nama</span
          ><span class="value">{{ mahasiswa.nama }}</span>
        </div>
        <div class="row">
          <span class="label">Fakultas</span
          ><span class="value">{{ mahasiswa.fakultas }}</span>
        </div>
        <div class="row">
          <span class="label">Program Studi</span
          ><span class="value">{{ mahasiswa.prodi }}</span>
        </div>
        <div class="row">
          <span class="label">Status</span
          ><span class="value status">{{ mahasiswa.status }}</span>
        </div>
      </section>

      <section class="stats">
        <div class="stats-card">
          <div class="stats-label">Total Cuti</div>
          <div class="stats-value">{{ jumlahCuti }}</div>
        </div>
        <div class="stats-card">
          <div class="stats-label">Total Aktif Kembali</div>
          <div class="stats-value">{{ jumlahAktif }}</div>
        </div>
      </section>

      <LayananHome
        v-if="view === 'home'"
        @go-cuti="goToCuti"
        @go-aktif="goToAktif"
      />

      <CutiSection
        v-else-if="view === 'cuti'"
        :items="tabelCuti"
        @back="goHome"
      />

      <AktifSection
        v-else-if="view === 'aktif'"
        :items="tabelAktif"
        @back="goHome"
      />

      <section v-if="showHelp" class="card help-card">
        <div class="help-title">Bantuan Layanan</div>
        <p class="help-text">
          • Pilih menu <b>Cuti</b> atau <b>Aktif Kembali</b> untuk melihat
          riwayat.<br />
          • Gunakan kolom pencarian untuk menyaring data.<br />
          • Klik baris tabel untuk melihat detail riwayat.
        </p>
      </section>

      <button class="fab" type="button" @click="toggleHelp">💬</button>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import LayananHome from "../../components/LayananHome.vue";
import CutiSection from "../../components/CutiSection.vue";
import AktifSection from "../../components/AktifSection.vue";

const view = ref("home");
const showHelp = ref(false);

const mahasiswa = ref({
  nim: "202210370311272",
  nama: "Gemilang Rizmart Samopdra",
  fakultas: "Fakultas Teknik",
  prodi: "Informatika",
  status: "Aktif",
});

const tabelCuti = ref([
  { tahunAjar: "2023/2024", semesterAjar: "3" },
  { tahunAjar: "2024/2025", semesterAjar: "5" },
]);

const tabelAktif = ref([{ tahunAjar: "2025/2026", semesterAjar: "7" }]);

const jumlahCuti = computed(() => tabelCuti.value.length);
const jumlahAktif = computed(() => tabelAktif.value.length);

const pageTitle = computed(() => {
  if (view.value === "cuti") return "Cuti";
  if (view.value === "aktif") return "Aktif Kembali";
  return "Layanan Mandiri";
});

const goHome = () => {
  view.value = "home";
};

const goToCuti = () => {
  view.value = "cuti";
};

const goToAktif = () => {
  view.value = "aktif";
};

const toggleHelp = () => {
  showHelp.value = !showHelp.value;
};
</script>

<style>
.page {
  min-height: 100vh;
  background: #f0f2f5;
  display: flex;
  justify-content: center;
  padding: 40px 16px;
}

.main {
  width: 100%;
  max-width: 520px;
  background: #ffffff;
  border-radius: 12px;
  padding: 24px 20px 80px;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
  position: relative;
}

.page-title {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 18px;
}

.identity {
  margin-bottom: 18px;
}

.row {
  display: flex;
  margin-bottom: 6px;
}

.label {
  width: 120px;
  font-size: 13px;
  color: #666666;
}

.value {
  font-size: 13px;
  color: #111111;
}

.status {
  font-weight: 600;
  color: #2e7d32;
}

.stats {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 10px;
  margin-bottom: 18px;
}

.stats-card {
  padding: 10px 12px;
  border-radius: 10px;
  background: linear-gradient(135deg, #e3f2fd, #ffffff);
  border: 1px solid #d0e3ff;
}

.stats-label {
  font-size: 12px;
  color: #555555;
  margin-bottom: 4px;
}

.stats-value {
  font-size: 18px;
  font-weight: 600;
  color: #1976d2;
}

.card {
  border-radius: 10px;
  border: 1px solid #e5e5e5;
  background: #ffffff;
  overflow: hidden;
  margin-bottom: 16px;
}

.layanan-list {
  display: flex;
  flex-direction: column;
}

.layanan-item {
  width: 100%;
  padding: 12px 14px;
  border: none;
  border-bottom: 1px solid #f0f0f0;
  font-size: 14px;
  background: #ffffff;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background-color 0.15s ease, transform 0.15s ease,
    box-shadow 0.15s ease;
}

.layanan-item:last-child {
  border-bottom: none;
}

.layanan-item:hover {
  background: #f5f9ff;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(25, 118, 210, 0.18);
}

.chevron {
  color: #999999;
}

.table-wrapper {
  padding: 12px 12px 18px;
  position: relative;
}

.table-header-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 10px;
}

.table-title {
  font-size: 14px;
  font-weight: 600;
  color: #333333;
}

.table-toolbar {
  flex-shrink: 0;
}

.search-input {
  padding: 6px 10px;
  border-radius: 16px;
  border: 1px solid #d0d0d0;
  font-size: 12px;
  min-width: 150px;
}

.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12px;
}

.table th,
.table td {
  padding: 8px 10px;
}

.table thead tr {
  border-bottom: 1px solid #e5e5e5;
  background: #fafafa;
}

.table-row + .table-row {
  border-top: 1px solid #f1f1f1;
}

.table-row:hover {
  background: #f5f5f5;
  cursor: pointer;
}

.row-selected {
  background: #e3f2fd;
}

.empty-cell {
  text-align: center;
  padding: 10px;
  color: #999999;
}

.detail-box {
  margin-top: 14px;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #d0e3ff;
  background: #f5f9ff;
  font-size: 12px;
}

.detail-title {
  font-weight: 600;
  margin-bottom: 6px;
  color: #1a3d7c;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 4px;
}

.detail-row span:first-child {
  color: #555555;
}

.detail-row span:last-child {
  font-weight: 500;
}

.back-button-bottom {
  margin-top: 14px;
  border-radius: 20px;
  border: 1px solid #1976d2;
  background: #e3f2fd;
  font-size: 13px;
  cursor: pointer;
  padding: 6px 14px;
  color: #1976d2;
  transition: background-color 0.18s ease, transform 0.18s ease,
    box-shadow 0.18s ease;
}

.back-button-bottom:hover {
  background: #bbdefb;
  transform: translateY(-1px);
  box-shadow: 0 2px 6px rgba(25, 118, 210, 0.3);
}

.help-card {
  position: fixed;
  right: 24px;
  bottom: 90px;
  width: 260px;
  max-width: calc(100% - 48px);
  padding: 12px 14px;
  border-radius: 12px;
  font-size: 12px;
}

.help-title {
  font-weight: 600;
  margin-bottom: 6px;
}

.help-text {
  line-height: 1.4;
}

.fab {
  position: fixed;
  right: 24px;
  bottom: 24px;
  width: 52px;
  height: 52px;
  background: #f44336;
  color: #ffffff;
  border-radius: 50%;
  border: none;
  font-size: 24px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 14px rgba(244, 67, 54, 0.5);
  transition: transform 0.2s ease, box-shadow 0.2s ease,
    background-color 0.2s ease;
}

.fab:hover {
  transform: translateY(-2px) scale(1.03);
  box-shadow: 0 6px 18px rgba(244, 67, 54, 0.6);
  background: #e53935;
}

.fab:active {
  transform: translateY(0) scale(0.98);
  box-shadow: 0 3px 10px rgba(244, 67, 54, 0.45);
}
</style>
