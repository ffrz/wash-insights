<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { useQuasar } from 'quasar';
import { validateEmail } from "@/helpers/validations";

defineProps({
  status: {
    type: String,
  },
});

const emailInput = ref();
const form = useForm({
  email: '',
});

const submit = () => {
  form.clearErrors();
  form.post(route('password.email'), {
    preserveScroll: true,
    onError: () => {
      emailInput.value.focus();
    }
  });
};


</script>

<template>
  <guest-layout>
    <i-head title="Lupa Kata Sandi" />
    <q-page class="row justify-center items-center">
      <div class="column">
        <q-form @submit.prevent="submit">
          <q-card square bordered class="q-pa-md shadow-1">
            <q-card-section>
              <h5 class="q-my-sm text-center">Lupa Kata Sandi</h5>
            </q-card-section>
            <q-card-section class="text-grey-8">
              Lupa kata sandi? Tidak masalah. Beri tahu kami alamat email anda
              dan kami akan mengirim tautan untuk mengatur ulang kata sandi ke email anda
              sehingga anda bisa membuat kata sandi baru.
            </q-card-section>
            <q-card-section v-if="status" class="text-green-9 text-weight-bold border">
              {{ status }}
            </q-card-section>
            <q-card-section>
              <q-input square v-model.trim="form.email" label="Email" lazy-rules :error="!!form.errors.email"
                :error-message="form.errors.email" :disable="form.processing"
                :rules="[(val) => validateEmail(val) || 'Format Email tidak valid.']">
                <template v-slot:append>
                  <q-icon name="email" />
                </template>
              </q-input>
            </q-card-section>
            <q-card-actions>
              <q-btn icon="email" type="submit" color="primary" class="full-width" label="Email Password Reset Link"
                :disable="form.processing" />
            </q-card-actions>
            <q-card-section class="text-center q-pa-none q-mt-md">
              <p class="q-my-xs text-grey-7">
                <i-link :href="route('admin.auth.login')">Kembali ke halaman login</i-link>
              </p>
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </guest-layout>
</template>

<style>
.q-card {
  width: 360px;
}
</style>
