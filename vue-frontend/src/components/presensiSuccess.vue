<template>
  <div class="page">
    <div class="overlay"></div>

    <div class="modal-card" role="dialog" aria-modal="true">
      <div class="icon-wrap">
        <div class="icon-outer">
          <div class="icon-inner">✓</div>
        </div>
      </div>

      <h2 class="title">{{ pesan }}</h2>
      <p class="subtitle">
        Anda akan kembali ke halaman Jadwal Kuliah secara otomatis.
      </p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue";

const props = defineProps({
  pesan: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["close"]);

onMounted(() => {
  setTimeout(() => {
    emit("close");
  }, 3000);
});
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

.icon-wrap {
  display: flex;
  justify-content: center;
  margin-bottom: 16px;
}

.icon-outer {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: #ecfdf3;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-inner {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #a6f4c5;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  font-weight: 700;
  color: #027a48;
}

.title {
  margin: 6px 0 8px 0;
  font-size: 18px;
  font-weight: 700;
  color: #111827;
}

.subtitle {
  margin: 0;
  font-size: 13px;
  color: #6b7280;
}
</style>
