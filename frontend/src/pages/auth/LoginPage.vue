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
          <q-btn
            size="small"
            flat
            :to="{ path: '/register' }"
            class="text-dark text-weight-bold"
            style="text-decoration: none"
            >Sign up</q-btn
          >
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { useQuasar } from "quasar";
import { ref, onMounted } from "vue";
import { useUserStore } from "src/stores/user-store";
import { useRouter } from "vue-router";

const $q = useQuasar();
const router = useRouter();
const userStore = useUserStore();
const login = ref({
  email: "",
  password: "",
});

onMounted(() => {
  const token = window.localStorage.getItem("access_token");
  if (token) {
    console.log("token found");
    router.push({ path: "/expenses" });
  }
});

async function onSubmit() {
  await userStore.getSanctumCookie();
  const data = await userStore.login(login.value);

  if (data.user) {
    userStore.setUser(data.user);
    userStore.setToken(data.token);

    router.push({ path: "/expenses" });
  }
}
</script>
