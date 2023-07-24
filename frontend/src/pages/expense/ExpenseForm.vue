<template>
  <q-page class="bg-grey-2" padding>
    <q-card class="q-pa-md">
      <q-card-title class="relative-position">
        <p class="text-h4">Add Expense</p>
      </q-card-title>
      <q-card-section>
        <q-form
          @submit="onSubmit"
          @reset="onReset"
          class="row q-col-gutter-md"
          ref="myForm"
        >
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
            type="number"
            label="Value"
            outlined
            class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
            :rules="[
              (val) => (val && val.length > 0) || 'Value is missing',
              (val) => (val && val.length < 0) || 'Value cannot be less than 0',
            ]"
          />
          <q-input
            v-model="expense.date"
            type="date"
            label="Date of expense"
            outlined
            class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
            :rules="[
              (val) => (val && val.length > 0) || 'Date is missing',
              (val) => (val && val.length < 0) || 'Value cannot be less than 0',
            ]"
          />

          <div class="col-12 row justify-center">
            <q-btn
              label="Submit"
              :disable="submiting"
              type="submit"
              color="primary"
            />
            <q-btn
              label="Reset"
              type="reset"
              color="default"
              class="text-grey-10 q-ml-md"
            />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { post } from "src/helpers/request";
import { useUserStore } from "src/stores/user-store";

export default {
  name: "ExpenseForm",
  data() {
    return {
      submiting: false,
      expense: {
        description: "",
        value: 0.0,
        date: "",
        user_id: null,
      },
    };
  },
  methods: {
    onSubmit() {
      this.submiting = true;
      const userStore = useUserStore();
      expense.user_id = userStore.getId();

      post("expenses", this.expense)
        .then((response) => {
          if (response.status == 201) {
            const { data } = response;

            this.$q.notify({
              message: "Expense registered successfully",
              color: "positive",
              icon: "check_circle_outline",
            });
            this.$router.push({ path: "/expenses" });
          }
        })
        .catch((error) => {
          let message =
            error.response?.data?.message || "Failed to register a expense";

          this.$q.notify({
            message: message,
            color: "negative",
            icon: "error",
          });
        })
        .finally(() => {
          this.submiting = false;
        });
    },
    async onReset() {
      await this.resetForm();
      this.$refs.myForm.resetValidation();
    },
    async resetForm() {
      this.expense = {
        description: "",
        value: 0.0,
        date: "",
        user_id: null,
      };
    },
  },
};
</script>
