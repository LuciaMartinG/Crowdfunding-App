import React, { useState, useEffect } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createDrawerNavigator } from '@react-navigation/drawer';
import { createStackNavigator } from '@react-navigation/stack';
import { StyleSheet, View, Text, Button } from 'react-native';
import Project from './src/pages/Project';
import ProjectDetail from './src/pages/ProjectDetail';
import CreateProject from './src/pages/CreateProject';
import MyProjects from './src/pages/MyProjects';
import EditProject from './src/pages/EditProject';
import Investors from './src/pages/Investors';
import MyProfile from './src/pages/MyProfile';
import EditUser from './src/pages/EditUser';
import Login from './src/pages/Login';
import Register from './src/pages/Register';
import AsyncStorage from '@react-native-async-storage/async-storage'; // Usado para almacenar el token
import { logout } from './src/services/projectService'; // Importa la función de logout

const Stack = createStackNavigator();
const Drawer = createDrawerNavigator();

export default function App() {
    const [userLoggedIn, setUserLoggedIn] = useState(false);

    // Función para verificar el estado de login
    const checkLoginStatus = async () => {
        try {
            const token = await AsyncStorage.getItem('authToken'); // O la clave que usas para almacenar el token
            setUserLoggedIn(!!token); // Si hay un token, el usuario está logueado
        } catch (error) {
            console.error(error);
        }
    };

    // Revisa el estado de login cuando la app se monta
    useEffect(() => {
        checkLoginStatus();
    }, []);

    // Función para cerrar sesión (Logout)
    const handleLogout = async () => {
        try {
            // Obtener el token almacenado en AsyncStorage
            const token = await AsyncStorage.getItem('authToken');

            if (token) {
                // Llamar a la función de logout desde el servicio
                await logout(token);

                // Eliminar el token de AsyncStorage
                await AsyncStorage.removeItem('authToken');
                setUserLoggedIn(false); // Actualiza el estado de login
                navigation.navigate('Login'); // Navega a la pantalla de login
            }
        } catch (error) {
            console.error('Error en el logout:', error);
            alert('Hubo un problema al cerrar sesión');
        }
    };

    // Navegación de los proyectos
    const ProjectStack = () => (
        <Stack.Navigator
            screenOptions={{
                headerStyle: styles.header, // Fondo verde para la barra de navegación
                headerTintColor: '#ffffff', // Letras blancas en la barra de navegación
            }}
        >
            <Stack.Screen name="Projects" component={Project} />
            <Stack.Screen name="ProjectDetail" component={ProjectDetail} />
            <Stack.Screen name="EditProject" component={EditProject} />
            <Stack.Screen name="Investors" component={Investors} />
            <Stack.Screen name="MyProfile" component={MyProfile} />
        </Stack.Navigator>
    );

    // Navegación de los proyectos de usuario
    const MyProjectsStack = () => (
        <Stack.Navigator
            screenOptions={{
                headerStyle: styles.header, // Fondo verde para la barra de navegación
                headerTintColor: '#ffffff', // Letras blancas en la barra de navegación
            }}
        >
            <Stack.Screen name="MyProjects" component={MyProjects} />
            <Stack.Screen name="EditProject" component={EditProject} />
        </Stack.Navigator>
    );

    // Navegación del Drawer
    const AppDrawer = () => (
        <Drawer.Navigator
            screenOptions={{
                drawerActiveBackgroundColor: '#578E7E', // Fondo verde para las opciones activas del menú
                drawerActiveTintColor: '#ffffff', // Letras blancas en las opciones activas
                drawerInactiveTintColor: '#000000', // Letras negras en las opciones inactivas
                headerStyle: styles.header, // Fondo verde en la cabecera
                headerTintColor: '#ffffff', // Letras blancas en la cabecera
            }}
        >
            {/* Pantallas siempre visibles */}
            <Drawer.Screen name="Projects" component={ProjectStack} />
            
            {/* Pantallas solo visibles si el usuario está logueado */}
            {userLoggedIn && (
                <>
                    <Drawer.Screen name="My Projects" component={MyProjectsStack} />
                    <Drawer.Screen name="Create Project" component={CreateProject} />
                    <Drawer.Screen name="MyProfile" component={MyProfile} />
                    <Drawer.Screen name="Investors" component={Investors} />
                    <Drawer.Screen name="Edit User" component={EditUser} />
                    <Drawer.Screen name="Edit Project" component={EditProject} />
                    
                    {/* Opción de logout */}
                    <Drawer.Screen
                        name="Logout"
                        component={() => (
                            <Button title="Logout" onPress={handleLogout} />
                        )}
                    />
                </>
            )}

            {/* Pantallas siempre visibles */}
            <Drawer.Screen name="Login">
                {props => <Login {...props} setUserLoggedIn={setUserLoggedIn} />}
            </Drawer.Screen>
            <Drawer.Screen name="Register" component={Register} />
        </Drawer.Navigator>
    );

    return (
        <NavigationContainer>
            <AppDrawer />
        </NavigationContainer>
    );
}

// Estilos de la aplicación
const styles = StyleSheet.create({
    header: {
        backgroundColor: '#578E7E', // Fondo verde para la barra de navegación
        height: 80,
        justifyContent: 'center',
        alignItems: 'center',
    },
    headerTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#ffffff', // Letras blancas en la barra de navegación
    },
    footer: {
        backgroundColor: '#55877e',
        padding: 16,
        alignItems: 'center',
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
    },
    footerText: {
        color: '#ffffff',
        fontSize: 12,
    },
    container: {
        flex: 1,
        backgroundColor: '#ffffff', // Fondo blanco para el contenido
    },
});
