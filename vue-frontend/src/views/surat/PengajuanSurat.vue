<template>
  <div class="page-inner">
    <h1 class="title">Pengajuan Surat</h1>

    <div class="info-container">
      <div class="info"><p class="info-label">NIM</p><p class="info-value">{{ mahasiswa.nim }}</p></div>
      <div class="info"><p class="info-label">Nama</p><p class="info-value">{{ mahasiswa.nama }}</p></div>
      <div class="info"><p class="info-label">Fakultas</p><p class="info-value">{{ mahasiswa.fakultas }}</p></div>
      <div class="info"><p class="info-label">Program Studi</p><p class="info-value">{{ mahasiswa.prodi }}</p></div>
      <div class="info"><p class="info-label">Status</p><p class="info-value status">{{ mahasiswa.status }}</p></div>
    </div>

    <section class="card">
      <div class="table-wrapper">
        <div class="table-header-top">
          <div class="table-title">Daftar Surat</div>
          <div class="toolbar-right">
            <button class="btn-green" @click="openCreate">+ Buat Surat</button>
            <input class="search-input" v-model="search" placeholder="Cari jenis/judul/status..." />
          </div>
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Jenis</th>
              <th>Judul</th>
              <th>Status</th>
              <th style="width:230px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(s, idx) in filtered" :key="s.id" class="table-row">
              <td>{{ idx + 1 }}</td>
              <td>{{ s.jenis }}</td>
              <td>{{ s.judul }}</td>
              <td>{{ s.status }}</td>
              <td>
                <button class="btn-outline" @click="openRead(s)">Read</button>
                <button class="btn-outline" @click="openEdit(s)">Update</button>
                <button class="btn-danger" @click="remove(s.id)">Delete</button>
              </td>
            </tr>

            <tr v-if="filtered.length === 0">
              <td colspan="5" class="empty-cell">Belum ada surat</td>
            </tr>
          </tbody>
        </table>

        <button class="back-button-bottom" @click="goBack">← Kembali</button>
      </div>
    </section>

    <!-- Modal Form -->
    <div v-if="modal.open" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <div class="modal-title">
          {{ modal.mode === "create" ? "Buat Surat" : "Update Surat" }}
        </div>

        <div class="modal-form">
          <label>Jenis Surat</label>
          <input class="modal-input" v-model="form.jenis" placeholder="Contoh: Surat Keterangan Aktif Kuliah" />

          <label>Judul</label>
          <input class="modal-input" v-model="form.judul" placeholder="Contoh: Permohonan Surat ..." />

          <label>Keterangan</label>
          <textarea class="modal-input" rows="4" v-model="form.keterangan" placeholder="Isi keperluan..."></textarea>

          <label>Status</label>
          <select class="modal-input" v-model="form.status">
            <option value="draft">draft</option>
            <option value="diajukan">diajukan</option>
          </select>
        </div>

        <div class="modal-actions">
          <button class="btn-outline" @click="closeModal">Batal</button>
          <button class="btn-green" @click="submit">
            {{ modal.mode === "create" ? "Simpan" : "Update" }}
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Read -->
    <div v-if="read.open" class="modal-overlay" @click.self="closeRead">
      <div class="modal">
        <div class="modal-title">Detail Surat</div>
        <div class="read-box">
          <div><b>Jenis:</b> {{ read.data?.jenis }}</div>
          <div><b>Judul:</b> {{ read.data?.judul }}</div>
          <div><b>Status:</b> {{ read.data?.status }}</div>
          <div style="margin-top:8px;"><b>Keterangan:</b><br />{{ read.data?.keterangan }}</div>
        </div>
        <div class="modal-actions">
          <button class="btn-outline" @click="closeRead">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const mahasiswa = ref({
  nama: "Ferdi Naufal Prasetyo",
  nim: "202210370311272",
  fakultas: "Teknik",
  prodi: "Program Studi Informatika",
  status: "aktif",
});

const search = ref("");
const list = ref([]);

// simpan surat per NIM
const LS_KEY = computed(() => `pengajuan_surat:${mahasiswa.value.nim}`);

const loadLocal = () => {
  try {
    const raw = localStorage.getItem(LS_KEY.value);
    list.value = raw ? JSON.parse(raw) : [];
  } catch {
    list.value = [];
  }
};

const saveLocal = () => {
  localStorage.setItem(LS_KEY.value, JSON.stringify(list.value));
};

const filtered = computed(() => {
  const q = search.value.trim().toLowerCase();
  if (!q) return list.value;
  return list.value.filter(
    (s) =>
      (s.jenis || "").toLowerCase().includes(q) ||
      (s.judul || "").toLowerCase().includes(q) ||
      (s.status || "").toLowerCase().includes(q)
  );
});

const modal = ref({ open: false, mode: "create", editId: null });
const form = ref({ jenis: "", judul: "", keterangan: "", status: "draft" });

const read = ref({ open: false, data: null });

const fetchMahasiswa = () => {
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

onMounted(() => {
  fetchMahasiswa();
  loadLocal();
});

const openCreate = () => {
  modal.value = { open: true, mode: "create", editId: null };
  form.value = { jenis: "", judul: "", keterangan: "", status: "draft" };
};
const openEdit = (s) => {
  modal.value = { open: true, mode: "edit", editId: s.id };
  form.value = { jenis: s.jenis, judul: s.judul, keterangan: s.keterangan, status: s.status };
};
const closeModal = () => (modal.value.open = false);

const submit = () => {
  if (!form.value.jenis.trim()) return alert("Jenis wajib diisi!");
  if (!form.value.judul.trim()) return alert("Judul wajib diisi!");

  if (modal.value.mode === "create") {
    list.value.unshift({ id: Date.now(), ...form.value });
  } else {
    const idx = list.value.findIndex((x) => x.id === modal.value.editId);
    if (idx !== -1) list.value[idx] = { ...list.value[idx], ...form.value };
  }

  saveLocal();
  closeModal();
};

const remove = (id) => {
  if (!confirm("Yakin hapus surat ini?")) return;
  list.value = list.value.filter((x) => x.id !== id);
  saveLocal();
};

const openRead = (s) => (read.value = { open: true, data: s });
const closeRead = () => (read.value = { open: false, data: null });

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

.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.35);display:flex;justify-content:center;align-items:center;padding:18px;z-index:9999;}
.modal{width:100%;max-width:520px;background:#fff;border-radius:14px;padding:14px;}
.modal-title{font-weight:700;margin-bottom:10px;}
.modal-form{display:grid;gap:8px;}
.modal-form label{font-size:12px;color:#555;font-weight:600;}
.modal-input{width:100%;border:1px solid #e0e0e0;border-radius:10px;padding:10px 12px;font-size:13px;}
.modal-actions{margin-top:12px;display:flex;justify-content:flex-end;gap:10px;}
.read-box{font-size:13px;line-height:1.5;}
</style>
