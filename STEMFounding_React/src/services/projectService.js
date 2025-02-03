import { API } from './api.js';
import AsyncStorage from '@react-native-async-storage/async-storage';

export const getProjectList = () => API.get('/project');
export const getProjectById = (id) => API.get('/project/'+id);
// export const getUserProjects = () => API.get('/userProjects/'+userId);

export const getUserProjects = async () => {
    
    try {
        const userString = await AsyncStorage.getItem('user');
        const user = await JSON.parse(userString);
        // console.log(user);
        const response = await API.get(`/userProjects`, {
            headers: {
                Authorization: `Bearer ${user.access_token}`, // Incluye el token en los encabezados
            },
        });
        return response.data; // Retorna los datos de la respuesta
    } catch (error) {
        console.error('Error al obtener los proyectos:', error);
        throw error;
    }
};

export const getUserById = (userId) => API.get('/user/'+userId);
export const getProjectUpdates = (id) => API.get('/showUpdates/'+id);

// Envía la solicitud POST para insertar un nuevo proyecto
export const postInsertProject = (projectData) => {
    return API.post('/project', projectData); // Se pasan los datos del proyecto en el cuerpo de la solicitud
};

export const addUpdates = (projectId, projectData) => {
    return API.post(`/addUpdate/${projectId}`, projectData); // Enviar el projectId en la URL
};

// Actualiza un proyecto existente
export const updateProject = (projectData) => {
    return API.put('/updateProjectPostman', projectData); // Enviar los datos del proyecto en el cuerpo de la solicitud
};

// Actualiza un usuario existente
export const updateUser = (userData) => {
    return API.put('/user', userData); // Enviar los datos del usuario en el cuerpo de la solicitud
};

// Actualiza el saldo del usuario
export const updateUserBalance = (balanceData) => {
    return API.put('/updateUserBalance', balanceData); 
};

export const getProjectInvestors = (projectId) => {
    return API.get(`/projects/${projectId}/investors`); // Llamada GET al endpoint de inversores
};


// Actualiza el estado de un proyecto
export const activateOrReject = (projectId, state) => {
    return API.put(`/activateOrRejectProject/${projectId}`, { state });
};

export const deleteUpdate = (id) => {
    return API.delete(`/update/${id}`);
};

// Edita una actualización existente
export const editUpdate = (updateId, updateData) => {
    return API.put(`/update/${updateId}`, updateData); // Se envían los datos de la actualización
};


// Login: Enviar las credenciales del usuario
export const login = (data) => {
    return API.post('/login', data)  // Enviar los datos del usuario a la API Laravel
        .then(response => {
            // Guardar el token y los datos del usuario en AsyncStorage
            const user = response.data;  // Asumiendo que la respuesta contiene los datos del usuario
            AsyncStorage.setItem('user', JSON.stringify(user));  // Guardar el usuario completo

            // Retornar la respuesta que incluye el token
            return response.data;
        })
        .catch(error => {
            console.error('Error en el login:', error.response);
            throw error;  // Lanza el error para manejarlo en el componente
        });
};

// Register: Enviar los datos del nuevo usuario
export const register = (data) => {
    return API.post('/register', data)  // Enviar los datos de registro a la API Laravel
        .then(response => response.data)  // Retorna la respuesta, que incluye el token
        .catch(error => {
            console.error('Error en el registro:', error.response);
            throw error;  // Lanza el error para manejarlo en el componente
        });
};

// Logout: Revocar el token del usuario actual
export const logout = (token) => {
    return API.post('/logout', {}, {
        headers: {
            Authorization: `Bearer ${token}`,  // Enviar el token de autorización en el header
        }
    }).then(response => response.data)  // Retorna la respuesta de logout
      .catch(error => {
          console.error('Error en el logout:', error.response);
          throw error;  // Lanza el error para manejarlo en el componente
      });
};