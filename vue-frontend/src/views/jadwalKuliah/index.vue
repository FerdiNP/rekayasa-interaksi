<template>
  <div class="page">
    <div class="page-inner">
      <h1 class="title">Jadwal Kuliah</h1>

      <div class="info-container">
        <div class="info">
          <p><strong>NIM</strong></p>
          <p>202210370311272</p>
        </div>
        <div class="info">
          <p><strong>Nama</strong></p>
          <p>Gemilang Rizmart Samopdra</p>
        </div>
        <div class="info">
          <p><strong>Tahun Akademik</strong></p>
          <p>2025/2026</p>
        </div>
        <div class="info">
          <p><strong>Semester</strong></p>
          <p>Ganjil</p>
        </div>
      </div>

      <div class="alert alert-warning">
        <div class="alert-header">
          <div class="icon-wrap">
            <div class="icon-wrapper-alert">
              <div class="icon-wrapper-2-alert">
                <div class="icon-alert"></div>
              </div>
            </div>
          </div>
          <div class="content">
            <span class="alert-title">Terdapat Perubahan Jadwal</span>
            <p class="alert-desc">
              Cek perubahan jadwal pada Mata Kuliah dengan menekan tombol Icon
              Danger pada tabel.
            </p>
          </div>
        </div>
      </div>

      <div class="alert alert-danger">
        <div class="alert-header">
          <div class="icon-wrap">
            <div class="icon-wrapper-danger">
              <div class="icon-wrapper-2-danger">
                <div class="icon-danger"></div>
              </div>
            </div>
          </div>
          <div class="content">
            <span class="alert-title">Terdapat Perubahan Kelas</span>
            <p class="alert-desc">
              Cek perubahan Kelas pada Mata Kuliah dengan menekan tombol Icon
              Error pada tabel.
            </p>
          </div>
        </div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Mata Kuliah</th>
              <th>SKS</th>
              <th>Kelas</th>
              <th>Nama Dosen</th>
              <th>Ruang</th>
              <th>Jam</th>
              <th>Presensi</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(row, index) in tableData" :key="index">
              <td>
                <div class="no">
                  <div
                    v-if="row.alert === 'warning'"
                    class="icon-wrap clickable"
                    @click="openAlert(row, 'warning')"
                  >
                    <div class="icon-wrapper-alert">
                      <div class="icon-wrapper-2-alert">
                        <div class="icon-alert"></div>
                      </div>
                    </div>
                  </div>

                  <div
                    v-if="row.alert === 'error'"
                    class="icon-wrap clickable"
                    @click="openAlert(row, 'error')"
                  >
                    <div class="icon-wrapper-danger">
                      <div class="icon-wrapper-2-danger">
                        <div class="icon-danger"></div>
                      </div>
                    </div>
                  </div>

                  {{ index + 1 }}
                </div>
              </td>
              <td>{{ row.matkul }}</td>
              <td>{{ row.sks }}</td>
              <td>{{ row.kelas_baru || row.kelas }}</td>
              <td>{{ row.dosen }}</td>
              <td>{{ row.ruang_baru || row.ruang_lama }}</td>
              <td>{{ row.jadwal_baru }}</td>
              <td>
                <button class="btn-presensi" @click="presensi(row)">
                  Presensi
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <AlertDetails
      v-if="showAlert"
      :alert-type="selectedAlertType"
      :data="selectedRow"
      @close="showAlert = false"
    />

    <NotificationJadwal
      v-if="showNotification"
      @close="showNotification = false"
    />

    <PresensiSuccess
      v-if="showPresensiSuccess"
      @close="showPresensiSuccess = false"
    />
  </div>
</template>

<script setup>
import { ref } from "vue";
import AlertDetails from "../../components/alertDetails.vue";
import NotificationJadwal from "../../components/notificationJadwal.vue";
import PresensiSuccess from "../../components/presensiSuccess.vue";

const showAlert = ref(false);
const showNotification = ref(true);
const showPresensiSuccess = ref(false);
const selectedAlertType = ref("warning");
const selectedRow = ref(null);

const tableData = [
  {
    matkul: "Rekayasa Ulang Sistem",
    sks: 2,
    alert: "warning",
    kelas_lama: "A",
    kelas_baru: "B",
    dosen: "Ridho, M.Kom",
    ruang_baru: "Lab 301",
    jadwal_baru: "Selasa, 08:00 - 10:00",
  },
  {
    matkul: "Rekayasa Interaksi",
    sks: 2,
    alert: "error",
    kelas: "B",
    dosen: "Rusdi, M.Kom",
    ruang_lama: "GKB 3 R612",
    ruang_baru: "R.GKB 4 R615",
    jadwal_lama: "Senin, 08.00-10.00",
    jadwal_baru: "Rabu, 10:00 - 12:00",
  },
];

function openAlert(row, type) {
  selectedRow.value = row;
  selectedAlertType.value = type;
  showAlert.value = true;
}

function openNotification() {
  showNotification.value = true;
}

function presensi(row) {
  showPresensiSuccess.value = true;
}
</script>

<style scoped>
.page {
  min-height: 100vh;
  align-items: center;
  padding: 24px 16px 40px;
  box-sizing: border-box;
  width: 100%;
  background-color: white;
}

.page-inner {
  width: 100%;
}

.title {
  font-size: 28px;
  font-weight: 700;
  color: #555;
  margin-bottom: 24px;
}

.info-container {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 24px;
}

.info p:first-child {
  font-size: 13px;
  color: #6b7280;
  margin: 0;
}

.info p:last-child {
  font-size: 14px;
  color: #111827;
  margin-bottom: 5px;
}

.alert {
  padding: 16px 18px;
  border-radius: 18px;
  margin-top: 16px;
  text-align: center;
  border: none;
}

.alert-warning {
  background: #fee9a6;
  color: #4f3b00;
}

.alert-danger {
  background: #f97373;
  color: #fff;
}

.alert-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0;
}

.alert-title {
  font-weight: 700;
  font-size: 15px;
}

.alert-desc {
  font-size: 13px;
  margin: 4px 0 0 0;
}

.icon-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
}

.clickable {
  cursor: pointer;
}

.icon-wrapper-alert,
.icon-wrapper-danger {
  width: 52px;
  height: 52px;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fffaeb;
}

.icon-wrapper-2-alert {
  width: 70%;
  height: 70%;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fef0c7;
}

.icon-wrapper-2-danger {
  width: 70%;
  height: 70%;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #d92d20;
}

.icon-alert {
  width: 50%;
  height: 50%;
  background-image: url("../../assets/alert-triangle.svg");
  background-size: cover;
  background-position: center;
}

.icon-danger {
  width: 50%;
  height: 50%;
  border-radius: 50%;
  background-image: url("../../assets/alert-circle-white.svg");
  background-size: cover;
  background-position: center;
}

.table-wrap {
  margin-top: 24px;
  overflow-x: auto;
}

table {
  width: 100%;
  border-radius: 16px;
  overflow: hidden;
  border-collapse: separate;
  border-spacing: 0;
  background: #ffffff;
  font-size: 14px;
  border: 1px solid #e5e7eb;
  box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.1);
}

.no {
  display: flex;
  align-items: center;
  gap: 8px;
}

th,
td {
  padding: 12px;
  border-bottom: 1px solid #f1f5f9;
}

th {
  background: #f9fafb;
  font-weight: 600;
  color: #6b7280;
  font-size: 12px;
}

td {
  font-size: 13px;
  color: #111827;
}

tr:last-child td {
  border-bottom: none;
}

tr:hover {
  background: #f9fafb;
}

.btn-presensi {
  padding: 6px 12px;
  border-radius: 999px;
  border: none;
  background: #12b76a;
  color: white;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
}

.btn-presensi:active {
  transform: scale(0.98);
}

@media (min-width: 768px) {
  .title {
    font-size: 32px;
  }

  .info-container {
    flex-direction: column;
    max-width: 360px;
  }

  table {
    font-size: 14px;
  }

  th {
    font-size: 13px;
  }
}
</style>
