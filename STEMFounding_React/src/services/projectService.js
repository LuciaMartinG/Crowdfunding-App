import { API } from './api.js';

export const getProjectList = () => API.get('/project');
export const getProjectById = (id) => API.get('/project/'+id);