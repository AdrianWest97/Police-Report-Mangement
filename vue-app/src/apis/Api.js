import axios from 'axios'
import store from '../store'
import router from "../router";

let BaseApi = axios.create({
  baseURL: process.env.BASE_API
})

BaseApi.interceptors.response.use(
  response => response,
  error => {
  if (error.response.status === 401) {
      store.commit('LOGIN', true)
      store.commit("AUTH_USER", null);
      localStorage.removeItem("token");
      router.push({ name: "Login" });
    } else {
      return Promise.reject(error);
    }
  }
);

let Api = function () {
  let token = localStorage.getItem('token')

  if (token) {
    BaseApi.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }

  return BaseApi
}

export default Api
