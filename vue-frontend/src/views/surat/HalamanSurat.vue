<template>
  <div class="page-inner">
    <h1 class="title">Layanan Mandiri</h1>

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

    <section class="card layanan-list">
      <button class="layanan-item" @click="go('layanan.cuti')">
        <span>Cuti</span><span class="chevron">›</span>
      </button>

      <button class="layanan-item" @click="go('layanan.aktif')">
        <span>Aktif Kembali</span><span class="chevron">›</span>
      </button>

      <button class="layanan-item layanan-surat" @click="go('layanan.surat')">
        <span>Pengajuan Surat</span><span class="chevron">›</span>
      </button>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../api.js";

const route = useRoute();
const router = useRouter();
const mahasiswa = reactive({
  nim: "",
  nama: "",
  fakultas: "",
  prodi: "",
  status: "",
});
const tabelCuti = ref([]);
const tabelAktif = ref([]);

onMounted( async () => {
  const storedUser = localStorage.getItem("user");

  if (storedUser) {
    const user = JSON.parse(storedUser);
    mahasiswa.nim = user.nim;
    mahasiswa.nama = user.nama_lengkap;
    mahasiswa.status = user.status_mhs;
    mahasiswa.prodi = user.program_studi_id?.nama_prodi || "-";
    mahasiswa.fakultas = user.program_studi_id?.fakultas || "-";
  }

  await fetchKrsAktif();
  await fetchPengajuanSurat();
});

const fetchKrsAktif = async () => {
  const res = await api.get(`/krs/aktif/${mahasiswaId}`);
  const data = res.data;

  tahunAkademik.value = data.semester_akademik?.nama_semester ?? "-";

  const s = Number(data.semester);
  semesterAktif.value = isNaN(s)
    ? "-"
    : s % 2 === 0
    ? "Genap"
    : "Ganjil";
};

const fetchPengajuanSurat = async () => {
  const res = await api.get("/pengajuan-surat", {
    params: { mahasiswa_id: mahasiswaId },
  });

  const data = res.data || [];

  tabelCuti.value = data.filter((i) =>
    i.jenis_surat?.nama_surat?.toLowerCase().includes("cuti")
  );

  tabelAktif.value = data.filter(
    (i) =>
      i.jenis_surat &&
      !i.jenis_surat.nama_surat.toLowerCase().includes("cuti")
  );
};

const jumlahCuti = computed(() => tabelCuti.value.length);
const jumlahAktif = computed(() => tabelAktif.value.length);

const go = (name) =>
  router.push({ name, params: { id: mahasiswaId } });
</script>


<style scoped>
/* pakai style yang kamu sudah punya, ini minimal biar jalan */
.title{font-size:28px;font-weight:700;color:#555;margin-bottom:24px;}
.info-container{display:flex;flex-direction:column;gap:8px;margin-bottom:24px;}
.info-label{font-size:13px;color:#6b7280;margin:0;}
.info-value{font-size:14px;color:#111827;margin:0;}
.status{font-weight:600;color:#2e7d32;}
.stats{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-bottom:18px;}
.stats-card{padding:10px 12px;border-radius:10px;background:linear-gradient(135deg,#e3f2fd,#ffffff);border:1px solid #d0e3ff;}
.stats-label{font-size:12px;color:#555;margin-bottom:4px;}
.stats-value{font-size:18px;font-weight:600;color:#1976d2;}

.card{border-radius:10px;border:1px solid #e5e5e5;background:#fff;overflow:hidden;}
.layanan-item{width:100%;padding:12px 14px;border:none;border-bottom:1px solid #f0f0f0;background:#fff;cursor:pointer;display:flex;justify-content:space-between;align-items:center;}
.layanan-item:last-child{border-bottom:none;}
.chevron{color:#999;}

/* ✅ hijau muda */
.layanan-surat{background:#e9f8ee;}
.layanan-surat:hover{background:#dff5e7;}
</style>
