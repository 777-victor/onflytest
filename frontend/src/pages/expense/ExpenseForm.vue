<template>
  <q-page class="bg-grey-2" padding>
    <q-card class="q-pa-md">
      <q-card-title class="relative-position">
        <p class="text-h4">Add Expense</p>
      </q-card-title>
      <q-card-section>
        <q-form @submit="onSubmit" class="row q-col-gutter-md" ref="myForm">
          <q-input
            outlined
            v-model="expense.description"
            color="deep-purple"
            label="Description"
            class="col-md-12 col-sm-12 col-xs-12"
            :rules="[
              (val) => (val && val.length > 0) || 'description is missing',
              (val) =>
                (val && val.length <= 191) ||
                'description cannot have more than 191 characters',
            ]"
          />

          <q-input
            v-model="expense.value"
            label="Value"
            outlined
            class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
            mask="#.##"
            :rules="[
              (val) => (val && val.length > 0) || 'Value is missing',
              (val) => (val && val > 0) || 'Value cannot be less than 0',
            ]"
          />
          <q-input
            v-model="expense.date"
            type="date"
            label="Date of expense"
            outlined
            class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
            :rules="[(val) => (val && val.length > 0) || 'Date is missing']"
          />

          <div class="col-12 row justify-center">
            <q-btn
              label="Submit"
              :disable="submiting"
              type="submit"
              color="primary"
            />
            <q-btn
              label="Back"
              color="default"
              @click="goBack()"
              class="text-grey-10 q-ml-md"
            />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { useQuasar } from "quasar";
import { post } from "src/helpers/request";
import { useUserStore } from "src/stores/user-store";
import { storeToRefs } from "pinia";
import { ref } from "vue";
import { useRouter } from "vue-router";

const userStore = useUserStore();
const $q = useQuasar();
const { getId } = storeToRefs(userStore);
const submiting = ref(false);
const router = useRouter();
const expense = ref({
  description: "teste",
  value: 1.1,
  date: new Date().toDateString(),
  user_id: null,
});

async function onSubmit() {
  submiting.value = true;
  console.log(getId.value);
  const user = JSON.parse(window.localStorage.getItem("user"));
  let userId = getId.value ?? user.id;
  expense.value.user_id = userId;

  post("expenses", expense.value)
    .then((response) => {
      if (response.status == 201) {
        const { data } = response;

        $q.notify({
          message: "Expense registered successfully",
          color: "positive",
          icon: "check_circle_outline",
        });
        router.push({ path: "/expenses" });
      }
    })
    .catch((error) => {
      console.log(error);
      let message =
        error.response?.data?.message || "Failed to register a expense";

      $q.notify({
        message: message,
        color: "negative",
        icon: "error",
      });
    })
    .finally(() => {
      submiting.value = false;
    });
}

async function goBack() {
  router.push("/expenses");
}
</script>
