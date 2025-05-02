<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

const props = defineProps({
  status: {
    type: String,
  },
});

const form = useForm({});

const submit = () => {
  form.clearErrors();
  form.post(route('verification.send'));
};

const verificationLinkSent = computed(
  () => props.status === 'verification-link-sent',
);

</script>

<template>
  <guest-layout>
    <i-head title="Email Verification" />
    <q-page class="row justify-center items-center">
      <div class="column">
        <div class="row">
          <q-form @submit.prevent="submit">
            <q-card square bordered class="q-pa-md shadow-1">
              <q-card-section>
                <h5 class="q-my-sm text-center">Email Verification</h5>
              </q-card-section>
              <q-card-section>
                <p>
                  Thanks for signing up! Before getting started, could you verify your
                  email address by clicking on the link we just emailed to you? If you
                  didn't receive the email, we will gladly send you another.
                </p>
                <p class="text-weight-bold text-green-8" v-if="verificationLinkSent">
                  A new verification link has been sent to the email address you
                  provided during registration.
                </p>
              </q-card-section>
              <q-card-section>
                <q-btn class="full-width" color="primary" :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing" type="submit" icon="send">
                  <span class="q-ml-sm">Resend Verification Email</span>
                </q-btn>
                <q-btn class="q-mt-md full-width" icon="logout"
                  @click.prevent="router.post(route('logout'))">
                  <span class="q-ml-sm">Log Out</span>
                </q-btn>
              </q-card-section>
            </q-card>
          </q-form>
        </div>
      </div>
    </q-page>
  </guest-layout>
</template>


<style>
.q-card {
  width: 360px;
}
</style>
