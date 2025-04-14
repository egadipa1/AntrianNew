<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Poli, Dokter, Ruangan } from "@/types";
import ApiService from "@/core/services/ApiService";
import { usePoli } from "@/services/usePoli";
import { useRuangan } from "@/services/useRuangan";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const dokter = ref<Dokter>({} as Dokter);
const formRef = ref();

// const formSchema = Yup.object().shape({
//     poli_id: Yup.string().required("Poliklinik harus diisi"),
//     ruangan_id: Yup.string().required("Ruangan harus diisi"),
// });

function getEdit() {
    block(document.getElementById("form-user"));
    ApiService.get("master/dokter", props.selected)
        .then(({ data }) => {
            dokter.value = data.dokter;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("poli_id", dokter.value.poli_id.toString());
    formData.append("ruangan_id", dokter.value.ruangan_id.toString());

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-user"));
    console.log(formData.get("poli"));
    console.log(formData.get("ruangan"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/dokter/${props.selected}`
            : "/master/dokter/store",
        data: formData,
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(() => {
            emit("close");
            emit("refresh");
            toast.success("Data berhasil disimpan");
            formRef.value.resetForm();
        })
        .catch((err: any) => {
            formRef.value.setErrors(err.response.data.errors);
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

const poli = usePoli();
const polis = computed(() =>
    poli.data.value?.map((item: Poli) => ({
        id: item.id,
        text: item.name,
    }))
);

const ruangan = useRuangan();
const ruangans = computed(() =>
    ruangan.data.value?.map((item: Ruangan) => ({
        id: item.id,
        text: item.ruang,
    }))
);

onMounted(async () => {
    if (props.selected) {
        getEdit();
    }
});

watch(
    () => props.selected,
    () => {
        if (props.selected) {
            getEdit();
        }
    }
);
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submit"
        id="form-user"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Dokter</h2>
            <button
                type="button"
                class="btn btn-sm btn-light-danger ms-auto"
                @click="emit('close')"
            >
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Poliklinik
                        </label>
                        <Field
                            name="poli_id"
                            type="hidden"
                            v-model="dokter.poli_id"
                        >
                            <select2
                                placeholder="Pilih role"
                                class="form-select-solid"
                                :options="polis"
                                name="poli_id"
                                v-model="dokter.poli_id"
                            >
                            </select2>
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="poli_id" />
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Ruangan
                        </label>
                        <Field
                            name="ruangan_id"
                            type="hidden"
                            v-model="dokter.ruangan_id"
                        >
                            <select2
                                placeholder="Pilih role"
                                class="form-select-solid"
                                :options="ruangans"
                                name="ruangan_id"
                                v-model="dokter.ruangan_id"
                            >
                            </select2>
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="ruangan_id" />
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
        </div>
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>
