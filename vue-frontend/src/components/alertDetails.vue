<template>
  <div class="page">
    <div class="overlay"></div>

    <div
      v-if="alertType === 'warning'"
      class="modal-card"
      role="dialog"
      aria-modal="true"
    >
      <div class="icon-wrap">
        <div class="icon-wrapper-alert">
          <div class="icon-wrapper-2-alert">
            <div class="icon-alert"></div>
          </div>
        </div>
      </div>

      <h2 class="title">Perubahan Kelas!</h2>
      <p class="subtitle">
        Kelas anda telah berpindah karena kuota penuh!
        <br /><br />
        Mata Kuliah : {{ data?.kelas_kuliah_baru.mata_kuliah.nama_mk || "-" }}
        <br />
        Kelas :
        {{
          `${data?.kelas_kuliah_lama.kode_kelas} → ${data?.kelas_kuliah_baru.kode_kelas}` ||
          "-"
        }}
        <br />
        Dosen : {{ data?.kelas_kuliah_baru.dosen.nama_dosen || "-" }}
      </p>

      <div class="actions">
        <button class="btn primary" @click="tutup">OK</button>
      </div>
    </div>

    <div
      v-else-if="alertType === 'error'"
      class="modal-card"
      role="dialog"
      aria-modal="true"
    >
      <div class="icon-wrap">
        <div class="icon-wrapper-danger">
          <div class="icon-wrapper-2-danger">
            <div class="icon-danger"></div>
          </div>
        </div>
      </div>
      <h2 class="title">Perubahan Jadwal Kuliah</h2>
      <p class="subtitle">
        Kelas anda berpindah karena kelas sebelumnya penuh.
        <br /><br />
        Mata Kuliah : {{ data?.kelas_kuliah_baru.mata_kuliah.nama_mk || "-" }}
        <br />
        Jadwal Lama :
        {{
          `${data?.kelas_kuliah_lama.hari}, ${data?.kelas_kuliah_lama.jam_mulai}-${data?.kelas_kuliah_lama.jam_selesai}` ||
          "-"
        }}
        <br />
        Jadwal Baru :
        {{
          `${data?.kelas_kuliah_baru.hari}, ${data?.kelas_kuliah_baru.jam_mulai}-${data?.kelas_kuliah_baru.jam_selesai}` ||
          "-"
        }}
        <br />
        Ruangan :
        {{
          `${data?.kelas_kuliah_lama.ruang} → ${data?.kelas_kuliah_baru.ruang}` ||
          "-"
        }}
        <br />
        Dosen : {{ data?.kelas_kuliah_baru.dosen.nama_dosen || "-" }}
      </p>
      <div class="actions">
        <button class="btn primary" @click="tutup">OK</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import api from "../api";
const props = defineProps({
  alertType: {
    type: String,
    default: "warning",
  },
  data: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(["close"]);

async function tutup() {
  try {
    const res = await api.put(
      `/jadwal/${props.data?.id}`,
      {
        kelas_kuliah_lama: props.data?.kelas_kuliah_baru.id,
        kelas_kuliah_baru: props.data?.kelas_kuliah_baru.id,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          Accept: "application/json",
        },
      }
    );
  } catch (err) {
    console.error("Error fetch jadwal:", err);
  }
  emit("close");
}
</script>

<style scoped>
.page {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 3000;
  pointer-events: auto;
}

.overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(6px);
  z-index: 1;
}

.modal-card {
  position: relative;
  z-index: 2;
  width: min(380px, 92%);
  background: #ffffff;
  border-radius: 18px;
  padding: 24px;
  box-shadow: 0 18px 38px rgba(15, 23, 42, 0.45);
  text-align: center;
}

.modal-card {
  transform: scale(0.95);
  opacity: 0;
  animation: fadeIn 0.25s ease-out forwards;
}

@keyframes fadeIn {
  to {
    transform: scale(1);
    opacity: 1;
  }
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

.icon-wrap {
  display: flex;
  justify-content: center;
  margin-bottom: 16px;
}

.title {
  color: black;
  margin: 6px 0 8px 0;
  font-size: 20px;
  font-weight: 700;
}

.subtitle {
  margin: 0 0 20px 0;
  font-size: 14px;
  color: #555;
}

.actions {
  display: flex;
  gap: 12px;
  flex-direction: column;
}

.btn {
  padding: 12px;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  border: 1px solid transparent;
}

.btn.primary {
  background: #12b76a;
  color: white;
}
</style>
