<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title> Quasar App v{{ $q.version }} </q-toolbar-title>

        <q-btn flat dense round icon="logout" @click="logout" />
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from "./../stores/user-store";

const userStore = useUserStore();
const router = useRouter();

export default defineComponent({
  name: "MainLayout",

  setup() {
    return {
      logout() {
        userStore.logout();
        router.push({ path: "/auth" });
      },
    };
  },
});
</script>
