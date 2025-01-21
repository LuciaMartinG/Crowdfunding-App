import axios from "axios";

const API = axios.create({
  baseURL: "http://localhost:8000/api",
});

// Función para obtener datos
export const getPosts = () => API.get("/project");

export default API;
