<template>
  <div class="mobile-container">
    <header class="header">
      <button class="btn-icon" aria-label="menu" @click="onMenuClick">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>

      <div class="account-btn" role="button" tabindex="0" @click="onAccountClick">
        <span>Account</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 9l6 6 6-6"/>
        </svg>
      </div>
    </header>

    <main class="content">
      <h1 class="page-title">Profil</h1>

      <form @submit.prevent="saveProfile" novalidate>
        <section class="profile-section" aria-labelledby="biodata-heading">
          <h2 id="biodata-heading" class="section-heading">Biodata</h2>

          <div class="info-banner purple-dashed" role="status" aria-live="polite">
            <p>Isikan data berikut dengan data yang benar.</p>
          </div>

          <div class="static-group" aria-hidden="true">
            <div class="static-item">
              <label>NIM</label>
              <p>{{ profile.nim }}</p>
            </div>
            <div class="static-item">
              <label>Nama</label>
              <p>{{ profile.nama }}</p>
            </div>
            <div class="static-item">
              <label>Fakultas</label>
              <p>{{ profile.fakultas }}</p>
            </div>
            <div class="static-item">
              <label>Program Studi</label>
              <p>{{ profile.prodi }}</p>
            </div>
            <div class="static-item">
              <label>Email UMM</label>
              <p>{{ profile.emailUmm }}</p>
            </div>
          </div>

          <div class="form-group">
            <label for="emailAlt">Email Alternatif</label>
            <input id="emailAlt" v-model.trim="form.emailAlt" type="email" class="form-input" autocomplete="email" />
            <p v-if="errors.emailAlt" class="error-text">{{ errors.emailAlt }}</p>
          </div>

          <div class="form-group">
            <label for="hp">No. HP</label>
            <input id="hp"
                   v-model="form.hp"
                   @input="sanitizeNumber('hp')"
                   inputmode="tel"
                   type="tel"
                   maxlength="15"
                   placeholder="08xxxxxxxxxx"
                   class="form-input" />
            <p v-if="errors.hp" class="error-text">{{ errors.hp }}</p>
          </div>

          <div class="form-group">
            <label for="ktp">No. KTP/Passport Number</label>
            <input id="ktp"
                   v-model="form.ktp"
                   @input="sanitizeNumber('ktp')"
                   inputmode="numeric"
                   type="text"
                   maxlength="20"
                   class="form-input" />
            <p v-if="errors.ktp" class="error-text">{{ errors.ktp }}</p>
          </div>

          <div class="form-group">
            <label for="alamat">Alamat di Malang</label>
            <input id="alamat" v-model.trim="form.alamat" type="text" class="form-input">
            <p v-if="errors.alamat" class="error-text">{{ errors.alamat }}</p>
          </div>
        </section>

        <section class="profile-section mt-8" aria-labelledby="password-heading">
          <h2 id="password-heading" class="section-heading">Password/PIC</h2>

          <div class="info-banner blue-solid" role="status" aria-live="polite">
            <p>Kosongkan Password/PIC Baru jika tidak ingin mengganti Password/PIC anda.</p>
          </div>

          <div class="form-group">
            <label for="passBaru">Password/PIC Baru</label>
            <input id="passBaru"
                   v-model="form.passBaru"
                   @input="sanitizeNumber('passBaru')"
                   inputmode="numeric"
                   type="password"
                   maxlength="20"
                   class="form-input" />
            <p v-if="errors.passBaru" class="error-text">{{ errors.passBaru }}</p>
          </div>

          <div class="form-group">
            <label for="ulangiPass">Ulangi Password/PIC Baru</label>
            <input id="ulangiPass"
                   v-model="form.ulangiPass"
                   @input="sanitizeNumber('ulangiPass')"
                   inputmode="numeric"
                   type="password"
                   maxlength="20"
                   class="form-input" />
            <p v-if="errors.ulangiPass" class="error-text">{{ errors.ulangiPass }}</p>
          </div>

          <p class="helper-text">Password/PIC harus angka dan minimal 8 digit</p>
        </section>

        <div class="action-buttons">
          <button type="button" class="btn-outline" @click="goBack">Kembali</button>
          <button type="submit" class="btn-primary" :disabled="!isFormValid || saving">
            <span v-if="!saving">Simpan</span>
            <span v-else>...Menyimpan</span>
          </button>
        </div>

        <div v-if="successMessage" class="success-banner" role="status" aria-live="polite">
          {{ successMessage }}
        </div>
      </form>
    </main>
  </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue';
import { useRouter } from 'vue-router';

/* -------------------------
   DATA STATIS / PROFILE
   ------------------------- */
const profile = reactive({
  nim: '202210370311285',
  nama: 'Ferdhant Arya Exshandy',
  fakultas: 'Fakultas Teknik',
  prodi: 'Informatika',
  emailUmm: 'ferdhant@webmail.umm.ac.id'
});

/* -------------------------
   FORM EDITABLE
   ------------------------- */
const form = reactive({
  emailAlt: 'ferdhantarya@gmail.com',
  hp: '08123456789',
  ktp: '630906090909090',
  alamat: 'Jl. jalan jalan',
  passBaru: '',
  ulangiPass: ''
});

/* -------------------------
   STATE & VALIDATION
   ------------------------- */
const router = (() => {
  try { return useRouter(); } catch (e) { return null; }
})();

const errors = reactive({
  emailAlt: '',
  hp: '',
  ktp: '',
  alamat: '',
  passBaru: '',
  ulangiPass: ''
});

const successMessage = ref('');
const saving = ref(false);

/* Helper: sanitize numeric-only fields */
const sanitizeNumber = (field) => {
  if (!form[field]) return;
  // hanya ambil angka
  form[field] = String(form[field]).replace(/\D+/g, '');
};

/* Basic validators */
const validateEmail = (value) => {
  if (!value) return 'Email alternatif wajib diisi.';
  // sederhana: RFC-kompleks tidak diperlukan di sini
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(value) ? '' : 'Format email tidak valid.';
};

const validatePhone = (value) => {
  if (!value) return 'No. HP wajib diisi.';
  if (!/^[0-9]{8,15}$/.test(value)) return 'No. HP harus angka 8-15 digit (contoh: 08xxxxxxxx).';
  return '';
};

const validateKtp = (value) => {
  if (!value) return 'No. KTP/Passport wajib diisi.';
  if (!/^[0-9]{6,20}$/.test(value)) return 'No. KTP harus angka (6-20 digit).';
  return '';
};

const validateAlamat = (value) => {
  if (!value) return 'Alamat wajib diisi.';
  if (value.length < 5) return 'Alamat terlalu singkat.';
  return '';
};

const validatePass = (value) => {
  if (!value) return ''; // kosong = tidak ingin ganti
  if (!/^[0-9]+$/.test(value)) return 'Password/PIC harus berupa angka.';
  if (value.length < 8) return 'Password/PIC minimal 8 digit.';
  return '';
};

const validateRepeatPass = (pass, repeat) => {
  if (!pass && !repeat) return '';
  if (pass && !repeat) return 'Ulangi password wajib diisi.';
  if (pass !== repeat) return 'Password tidak cocok.';
  return '';
};

/* Computed: apakah form valid */
const isFormValid = computed(() => {
  // jalankan validasi ringkas (tidak mengubah pesan)
  const vEmail = validateEmail(form.emailAlt) === '';
  const vHp = validatePhone(form.hp) === '';
  const vKtp = validateKtp(form.ktp) === '';
  const vAlamat = validateAlamat(form.alamat) === '';
  const vPass = validatePass(form.passBaru) === '';
  const vRepeat = validateRepeatPass(form.passBaru, form.ulangiPass) === '';
  // pass optional: hanya ketika passBaru diisi, validasi pass & ulangiPass harus lolos
  return vEmail && vHp && vKtp && vAlamat && vPass && vRepeat;
});

/* Jalankan validasi dan isi errors sebelum submit */
const runAllValidation = () => {
  errors.emailAlt = validateEmail(form.emailAlt);
  errors.hp = validatePhone(form.hp);
  errors.ktp = validateKtp(form.ktp);
  errors.alamat = validateAlamat(form.alamat);
  errors.passBaru = validatePass(form.passBaru);
  errors.ulangiPass = validateRepeatPass(form.passBaru, form.ulangiPass);
};

/* ACTIONS */
const goBack = () => {
  if (router && router.back) {
    router.back();
    return;
  }
  // fallback
  window.history.back();
};

const saveProfile = async () => {
  runAllValidation();
  if (!isFormValid.value) {
    // beri fokus ke error pertama - UX sederhana
    successMessage.value = '';
    return;
  }

  saving.value = true;
  successMessage.value = '';

  // Simulasi pemanggilan API (ganti dengan fetch/axios yang nyata)
  try {
    // contoh payload yang dikirim
    const payload = {
      emailAlt: form.emailAlt,
      hp: form.hp,
      ktp: form.ktp,
      alamat: form.alamat,
      passBaru: form.passBaru ? form.passBaru : undefined
    };
    console.log('Mengirim payload ke server:', payload);

    // Simulasi delay
    await new Promise((r) => setTimeout(r, 700));

    // Anggap sukses: update profile lokal jika perlu
    successMessage.value = 'Data profil berhasil disimpan!';
    // kosongkan field password setelah sukses
    form.passBaru = '';
    form.ulangiPass = '';
    errors.passBaru = '';
    errors.ulangiPass = '';

    // jika perlu: update profile.emailUmm atau lain
  } catch (err) {
    console.error(err);
    successMessage.value = 'Gagal menyimpan data. Coba lagi.';
  } finally {
    saving.value = false;
    // tutup notifikasi setelah beberapa detik
    setTimeout(() => successMessage.value = '', 4000);
  }
};

/* UI kecil untuk tombol menu/account (placeholder) */
const onMenuClick = () => { console.log('menu clicked'); };
const onAccountClick = () => { console.log('account clicked'); };
</script>

<style>
:root {
  --bg: #ffffff;
  --muted: #6b7280;
  --text: #1f2937;
  --border: #d1d5db;
  --green-btn: #10b981;
  --blue-info: #0ea5e9;
  --max-mobile: 480px;
  --error: #dc2626;
  --success: #16a34a;
}

* { box-sizing: border-box; }

.mobile-container {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: var(--bg);
  width: 100%;
  max-width: var(--max-mobile);
  margin: 0 auto;
  min-height: 100vh;
  position: relative;
  border: 1px solid #e5e7eb;
  color: var(--text);
}

.header {
  background-color: #f3f4f6;
  padding: 14px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.btn-icon { background: none; border: none; cursor: pointer; padding: 6px; color: var(--muted); }
.account-btn {
  background-color: white; border: 1px solid #d1d5db; border-radius: 6px;
  padding: 6px 10px; display: flex; gap: 8px; align-items: center;
  font-size: 13px; font-weight: 600; color: #374151;
}

.content { padding: 20px; padding-bottom: 60px; }

.page-title {
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

.mt-8 { margin-top: 32px; }

.info-banner {
  padding: 12px;
  margin-bottom: 20px;
  font-size: 13px;
  color: white;
  line-height: 1.4;
}

.purple-dashed {
  background: linear-gradient(90deg, #6366f1 0%, #0ea5e9 100%);
  border: 2px dashed #7c3aed;
  color: white;
  border-radius: 6px;
}

.blue-solid {
  background-color: var(--blue-info);
  border-radius: 4px;
}

.static-item {
  margin-bottom: 16px;
}
.static-item label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  color: #374151;
  margin-bottom: 2px;
}
.static-item p {
  margin: 0;
  font-size: 14px;
  color: #6b7280;
}

.form-group {
  margin-bottom: 16px;
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
  padding: 10px 12px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 14px;
  color: #374151;
  outline: none;
}
.form-input:focus {
  border-color: var(--blue-info);
  box-shadow: 0 0 0 3px rgba(14,165,233,0.08);
}

.helper-text {
  font-size: 11px;
  color: #6b7280;
  margin-top: -10px;
  margin-bottom: 24px;
}

.action-buttons {
  display: flex;
  gap: 12px;
  margin-top: 30px;
}

.btn-outline, .btn-primary {
  flex: 1;
  padding: 10px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  text-align: center;
}

.btn-outline {
  background-color: white;
  border: 1px solid #9ca3af;
  color: #4b5563;
}

.btn-primary {
  background-color: var(--green-btn);
  border: none;
  color: white;
}
.btn-primary:hover:not([disabled]) {
  background-color: #059669;
}
.btn-primary[disabled] {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-text {
  color: var(--error);
  font-size: 12px;
  margin-top: 6px;
}

.success-banner {
  margin-top: 12px;
  padding: 10px;
  border-radius: 6px;
  background-color: rgba(22,163,74,0.12);
  color: var(--success);
  font-weight: 600;
  font-size: 13px;
}

/* RESPONSIVE */
@media (min-width: 768px) {
  .mobile-container {
    max-width: 900px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    margin-top: 20px;
    margin-bottom: 20px;
  }
  .content { padding: 40px; }
  .profile-section { max-width: 600px; }
}
</style>
