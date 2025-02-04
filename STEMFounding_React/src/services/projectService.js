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

// export const getUserById = (userId) => API.get('/user/'+userId);

export const getUserData = async () => {
    try {
        const userString = await AsyncStorage.getItem('user');
        const user = JSON.parse(userString);
        
        const response = await API.get('/user', {
            headers: {
                Authorization: `Bearer ${user.access_token}`, // Autenticación con el token
            },
        });

        return response.data; // Devuelve la respuesta de la API
    } catch (error) {
        console.error('Error al obtener los datos del usuario:', error);
        throw error;
    }
};


export const getProjectUpdates = (id) => API.get('/showUpdates/'+id);

// Envía la solicitud POST para insertar un nuevo proyecto
export const postInsertProject = (projectData, token) => {
    return API.post('/project', projectData, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    });
};


export const addUpdates = async (projectId, projectData) => {
    try {
        // Obtener el token desde AsyncStorage
        const userString = await AsyncStorage.getItem('user');
        const user = JSON.parse(userString);

        // Hacer la solicitud POST a la API
        const response = await API.post(`/addUpdate/${projectId}`, projectData, {
            headers: {
                Authorization: `Bearer ${user.access_token}`, // Incluir el token en el encabezado
            },
        });

    } catch (error) {
        console.error('Error en addUpdates:', error);
        throw error; // Maneja el error adecuadamente
    }
};

// Edita una actualización existente

export const editUpdate = async (updateId, updateData) => {
    try {
        // Obtener el token desde AsyncStorage
        const userString = await AsyncStorage.getItem('user');
        const user = JSON.parse(userString);

        if (!user || !user.access_token) {
            throw new Error("User is not authenticated.");
        }

        // Hacer la solicitud PUT a la API para editar la actualización
        const response = await API.put(`/update/${updateId}`, updateData, {
            headers: {
                Authorization: `Bearer ${user.access_token}`, // Incluir el token en el encabezado
            },
        });
        console.log('API response:', response);

        // Verificar la respuesta
        if (response.status === 200) {
            console.log('Update successfully edited:', response.data);
            return response.data;
        } else {
            throw new Error('Failed to edit update');
        }
    } catch (error) {
        console.error('Error in editUpdate:', error);
        throw error;
    }
};

// Actualiza un proyecto existente
export const updateProject = async (projectData) => {
    try {
        // Obtener el token desde AsyncStorage
        const userString = await AsyncStorage.getItem('user');
        const user = JSON.parse(userString);

        return API.put('/updateProject', projectData, {
            headers: {
                Authorization: `Bearer ${user.access_token}`, // Asegúrate de incluir el token aquí
            }
        });
    } catch (error) {
        console.error("Error updating project:", error);
        throw error; // Maneja el error adecuadamente
    }
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
export const activateOrDeactivate = (projectId, state) => {
    console.log('Enviando solicitud a la API:', { projectId, state });
    return API.put(`/activateOrDeactivateProject/${projectId}`,  { state });
};

export const deleteUpdate = (id) => {
    return API.delete(`/update/${id}`);
};



export const withdrawFunds = async (projectId) => {
    try {
        const userString = await AsyncStorage.getItem('user');
        const user = JSON.parse(userString);

        const response = await API.post(`/withdraw/${projectId}`, null, {
            headers: {
                Authorization: `Bearer ${user.access_token}`, // Incluye el token en los encabezados
            },
        });

        return response.data; // Retorna la respuesta de la API
    } catch (error) {
        console.error('Error al retirar fondos:', error);
        throw error;
    }
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