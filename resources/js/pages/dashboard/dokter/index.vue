<template>
  <div class="card mb-2">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h2 class="mb-0">Status Dokter</h2>
      <div class="switch-fixed">
        <v-switch
          v-model="model"
          color="info"
          :label="model"
          false-value="Nonaktif"
          true-value="Aktif"
          hide-details
          inset
          @change="onStatusChange"
        />
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h2 class="mb-0">Sedang Dilayani</h2>
    </div>
    <div class="card-body">
      
    </div>
  </div>
  
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import axios from "@/libs/axios";
import ApiService from '@/core/services/ApiService';
import { toast } from "vue3-toastify";

const store = useAuthStore();
ApiService.get("menu-dokter/dokter", store.user.id.toString())
        .then(({ data }) => {
          dokter.value = data.dokter;
          console.log(dokter.value.id);
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
const dokter = ref({
  status: 'Nonaktif', // bisa "Aktif", "Nonaktif", atau "Menunggu"
})

// Model yang akan dikontrol switch
const model = computed({
  get() {
    return dokter.value.status === 'Aktif' || dokter.value.status === 'Menunggu'
      ? 'Aktif'
      : 'Nonaktif'
  },
  set(val) {
    // ketika user toggle switch, kita update status
    dokter.value.status = val === 'Aktif' ? 'Aktif' : 'Nonaktif'
  },
})

const labelStatus = computed(() => {
  return dokter.value.status
})

const onStatusChange = () => {
  axios({
        method: "put",
        url: `/menu-dokter/dokter/${dokter.value.id}`,
        data: dokter.value,
    })
        .then(() => {
            toast.success(`Status Telah Diubah Ke : ${dokter.value.status}`);
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
  console.log('Status diubah jadi:', dokter.value.status)
}
</script>

<style scoped>
.switch-fixed {
  min-width: 120px; /* Biar tombol switch + label punya ruang tetap */
  display: flex;
  justify-content: flex-start;
  align-items: center;
}
</style>
