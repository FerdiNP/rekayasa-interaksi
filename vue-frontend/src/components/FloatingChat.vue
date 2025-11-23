<script setup>
import { ref, onMounted } from "vue";

const isOpen = ref(false);
const messages = ref([]);
const input = ref("");

// Chat pertama
onMounted(() => {
  messages.value.push({
    from: "bot",
    text: `Halo! Saya NARA UMM, asisten digital InfoKHS 😊.
Saya bisa bantu kamu cek status surat, jadwal kuliah, dan informasi akademik lainnya.
Mau mulai dari mana nih?`
  });
});

// Kirim pesan
const sendMessage = () => {
  if (!input.value) return;

  messages.value.push({ from: "user", text: input.value });

  handleBotResponse(input.value.toLowerCase());
  input.value = "";
};

// Respon bot (mock)
const handleBotResponse = (msg) => {
  if (msg.includes("surat") || msg.includes("cuti")) {
    messages.value.push({
      from: "bot",
      text: `Berikut daftar surat cuti kamu:
   1. Cuti Semester 5 (10 Oktober 2025)
Ketik nomor surat untuk detail lebih lanjut.`
    });
    return;
  }

  if (msg === "1") {
    messages.value.push({
      from: "bot",
      text:
        "Surat cuti anda untuk Semester 5 Tahun ajar 2025 yang dibuat pada 10 Oktober 2025 – Sedang di proses"
    });
    return;
  }

  messages.value.push({
    from: "bot",
    text: "Baik, fitur lain akan ditambahkan berikutnya."
  });
};
</script>

<template>
  <!-- Floating Button -->
  <button class="floating-btn" @click="isOpen = true" v-if="!isOpen">
    💬
  </button>

  <!-- Popup -->
  <div class="chat-popup" v-if="isOpen">
    <!-- Header -->
    <div class="chat-header">
      <span>UMM Assistant</span>
      <button class="close-btn" @click="isOpen = false">−</button>
    </div>

    <!-- Body -->
    <div class="chat-body">
      <div v-for="(m, i) in messages" :key="i" class="msg-wrapper">
        <p class="chat-bubble" :class="m.from">{{ m.text }}</p>
      </div>
    </div>

    <!-- Input -->
    <div class="chat-input">
      <input
        v-model="input"
        placeholder="Masukkan pesan anda disini.."
        @keyup.enter="sendMessage"
      />
      <button class="send-btn" @click="sendMessage">🛫</button>
    </div>
  </div>
</template>

<style scoped>
/* Floating Button */
.floating-btn {
  position: fixed;
  bottom: 22px;
  right: 22px;
  width: 60px;
  height: 60px;
  background: #ff4d4d;
  border-radius: 50%;
  font-size: 26px;
  border: none;
  color: white;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}

/* Chat Popup */
.chat-popup {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 355px;
  height: 520px;
  background: white;
  border-radius: 20px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  box-shadow: 0 6px 18px rgba(0,0,0,0.25);
}

/* Header */
.chat-header {
  background: #ff4d4d;
  padding: 14px;
  color: white;
  font-size: 18px;
  font-weight: bold;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 24px;
  color: white;
  cursor: pointer;
}

/* Body */
.chat-body {
  flex: 1;
  padding: 16px;
  overflow-y: auto;
  background: #fafafa;
}

.msg-wrapper {
  margin-bottom: 14px;
}

/* Bubbles */
.chat-bubble {
  max-width: 85%;
  padding: 12px;
  border-radius: 16px;
  white-space: pre-line;
  line-height: 1.35;
  font-size: 14px;
}

.user {
  background: #d9dde4;
  color: #000;
  margin-left: auto;
}

.bot {
  background: #ffd6d6;
  color: #000;
  margin-right: auto;
}

/* Input area */
.chat-input {
  display: flex;
  padding: 12px;
  gap: 8px;
  background: #fff5f5;
}

.chat-input input {
  flex: 1;
  padding: 12px;
  border-radius: 12px;
  border: 1px solid #ddd;
}

.send-btn {
  background: #ff4d4d;
  border: none;
  color: white;
  padding: 0 16px;
  border-radius: 12px;
  font-size: 18px;
  cursor: pointer;
}
</style>
