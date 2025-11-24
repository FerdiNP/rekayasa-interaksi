<template>
  <section class="card table-wrapper">
    <div class="table-header-top">
      <div class="table-title">Riwayat Cuti</div>
      <div class="table-toolbar">
        <input
          v-model="search"
          type="text"
          class="search-input"
          placeholder="Cari tahun/semester..."
        />
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Tahun Ajar</th>
          <th>Semester Ajar</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in filteredItems"
          :key="index"
          :class="['table-row', isSelected(index) ? 'row-selected' : '']"
          @click="selectRow(item, index)"
        >
          <td>{{ index + 1 }}</td>
          <td>{{ item.tahunAjar }}</td>
          <td>{{ item.semesterAjar }}</td>
        </tr>
        <tr v-if="filteredItems.length === 0">
          <td colspan="3" class="empty-cell">Data tidak ditemukan</td>
        </tr>
      </tbody>
    </table>

    <div v-if="selectedRow" class="detail-box">
      <div class="detail-title">Detail Riwayat Cuti</div>
      <div class="detail-row"><span>No</span><span>{{ selectedRow.no }}</span></div>
      <div class="detail-row"><span>Tahun Ajar</span><span>{{ selectedRow.tahunAjar }}</span></div>
      <div class="detail-row"><span>Semester Ajar</span><span>{{ selectedRow.semesterAjar }}</span></div>
    </div>

    <button class="back-button-bottom" type="button" @click="$emit('back')">
      ← Kembali
    </button>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  }
})

const search = ref('')
const selectedRow = ref(null)

const filteredItems = computed(() => {
  if (!search.value) return props.items
  const q = search.value.toLowerCase()
  return props.items.filter(
    (item) =>
      item.tahunAjar.toLowerCase().includes(q) ||
      String(item.semesterAjar).toLowerCase().includes(q)
  )
})

const selectRow = (item, index) => {
  selectedRow.value = {
    no: index + 1,
    tahunAjar: item.tahunAjar,
    semesterAjar: item.semesterAjar
  }
}

const isSelected = (index) => {
  if (!selectedRow.value) return false
  return selectedRow.value.no === index + 1
}
</script>
