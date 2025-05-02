<script setup>
import { useForm } from "@inertiajs/vue3";
import { validateEmail } from "@/helpers/validations";
import { scrollToFirstErrorField } from "@/helpers/utils";
import { handleSubmit } from "@/helpers/client-req-handler";
import { ref } from "vue";

// TODO:
// 1. Lakukan validasi lanjutan untuk company_code, username dan password di local
// company_code: hanya boleh alpha num minimal m karakter, maksimal n karakter
// username: hanya boleh alpha num minimal m karakter, maksimal n karakter
// password: harus terdiri dari huruf dan angka, minimal 5 karakter
// sinkronisasi kriteria validasi dengan server side validation

let form = useForm({
  company_code: "",
  company_name: "",
  username: "admin",
  name: "Administrator",
  email: "",
  password: "",
  password_confirmation: "",
});

const submit = () => handleSubmit({ form, url: route("admin.auth.register") });
const showPassword = ref(false);
</script>

<template>
  <guest-layout>
    <i-head title="Register" />
    <q-page class="row justify-center items-center">
      <q-form
        id="register-page"
        @submit.prevent="submit"
        @validation-error="scrollToFirstErrorField"
      >
        <q-card square bordered class="q-pa-md shadow-1 full-width">
          <q-card-section>
            <h6 class="q-my-none text-center">
              Buat akun {{ $config.APP_NAME }}
            </h6>
          </q-card-section>
          <q-card-section>
            <h6 class="q-my-none text-body1">Informasi Perusahaan</h6>
            <q-input
              v-model.trim="form.company_code"
              label="Kode Perusahaan *"
              lazy-rules
              autocomplete="off"
              :error="!!form.errors.company_code"
              :error-message="form.errors.company_code"
              :disable="form.processing"
              :rules="[
                (val) =>
                  (val && val.length > 0) || 'Kode perusahaan harus diisi',
              ]"
              placeholder="Setelah terdaftar kode tidak dapat diganti."
            >
            </q-input>
            <q-input
              v-model.trim="form.company_name"
              label="Nama Perusahaan *"
              lazy-rules
              autocomplete="off"
              :error="!!form.errors.company_name"
              :error-message="form.errors.company_name"
              :disable="form.processing"
              :rules="[
                (val) =>
                  (val && val.length > 0) || 'Nama perusahaan harus diisi',
              ]"
              placeholder="Anda dapat mengubahnya nanti."
            >
              <template v-slot:append>
                <q-icon name="apartment" />
              </template>
            </q-input>
          </q-card-section>
          <q-card-section>
            <h6 class="q-my-none text-body1">
              Informasi Akun Utama (Administrator)
            </h6>
            <q-input
              v-model.trim="form.username"
              label="Username / ID Pengguna *"
              lazy-rules
              autocomplete="username"
              readonly
              :error="!!form.errors.username"
              :error-message="form.errors.username"
              :disable="form.processing"
              :rules="[
                (val) =>
                  (val && val.length > 0) ||
                  'Username / ID Pengguna harus diisi',
              ]"
            >
            </q-input>
            <q-input
              v-model.trim="form.name"
              label="Nama *"
              lazy-rules
              :error="!!form.errors.name"
              autocomplete="name"
              :error-message="form.errors.name"
              :disable="form.processing"
              :rules="[(val) => (val && val.length > 0) || 'Nama harus diisi']"
            >
              <template v-slot:append>
                <q-icon name="person" />
              </template>
            </q-input>
            <q-input
              v-model.trim="form.email"
              label="Email *"
              lazy-rules
              :error="!!form.errors.email"
              autocomplete="email"
              :error-message="form.errors.email"
              :disable="form.processing"
              :rules="[
                (val) => validateEmail(val) || 'Alamat email tidak valid',
              ]"
            >
              <template v-slot:append>
                <q-icon name="email" />
              </template>
            </q-input>
            <q-input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              label="Kata Sandi *"
              :error="!!form.errors.password"
              autocomplete="off"
              :error-message="form.errors.password"
              lazy-rules
              :disable="form.processing"
              :rules="[
                (val) =>
                  (val && val.length > 0) || 'Silahkan masukkan kata sandi.',
              ]"
            >
              <template v-slot:append>
                <q-btn dense flat round @click="showPassword = !showPassword"
                  ><q-icon :name="showPassword ? 'key_off' : 'key'"
                /></q-btn>
              </template>
            </q-input>
            <q-input
              square
              v-model="form.password_confirmation"
              :type="showPassword ? 'text' : 'password'"
              label="Konfirmasi Kata Sandi *"
              autocomplete="off"
              :disable="form.processing"
              :error="!!form.errors.password_confirmation"
              :error-message="form.errors.password_confirmation"
              lazy-rules
              :rules="[
                (val) =>
                  (val && val.length > 0) ||
                  'Silahkan konfirmasi kata sandi anda.',
                () =>
                  form.password == form.password_confirmation ||
                  'Konfirmasi kata sandi tidak cocok.',
              ]"
            >
              <template v-slot:append>
                <q-btn dense flat round @click="showPassword = !showPassword"
                  ><q-icon :name="showPassword ? 'key_off' : 'key'"
                /></q-btn>
              </template>
            </q-input>
          </q-card-section>
          <q-card-actions>
            <q-btn
              icon="how_to_reg"
              type="submit"
              color="primary"
              class="full-width"
              label="Buat Akun"
              :disable="form.processing"
            />
          </q-card-actions>
          <q-card-section class="text-center q-pa-none q-mt-md">
            <p class="q-my-xs text-grey-7">
              Sudah terdaftar?
              <i-link :href="route('admin.auth.login')">Masuk</i-link>
            </p>
          </q-card-section>
        </q-card>
      </q-form>
    </q-page>
  </guest-layout>
</template>

<style scoped>
#register-page {
  width: 600px;
}

@media (max-width: 1024px) {
  #register-page {
    max-width: 360px !important;
  }
}
</style>
