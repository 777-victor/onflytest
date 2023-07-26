import { api } from "src/boot/axios";
import { useUserStore } from "src/stores/user-store";

function handleError(err) {
  console.log(err);
  if (err.response.status == 401 || err.response.status == 403) {
    const userStore = useUserStore();
    userStore.logout();
  }
}

export async function post(url, data) {
  try {
    return await api.post(url, data);
  } catch (err) {
    console.log(err);
    if (err.response.status == 403) {
      const userStore = useUserStore();
      userStore.logout();
    }
    if (err) throw err;
  }
}

export async function get(url) {
  try {
    return await api.get(url);
  } catch (err) {
    handleError(err);
    throw err;
  }
}

export async function put(url, data) {
  try {
    return await api.put(url, data);
  } catch (err) {
    handleError(err);
    if (err) throw err;
  }
}

export async function remove(url) {
  try {
    return await api.delete(url);
  } catch (err) {
    handleError(err);
    if (err) throw err;
  }
}
