<!-- ================= TEMPLATE ================= -->
<template>
  <div class="page" v-if="visible">
    <div class="bg-layer" />
    <div class="overlay"></div>
    <div
      v-if="alert_type === 'warning'"
      class="modal-card"
      role="dialog"
      aria-modal="true"
    >
      <div class="icon-wrap" @click="lihat">
        <div class="icon-wrapper-alert">
          <div class="icon-wrapper-2-alert">
            <div class="icon-alert"></div>
          </div>
        </div>
      </div>

      <h2 class="title">Perubahan Kelas!</h2>
      <p class="subtitle">
        Kelas anda telah berpindah karena kuota penuh!
        <br />
        <br />Mata Kuliah : {{ Data[0].Mata_Kuliah }} <br />Kelas :
        {{ Data[0].Kelas }} <br />Dosen : {{ Data[0].Dosen }}
      </p>

      <div class="actions">
        <button class="btn primary" @click="tutup">OK</button>
      </div>
    </div>

    <div
      v-else-if="alert_type === 'error'"
      class="modal-card"
      role="dialog"
      aria-modal="true"
    >
      <div @click="lihat" class="icon-wrap">
        <div class="icon-wrapper-danger">
          <div class="icon-wrapper-2-danger">
            <div class="icon-danger"></div>
          </div>
        </div>
      </div>
      <h2 class="title">Perubahan Jadwal Kuliah</h2>
      <p class="subtitle">
        Kelas anda berpindah karena kelas sebelumnya penuh.
        <br />
        <br />Mata Kuliah : {{ Data[0].Mata_kuliah }} <br />Jadwal Lama :
        {{ Data[0].Jadwal_Lama }} <br />Jadwal Baru : {{ Data[0].Jadwal_Baru }}
        <br />Ruangan : {{ Data[0].Ruangan }} <br />Dosen : {{ Data[0].Dosen }}
      </p>
      <div class="actions">
        <button class="btn primary" @click="tutup">OK</button>
      </div>
    </div>
  </div>
</template>

<!-- ================= SCRIPT ================= -->
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();
const Data = [
  {
    alert_type: "error",
    Mata_Kuliah: "Rekayasa Ulang Sistem",
    Kelas: null,
    Dosen: "Rusdi, M.Kom",
    Jadwal_Lama: "Senin, 08.00-10.00",
    Jadwal_Baru: "Rabu, 09.00-10.00",
    Ruangan: "GKB 3 R612 → GKB 4 R615",
  },
];
// Ambil alert_type dari elemen pertama
const alert_type = ref(Data[0].alert_type);
const visible = ref(true);

function tutup() {
  router.push("/jadwal-kuliah");
}
</script>

<!-- ================= STYLE ================= -->
<style scoped>
.page {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  font-family: Inter, "Segoe UI", Roboto;
}

.bg-layer {
  position: absolute;
  inset: 0;
  background-color: white;
  background-size: cover;
  background-position: center;
  filter: blur(8px) brightness(0.6);
  transform: scale(1.03);
  z-index: 0;
}

.overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(6px);
  z-index: 1;
}

.modal-card {
  position: relative;
  z-index: 2;
  width: min(376px, 92%);
  background: white;
  border-radius: 12px;
  padding: 28px;
  box-shadow: 0 18px 30px rgba(13, 18, 25, 0.45);
  text-align: center;
  margin: 10%;
}

.icon-wrapper {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fef3f2;
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
