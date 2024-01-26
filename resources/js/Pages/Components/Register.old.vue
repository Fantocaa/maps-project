<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Link } from "@inertiajs/vue3";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net";
import $ from "jquery";
import axios from "axios";
import { ref, onMounted } from "vue";
import Button from "@/Components/Button.vue";
import { GithubIcon } from "@/Components/Icons/brands";
import "datatables.net-select";
import "datatables.net-responsive";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
/* import specific icons */
import { faPencil } from "@fortawesome/free-solid-svg-icons";

DataTable.use(DataTablesCore);
library.add(faPencil);

let data = ref([]);

// const deleteUser = async (id) => {
//     if (confirm("Are you sure you want to delete this user?")) {
//         await axios.delete(`/api/user/${id}`);
//         await fetchData();
//     }
// };

const fetchData = async () => {
    const response = await axios.get("/api/role");
    data.value = response.data;
    console.log(response.data);
};

onMounted(async () => {
    fetchData();
});

const columns = [
    {
        data: "id",
        title: "No",
        render: function (data, type, row, meta) {
            return meta.row + 1;
        },
    },
    { data: "name", title: "Name" },
    { data: "email", title: "Email" },
    // { data: "password", title: "Password" },
    { data: "role_names", title: "Role" },
    {
        data: null,
        title: "Actions",
        orderable: false,
        render: function (data, type, row) {
            return `<FontAwesomeIcon icon="pencil" @click="navigateToUserEdit(${data.id})"/>`;
        },
    },
];
</script>

<style>
@import "datatables.net-dt";
@import "datatables.net-bs5";
/* @import "bootstrap"; */
</style>

<template>
    <AuthenticatedLayout title="Manage User">
        <template #header>
            <div
                class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8"
            >
                <h2 class="text-xl font-semibold leading-tight">Manage User</h2>
                <Button
                    external
                    variant="black"
                    class="items-center gap-2 max-w-xs"
                    v-slot="{ iconSizeClasses }"
                    href="/register"
                >
                    <!-- <GithubIcon aria-hidden="true" :class="iconSizeClasses" /> -->
                    <span>Add New User</span>
                </Button>
            </div>
        </template>
        <template #default>
            <DataTable
                :data="data"
                :columns="columns"
                class="table table-hover table-striped"
                width="100%"
                @click="navigateToUserEdit"
            />
        </template>
    </AuthenticatedLayout>
</template>
