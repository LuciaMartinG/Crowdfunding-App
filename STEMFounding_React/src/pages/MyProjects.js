import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, Alert } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { getUserProjects, activateOrReject } from '../services/projectService';

const MyProjects = () => {
    const [projects, setProjects] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const navigation = useNavigation();

    // Función para obtener proyectos del usuario
    const fetchProjects = async () => {
        try {
            const response = await getUserProjects();
            setProjects(response);
            setLoading(false);
        } catch (error) {
            setError('Error al cargar los proyectos');
            setLoading(false);
        }
    };

    // Función para cambiar el estado del proyecto
    const handleToggleProjectState = async (projectId, currentState) => {
        const newState = currentState === 'active' ? 'inactive' : 'active';
        try {
            await activateOrReject(projectId, {state:newState}); // Llamar al servicio con el nuevo estado
            Alert.alert('Success', `Project has been ${newState === 'active' ? 'activated' : 'deactivated'} successfully.`);
            fetchProjects(); // Recargar los proyectos para reflejar los cambios
        } catch (error) {
            Alert.alert('Error', 'Failed to update project state.');
        }
    };

    useEffect(() => {
        fetchProjects();
    }, []);

    if (loading) {
        return (
            <View style={styles.container}>
                <Text style={styles.title}>Loading projects...</Text>
            </View>
        );
    }

    if (error) {
        return (
            <View style={styles.container}>
                <Text style={styles.title}>{error}</Text>
            </View>
        );
    }

    return (
        <ScrollView style={styles.container}>
            <Text style={styles.title}>My Projects</Text>
            {projects.length > 0 ? (
                projects.map((project) => (
                    <View key={project.id} style={styles.projectCard}>
                        <Image source={{ uri: project.image_url }} style={styles.image} />
                        <Text style={styles.projectTitle}>{project.title}</Text>
                        <Text style={styles.projectText}>Invested Amount: €{project.current_investment}</Text>
                        <Text style={styles.projectText}>Max Investment: €{project.max_investment}</Text>
                        <Text style={styles.projectText}>Status: {project.state}</Text>

                        {/* Botón para editar proyecto */}
                        {project.state === 'active' && (
                            <TouchableOpacity
                                style={styles.editButton}
                                onPress={() => navigation.navigate('EditProject', { projectId: project.id })}
                            >
                                <Text style={styles.editButtonText}>Edit Project</Text>
                            </TouchableOpacity>
                        )}

                        {/* Botón para ver inversores */}
                        <TouchableOpacity
                            style={styles.editButton}
                            onPress={() => navigation.navigate('Investors', { projectId: project.id })}
                        >
                            <Text style={styles.editButtonText}>View Investors</Text>
                        </TouchableOpacity>

                        {/* Botón para activar/desactivar proyecto */}
                        <TouchableOpacity
                            style={styles.toggleButton}
                            onPress={() => handleToggleProjectState(project.id, project.state)}
                        >
                            <Text style={styles.toggleButtonText}>
                                {project.state === 'active' ? 'Deactivate' : 'Activate'}
                            </Text>
                        </TouchableOpacity>
                    </View>
                ))
            ) : (
                <Text style={styles.noProjects}>You don't have any projects yet.</Text>
            )}
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 16,
        backgroundColor: '#f9f5e9',
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#333',
        textAlign: 'center',
        marginBottom: 20,
    },
    projectCard: {
        backgroundColor: '#fff',
        padding: 16,
        marginBottom: 12,
        borderRadius: 10,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 5,
        elevation: 3,
    },
    image: {
        width: '100%',
        height: 200,
        borderRadius: 10,
        marginBottom: 16,
    },
    projectTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#333',
    },
    projectText: {
        fontSize: 14,
        color: '#555',
        marginTop: 5,
    },
    noProjects: {
        fontSize: 16,
        color: '#888',
        textAlign: 'center',
        marginTop: 50,
    },
    editButton: {
        marginTop: 10,
        backgroundColor: '#007BFF',
        padding: 10,
        borderRadius: 8,
    },
    editButtonText: {
        color: '#fff',
        fontSize: 14,
        textAlign: 'center',
    },
    toggleButton: {
        marginTop: 10,
        backgroundColor: '#FF5733',
        padding: 10,
        borderRadius: 8,
    },
    toggleButtonText: {
        color: '#fff',
        fontSize: 14,
        textAlign: 'center',
    },
});

export default MyProjects;
