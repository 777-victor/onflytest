import { api } from "src/boot/axios";
import { useUserStore } from "src/stores/user-store";

export async function post(url, data) {
  try {
    return await api.post(url, data);
  } catch (err) {
    console.log(err);
    if (err) throw err;
  }
}

export async function get(url) {
  try {
    return await api.get(url);
  } catch (err) {
    console.log(err);
    if (err.response.status == 401) {
      const userStore = useUserStore();
      userStore.logout();
    }
    throw err;
  }
}

export async function put(url, data) {
  try {
    return await api.put(url, data);
  } catch (err) {
    console.log(err);
    if (err) throw err;
  }
}

export async function remove(url) {
  try {
    return await api.delete(url);
  } catch (err) {
    console.log(err);
    if (err) throw err;
  }
}
