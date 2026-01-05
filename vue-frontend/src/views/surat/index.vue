<template>
  <div class="page-inner">

    <h1 class="title">
      {{
        view === 'home' ? 'Layanan Mandiri'
        : view === 'cuti' ? 'Cuti'
        : view === 'aktif' ? 'Aktif Kembali'
        : view === 'pengajuan' ? 'Pengajuan Surat'
        : view === 'buat-surat' ? 'Buat Surat'
        : ''
      }}
    </h1>


    <div class="info-container">
      <div class="info"><p class="info-label">NIM</p><p class="info-value">{{ mahasiswa.nim }}</p></div>
      <div class="info"><p class="info-label">Nama</p><p class="info-value">{{ mahasiswa.nama }}</p></div>
      <div class="info"><p class="info-label">Fakultas</p><p class="info-value">{{ mahasiswa.fakultas }}</p></div>
      <div class="info"><p class="info-label">Program Studi</p><p class="info-value">{{ mahasiswa.prodi }}</p></div>
      <div class="info"><p class="info-label">Status</p><p class="info-value status">{{ mahasiswa.status }}</p></div>
    </div>

    <section v-if="view === 'home'">
      <section class="stats">
        <div class="stats-card">
          <div class="stats-label">Total Cuti</div>
          <div class="stats-value">{{ cuti.length }}</div>
        </div>
        <div class="stats-card">
          <div class="stats-label">Total Aktif Kembali</div>
          <div class="stats-value">{{ aktif.length }}</div>
        </div>
      </section>

      <section class="card layanan-list">
        <button class="layanan-item" @click="view='cuti'">
          <span>Cuti</span><span class="chevron">›</span>
        </button>
        <button class="layanan-item" @click="view='aktif'">
          <span>Aktif Kembali</span><span class="chevron">›</span>
        </button>
        <button class="layanan-item layanan-surat" @click="view='pengajuan'">
          <span>Pengajuan Surat</span><span class="chevron">›</span>
        </button>
      </section>
    </section>

    <section v-if="view === 'cuti' || view === 'aktif'" class="card">
      <div class="table-wrapper">
        <div class="table-header-top">
          <div class="table-title">
            {{ view === 'cuti' ? 'Riwayat Cuti' : 'Riwayat Aktif Kembali' }}
          </div>
          <input class="search-input" v-model="search" placeholder="Cari tahun/semester..." />
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tahun Ajar</th>
              <th>Semester</th>
              <th>Status</th>
              <th>Tgl Pengajuan</th>
              <th>Tgl Disetujui</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(i, idx) in filteredRiwayat" :key="i.id">
              <td>{{ idx + 1 }}</td>
              <td>{{ i.tahun_ajar }}</td>
              <td>{{ i.semester_ajar }}</td>

              <td>
                <span class="badge" :class="badgeClass(i.status)">
                  {{ i.status }}
                </span>
              </td>

              <td>{{ formatDate(i.tanggal_pengajuan) }}</td>
              <td>{{ formatDate(i.tanggal_disetujui) }}</td>
            </tr>

            <tr v-if="filteredRiwayat.length === 0">
              <td colspan="6" class="empty-cell">Data tidak ditemukan</td>
            </tr>
          </tbody>

        </table>

        <button class="back-button-bottom" @click="view='home'">← Kembali</button>
      </div>
    </section>

    <section v-if="view === 'pengajuan'" class="card">
      <div class="table-wrapper">
        <div class="table-header-top">
          <div class="table-title">Daftar Surat</div>
          <div class="toolbar-right">
            <button
              class="btn-green"
              @click="openCreate"
            >
              + Buat Surat
            </button>
            <input class="search-input" v-model="search" placeholder="Cari..." />
          </div>
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Jenis</th>
              <th>Status</th>
              <th>Tgl Pengajuan</th>
              <th>Tgl Disetujui</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(s, i) in filteredSurat" :key="s.id">
              <td>{{ i + 1 }}</td>
              <td>{{ s.jenis }}</td>

              <td>
                <span class="badge" :class="badgeClass(s.status)">
                  {{ s.status }}
                </span>
              </td>

              <td>{{ formatDate(s.tanggal_pengajuan) }}</td>
              <td>{{ formatDate(s.tanggal_disetujui) }}</td>

              <td class="cell-center">
                <a
                  v-if="s.file_url"
                  :href="s.file_url"
                  target="_blank"
                  class="badge badge-download"
                >
                  ⬇ Download
                </a>

                <span v-else class="badge badge-gray">
                  Belum tersedia
                </span>
              </td>

            </tr>

            <tr v-if="filteredSurat.length === 0">
              <td colspan="7" class="empty-cell">Belum ada surat</td>
            </tr>
          </tbody>

        </table>

        <button class="back-button-bottom" @click="view='home'">← Kembali</button>
      </div>
    </section>

    <section v-if="view === 'buat-surat'" class="card">
      <div class="table-wrapper">

        <div class="form-header">
          <div class="form-title">Pengajuan Surat</div>
          <div class="form-subtitle">
            Silakan lengkapi data pengajuan surat mahasiswa
          </div>
        </div>

        <div class="form-body">
          <label class="form-label">Jenis Surat</label>
          <select v-model="selectedJenis" class="form-select">
            <option disabled :value="null">-- Pilih Jenis Surat --</option>
            <option
              v-for="j in jenisSuratList"
              :key="j.id"
              :value="j"
            >
              {{ j.nama }}
            </option>
          </select>

          <label class="form-label">Keterangan Mahasiswa</label>
          <textarea
            v-model="keterangan"
            class="form-textarea"
            rows="4"
            placeholder="Tuliskan alasan / keterangan pengajuan surat..."
          ></textarea>

          <p v-if="keterangan" class="form-hint">
            Keterangan telah diisi
          </p>
        </div>
        <div class="form-actions">
          <button class="back-button-bottom" @click="view='pengajuan'">← Kembali</button>

          <button
            class="btn-green"
            :disabled="!canSubmit"
            @click="submitCreate"
          >
            Ajukan Surat
          </button>

        </div>

      </div>
    </section>

  </div>

</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "../../api.js";
import Swal from "sweetalert2";

const view = ref("home");
const search = ref("");

const mahasiswa = ref({ nim:"", nama:"", fakultas:"", prodi:"", status:"" });
const cuti = ref([]);
const aktif = ref([]);
const surat = ref([]);

onMounted(async () => {
  const u = JSON.parse(localStorage.getItem("user") || "{}");
  if (!u.nim) return;

  mahasiswa.value = {
    nim: String(u.nim),
    nama: u.nama_lengkap,
    status: u.status_mhs,
    prodi: u.program_studi_id?.nama_prodi || "-",
    fakultas: u.program_studi_id?.fakultas || "-"
  };

  const res = await api.get("/surat/pengajuan", {
    params: {
      mahasiswa_id: String(u.nim).trim()
    }
  });

  surat.value = (res.data || []).map(i => ({
    id: i.id,
    jenis: i.jenis_surat?.nama_surat || "-",
    status: (i.status || "").toString().trim().toLowerCase(),

    tanggal_pengajuan: i.tgl_pengajuan,
    tanggal_disetujui: i.tgl_disetujui || null,

    file_url: i.file_surat_path
      ? `${import.meta.env.VITE_API_URL}/${i.file_surat_path}`: null,

    tahun_ajar: new Date(i.tgl_pengajuan).getFullYear(),
    semester_ajar:
      new Date(i.tgl_pengajuan).getMonth() < 6 ? "Genap" : "Ganjil",
  }));

  cuti.value = surat.value.filter(s =>
    s.jenis.toLowerCase().includes("cuti")
  );

  aktif.value = surat.value.filter(s =>
    !s.jenis.toLowerCase().includes("cuti")
  );
});

const formatDate = (val) => {
  if (!val) return "-";
  return new Date(val).toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric"
  });
};

const badgeClass = (status) => {
  const s = String(status).trim().toLowerCase();

  const map = {
    diproses: "badge-yellow",
    proses: "badge-yellow",

    ditolak: "badge-red",
    reject: "badge-red",

    diajukan: "badge-blue",
    submit: "badge-blue",

    diterima: "badge-green",
    disetujui: "badge-green",
    acc: "badge-green",
    approved: "badge-green",
    "1": "badge-green",
  };

  return map[s] || "badge-gray";
};

const keterangan = ref("");

const canSubmit = computed(() => {
  return !!selectedJenis.value && keterangan.value.trim().length > 0;
});


const submitCreate = async () => {
  if (!canSubmit.value) return;

  const payload = {
    mahasiswa_id: mahasiswa.value.nim,
    jenis_surat_id: selectedJenis.value.id,
    tgl_pengajuan: new Date().toISOString().slice(0, 10),
    keterangan_mhs: keterangan.value,
    status: "DIAJUKAN",
  };

  try {
    await api.post("/surat/pengajuan", payload);

    Swal.fire({
      icon: "success",
      title: "Berhasil",
      text: "Pengajuan surat berhasil dikirim",
      confirmButtonText: "OK",
      confirmButtonColor: "#2e7d32",
    });

    // reset form
    selectedJenis.value = null;
    keterangan.value = "";
    view.value = "pengajuan";

  } catch (err) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text:
        err.response?.data?.message ||
        "Terjadi kesalahan saat mengajukan surat",
      confirmButtonText: "Tutup",
      confirmButtonColor: "#c62828",
    });
  }
};


const openCreate = () => {
  view.value = 'buat-surat';
}

const jenisSuratList = ref([
  { id: 1, nama: "Surat Cuti" },
  { id: 2, nama: "Surat Aktif Kembali" },
]);

const selectedJenis = ref(null);

const filteredRiwayat = computed(() => {
  const q = search.value;
  const list = view.value === "cuti" ? cuti.value : aktif.value;
  if (!q) return list;
  return list.filter(i =>
    String(i.tahun_ajar).includes(q) ||
    String(i.semester_ajar).includes(q)
  );
});

const filteredSurat = computed(() => {
  const q = search.value.toLowerCase();
  if (!q) return surat.value;
  return surat.value.filter(s =>
    s.jenis.toLowerCase().includes(q) ||
    s.status.toLowerCase().includes(q)
  );
});

</script>

<style>
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

.title{font-size:28px;font-weight:700;color:#555;margin-bottom:24px;}
.info-container{display:flex;flex-direction:column;gap:8px;margin-bottom:24px;}
.info-label{font-size:13px;color:#6b7280;margin:0;}
.info-value{font-size:14px;color:#111827;margin:0;}
.status{font-weight:600;color:#2e7d32;}

.card{border-radius:10px;border:1px solid #e5e5e5;background:#fff;overflow:hidden;margin-bottom:16px;}
.table-wrapper{padding:12px 12px 18px;}
.table-header-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;}
.table-title{font-size:14px;font-weight:600;color:#333;}
.toolbar-right{display:flex;gap:10px;align-items:center;}
.search-input{padding:6px 10px;border-radius:16px;border:1px solid #d0d0d0;font-size:12px;min-width:150px;}

.table{width:100%;border-collapse:collapse;font-size:12px;}
.table th,.table td{padding:8px 10px;}
.table thead tr{border-bottom:1px solid #e5e5e5;background:#fafafa;}
.table-row + .table-row{border-top:1px solid #f1f1f1;}
.table-row:hover{background:#f5f5f5;cursor:pointer;}
.empty-cell{text-align:center;padding:10px;color:#999;}
.back-button-bottom{margin-top:14px;border-radius:20px;border:1px solid #1976d2;background:#e3f2fd;font-size:13px;cursor:pointer;padding:6px 14px;color:#1976d2;}

.btn-green{border:1px solid #bfe9c6;background:#e9f8ee;padding:6px 12px;border-radius:16px;font-size:12px;cursor:pointer;font-weight:600;}
.btn-green:hover{background:#dff5e7;}
.btn-outline{border:1px solid #d0d0d0;background:#fff;padding:6px 10px;border-radius:12px;font-size:12px;cursor:pointer;margin-right:6px;}
.btn-danger{border:1px solid #ffcdd2;background:#ffebee;padding:6px 10px;border-radius:12px;font-size:12px;cursor:pointer;}

.read-box{font-size:13px;line-height:1.5;}

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

.layanan-surat{background:#e9f8ee;}
.layanan-surat:hover{background:#dff5e7;}

.badge{
  padding:4px 10px;
  border-radius:12px;
  font-size:11px;
  font-weight:600;
  text-transform:capitalize;
  display:inline-block;
}

.badge-yellow{
  background:#fff8e1;
  color:#f9a825;
  border:1px solid #ffe082;
}

.badge-red{
  background:#ffebee;
  color:#c62828;
  border:1px solid #ffcdd2;
}

.badge-blue{
  background:#e3f2fd;
  color:#1565c0;
  border:1px solid #bbdefb;
}

.badge-green{
  background:#e8f5e9;
  color:#2e7d32;
  border:1px solid #c8e6c9;
}

.badge-gray{
  background:#f5f5f5;
  color:#616161;
  border:1px solid #e0e0e0;
}

.text-muted{
  font-size:12px;
  color:#9e9e9e;
  font-style:italic;
}
.btn-green:disabled{
  opacity: 100%;
  cursor:not-allowed;
}

.badge-download{
  background:#e3f2fd;
  color:#1565c0;
  border:1px solid #bbdefb;
  text-decoration:none;
  cursor:pointer;
}

.badge-download:hover{
  background:#bbdefb;
  color:#0d47a1;
}

.cell-center{
  text-align: center;
  vertical-align: middle;
}

/* ================================
   FORM BUAT SURAT (FINAL CLEAN UI)
   ================================ */

.form-header{
  margin-bottom: 18px;
}

.form-title{
  font-size: 16px;
  font-weight: 700;
  color: #374151;
}

.form-subtitle{
  margin-top: 4px;
  font-size: 12.5px;
  color: #6b7280;
  line-height: 1.4;
}

/* FORM BODY */
.form-body{
  display: grid;
  gap: 12px;
  margin-bottom: 20px;
}

/* LABEL */
.form-label{
  font-size: 12px;
  font-weight: 600;
  color: #4b5563;
}

/* SELECT */
.form-select{
  width: 100%;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #d1d5db;
  font-size: 13px;
  background-color: #ffffff;
  transition: border .2s ease, box-shadow .2s ease;
}

.form-select:focus{
  outline: none;
  border-color: #90caf9;
  box-shadow: 0 0 0 3px rgba(144,202,249,.35);
}

/* TEXTAREA */
.form-textarea{
  width: 100%;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #d1d5db;
  font-size: 13px;
  resize: vertical;
  min-height: 90px;
  transition: border .2s ease, box-shadow .2s ease;
}

.form-textarea:focus{
  outline: none;
  border-color: #81c784;
  box-shadow: 0 0 0 3px rgba(129,199,132,.35);
}

/* HINT */
.form-hint{
  font-size: 12px;
  color: #2e7d32;
  background: linear-gradient(135deg,#e8f5e9,#ffffff);
  border: 1px solid #c8e6c9;
  padding: 6px 10px;
  border-radius: 8px;
}

/* ACTIONS */
.form-actions{
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 14px;
  border-top: 1px solid #e5e7eb;
}

/* BUTTON DISABLED IMPROVEMENT */
.btn-green:disabled{
  background: #e5e7eb;
  border-color: #d1d5db;
  color: #9ca3af;
  cursor: not-allowed;
}

/* MOBILE FRIENDLY */
@media (max-width: 640px){
  .form-actions{
    flex-direction: column-reverse;
    gap: 10px;
  }

  .form-actions button{
    width: 100%;
  }
}
</style>
