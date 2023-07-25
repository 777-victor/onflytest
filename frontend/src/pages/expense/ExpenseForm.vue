<template>
  <q-page class="bg-grey-2" padding>
    <q-card class="q-pa-md">
      <q-card-section class="relative-position">
        <p class="text-h4">{{ editing ? "Edit" : "Add" }} Expense</p>
      </q-card-section>
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
            mask="##.##"
            :rules="[
              (val) => (val && val > 0) || 'Value cannot be less than 0',
            ]"
          />

          <q-input
            v-model="expense.date"
            label="Date of the expense"
            class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
            outlined
            mask="date"
            :rules="['required', 'date']"
          >
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy>
                  <q-date v-model="expense.date"></q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>

          <!-- <q-input
            v-model="expense.date"
            type="date"
            mask="YYYY-MM-DD"
            label="Date of the expense"
            outlined
            class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
            :rules="[(val) => (val && val.length > 0) || 'Date is missing']"
          /> -->

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
import { useUserStore } from "src/stores/user-store";
import { storeToRefs } from "pinia";
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { get, post, put } from "../../helpers/request";

const userStore = useUserStore();
const $q = useQuasar();
const { getId } = storeToRefs(userStore);
const submiting = ref(false);
const editing = ref(false);
const router = useRouter();
const route = useRoute(); // actual route
const expense = ref({
  id: null,
  description: "",
  value: 0,
  date: "",
  user_id: null,
});

onMounted(async () => {
  editing.value = route.params.id !== undefined;
  if (editing.value) {
    const { data } = await fetchExpense(route.params.id);
    expense.value = data.expense;
  }
});

async function fetchExpense(id) {
  return await get("/expenses/" + id);
}

function onSubmit() {
  submiting.value = true;
  const user = JSON.parse(window.localStorage.getItem("user"));
  let userId = getId.value ?? user.id;
  expense.value.user_id = userId;

  editing.value ? handleUpdateExpense() : handleAddExpense();
}

function handleAddExpense() {
  post("expenses", expense.value)
    .then((response) => {
      if (response.status == 201) {
        $q.notify({
          message: "Expense registered successfully",
          color: "positive",
          icon: "check_circle_outline",
        });
        router.push({ path: "/expenses" });
      }
    })
    .catch((error) => {
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

function handleUpdateExpense() {
  put("expenses/" + expense.value.id, expense.value)
    .then((response) => {
      $q.notify({
        message: response.data.message,
        color: "positive",
        icon: "check_circle_outline",
      });
      router.push({ path: "/expenses" });
    })
    .catch((error) => {
      let message =
        error.response?.data?.message || "Failed to update this expense";

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
