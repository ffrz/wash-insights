<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { handleFetchItems, handleDelete } from "@/helpers/client-req-handler";
import { create_options } from "@/helpers/utils";
import i18n from "@/i18n";
import { useQuasar } from "quasar";

const roles = [
  { value: "all", label: "Semua" },
  ...create_options(window.CONSTANTS.USER_ROLES),
];

const statuses = [
  { value: "all", label: "Semua" },
  { value: "active", label: "Aktif" },
  { value: "inactive", label: "Tidak Aktif" },
];

const page = usePage();
const $q = useQuasar();
const currentUser = page.props.auth.user;
const title = i18n.global.t("users");
const rows = ref([]);
const loading = ref(true);
const showFilter = ref(false);
const filter = reactive({
  role: "all",
  status: "active",
  search: "",
});

const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "username",
  descending: false,
});

const columns = [
  {
    name: "username",
    label: "ID Pengguna",
    field: "username",
    align: "left",
    sortable: true,
  },
  {
    name: "name",
    label: "Nama",
    field: "name",
    align: "left",
    sortable: true,
  },
  {
    name: "role",
    label: "Hak Akses",
    field: "role",
    align: "center",
    sortable: true,
  },
  {
    name: "action",
    align: "right",
  },
];

onMounted(() => {
  fetchItems();
});

const onFilterChange = () => fetchItems();

const fetchItems = (props = null) =>
  handleFetchItems({
    pagination,
    props,
    rows,
    loading,
    filter,
    url: route("admin.user.data"),
  });

const deleteItem = (row) =>
  handleDelete({
    url: route("admin.user.delete", row.id),
    message: `Hapus pengguna ${row.username}?`,
    fetchItemsCallback: fetchItems,
    loading,
  });

const computedColumns = computed(() => {
  if ($q.screen.gt.sm) return columns;
  return columns.filter((col) => col.name === "username" || col.name === "action");
});

const onRowClicked = (row) => router.get(route("admin.user.detail", row.id));

</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #right-button>
      <q-btn
        icon="add"
        dense
        color="primary"
        @click="router.get(route('admin.user.add'))"
      />
      <q-btn
        class="q-ml-sm"
        :icon="!showFilter ? 'filter_alt' : 'filter_alt_off'"
        color="grey"
        dense
        @click="showFilter = !showFilter"
      />
    </template>
    <template #header v-if="showFilter">
      <q-toolbar class="filter-bar">
        <div class="row q-col-gutter-xs items-center q-pa-sm full-width">
          <q-select
            v-model="filter.role"
            class="custom-select col-xs-12 col-sm-2"
            :options="roles"
            label="Role"
            dense
            map-options
            emit-value
            outlined
            style="min-width: 150px"
            @update:model-value="onFilterChange"
          />
          <q-select
            v-model="filter.status"
            class="custom-select col-xs-12 col-sm-2"
            :options="statuses"
            label="Status"
            dense
            map-options
            emit-value
            outlined
            style="min-width: 150px"
            @update:model-value="onFilterChange"
          />
          <q-input
            class="col"
            outlined
            dense
            debounce="300"
            v-model="filter.search"
            placeholder="Cari"
            clearable
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </div>
      </q-toolbar>
    </template>
    <div class="q-pa-sm">
      <q-table
        class="full-height-table"
        flat
        bordered
        square
        color="primary"
        row-key="id"
        virtual-scroll
        v-model:pagination="pagination"
        :filter="filter.search"
        :loading="loading"
        :columns="computedColumns"
        :rows="rows"
        :rows-per-page-options="[10, 25, 50]"
        @request="fetchItems"
        binary-state-sort
      >
        <template v-slot:loading>
          <q-inner-loading showing color="red" />
        </template>

        <template v-slot:no-data="{ icon, message, term }">
          <div class="full-width row flex-center text-grey-8 q-gutter-sm">
            <span>{{ message }} {{ term ? " with term " + term : "" }}</span>
          </div>
        </template>

        <template v-slot:body="props">
          <q-tr :props="props" :class="!props.row.active ? 'bg-red-1' : ''" @click="onRowClicked(props.row)" class="cursor-pointer">
            <q-td key="username" :props="props">
              <div>{{ props.row.username }}</div>
              <template v-if="!$q.screen.gt.sm">
                <div><q-icon name="person"/> {{ props.row.name }}</div>
                <div class="elipsis" style="max-width: 200px;"><q-icon name="group"/> <span>{{ $CONSTANTS.USER_ROLES[props.row.role] }}</span></div>
              </template>
            </q-td>
            <q-td key="name" :props="props">
              {{ props.row.name }}
            </q-td>
            <q-td key="role" :props="props" align="center">
              <span>{{ $CONSTANTS.USER_ROLES[props.row.role] }}</span>
            </q-td>
            <q-td
              key="action"
              :props="props"
            >
              <div class="flex justify-end">
                <q-btn
                  :disable="props.row.id == currentUser.id || props.row.username == 'admin'"
                  icon="more_vert"
                  dense
                  flat
                  style="height: 40px; width: 30px"
                  @click.stop
                >
                  <q-menu
                    anchor="bottom right"
                    self="top right"
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-list style="width: 200px">
                      <q-item
                        clickable
                        v-ripple
                        v-close-popup
                        @click.stop="router.get(route('admin.user.duplicate', props.row.id))"
                      >
                        <q-item-section avatar>
                          <q-icon name="file_copy" />
                        </q-item-section>
                        <q-item-section icon="copy"> Duplikat </q-item-section>
                      </q-item>
                      <q-item
                        clickable
                        v-ripple
                        v-close-popup
                        @click.stop="router.get(route('admin.user.edit', props.row.id))"
                      >
                        <q-item-section avatar>
                          <q-icon name="edit" />
                        </q-item-section>
                        <q-item-section icon="edit">{{ $t("edit_user") }}</q-item-section>
                      </q-item>
                      <q-item
                        @click.stop="deleteItem(props.row)"
                        clickable
                        v-ripple
                        v-close-popup
                      >
                        <q-item-section avatar>
                          <q-icon name="delete_forever" />
                        </q-item-section>
                        <q-item-section>{{ $t("delete_user") }}</q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
              </div>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </div>
  </authenticated-layout>
</template>
