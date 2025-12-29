<script setup>
import { ref, onMounted } from "vue";
import NotificationJadwal from "../../components/notificationJadwal.vue";

const user = ref(null);
const jadwal = ref(null);
const showNotification = ref(false);

import { Bar } from "vue-chartjs";
import {
  Chart,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
} from "chart.js";

Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

onMounted(() => {
  const storedUser = localStorage.getItem("user");
  if (storedUser) {
    user.value = JSON.parse(storedUser);
  }
  const storedJadwal = localStorage.getItem("jadwal");
  if (storedJadwal != "undefined") {
    jadwal.value = JSON.parse(storedJadwal);
    for (let i = 0; i < jadwal.value.length; i++) {
      if (
        jadwal.value[i].kelas_kuliah_baru.id !=
        jadwal.value[i].kelas_kuliah_lama.id
      ) {
        showNotification.value = true;
        break;
      }
    }
  }
});

const ipLabels = [
  "2022/2023 Ganjil",
  "2022/2023 Genap",
  "2023/2024 Ganjil",
  "2023/2024 Genap",
  "2024/2025 Ganjil",
  "2024/2025 Genap",
];

const ipData = [4.0, 3.8, 3.9, 4.0, 3.9, 3.9];

const chartData = {
  labels: ipLabels,
  datasets: [
    {
      label: "Index Prestasi (IP)",
      data: ipData,
      backgroundColor: "#13c4a3",
      borderRadius: 6,
      barThickness: 32,
      maxBarThickness: 40,
    },
  ],
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
};
</script>

<template>
  <div class="page-inner">
    <h1 class="title">Profil</h1>

    <div class="card bio-card">
      <h3 class="student-name">{{ user?.nama_lengkap || "-" }}</h3>

      <ul class="bio-list">
        <li class="bio-row">
          <span class="bio-icon">💳</span>
          <span class="bio-text">{{ user?.nim || "-" }}</span>
        </li>
        <li class="bio-row">
          <span class="bio-icon">🏛️</span>
          <span class="bio-text">{{ user?.fakultas || "-" }}</span>
        </li>
        <li class="bio-row">
          <span class="bio-icon">📍</span>
          <span class="bio-text">{{ user?.program_studi_id.nama_prodi }}</span>
        </li>
        <li class="bio-row">
          <span class="bio-icon">📞</span>
          <span class="bio-text">{{ user?.no_telp || "-" }}</span>
        </li>
        <li class="bio-row">
          <span class="bio-icon">✉️</span>
          <span class="bio-text">{{ user?.email_pribadi || "-" }}</span>
        </li>
        <li class="bio-row">
          <span class="bio-icon">📧</span>
          <span class="bio-text">{{ user?.email_UMM || "-" }}</span>
        </li>
      </ul>

      <div class="edit-btn-wrap">
        <router-link to="/edit-profile">
          <button class="edit-btn">Edit Profile</button>
        </router-link>
      </div>
    </div>

    <div class="card">
      <h2 class="card-title">Index Prestasi (IP)</h2>
      <p class="card-subtitle">Riwayat IP per semester.</p>

      <div class="chart-wrapper">
        <Bar :data="chartData" :options="chartOptions" />
      </div>
    </div>
  </div>

  <NotificationJadwal
    v-if="showNotification"
    @close="showNotification = false"
  />
</template>

<style scoped>
* {
  box-sizing: border-box;
}

.page-inner {
  max-width: 100%;
  margin: 0 auto;
  background: #ffffff;
  border-radius: 25px;
  padding: 20px 16px 28px;
  box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
  transition: box-shadow 0.25s ease, transform 0.25s ease;
  margin: 0;
}

.page-inner:hover {
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15), 0 20px 45px rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
}

@media (min-width: 768px) {
  .page-inner {
    max-width: 100%;
    padding: 24px 24px 32px;
  }
}

@media (min-width: 1200px) {
  .page-inner {
    max-width: 100%;
  }
}

.title {
  font-size: 28px;
  font-weight: 700;
  color: #4b5563;
  margin-bottom: 24px;
}

.card {
  background: #ffffff;
  border-radius: 18px;
  padding: 18px 16px 20px;
  margin-bottom: 20px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 3px 10px rgba(15, 23, 42, 0.04);
}

.card-title {
  font-size: 16px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 8px 0;
}

.card-subtitle {
  margin: 0 0 12px 0;
  font-size: 13px;
  color: #6b7280;
}

.bio-card {
  margin-bottom: 24px;
}

.student-name {
  font-size: 18px;
  font-weight: 700;
  color: #4b5563;
  margin: 0 0 10px 0;
}

.bio-list {
  list-style: none;
  padding: 0;
  margin: 0 0 12px 0;
}

.bio-row {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #111827;
  margin-bottom: 4px;
}

.bio-icon {
  width: 22px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 15px;
}

.bio-text {
  flex: 1;
  overflow: hidden;
}

.edit-btn-wrap {
  display: flex;
  justify-content: center;
  margin-top: 8px;
}

.edit-btn {
  padding: 8px 20px;
  background: #13c4a3;
  border: none;
  border-radius: 6px;
  color: #ffffff;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.18s ease, transform 0.18s ease,
    box-shadow 0.18s ease;
}

.edit-btn:hover {
  background: #0fa38a;
  transform: translateY(-1px);
  box-shadow: 0 3px 8px rgba(19, 196, 163, 0.35);
}

.edit-btn:active {
  transform: translateY(0);
  box-shadow: 0 1px 3px rgba(19, 196, 163, 0.25);
}

.chart-wrapper {
  width: 100%;
  height: 260px;
}

@media (min-width: 768px) {
  .chart-wrapper {
    height: 320px;
  }
}
</style>
