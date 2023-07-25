<template>
  <q-page class="q-mt-lg bg-grey-2" padding>
    <q-card class="q-pa-md">
      <q-card-section class="relative-position">
        <p class="text-h4">User registration</p>
      </q-card-section>
      <q-card-section>
        <q-form
          @submit="onSubmit"
          @reset="onReset"
          class="row q-col-gutter-md"
          ref="myForm"
        >
          <q-input
            outlined
            v-model="form.name"
            color="deep-purple"
            label="Name"
            class="col-md-12 col-sm-12 col-xs-12"
            :rules="[(val) => (val && val.length > 0) || 'Name is missing']"
          />

          <q-input
            v-model="form.email"
            label="Email"
            outlined
            class="col-md-12 col-sm-12 col-xs-12"
            :rules="[(val) => (val && val.length > 0) || 'Email is missing']"
          />

          <q-input
            v-model="form.password"
            type="password"
            label="Password"
            outlined
            class="col-md-12 col-sm-12 col-xs-12"
            unmasked-value
            :rules="[
              (val) => (val && val.length > 0) || 'Password is missing',
              (val) => (val && val.length >= 8) || 'minimum 8 characters',
            ]"
          />

          <q-input
            v-model="form.password_confirmation"
            type="password"
            label="Password confirmation"
            outlined
            class="col-md-12 col-sm-12 col-xs-12"
            unmasked-value
            :rules="[
              (val) =>
                (val && val.length > 0) || 'Password confirmation is missing',
              (val) =>
                val === this.form.password ||
                'The password confirmation does not match.',
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
  name: "RegisterPage",
  data() {
    return {
      submiting: false,
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
    };
  },
  methods: {
    onSubmit() {
      this.submiting = true;

      post("register", this.form)
        .then((response) => {
          if (response.status == 201) {
            const { data } = response;
            const userStore = useUserStore();
            userStore.setUser(data.user);
            userStore.setToken(data.token);

            this.$q.notify({
              message: "User registered successfully",
              color: "positive",
              icon: "check_circle_outline",
            });
          }
        })
        .catch((error) => {
          let message =
            error.response?.data?.message || "Failed to register a user";

          this.$q.notify({
            message: message,
            color: "negative",
            icon: "error",
          });
        })
        .finally(() => {
          this.submiting = false;
          this.$router.push({ path: "/expenses" });
        });
    },
    async onReset() {
      await this.resetForm();
      this.$refs.myForm.resetValidation();
    },
    async resetForm() {
      this.form = {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      };
    },
  },
};
</script>
