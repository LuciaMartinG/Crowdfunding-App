import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { StyleSheet, View, Text, Button } from 'react-native';
import Project from './src/pages/Project';
import ProjectDetail from './src/pages/ProjectDetail'; 
import CreateProject from './src/pages/CreateProject'; 
import MyProjects from './src/pages/MyProjects';  // Importa el nuevo componente

const Stack = createStackNavigator();

export default function App() {
    return (
        <NavigationContainer>
            <View style={styles.container}> 
                <Stack.Navigator
                    initialRouteName="Projects"
                    screenOptions={{
                        headerStyle: styles.header, 
                        headerTintColor: '#fff', 
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
                                <View style={{ flexDirection: 'row' }}>
                                    <Button
                                        title="Create Project"
                                        onPress={() => navigation.navigate('CreateProject')}
                                        color="#55877e"
                                    />
                                    <Button
                                        title="My Projects"
                                        onPress={() => navigation.navigate('MyProjects')} // Agrega el botón para ir a "MyProjects"
                                        color="#55877e"
                                    />
                                </View>
                            ),
                        })}
                    />
                    <Stack.Screen
                        name="ProjectDetail"
                        component={ProjectDetail}
                        options={{
                            headerTitle: 'Project Details',
                        }}
                    />
                    <Stack.Screen
                        name="CreateProject"
                        component={CreateProject} 
                        options={{
                            headerTitle: 'Create New Project',
                        }}
                    />
                    <Stack.Screen
                        name="MyProjects"
                        component={MyProjects} 
                        options={{
                            headerTitle: 'My Projects',
                        }}
                    />
                </Stack.Navigator>

                <View style={styles.footer}>
                    <Text style={styles.footerText}>© 2024 STEMFounding | All Rights Reserved</Text>
                </View>
            </View>
        </NavigationContainer>
    );
}

const styles = StyleSheet.create({
    header: {
        backgroundColor: '#55877e',
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
        backgroundColor: '#f9f5e9',
    },
});
