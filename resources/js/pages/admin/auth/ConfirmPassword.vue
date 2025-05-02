<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  password: '',
});

const submit = () => {
  form.clearErrors();
  form.post(route('password.confirm'), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <guest-layout>
    <i-head title="Confirm Password" />
    <q-page class="row justify-center items-center">
      <div class="column">
        <q-form @submit.prevent="submit">
          <q-card square bordered class="q-pa-md shadow-1">
            <q-card-section>
              <h5 class="q-my-sm text-center">Confirm Password</h5>
            </q-card-section>
            <q-card-section class="text-grey-8">
              This is a secure area of the application. Please confirm your
              password before continuing.
            </q-card-section>
            <q-card-section v-if="status" class="text-green-9 text-weight-bold border">
              {{ status }}
            </q-card-section>
            <q-card-section>
              <q-input autofocus square v-model.trim="form.password" label="Password" type="password" lazy-rules
                :disable="form.processing" :error="!!form.errors.password" :error-message="form.errors.password"
                :rules="[(val) => val && val.length > 0 || 'Password field is required.']">
                  <template v-slot:append>
                    <q-icon name="key" />
                  </template>
                </q-input>
            </q-card-section>
            <q-card-actions>
              <q-btn icon="send" type="submit" color="primary" class="full-width" label="Confirm" :disable="form.processing" />
            </q-card-actions>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </guest-layout>
</template>
