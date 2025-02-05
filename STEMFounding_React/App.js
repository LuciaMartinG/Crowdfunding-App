import React, { useState, useEffect } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createDrawerNavigator } from '@react-navigation/drawer';
import { createStackNavigator } from '@react-navigation/stack';
import { StyleSheet, Button } from 'react-native';
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
import AsyncStorage from '@react-native-async-storage/async-storage';
import { logout } from './src/services/projectService'; // Función de logout

const Stack = createStackNavigator();
const Drawer = createDrawerNavigator();

export default function App() {
    const [userLoggedIn, setUserLoggedIn] = useState(false);

    // Función para verificar el estado de login
    const checkLoginStatus = async () => {
        try {
            const token = await AsyncStorage.getItem('authToken'); // Recupera el token
            setUserLoggedIn(!!token); // Si hay un token, el usuario está logueado
        } catch (error) {
            console.error(error);
        }
    };

    // Verifica el estado de login al montar la aplicación
    useEffect(() => {
        checkLoginStatus();
    }, []);

    // Función para cerrar sesión (Logout)
    const handleLogout = async (navigation) => {
        try {
            const token = await AsyncStorage.getItem('authToken'); // Obtén el token
            if (token) {
                await logout(token); // Llama a la función de logout
                await AsyncStorage.removeItem('authToken'); // Borra el token
                setUserLoggedIn(false); // Cambia el estado a "no logueado"
                navigation.navigate('Login'); // Redirige al login
            }
        } catch (error) {
            console.error('Error en el logout:', error);
            alert('Hubo un problema al cerrar sesión');
        }
    };

    // Navegación del Stack de proyectos
    const ProjectStack = () => (
        <Stack.Navigator
            screenOptions={{
                headerStyle: styles.header,
                headerTintColor: '#ffffff',
            }}
        >
            <Stack.Screen name="Projects" component={Project} />
            <Stack.Screen name="ProjectDetail" component={ProjectDetail} />
            <Stack.Screen name="EditProject" component={EditProject} />
            <Stack.Screen name="Investors" component={Investors} />
            <Stack.Screen name="MyProfile" component={MyProfile} />
            <Stack.Screen name="EditUser" component={EditUser} />
        </Stack.Navigator>
    );

    // Navegación del Stack de proyectos del usuario
    const MyProjectsStack = () => (
        <Stack.Navigator
            screenOptions={{
                headerStyle: styles.header,
                headerTintColor: '#ffffff',
            }}
        >
            <Stack.Screen name="MyProjects" component={MyProjects} />
            <Stack.Screen name="ProjectDetail" component={ProjectDetail} />
            <Stack.Screen name="EditProject" component={EditProject} />
            <Stack.Screen name="Investors" component={Investors} />
        </Stack.Navigator>
    );

    const ProfileStack = () => (
        <Stack.Navigator
            screenOptions={{
                headerStyle: styles.header,
                headerTintColor: '#ffffff',
            }}
        >
            {/* Pantallas del perfil */}
            <Stack.Screen name="MyProfile" component={MyProfile} />
            <Stack.Screen name="EditUser" component={EditUser} />
        </Stack.Navigator>
    );

    // Navegación del Drawer
    const AppDrawer = () => (
        <Drawer.Navigator
            screenOptions={{
                drawerActiveBackgroundColor: '#578E7E',
                drawerActiveTintColor: '#ffffff',
                drawerInactiveTintColor: '#000000',
                headerStyle: styles.header,
                headerTintColor: '#ffffff',
            }}
        >
            {/* Pantallas visibles para usuarios autenticados */}
            <Drawer.Screen name="Projects" component={ProjectStack} />
            <Drawer.Screen name="My Projects" component={MyProjectsStack} />
            <Drawer.Screen name="Create Project" component={CreateProject} />
            <Drawer.Screen name="Profile" component={ProfileStack} />
            <Drawer.Screen
                name="Logout"
                component={({ navigation }) => (
                    <Button title="Logout" onPress={() => handleLogout(navigation)} />
                )}
            />
        </Drawer.Navigator>
    );

    return (
        <NavigationContainer>
            {userLoggedIn ? (
                // Drawer para usuarios autenticados
                <AppDrawer />
            ) : (
                // Stack para usuarios no autenticados
                <Stack.Navigator
                    screenOptions={{
                        headerStyle: styles.header,
                        headerTintColor: '#ffffff',
                    }}
                >
                    <Stack.Screen name="Login">
                        {props => <Login {...props} setUserLoggedIn={setUserLoggedIn} />}
                    </Stack.Screen>
                    <Stack.Screen name="Register" component={Register} />
                </Stack.Navigator>
            )}
        </NavigationContainer>
    );
}

// Estilos de la aplicación
const styles = StyleSheet.create({
    header: {
        backgroundColor: '#578E7E', 
        height: 80,
        justifyContent: 'center',
        alignItems: 'center',
    },
    headerTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#ffffff', 
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
        backgroundColor: '#ffffff', 
    },
});
