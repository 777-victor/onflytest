<template>
  <q-item clickable @click="redirect(route)">
    <q-item-section v-if="icon" avatar>
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section>
      <q-item-label>{{ title }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
import { defineComponent } from "vue";
import { useRouter } from "vue-router";

export default defineComponent({
  name: "EssentialLink",
  props: {
    title: {
      type: String,
      required: true,
    },

    route: {
      type: Object,
    },

    icon: {
      type: String,
      default: "",
    },
  },

  setup() {
    const router = useRouter();

    return {
      redirect(route) {
        router.push({ path: "/" + route.name });
      },
    };
  },
});
</script>
