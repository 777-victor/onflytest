import { defineStore } from "pinia";
import { get, post } from "src/helpers/request";

export const useUserStore = defineStore("user", {
  state: () => ({
    id: null,
    name: null,
    email: null,
    token: null,
  }),
  getters: {
    getId: (state) => state.id,
    getName: (state) => state.name,
    getEmail: (state) => state.email,
    getToken: (state) => state.token,
  },
  actions: {
    async getSanctumCookie(url) {
      await get("/csrf-cookie");
    },

    async login(loginForm) {
      const { data } = await post("login", loginForm);

      return data;
    },

    async fetchUser() {
      return await get("/user");
    },

    setUser(payload) {
      if (payload.id) this.id = payload.id;
      if (payload.name) this.name = payload.name;
      if (payload.email) this.email = payload.email;
      if (payload.token) this.email = payload.token;
    },

    clearUser() {
      this.id = null;
      this.name = null;
      this.email = null;
    },
  },
});
