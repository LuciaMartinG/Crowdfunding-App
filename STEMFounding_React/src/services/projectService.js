import { API } from './api.js';

export const getProjectList = () => API.get('/project');
export const getProjectById = (id) => API.get('/project/'+id);
export const getUserProjects = (userId) => API.get('/userProjects/'+userId);
export const getUserById = (userId) => API.get('/user/'+userId);

// Envía la solicitud POST para insertar un nuevo proyecto
export const postInsertProject = (projectData) => {
    return API.post('/project', projectData); // Se pasan los datos del proyecto en el cuerpo de la solicitud
};

// Actualiza un proyecto existente
export const updateProject = (projectData) => {
    return API.put('/updateProjectPostman', projectData); // Enviar los datos del proyecto en el cuerpo de la solicitud
};

export const getProjectInvestors = (projectId) => {
    return API.get(`/projects/${projectId}/investors`); // Llamada GET al endpoint de inversores
};


// Actualiza el estado de un proyecto
export const activateOrReject = (projectId, state) => {
    return API.put(`/activateOrRejectProject/${projectId}`, { state });
};