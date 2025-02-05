import { API } from './api.js';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Función reutilizable para obtener los encabezados con autenticación
const getAuthHeaders = async () => {
    try {
        const userString = await AsyncStorage.getItem('user');
        if (!userString) throw new Error('No user found in storage');

        const user = JSON.parse(userString);
        if (!user.access_token) throw new Error('No access token found');

        return { Authorization: `Bearer ${user.access_token}` };
    } catch (error) {
        console.error('Error getting auth headers:', error);
        throw error;
    }
};

// Endpoints
export const getProjectList = () => API.get('/project');
export const getProjectById = (id) => API.get(`/project/${id}`);
export const getProjectUpdates = (id) => API.get(`/showUpdates/${id}`);
export const getProjectInvestors = (projectId) => API.get(`/projects/${projectId}/investors`);
export const deleteUpdate = (id) => API.delete(`/update/${id}`);

export const getUserProjects = async () => {
    try {
        const headers = await getAuthHeaders();
        const response = await API.get('/userProjects', { headers });
        return response.data;
    } catch (error) {
        console.error('Error getting user projects:', error);
        throw error;
    }
};

export const getUserData = async () => {
    try {
        const headers = await getAuthHeaders();
        const response = await API.get('/user', { headers });
        return response.data;
    } catch (error) {
        console.error('Error getting user data:', error);
        throw error;
    }
};

export const postInsertProject = (projectData, token) => 
    API.post('/project', projectData, { headers: { Authorization: `Bearer ${token}` } });

export const addUpdates = async (projectId, projectData) => {
    try {
        const headers = await getAuthHeaders();
        return await API.post(`/addUpdate/${projectId}`, projectData, { headers });
    } catch (error) {
        console.error('Error in addUpdates:', error);
        throw error;
    }
};

export const editUpdate = async (updateId, updateData) => {
    try {
        const headers = await getAuthHeaders();
        const response = await API.put(`/update/${updateId}`, updateData, { headers });
        return response.data;
    } catch (error) {
        console.error('Error in editUpdate:', error);
        throw error;
    }
};

export const updateProject = async (projectData) => {
    try {
        const headers = await getAuthHeaders();
        return await API.put('/updateProject', projectData, { headers });
    } catch (error) {
        console.error('Error updating project:', error);
        throw error;
    }
};

export const updateUser = (userData) => API.put('/user', userData);
export const updateUserBalance = (balanceData) => API.put('/updateUserBalance', balanceData);

export const activateOrDeactivate = (projectId, state) => 
    API.put(`/activateOrDeactivateProject/${projectId}`, { state });

export const withdrawFunds = async (projectId) => {
    try {
        const headers = await getAuthHeaders();
        const response = await API.post(`/withdraw/${projectId}`, null, { headers });
        return response.data;
    } catch (error) {
        console.error('Error withdrawing funds:', error);
        throw error;
    }
};

export const login = (data) => 
    API.post('/login', data)
        .then(response => {
            AsyncStorage.setItem('user', JSON.stringify(response.data));
            return response.data;
        })
        .catch(error => {
            console.error('Error in login:', error.response);
            throw error;
        });

export const register = (data) => 
    API.post('/register', data)
        .then(response => response.data)
        .catch(error => {
            console.error('Error in register:', error.response);
            throw error;
        });

export const logout = (token) => 
    API.post('/logout', {}, { headers: { Authorization: `Bearer ${token}` } })
        .then(response => response.data)
        .catch(error => {
            console.error('Error in logout:', error.response);
            throw error;
        });
