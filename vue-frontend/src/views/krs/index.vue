<template>
  <div class="page-inner">
    <h1 class="title">Pemrograman KRS</h1>

    <div class="info-container">
      <div class="info">
        <p class="info-label">NIM</p>
        <p class="info-value">{{ nim }}</p>
      </div>
      <div class="info">
        <p class="info-label">Nama</p>
        <p class="info-value">{{ nama }}</p>
      </div>
      <div class="info">
        <p class="info-label">Tahun Akademik</p>
        <p class="info-value">{{ tahunAkademik }}</p>
      </div>
      <div class="info">
        <p class="info-label">Semester</p>
        <p class="info-value">{{ semesterAktif }}</p>
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
          <span class="alert-title">Jadwal Kelas Belum Tersedia</span>
          <p class="alert-desc">
            Saat ini jadwal dari fakultas belum diunggah. Silakan cek kembali
            setelah tanggal 20 Oktober 2025 atau hubungi admin program studi
            jika masalah berlanjut.
          </p>
        </div>
      </div>
    </div>

    <div v-for="(mk, index) in matkulList" :key="mk.id" class="course-card">
      <button class="course-header" @click="toggleMatkul(index)">
        <span class="course-title">
          {{ mk.nama }} | Semester {{ mk.semester }} ({{ mk.sks }} SKS)
        </span>
        <span class="course-arrow" :class="{ open: mk.isOpen }">⌃</span>
      </button>

      <transition name="accordion">
        <div v-if="mk.isOpen" class="course-body">
          <div class="course-body-scroll">
            <table class="krs-table">
              <thead>
                <tr>
                  <th>Kelas</th>
                  <th>Jadwal</th>
                  <th>Nilai</th>
                  <th>Kuota</th>
                  <th>Terisi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, i) in mk.kelas" :key="i">
                  <td>{{ row.kelas }}</td>
                  <td>{{ row.jadwal }}</td>
                  <td>{{ row.nilai || "-" }}</td>
                  <td>{{ row.kuota }}</td>
                  <td>{{ row.terisi }}</td>
                  <td>
                    <button
                      v-if="!row.full"
                      class="btn-pilih"
                      @click="pilihKelas(mk, row)"
                    >
                      Pilih
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </transition>
    </div>
  </div>

  <JadwalKelasBelumTersedia
    v-if="showJadwalModal"
    @close="showJadwalModal = false"
  />
</template>

<script setup>
import { ref, onMounted } from "vue";
import JadwalKelasBelumTersedia from "../../components/KRSUnavailable.vue";
import api from "../../api";

const matkulList = ref([]);

const showJadwalModal = ref(false);
const selectedMatkul = ref(null);
const selectedKelas = ref(null);

const nim = ref("-");
const nama = ref("-");
const tahunAkademik = ref("-");
const semesterAktif = ref("-");

onMounted(() => {
  const storedUser = localStorage.getItem("user");
  const storedSemester = localStorage.getItem("semester_aktif");
  const storedMahasiswa = localStorage.getItem("semester_mahasiswa");

  if (storedUser) {
    const user = JSON.parse(storedUser);
    nim.value = user.nim || "-";
    nama.value = user.nama_lengkap || "-";
  }

  if (storedSemester) {
    const SemesterAkademik = JSON.parse(storedSemester);
    const SemesterMahasiswa = JSON.parse(storedMahasiswa);
    tahunAkademik.value = SemesterAkademik.nama_semester || "-";
    semesterAktif.value = SemesterMahasiswa || "-";
  }

    fetchKRS();
});

async function fetchKRS() {
  if (!nim.value || nim.value === "-") return;

  try {
    const res = await api.get(`/mahasiswa/${nim.value}/krs`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
        Accept: "application/json",
      },
    });

    const data = res.data.data || [];

    matkulList.value = data.map((item) => ({
      id: item.mata_kuliah.id,
      nama: item.mata_kuliah.nama_mk,
      semester: item.mata_kuliah.semester,
      sks: item.mata_kuliah.sks,
      isOpen: false,
      kelas: item.kelas.map((k) => ({
        kelas: k.kode_kelas,
        jadwal: `${k.hari} ${k.jam}`,
        nilai: k.nilai ?? "",
        kuota: k.kuota,
        terisi: k.terisi,
        full: k.terisi >= k.kuota,
      })),
    }));
  } catch (err) {
    console.error("Gagal ambil KRS:", err.response || err);
  }
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

.icon-wrapper-alert {
  width: 40px;
  height: 40px;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fffaeb;
}

.icon-wrapper-2-alert {
  width: 65%;
  height: 65%;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fef0c7;
}

.icon-alert {
  width: 55%;
  height: 55%;
  background-image: url("../../assets/alert-triangle.svg");
  background-size: cover;
  background-position: center;
}

.course-card {
  margin-top: 18px;
  border-radius: 25px;
  background: #ffffff;
  box-shadow: 0px 6px 20px rgba(15, 23, 42, 0.08);
  overflow: hidden;
}

.course-header {
  width: 100%;
  border: none;
  outline: none;
  padding: 10px 14px;
  background: #b7d36b;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
}

.course-title {
  font-size: 13px;
  font-weight: 600;
  color: #111827;
  text-align: left;
}

.course-arrow {
  font-size: 14px;
  transition: transform 0.2s ease;
  display: inline-block;
  transform: rotate(180deg);
}

.course-arrow.open {
  transform: rotate(0deg);
}

.course-body {
  padding: 0;
}

.course-body-scroll {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.krs-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
  min-width: 600px;
}

@media (min-width: 768px) {
  .course-body-scroll {
    overflow-x: visible;
  }

  .krs-table {
    min-width: 100%;
  }
}

.krs-table th,
.krs-table td {
  padding: 10px 12px;
  border-bottom: 1px solid #f1f5f9;
  text-align: left;
}

.krs-table th {
  background: #f9fafb;
  font-weight: 600;
  color: #6b7280;
  font-size: 12px;
}

.krs-table tr:last-child td {
  border-bottom: none;
}

.krs-table tr:hover {
  background: #f9fafb;
}

.btn-pilih {
  padding: 6px 12px;
  border-radius: 999px;
  border: 1px solid #e5e7eb;
  background: #2cc3ff;
  color: #ffffff;
  font-size: 12px;
  cursor: pointer;
  white-space: nowrap;
}
</style>
