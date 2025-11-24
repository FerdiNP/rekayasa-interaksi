<template>
  <section class="card layanan-list">
    <button class="layanan-item" type="button" @click="$emit('go-cuti')">
      <span>Cuti</span><span class="chevron">›</span>
    </button>
    <button class="layanan-item" type="button" @click="$emit('go-aktif')">
      <span>Aktif Kembali</span><span class="chevron">›</span>
    </button>
  </section>
</template>

<script setup>
</script>
