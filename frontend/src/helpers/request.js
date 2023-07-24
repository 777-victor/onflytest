import { api } from "src/boot/axios";

export async function post(url, data) {
  try {
    const response = await api.post(url, data);
  } catch (err) {
    console.log(err);
    throw new Error(err);
  }

  return response;
}
