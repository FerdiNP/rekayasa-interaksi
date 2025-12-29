<template>
  <div class="page-inner">
    <h1 class="title">Jadwal Kuliah</h1>

    <div class="info-container">
      <div class="info">
        <p class="info-label">NIM</p>
        <p class="info-value">{{ user?.nim }}</p>
      </div>
      <div class="info">
        <p class="info-label">Nama</p>
        <p class="info-value">{{ user?.nama_lengkap }}</p>
      </div>
      <div class="info">
        <p class="info-label">Tahun Akademik</p>
        <p class="info-value">2025/2026</p>
      </div>
      <div class="info">
        <p class="info-label">Semester</p>
        <p class="info-value">Ganjil</p>
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
          <tr v-for="(row, index) in jadwal" :key="index">
            <td>
              <div class="no">
                <div
                  v-if="
                    row.kelas_kuliah_baru.kode_kelas !==
                    row.kelas_kuliah_lama.kode_kelas
                  "
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
                  v-if="
                    `${row.kelas_kuliah_baru.hari} ${row.kelas_kuliah_baru.jam_mulai} - ${row.kelas_kuliah_baru.jam_selesai}` !==
                    `${row.kelas_kuliah_lama.hari} ${row.kelas_kuliah_lama.jam_mulai} - ${row.kelas_kuliah_lama.jam_selesai}`
                  "
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
            <td>{{ row.kelas_kuliah_baru.mata_kuliah.nama_mk }}</td>
            <td>{{ row.kelas_kuliah_baru.mata_kuliah.sks }}</td>
            <td>{{ row.kelas_kuliah_baru.kode_kelas }}</td>
            <td>{{ row.kelas_kuliah_baru.dosen.nama_dosen }}</td>
            <td>{{ row.kelas_kuliah_baru.ruang }}</td>
            <td>
              {{
                `${row.kelas_kuliah_baru.hari} ${row.kelas_kuliah_baru.jam_mulai} - ${row.kelas_kuliah_baru.jam_selesai}`
              }}
            </td>
            <td>
              <button
                v-if="
                  row.kelas_kuliah_baru.jam_mulai <=
                    new Date().toTimeString().slice(0, 8) &&
                  row.kelas_kuliah_baru.jam_selesai >=
                    new Date().toTimeString().slice(0, 8) &&
                  row.kelas_kuliah_baru.hari ==
                    new Intl.DateTimeFormat('id-ID', { weekday: 'long' })
                      .format(new Date())
                      .toUpperCase()
                "
                class="btn-presensi"
                @click="presensi(row)"
              >
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
    :pesan="pesanPresensi"
    @close="showPresensiSuccess = false"
  />
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import AlertDetails from "../../components/alertDetails.vue";
import NotificationJadwal from "../../components/notificationJadwal.vue";
import PresensiSuccess from "../../components/presensiSuccess.vue";
import api from "../../api";

const showAlert = ref(false);
const showNotification = ref(false);
const showPresensiSuccess = ref(false);
const selectedAlertType = ref("warning");
const selectedRow = ref(null);
const user = ref(null);
const jadwal = ref(null);
let intervalId = null;
const pesanPresensi = ref("");

onMounted(() => {
  user.value = JSON.parse(localStorage.getItem("user"));
  jadwal.value =
    localStorage.getItem("jadwal") != "undefined"
      ? JSON.parse(localStorage.getItem("jadwal"))
      : null;
  intervalId = setInterval(async () => {
    try {
      const res = await api.get(`/jadwal/${user.value.nim}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          Accept: "application/json",
        },
      });
      localStorage.setItem("jadwal", JSON.stringify(res.data.data));
      jadwal.value =
        localStorage.getItem("jadwal") != "undefined"
          ? JSON.parse(localStorage.getItem("jadwal"))
          : null;
    } catch (err) {
      console.error("Error fetch jadwal:", err);
    }
  }, 2000);
});

onUnmounted(() => {
  if (intervalId) {
    clearInterval(intervalId);
    intervalId = null;
    console.log("Interval dihentikan");
  }
});

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

async function presensi(row) {
  const now = new Date();
  console.log("Waktu: " + new Date().toTimeString().slice(0, 8));
  const pad = (n) => n.toString().padStart(2, "0");
  const tanggal = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(
    now.getDate()
  )}`;
  const waktu = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(
    now.getSeconds()
  )}`;
  const tanggalWaktu = `${tanggal} ${waktu}`;
  console.log(tanggalWaktu);
  try {
    const res = await api.put(
      `/presensi`,
      {
        dateTime: tanggalWaktu,
        tanggal: tanggal,
        jam: waktu,
        id: row?.id,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      }
    );
    const data = await res;
    console.log(data.data.message);
    pesanPresensi.value = data.data.message;
  } catch (err) {
    console.error("Error fetch jadwal:", err);
  }
  showPresensiSuccess.value = true;
}
</script>

<style scoped>
.page {
  min-height: auto;
  width: 100%;
  box-sizing: border-box;
  padding: 24px 16px 40px;
  display: flex;
  justify-content: center;
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
  color: #555;
  margin-bottom: 24px;
}

.info-container {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 24px;
}

.info-label {
  font-size: 13px;
  color: #6b7280;
  margin: 0;
}

.info-value {
  font-size: 14px;
  color: #111827;
  margin: 0;
}

.alert {
  padding: 12px 14px;
  border-radius: 18px;
  margin-top: 12px;
  text-align: center;
  border: none;
}

.alert-warning {
  background: #fee9a6;
  color: #4f3b00;
}

.alert-danger {
  background: #f97373;
  color: #ffffff;
}

.alert-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}

.content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.alert-title {
  font-weight: 700;
  font-size: 14px;
}

.alert-desc {
  font-size: 12px;
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
  width: 40px;
  height: 40px;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fffaeb;
}

.icon-wrapper-2-alert,
.icon-wrapper-2-danger {
  width: 65%;
  height: 65%;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-wrapper-2-alert {
  background: #fef0c7;
}

.icon-wrapper-2-danger {
  background: #d92d20;
}

.icon-alert {
  width: 55%;
  height: 55%;
  background-image: url("../../assets/alert-triangle.svg");
  background-size: cover;
  background-position: center;
}

.icon-danger {
  width: 55%;
  height: 55%;
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
  color: #ffffff;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
}

.btn-presensi:active {
  transform: scale(0.98);
}
</style>
