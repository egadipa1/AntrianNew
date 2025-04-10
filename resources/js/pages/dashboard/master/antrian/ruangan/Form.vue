<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Ruangan } from "@/types";
import ApiService from "@/core/services/ApiService";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const ruangan = ref<Ruangan>({} as Ruangan);
const emit = defineEmits(["close", "refresh"]);
const formRef = ref();

const formSchema = Yup.object().shape({
    ruang: Yup.number().required("Nama harus diisi"),
    lantai: Yup.number().required("Deskripsi harus diisi"),
});

function getEdit(){
    block(document.getElementById("form-role"));
    ApiService.get("master/ruangan", props.selected)
        .then(({ data }) => {
            ruangan.value = data.data;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-role"));
        });
}

function submit(){

    const formData = new FormData();
    formData.append("ruang", ruangan.value.ruang.toString());
    formData.append("lantai", ruangan.value.lantai.toString());

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-ruangan"));
    axios({
        method: "post",
        url: props.selected
            ? `/master/ruangan/${props.selected}`
            : "/master/ruangan/store",
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
            unblock(document.getElementById("form-ruangan"));
        });
}


onMounted(async () => {
    if (props.selected) {
        console.log("selected", props.selected);
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
        :validation-schema="formSchema"
        id="form-ruangan"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Ruangan</h2>
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
                            Ruang
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="number"
                            name="ruang"
                            autocomplete="off"
                            v-model="ruangan.ruang"
                            placeholder="Masukkan Ruang"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="ruang" />
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Lantai
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="number"
                            name="lantai"
                            autocomplete="off"
                            placeholder="Masukkan Lantai"
                            v-model="ruangan.lantai"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="lantai" />
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
