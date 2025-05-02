<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import { create_options_from_users, scrollToFirstErrorField } from "@/helpers/utils";
import { ref } from "vue";

const page = usePage();
const users = ref(create_options_from_users(page.props.users));
const filteredUsers = ref([...users.value]);
const title = (!!page.props.data.id ? "Edit" : "Tambah") + " Teknisi";
const form = useForm({
  id: page.props.data.id,
  user_id: page.props.data.user_id,
  name: page.props.data.name,
  phone: page.props.data.phone,
  address: page.props.data.address,
  email: page.props.data.email,
  active: !!page.props.data.active,
});

const submit = () =>
  handleSubmit({ form, url: route('admin.technician.save') });

const filterUsers = (val, update) => {
  update(() => {
    filteredUsers.value = users.value.filter(user => user.label.toLowerCase().includes(val.toLowerCase()));
  });
};

</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <q-form class="row" @submit.prevent="submit" @validation-error="scrollToFirstErrorField">
          <q-card square flat bordered class="col">
            <q-card-section>
              <div class="text-subtitle1">Profil Teknisi</div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-input autofocus v-model.trim="form.name" label="Nama" lazy-rules :error="!!form.errors.name"
                :disable="form.processing" :error-message="form.errors.name" :rules="[
                  (val) => (val && val.length > 0) || 'Nama harus diisi.',
                ]" />
              <q-select v-model="form.user_id" label="Akun Pengguna" use-input input-debounce="300" clearable
                :options="filteredUsers" map-options emit-value @filter="filterUsers" :error="!!form.errors.user_id"
                :disable="form.processing">
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section>Pengguna tidak ditemukan</q-item-section>
                  </q-item>
                </template>
              </q-select>
              <q-input v-model.trim="form.phone" type="text" label="No HP" lazy-rules :disable="form.processing"
                :error="!!form.errors.phone" :error-message="form.errors.phone" />
                <q-input v-model.trim="form.email" type="email" label="Email" lazy-rules :disable="form.processing"
                :error="!!form.errors.email" :error-message="form.errors.email" />
              <q-input v-model.trim="form.address" type="textarea" autogrow counter maxlength="1000" label="Alamat"
                lazy-rules :disable="form.processing" :error="!!form.errors.address"
                :error-message="form.errors.address" />
              <div style="margin-left: -10px;">
                <q-checkbox class="full-width q-pl-none" v-model="form.active" :disable="form.processing"
                  label="Aktif" />
              </div>
            </q-card-section>
            <q-card-section class="q-gutter-sm">
              <q-btn icon="save" type="submit" label="Simpan" color="primary" :disable="form.processing" />
              <q-btn icon="cancel" label="Batal" :disable="form.processing"
                @click="router.get(route('admin.technician.index'))" />
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </q-page>

  </authenticated-layout>
</template>
