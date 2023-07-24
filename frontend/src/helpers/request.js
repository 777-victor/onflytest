import { api } from "src/boot/axios";

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
    if (err) throw err;
  }
}
