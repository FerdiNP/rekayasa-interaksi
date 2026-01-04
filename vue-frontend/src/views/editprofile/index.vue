<template>
  <div class="page-inner">
    <h1 class="title">Profil</h1>

    <form @submit.prevent="saveProfile" novalidate>
      <section class="profile-section" aria-labelledby="biodata-heading">
        <h2 id="biodata-heading" class="section-heading">Biodata</h2>

        <div class="info-banner blue-banner" role="status" aria-live="polite">
          <p>Isikan data berikut dengan data yang benar.</p>
        </div>

        <div class="info-container">
          <div class="info">
            <p class="info-label">NIM</p>
            <p class="info-value">{{ profile.nim }}</p>
          </div>
          <div class="info">
            <p class="info-label">Nama</p>
            <p class="info-value">{{ profile.nama }}</p>
          </div>
          <div class="info">
            <p class="info-label">Fakultas</p>
            <p class="info-value">{{ profile.fakultas }}</p>
          </div>
          <div class="info">
            <p class="info-label">Program Studi</p>
            <p class="info-value">{{ profile.prodi }}</p>
          </div>
          <div class="info">
            <p class="info-label">Email UMM</p>
            <p class="info-value">{{ profile.emailUmm }}</p>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label for="emailAlt">Email Alternatif</label>
            <input
              id="emailAlt"
              v-model.trim="form.emailAlt"
              type="email"
              class="form-input"
              autocomplete="email"
            />
            <p v-if="errors.emailAlt" class="error-text">
              {{ errors.emailAlt }}
            </p>
          </div>

          <div class="form-group">
            <label for="hp">No. HP</label>
            <input
              id="hp"
              v-model="form.hp"
              @input="sanitizeNumber('hp')"
              inputmode="tel"
              type="tel"
              maxlength="15"
              placeholder="08xxxxxxxxxx"
              class="form-input"
            />
            <p v-if="errors.hp" class="error-text">{{ errors.hp }}</p>
          </div>

          <div class="form-group">
            <label for="ktp">No. KTP/Passport Number</label>
            <input
              id="ktp"
              v-model="form.ktp"
              @input="sanitizeNumber('ktp')"
              inputmode="numeric"
              type="text"
              maxlength="20"
              class="form-input"
            />
            <p v-if="errors.ktp" class="error-text">{{ errors.ktp }}</p>
          </div>

          <div class="form-group form-group-full">
            <label for="alamat">Alamat di Malang</label>
            <input
              id="alamat"
              v-model.trim="form.alamat"
              type="text"
              class="form-input"
            />
            <p v-if="errors.alamat" class="error-text">
              {{ errors.alamat }}
            </p>
          </div>
        </div>
      </section>

      <section class="profile-section mt-8" aria-labelledby="password-heading">
        <h2 id="password-heading" class="section-heading">Password/PIC</h2>

        <div class="info-banner blue-banner" role="status" aria-live="polite">
          <p>
            Kosongkan Password/PIC Baru jika tidak ingin mengganti Password/PIC
            anda.
          </p>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label for="passBaru">Password/PIC Baru</label>
            <input
              id="passBaru"
              v-model="form.passBaru"
              @input="sanitizeNumber('passBaru')"
              inputmode="numeric"
              type="password"
              maxlength="20"
              class="form-input"
            />
            <p v-if="errors.passBaru" class="error-text">
              {{ errors.passBaru }}
            </p>
          </div>

          <div class="form-group">
            <label for="ulangiPass">Ulangi Password/PIC Baru</label>
            <input
              id="ulangiPass"
              v-model="form.ulangiPass"
              @input="sanitizeNumber('ulangiPass')"
              inputmode="numeric"
              type="password"
              maxlength="20"
              class="form-input"
            />
            <p v-if="errors.ulangiPass" class="error-text">
              {{ errors.ulangiPass }}
            </p>
          </div>

          <div class="form-group form-group-full helper-group">
            <p class="helper-text">
              Password/PIC harus angka dan minimal 8 digit
            </p>
          </div>
        </div>
      </section>

      <div class="action-buttons">
        <button type="button" class="btn-outline" @click="goBack">
          Kembali
        </button>
        <button
          type="submit"
          class="btn-primary"
          :disabled="!isFormValid || saving"
        >
          <span v-if="!saving">Simpan</span>
          <span v-else>...Menyimpan</span>
        </button>
      </div>

      <div
        v-if="successMessage"
        class="success-banner"
        role="status"
        aria-live="polite"
      >
        {{ successMessage }}
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import api from "../../api";

const router = useRouter();

const profile = reactive({
  nim: "",
  nama: "",
  fakultas: "",
  prodi: "",
  emailUmm: "",
});

const form = reactive({
  emailAlt: "",
  hp: "",
  ktp: "",
  alamat: "",
  passBaru: "",
  ulangiPass: "",
});

const errors = reactive({
  emailAlt: "",
  hp: "",
  ktp: "",
  alamat: "",
  passBaru: "",
  ulangiPass: "",
});

const isFormValid = computed(() => {
  if (!form.emailAlt) return false;
  if (!form.hp) return false;
  if (!form.ktp) return false;
  if (!form.alamat) return false;

  if (!form.passBaru && !form.ulangiPass) return true;

  if (form.passBaru && !form.ulangiPass) return true;
  if (!form.passBaru && form.ulangiPass) return true;

  if (form.passBaru.length < 8) return false;
  if (form.passBaru !== form.ulangiPass) return false;

  return true;
});

const saving = ref(false);
const error = ref("");
const success = ref("");

onMounted(async () => {
  const storedUser = localStorage.getItem("user");
  if (storedUser) {
    const userLS = JSON.parse(storedUser);

    profile.nim = userLS.nim;
    profile.nama = userLS.nama_lengkap;
    profile.emailUmm = userLS.email_UMM;

    profile.prodi = userLS.program_studi_id?.nama_prodi || "-";
    profile.fakultas =
      userLS.program_studi_id?.fakultas || "-";
  }

  try {
    const res = await api.get("/me", {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    const me = res.data;

    form.emailAlt = me.email_pribadi || "";
    form.hp = me.no_hp || "";
    form.ktp = me.no_ktp || "";
    form.alamat = me.alamat || "";
  } catch (e) {
    error.value = "Gagal memuat profil";
  }
});

const saveProfile = async () => {
  error.value = "";
  saving.value = true;

  if (form.passBaru && form.passBaru !== form.ulangiPass) {
    error.value = "Password tidak cocok";
    saving.value = false;
    return;
  }

  try {
    await api.put(
      "/profile",
      {
        email_pribadi: form.emailAlt,
        no_hp: form.hp,
        no_ktp: form.ktp,
        alamat: form.alamat,
        pin_login: form.passBaru || null,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    router.push({
      path: "/profile",
      query: { success: "profile-updated" },
    });
  } catch (e) {
    error.value = "Gagal menyimpan data";
  } finally {
    saving.value = false;
  }
};

</script>

<style>
* {
  box-sizing: border-box;
}

.page {
  min-height: auto;
  width: 100%;
  box-sizing: border-box;
  padding: 24px 16px 40px;
  display: flex;
  justify-content: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  color: #1f2937;
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
  font-size: 26px;
  font-weight: 700;
  color: #4b5563;
  margin-bottom: 24px;
}

.section-heading {
  font-size: 16px;
  font-weight: 700;
  color: #4b5563;
  margin-bottom: 12px;
}

.mt-8 {
  margin-top: 32px;
}

.info-banner {
  padding: 10px 12px;
  margin-bottom: 18px;
  border-radius: 10px;
  font-size: 13px;
  line-height: 1.4;
  border: 1px solid transparent;
}

.info-banner p {
  margin: 0;
}

.blue-banner {
  background: #e0f2fe;
  border-color: #bae6fd;
  color: #0f172a;
}

.info-container {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 20px;
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

.form-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
  margin-bottom: 4px;
}

.form-group-full {
  grid-column: 1 / -1;
}

.helper-group {
  margin-top: -6px;
}

@media (min-width: 768px) {
  .form-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    column-gap: 16px;
    row-gap: 16px;
  }
}

.form-group {
  margin-bottom: 0;
}

.form-group label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 6px;
}

.form-input {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 10px;
  font-size: 14px;
  color: #111827;
  outline: none;
  background-color: #ffffff;
  transition: border-color 0.15s ease, box-shadow 0.15s ease,
    background-color 0.15s ease;
}

.form-input:focus {
  border-color: #0ea5e9;
  box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.08);
  background-color: #f9fafb;
}

.helper-text {
  font-size: 11px;
  color: #6b7280;
  margin: 0;
}

.action-buttons {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 10px;
  margin-top: 28px;
}

@media (max-width: 400px) {
  .action-buttons {
    grid-template-columns: 1fr;
  }
}

.btn-outline,
.btn-primary {
  padding: 8px 0;
  height: 40px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: background-color 0.18s ease, transform 0.18s ease;
}

.btn-outline {
  background-color: #ffffff;
  border: 1px solid #9ca3af;
  color: #4b5563;
}

.btn-outline:hover {
  background-color: #f9fafb;
}

.btn-primary {
  background-color: #10b981;
  border: none;
  color: #ffffff;
}

.btn-primary:hover:not([disabled]) {
  background-color: #059669;
  transform: translateY(-1px);
}

.btn-primary[disabled] {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-text {
  color: #dc2626;
  font-size: 12px;
  margin-top: 6px;
}

.success-banner {
  margin-top: 12px;
  padding: 10px;
  border-radius: 6px;
  background-color: rgba(22, 163, 74, 0.12);
  color: #16a34a;
  font-weight: 600;
  font-size: 13px;
}
</style>
