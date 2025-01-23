import axios from "axios";

export const API = axios.create({
  baseURL: "http://localhost:8000/api",
  // baseURL: "http://192.168.1.149/api",
});

