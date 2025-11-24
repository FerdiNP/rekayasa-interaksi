<script setup>
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

// Dummy data IP Mahasiswa
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
    },
  ],
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
};
</script>

<template>
  <div class="profile-container">
    <h1 class="header-title">Profil</h1>

    <!-- Data Mahasiswa -->
    <div class="card">
      <h2>Data Mahasiswa</h2>

      <div class="bio">
        <h1>Ferdi Naufal Prasetyo</h1>

        <p>📘 202210370311272</p>
        <p>📍 Fakultas Teknik</p>
        <p>💻 Program Studi Informatika</p>
        <p>📞 08123456789</p>
        <p>📧 ferdi@gmail.com</p>
        <p>📧 ferdi@webmail.umm.ac.id</p>

        <button class="edit-btn">
          <RouterLink to="/editprofile">Profile</RouterLink>
        </button>
      </div>
    </div>

    <!-- IP Chart -->
    <div class="card">
      <h2>Index Prestasi (IP)</h2>

      <div class="chart-wrapper">
        <Bar :data="chartData" :options="chartOptions" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.profile-container {
  width: 100%;
  padding: 20px;
}

.header-title {
  font-size: 32px;
  margin-bottom: 20px;
  font-weight: bold;
  color: #333;
}

.card {
  position: static;
  background: white;
  padding: 22px;
  border: 1px solid #ddd;
  border-radius: 12px;
  margin-bottom: 25px;
}

.bio h1 {
  margin: 0 0 15px 0;
  font-weight: 700;
}

.bio p {
  margin: 4px 0;
  font-size: 16px;
}

.edit-btn {
  margin-top: 18px;
  padding: 10px 20px;
  background: #13c4a3;
  border: none;
  border-radius: 8px;
  color: white;
  cursor: pointer;
}

a {
  color: rgb(255 255 255);
  text-decoration: none;
}

.chart-wrapper {
  width: 100%;
  height: 350px;
}
</style>
