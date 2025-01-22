import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { StyleSheet, View, Text, Button } from 'react-native';
import Project from './src/pages/Project';
import ProjectDetail from './src/pages/ProjectDetail'; // Asegúrate de importar ProjectDetail
import CreateProject from './src/pages/CreateProject'; // Importa la pantalla CreateProject

const Stack = createStackNavigator();

export default function App() {
    return (
        <NavigationContainer>
            <View style={styles.container}> {/* Contenedor principal */}
                <Stack.Navigator
                    initialRouteName="Projects"
                    screenOptions={{
                        headerStyle: styles.header, // Estilo del header
                        headerTintColor: '#fff', // Color del texto del header
                    }}
                >
                    <Stack.Screen
                        name="Projects"
                        component={Project}
                        options={({ navigation }) => ({
                            headerTitle: () => (
                                <Text style={styles.headerTitle}>Projects</Text>
                            ),
                            headerRight: () => (
                                <Button
                                    title="Create Project"
                                    onPress={() => navigation.navigate('CreateProject')}
                                    color="#55877e" // Cambia el color del texto del botón a verde
                                />
                            ),
                        })}
                    />
                    <Stack.Screen
                        name="ProjectDetail"
                        component={ProjectDetail}
                        options={{
                            headerTitle: 'Project Details', // Título del header para esta pantalla
                        }}
                    />
                    <Stack.Screen
                        name="CreateProject"
                        component={CreateProject} // Pantalla de crear proyecto
                        options={{
                            headerTitle: 'Create New Project', // Título del header para la pantalla de creación
                        }}
                    />
                </Stack.Navigator>

                {/* Footer al final de la pantalla */}
                <View style={styles.footer}>
                    <Text style={styles.footerText}>© 2024 STEMFounding | All Rights Reserved</Text>
                </View>
            </View>
        </NavigationContainer>
    );
}

const styles = StyleSheet.create({
    header: {
        backgroundColor: '#55877e', // Verde para el header
        height: 80, // Ajusta la altura del header
        justifyContent: 'center',
        alignItems: 'center',
    },
    headerTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#ffffff',
    },
    footer: {
        backgroundColor: '#55877e', // Verde para el footer
        padding: 16,
        alignItems: 'center',
        position: 'absolute', // Fija el footer en la parte inferior
        bottom: 0, // Lo posiciona al fondo
        left: 0,
        right: 0,
    },
    footerText: {
        color: '#ffffff', // Texto blanco para el footer
        fontSize: 12,
    },
    container: {
        flex: 1,
        backgroundColor: '#f9f5e9', // Fondo crema para la aplicación
    },
});
