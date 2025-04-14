<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Dokter } from "@/types";

const column = createColumnHelper<Dokter>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");
const openForm = ref<boolean>(false);

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("name", {
        header: "Nama",
    }),
    column.accessor("email", {
        header: "Email",
    }),
    column.accessor("polis_name", {
        header: "Poliklinik",
    }),
    column.accessor("ruang", {
        header: "Ruangan",
    }),
    column.accessor("jumlah_melayani", {
        header: "Jumlah Melayani",
    }),
    column.accessor("status", {
        header: "Status",
        cell: (cell) =>
            h("div", { class: "d-flex justify-content-center" }, [
                h(
                    "button",
                    {
                        class: `btn btn-sm ${
                            cell.getValue() === "Aktif" ? "btn-success" :
                            cell.getValue() === "Nonaktif" ? "btn-danger" :
                            cell.getValue() === "Menunggu" ? "btn-warning" :
                            ""
                        }`,
                    },
                    h("h", cell.getValue())
                ),
            ]),
    }),
    column.accessor("dokter_id", {
        header: "Aksi",
        cell: (cell) =>
        
            h("div", { class: "d-flex justify-content-center gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => {
                            console.log("Full row:", cell.row.original);
                            selected.value = cell.getValue();
                            console.log(cell.getValue());
                            openForm.value = true;
                        },
                    },
                    h("i", { class: "la la-pencil fs-2" })
                ),
            ]),
    }),
];

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
});
</script>

<template>
    <Form
        :selected="selected"
        @close="openForm = false"
        v-if="openForm"
        @refresh="refresh"
    />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">List Dokter</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-auto"
                v-if="!openForm"
                @click="openForm = true"
            >
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-dokters"
                url="/master/dokter"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
