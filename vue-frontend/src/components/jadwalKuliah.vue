<template>
  <div class="page">
    <h1 class="title">Jadwal Kuliah</h1>

    <!-- Informasi Mahasiswa -->
    <div class="info-container">
      <div class="info">
        <p><strong>NIM</strong></p>
        <p>202210370311272</p>
      </div>
      <div class="info">
        <p>
          <strong>Nama</strong>
        </p>
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

    <!-- Notifikasi Jadwal -->
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

    <!-- Notifikasi Kelas -->
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

    <!-- Tabel Jadwal -->
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
          <tr v-for="(row, index) in tableData" :key="row.no">
            <td>
              <div class="no">
                <div
                  router-link
                  to="/alertDetails"
                  v-if="row.alert === 'warning'"
                  class="icon-wrap"
                  @click="lihat(row)"
                >
                  <div class="icon-wrapper-alert">
                    <div class="icon-wrapper-2-alert">
                      <div class="icon-alert"></div>
                    </div>
                  </div>
                </div>
                <div
                  router-link
                  to="/jadwal-kuliah"
                  @click="lihat(row)"
                  class="icon-wrap"
                  v-if="row.alert === 'error'"
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
            <td>{{ row.kelas_baru }}</td>
            <td>{{ row.dosen }}</td>
            <td>{{ row.ruang_baru }}</td>
            <td>{{ row.jadwal_baru }}</td>
            <td>
              <button class="btn-presensi" @click="lihat">Lihat</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();
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
    ruang_lama: "Senin, 08.00-10.00",
    ruang_baru: "R.GKB 4 R615",
    jadwal_lama: "Senin, 08.00-10.00",
    jadwal_baru: "Rabu, 10:00 - 12:00",
  },
];
function lihat(data) {
  router.push("/alert");
  return reactive(data);
}
</script>

<style scoped>
.page {
  font-family: "Inter", sans-serif;
  color: #444;
  background: #ffff;
}

.title {
  font-size: 32px;
  font-weight: 700;
  color: #555;
  margin-bottom: 20px;
  text-align: left;
}

.info-container {
  display: flex;
  flex-direction: column;
  align-items: start;
  margin-left: 20px;
}

.info {
  margin-bottom: 10px;
}

.info p {
  font-size: 14px;
  margin: 0px;
  text-align: left;
}

/* Alerts */
.alert {
  padding: 16px;
  border-radius: 14px;
  margin-top: 15px;
  text-align: center;
}

.alert-warning {
  background: #ffe9a6;
  color: #4f3b00;
}

.icon-wrap {
  display: flex;
  justify-content: center;
}

.icon-wrapper-alert {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fffaeb;
}

.icon-wrapper-2-alert {
  width: 70%;
  height: 70%;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fef0c7;
}

.icon-alert {
  width: 50%;
  height: 50%;
  background-image: url("../assets/alert-triangle.svg");
  background-size: cover;
}

.icon-wrapper-danger {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fffaeb;
}

.icon-wrapper-2-danger {
  width: 70%;
  height: 70%;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #d92d20;
}

.icon-danger {
  width: 50%;
  height: 50%;
  border-radius: 50%;
  background-image: url("../assets/alert-circle-white.svg");
  background-size: cover;
}

.alert-danger {
  background: #e14b4b;
  color: #fff;
}

.alert-header {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

.content {
  display: flex;
  align-items: center;
  flex-direction: column;
}

.alert-title {
  font-weight: bold;
}

.alert-desc {
  font-size: 13px;
  margin: 6px;
}

/* Tabel */
.table-wrap {
  margin-top: 20px;
  overflow-x: auto;
}

table {
  width: 100%;
  border-radius: 12px;
  overflow: hidden;
  border-collapse: collapse;
  background: #fff;
  font-size: 15px;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
}

.no {
  display: flex;
  align-items: center;
}

th,
td {
  padding: 12px;
  border-bottom: 1px solid #eee;
}

th {
  background: #fafafa;
  font-weight: 600;
}

.warn {
  color: #d08700;
  margin-right: 6px;
}

.err {
  color: #c70000;
  margin-right: 6px;
}

tr:hover {
  background: #f9f9f9;
}
</style>
