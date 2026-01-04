<template>
  <div class="page-inner">
    <h1 class="title">Keuangan</h1>

    <div class="info-container">
      <div class="info">
        <p class="info-label">NIM</p>
        <p class="info-value">{{ nim }}</p>
      </div>
      <div class="info">
        <p class="info-label">Nama</p>
        <p class="info-value"> {{ nama }}</p>
      </div>
      <div class="info">
        <p class="info-label">Jenjang</p>
        <p class="info-value">S1</p>
      </div>
      <div class="info">
        <p class="info-label">Jenis Biaya</p>
        <p class="info-value">Regular Gelombang 1</p>
      </div>
      <div class="info">
        <p class="info-label">Beasiswa</p>
        <p class="info-value">-</p>
      </div>
      <div class="info">
        <p class="info-label">Semester Pembayaran</p>
        <p class="info-value">{{ semesterAktif }}</p>
      </div>
    </div>

    <div class="status-banner">
      <div class="status-icon-wrapper">
        <svg
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <path d="M20 6L9 17l-5-5" />
        </svg>
      </div>
      <div class="status-text">
        <h3>SISTEM PEMBAYARAN LANCAR</h3>
        <p>Transaksi dapat dilakukan seperti biasa tanpa kendala.</p>
      </div>
    </div>

    <div class="payment-section">
      <h2 class="section-title">Pembayaran Semester {{ semesterAktif }}</h2>

      <div class="table-scroll-wrapper">
        <div class="table-min-width">
          <div class="table-row header-row">
            <div class="col-cell col-status">Lunas</div>
            <div class="col-cell col-desc">Jenis Biaya</div>
            <div class="col-cell col-money">Tagihan</div>
            <div class="col-cell col-money">Sudah Dibayarkan</div>
            <div class="col-cell col-money">Kekurangan</div>
            <div class="col-cell col-action"></div>
          </div>

          <div class="payment-list">
            <div
              class="table-row body-row"
              v-for="(pay, index) in payments"
              :key="pay.id"
            >
              <div class="col-cell col-status">
                <div
                  v-if="pay.status === 'paid'"
                  class="icon-circle success"
                  aria-hidden="true"
                >
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path d="M20 6L9 17l-5-5" />
                  </svg>
                </div>
                <div v-else class="icon-circle danger" aria-hidden="true">
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                  </svg>
                </div>
              </div>

              <div class="col-cell col-desc">
                <h4 class="pay-title">{{ pay.title }}</h4>
                <p class="pay-subtitle">{{ pay.desc }}</p>
              </div>

              <div class="col-cell col-money">
                <span class="money-text">{{ pay.tagihan }}</span>
              </div>
              <div class="col-cell col-money">
                <span class="money-text text-muted">{{ pay.terbayar }}</span>
              </div>
              <div class="col-cell col-money">
                <span class="money-text">{{ pay.kekurangan }}</span>
              </div>

              <div class="col-cell col-action">
                <button
                  v-if="pay.status !== 'paid'"
                  @click="openPaymentModal(pay)"
                  class="btn-bayar"
                >
                  Bayar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div
    v-if="isPaymentModalOpen"
    class="modal-overlay"
    @click.self="closePaymentModal"
  >
    <div class="modal-card" role="dialog" aria-modal="true">
      <div class="modal-icon-top purple-icon">
        <svg
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
          <line x1="1" y1="10" x2="23" y2="10"></line>
        </svg>
      </div>
      <h3 class="modal-title">Metode Pembayaran</h3>
      <p class="modal-subtitle">Masukkan data pembayaran anda</p>

      <div class="modal-form">
        <div class="form-group">
          <label>Name on card</label>
          <input
            v-model="cardName"
            type="text"
            class="form-input"
            placeholder="Masukkan nama anda"
          />
        </div>
        <div class="form-group">
          <label>Metode Pembayaran</label>
          <div class="select-wrapper">
            <span class="input-icon">
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <circle cx="9" cy="14" r="2"></circle>
                <circle cx="15" cy="14" r="2"></circle>
              </svg>
            </span>
            <select v-model="method" class="form-input with-icon">
              <option value="bca">BCA Virtual Account</option>
              <option value="gopay">GoPay</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group half">
            <label>CVV</label>
            <input
              v-model="cvv"
              type="text"
              class="form-input"
              placeholder="***"
            />
          </div>
          <div class="form-group half">
            <label>Expiry</label>
            <input
              v-model="expiry"
              type="text"
              class="form-input"
              placeholder="MM / YY"
            />
          </div>
        </div>
      </div>

      <button class="btn-confirm" @click="proceedToVerification">
        Confirm
      </button>
      <button class="btn-cancel" @click="closePaymentModal">Cancel</button>
    </div>
  </div>

  <div
    v-if="isVerificationOpen"
    class="modal-overlay"
    @click.self="closeVerification"
  >
    <div class="modal-card text-center" role="dialog" aria-modal="true">
      <div class="modal-icon-top purple-icon mx-auto">
        <svg
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
          ></path>
          <polyline points="22,6 12,13 2,6"></polyline>
        </svg>
      </div>
      <h3 class="modal-title">Verifikasi</h3>
      <p class="modal-subtitle">Masukkan kode yang dikirimkan pada SMS anda.</p>

      <div class="otp-container">
        <input
          v-for="(digit, index) in otpCode"
          :key="index"
          type="text"
          inputmode="numeric"
          maxlength="1"
          class="otp-input"
          v-model="otpCode[index]"
          @input="handleOtpInput($event, index)"
          @keydown="handleOtpKeydown($event, index)"
          :ref="(el) => (otpInputs[index] = el)"
          autocomplete="one-time-code"
        />
      </div>
      <div class="resend-text">
        Didn't get a code?
        <a href="#" @click.prevent="resendOtp">Click to resend.</a>
      </div>

      <button class="btn-confirm" @click="finishPayment">Confirm</button>
      <button class="btn-cancel" @click="closeVerification">Cancel</button>
    </div>
  </div>

  <div
    v-if="isSuccessOpen"
    class="modal-overlay"
    @click.self="isSuccessOpen = false"
  >
    <div
      class="modal-card text-center"
      style="padding-top: 40px; padding-bottom: 40px"
    >
      <div class="success-icon-wrapper mx-auto">
        <svg
          width="40"
          height="40"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="3"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
      </div>

      <h3 class="modal-title mt-4">Pembayaran Berhasil</h3>
      <p
        class="modal-subtitle mt-2"
        style="
          max-width: 280px;
          margin-left: auto;
          margin-right: auto;
          line-height: 1.6;
        "
      >
        Transaksi telah diterima dan diverifikasi oleh sistem. Anda akan kembali
        ke halaman Keuangan secara otomatis.
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, nextTick, onMounted, onBeforeUnmount } from "vue";

const nim = ref("-");
const nama = ref("-");
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
    const SemesterMahasiswa = JSON.parse(storedMahasiswa);
    semesterAktif.value = SemesterMahasiswa || "-";
  }
});

const payments = ref([
  {
    id: 1,
    title: "Her Registrasi",
    desc: "Wajib dibayar lunas sebelum melakukan KRS",
    tagihan: "Rp 300.000",
    terbayar: "Rp 300.000",
    kekurangan: "Rp 0",
    status: "paid",
  },
  {
    id: 2,
    title: "SPP Semester 7 HER",
    desc: "Wajib dibayar lunas sebelum melakukan KRS",
    tagihan: "Rp 2.650.000",
    terbayar: "Rp 2.650.000",
    kekurangan: "Rp 0",
    status: "paid",
  },
  {
    id: 3,
    title: "SPP Semester 7 UTS",
    desc: "Wajib dibayar lunas sebelum melakukan KRS",
    tagihan: "Rp 2.650.000",
    terbayar: "Rp 0",
    kekurangan: "Rp 2.650.000",
    status: "unpaid",
  },
]);

const isPaymentModalOpen = ref(false);
const isVerificationOpen = ref(false);
const isSuccessOpen = ref(false);
const selectedBill = ref(null);

const cardName = ref("");
const method = ref("bca");
const cvv = ref("");
const expiry = ref("");

const otpCode = reactive(["", "", "", ""]);
const otpInputs = [];

const openPaymentModal = (item) => {
  selectedBill.value = item;
  cardName.value = "Gemilang Rizmart";
  method.value = "bca";
  cvv.value = "";
  expiry.value = "";
  isPaymentModalOpen.value = true;
};

const closePaymentModal = () => {
  isPaymentModalOpen.value = false;
  selectedBill.value = null;
};

const proceedToVerification = async () => {
  if (!selectedBill.value) return;
  isPaymentModalOpen.value = false;
  otpCode[0] = "";
  otpCode[1] = "";
  otpCode[2] = "";
  otpCode[3] = "";
  await nextTick();
  isVerificationOpen.value = true;
  await nextTick();
  otpInputs[0]?.focus?.();
};

const closeVerification = () => {
  isVerificationOpen.value = false;
  selectedBill.value = null;
};

const handleOtpInput = (e, index) => {
  let value = (e.target.value || "").toString();
  value = value.replace(/\D/g, "");
  if (!value) {
    otpCode[index] = "";
    return;
  }

  if (value.length > 1) {
    const chars = value.split("");
    for (let i = 0; i < chars.length && index + i < otpCode.length; i++) {
      otpCode[index + i] = chars[i];
    }
    const nextPos = Math.min(index + value.length, otpCode.length - 1);
    nextTick(() => otpInputs[nextPos]?.focus?.());
    return;
  }

  otpCode[index] = value;
  if (index < otpCode.length - 1) {
    nextTick(() => otpInputs[index + 1]?.focus?.());
  } else {
    otpInputs[index]?.blur?.();
  }
};

const handleOtpKeydown = (e, index) => {
  if (e.key === "Backspace") {
    if (otpCode[index]) {
      otpCode[index] = "";
      e.preventDefault();
      return;
    }
    if (index > 0) {
      nextTick(() => {
        otpInputs[index - 1]?.focus?.();
        otpCode[index - 1] = "";
      });
      e.preventDefault();
    }
  } else if (e.key === "ArrowLeft") {
    if (index > 0) nextTick(() => otpInputs[index - 1]?.focus?.());
    e.preventDefault();
  } else if (e.key === "ArrowRight") {
    if (index < otpCode.length - 1)
      nextTick(() => otpInputs[index + 1]?.focus?.());
    e.preventDefault();
  } else if (e.key === "Enter") {
    finishPayment();
  }
};

const resendOtp = () => {
  otpCode[0] = "";
  otpCode[1] = "";
  otpCode[2] = "";
  otpCode[3] = "";
  nextTick(() => otpInputs[0]?.focus?.());
  alert("Kode verifikasi telah dikirim ulang (mock).");
};

const finishPayment = () => {
  const fullCode = otpCode.join("");
  if (fullCode.length < 4 || /\D/.test(fullCode)) {
    alert("Harap masukkan 4 digit kode OTP yang valid");
    return;
  }

  isVerificationOpen.value = false;
  isSuccessOpen.value = true;

  if (selectedBill.value) {
    const target = payments.value.find((p) => p.id === selectedBill.value.id);
    if (target) {
      target.status = "paid";
      target.terbayar = target.tagihan;
      target.kekurangan = "Rp 0";
    }
  }

  setTimeout(() => {
    isSuccessOpen.value = false;
    selectedBill.value = null;
  }, 2500);
};

const onGlobalKeydown = (e) => {
  if (e.key === "Escape") {
    if (isVerificationOpen.value) closeVerification();
    else if (isPaymentModalOpen.value) closePaymentModal();
    else if (isSuccessOpen.value) isSuccessOpen.value = false;
  }
};

onMounted(() => {
  window.addEventListener("keydown", onGlobalKeydown);
});
onBeforeUnmount(() => {
  window.removeEventListener("keydown", onGlobalKeydown);
});
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

  .table-min-width {
    min-width: 100%;
  }

  .table-scroll-wrapper {
    overflow-x: visible;
  }

  .title {
    font-size: 32px;
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

.status-banner {
  background-color: #d1fae5;
  border-radius: 10px;
  padding: 14px;
  display: flex;
  gap: 12px;
  margin-bottom: 22px;
  align-items: center;
}

.status-icon-wrapper {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff;
  border: 2px solid #a7f3d0;
  color: #10b981;
}

.status-text h3 {
  margin: 0;
  font-size: 12px;
  font-weight: 800;
  color: #065f46;
  text-transform: uppercase;
}

.status-text p {
  margin: 4px 0 0;
  font-size: 12px;
  color: #047857;
}

.payment-section {
  margin-top: 24px;
}

.section-title {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 12px;
  color: #111827;
}

.table-scroll-wrapper {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: #ffffff;
}

.table-min-width {
  min-width: 780px;
  display: flex;
  flex-direction: column;
}

.table-row {
  display: flex;
  align-items: center;
  padding: 14px 16px;
  border-bottom: 1px solid #e5e7eb;
}

.header-row {
  background: #f9fafb;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  color: #6b7280;
  padding: 12px 16px;
}

.body-row:last-child {
  border-bottom: none;
}

.body-row:hover {
  background: #f8fafc;
}

.col-cell {
  padding-right: 12px;
  flex-shrink: 0;
}

.col-status {
  width: 8%;
  display: flex;
  justify-content: flex-start;
}

.col-desc {
  width: 32%;
}

.col-money {
  width: 16%;
}

.col-action {
  width: 10%;
  display: flex;
  justify-content: flex-end;
  padding-right: 0;
}

.icon-circle {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-circle.success {
  background: #d1fae5;
  color: #10b981;
}

.icon-circle.danger {
  background: #fee2e2;
  color: #ef4444;
}

.pay-title {
  margin: 0;
  font-size: 14px;
  font-weight: 700;
  color: #1f2937;
}

.pay-subtitle {
  margin: 4px 0 0;
  font-size: 11px;
  color: #6b7280;
  line-height: 1.3;
}

.money-text {
  font-size: 14px;
  font-weight: 600;
  color: #1f2937;
  display: block;
}

.text-muted {
  color: #9ca3af;
  font-weight: 500;
}

.btn-bayar {
  background: #10b981;
  color: #ffffff;
  border: none;
  border-radius: 6px;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
}

.btn-bayar:hover {
  background: #059669;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

.modal-card {
  width: 100%;
  max-width: 420px;
  background: #ffffff;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  animation: slideUp 0.28s ease-out;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-icon-top {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
}

.purple-icon {
  background: #f3e8ff;
  color: #7c3aed;
}

.text-center {
  text-align: center;
}

.mx-auto {
  margin-left: auto;
  margin-right: auto;
}

.mt-2 {
  margin-top: 8px;
}

.mt-4 {
  margin-top: 16px;
}

.modal-title {
  font-size: 18px;
  font-weight: 700;
  margin: 0 0 6px;
  color: #1f2937;
}

.modal-subtitle {
  font-size: 14px;
  color: #6b7280;
  margin: 0 0 24px;
}

.success-icon-wrapper {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background-color: #d1fae5;
  color: #10b981;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 12px;
}

.form-group {
  margin-bottom: 16px;
  text-align: left;
}

.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.form-input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  color: #1f2937;
  outline: none;
  transition: border-color 0.15s;
}

.form-input:focus {
  border-color: #10b981;
}

.select-wrapper {
  position: relative;
}

.input-icon {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  pointer-events: none;
  color: #ea580c;
}

.form-input.with-icon {
  padding-left: 40px;
}

.form-row {
  display: flex;
  gap: 12px;
}

.form-group.half {
  width: 50%;
}

.btn-confirm {
  width: 100%;
  padding: 12px;
  background: #10b981;
  color: #ffffff;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  margin-bottom: 12px;
}

.btn-confirm:hover {
  background: #059669;
}

.btn-cancel {
  width: 100%;
  padding: 12px;
  background: #ffffff;
  color: #374151;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancel:hover {
  background: #f9fafb;
}

.otp-container {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-bottom: 24px;
}

.otp-input {
  width: 50px;
  height: 60px;
  border-radius: 12px;
  border: 1px solid #e9d5ff;
  text-align: center;
  font-size: 24px;
  font-weight: 700;
  color: #7c3aed;
  background-color: #ffffff;
  outline: none;
  transition: all 0.2s;
}

.otp-input:focus {
  border-color: #7c3aed;
  box-shadow: 0 0 0 3px #f3e8ff;
  transform: translateY(-2px);
}

.resend-text {
  font-size: 13px;
  color: #6b7280;
  margin-bottom: 24px;
}

.resend-text a {
  color: #374151;
  text-decoration: underline;
  font-weight: 600;
  cursor: pointer;
}
</style>
