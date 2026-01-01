<template>
  <div class="page-inner">
    <h1 class="title">Cuti</h1>

    <div class="info-container">
      <div class="info">
        <p class="info-label">NIM</p>
        <p class="info-value">{{ mahasiswa.nim }}</p>
      </div>
      <div class="info">
        <p class="info-label">Nama</p>
        <p class="info-value">{{ mahasiswa.nama }}</p>
      </div>
      <div class="info">
        <p class="info-label">Fakultas</p>
        <p class="info-value">{{ mahasiswa.fakultas }}</p>
      </div>
      <div class="info">
        <p class="info-label">Program Studi</p>
        <p class="info-value">{{ mahasiswa.prodi }}</p>
      </div>
      <div class="info">
        <p class="info-label">Status</p>
        <p class="info-value status">{{ mahasiswa.status }}</p>
      </div>
    </div>

    <section class="card">
      <div class="table-wrapper">
        <div class="table-header-top">
          <div class="table-title">Riwayat Cuti</div>
          <input class="search-input" v-model="search" placeholder="Cari tahun/semester..." />
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tahun Ajar</th>
              <th>Semester Ajar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in filtered" :key="item.id" class="table-row">
              <td>{{ idx + 1 }}</td>
              <td>{{ item.tahun_ajar }}</td>
              <td>{{ item.semester_ajar }}</td>
            </tr>

            <tr v-if="filtered.length === 0">
              <td colspan="3" class="empty-cell">Data tidak ditemukan</td>
            </tr>
          </tbody>
        </table>

        <button class="back-button-bottom" @click="goBack">← Kembali</button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../../api.js";

const router = useRouter();

const mahasiswa = ref({
  nama: "Ferdi Naufal Prasetyo",
  nim: "202210370311272",
  fakultas: "Teknik",
  prodi: "Program Studi Informatika",
  status: "aktif",
});

const tabel = ref([]);
const search = ref("");

const defaultCuti = [
  { id: 1, tahun_ajar: "2023", semester_ajar: "2" },
  { id: 2, tahun_ajar: "2024", semester_ajar: "3" },
];

const filtered = computed(() => {
  const q = search.value.trim().toLowerCase();
  const list = tabel.value.length ? tabel.value : defaultCuti;
  if (!q) return list;
  return list.filter(
    (i) =>
      String(i.tahun_ajar).toLowerCase().includes(q) ||
      String(i.semester_ajar).toLowerCase().includes(q)
  );
});

const fetchMahasiswa = () => {
  // ambil dari localStorage "user" kalau ada
  try {
    const raw = localStorage.getItem("user");
    const u = raw ? JSON.parse(raw) : null;
    if (u) {
      mahasiswa.value = {
        nama: u.nama_lengkap ?? u.nama ?? mahasiswa.value.nama,
        nim: u.nim ?? mahasiswa.value.nim,
        fakultas: u.fakultas ?? mahasiswa.value.fakultas,
        prodi: u.prodi ?? mahasiswa.value.prodi,
        status: u.status_mahasiswa ?? u.status ?? mahasiswa.value.status,
      };
    }
  } catch {}
};

const fetchCuti = async () => {
  try {
    // kalau backend butuh nim / mahasiswa_id, kamu bisa tambahkan params sesuai API kamu
    const res = await api.get("/pengajuan-surat");
    const data = res.data || [];

    const cuti = data
      .filter((i) => i.jenis_surat?.nama_surat?.toLowerCase().includes("cuti"))
      .map((i) => ({
        id: i.id ?? i.pengajuan_id ?? Math.random(),
        tahun_ajar: i.tahun_ajar ?? i.tahunAjar ?? i.tahun ?? "-",
        semester_ajar: i.semester_ajar ?? i.semesterAjar ?? i.semester ?? "-",
      }));

    tabel.value = cuti.length ? cuti : defaultCuti;
  } catch (e) {
    tabel.value = defaultCuti;
  }
};

onMounted(() => {
  fetchMahasiswa();
  fetchCuti();
});

const goBack = () => router.push({ name: "surat" });
</script>

<style scoped>
.title{font-size:28px;font-weight:700;color:#555;margin-bottom:24px;}
.info-container{display:flex;flex-direction:column;gap:8px;margin-bottom:24px;}
.info-label{font-size:13px;color:#6b7280;margin:0;}
.info-value{font-size:14px;color:#111827;margin:0;}
.status{font-weight:600;color:#2e7d32;}

.card{border-radius:10px;border:1px solid #e5e5e5;background:#fff;overflow:hidden;margin-bottom:16px;}
.table-wrapper{padding:12px 12px 18px;}
.table-header-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;}
.table-title{font-size:14px;font-weight:600;color:#333;}
.search-input{padding:6px 10px;border-radius:16px;border:1px solid #d0d0d0;font-size:12px;min-width:150px;}
.table{width:100%;border-collapse:collapse;font-size:12px;}
.table th,.table td{padding:8px 10px;}
.table thead tr{border-bottom:1px solid #e5e5e5;background:#fafafa;}
.table-row + .table-row{border-top:1px solid #f1f1f1;}
.table-row:hover{background:#f5f5f5;cursor:pointer;}
.empty-cell{text-align:center;padding:10px;color:#999;}
.back-button-bottom{margin-top:14px;border-radius:20px;border:1px solid #1976d2;background:#e3f2fd;font-size:13px;cursor:pointer;padding:6px 14px;color:#1976d2;}
</style>
