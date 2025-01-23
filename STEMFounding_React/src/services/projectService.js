import { API } from './api.js';

export const getProjectList = () => API.get('/project');
export const getProjectById = (id) => API.get('/project/'+id);
export const getUserProjects = (userId) => API.get('/userProject/'+userId);

// Envía la solicitud POST para insertar un nuevo proyecto
export const postInsertProject = (projectData) => {
    return API.post('/project', projectData); // Se pasan los datos del proyecto en el cuerpo de la solicitud
};