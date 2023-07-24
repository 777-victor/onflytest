<template>
  <q-page class="flex flex-center bg-grey-2">
    <q-card class="q-pa-md shadow-2 my_card" bordered>
      <q-card-section class="text-center">
        <div class="text-grey-9 text-h5 text-weight-bold">Sign in</div>
        <div class="text-grey-8">Sign in below to access your account</div>
      </q-card-section>
      <q-form @submit="onSubmit">
        <q-card-section>
          <q-input dense v-model="login.email" label="Email"></q-input>
          <q-input
            dense
            class="q-mt-md"
            v-model="login.password"
            type="password"
            label="Password"
          ></q-input>
        </q-card-section>
        <q-card-section>
          <q-btn
            style="border-radius: 8px"
            color="dark"
            rounded
            size="md"
            label="Sign in"
            no-caps
            class="full-width"
            type="submit"
          ></q-btn>
        </q-card-section>
      </q-form>
      <q-card-section class="text-center q-pt-none">
        <div class="text-grey-8">
          Don't have an account yet?
          <a
            href="#"
            class="text-dark text-weight-bold"
            style="text-decoration: none"
            >Sign up.</a
          >
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { useQuasar } from "quasar";
import { ref } from "vue";
import { post } from "src/helpers/request";

const $q = useQuasar();
const login = ref({
  email: "",
  password: "",
});

async function onSubmit() {
  const data = await post("login", login);
  console.log(data);
}
</script>
