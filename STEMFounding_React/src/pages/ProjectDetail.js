import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Image, ScrollView } from 'react-native';
import { useRoute } from '@react-navigation/native'; // Para obtener el parámetro de la URL en React Navigation
import { getProjectById } from '../services/projectService'; // Servicio para obtener los datos

const ProjectDetail = () => {
    const route = useRoute();
    const { id } = route.params; // Obtenemos el ID del proyecto

    const [project, setProject] = useState(null); // Para almacenar los detalles del proyecto

    // Efecto para cargar los detalles del proyecto al montar el componente
    useEffect(() => {
        async function getProjectDetails() {
            try {
                const projectResponse = await getProjectById(id);
                setProject(projectResponse.data); // Asumimos que el servicio devuelve un objeto con la info del proyecto
            } catch (error) {
                console.error('Error al cargar los detalles del proyecto:', error);
            }
        }

        getProjectDetails();
    }, [id]);

    return (
        <ScrollView style={styles.container}>
            {project ? (
                <View style={styles.projectDetails}>
                    <Image source={{ uri: project.image_url }} style={styles.image} />
                    <View style={styles.textContainer}>
                        <Text style={styles.title}>{project.title}</Text>
                        <Text style={styles.status}>Status: {project.state}</Text>
                        <Text style={styles.description}>{project.description}</Text>
                    </View>
                </View>
            ) : (
                <Text style={styles.loading}>Loading project details...</Text>
            )}
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f9f5e9', // Fondo crema
        padding: 16,
    },
    projectDetails: {
        flexDirection: 'row',
        marginBottom: 20,
        alignItems: 'center',
    },
    image: {
        width: 150,
        height: 150,
        borderRadius: 10,
        marginRight: 16,
    },
    textContainer: {
        flex: 1,
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#333',
        marginBottom: 8,
    },
    status: {
        fontSize: 16,
        color: '#55877e', // Verde para el estado
        marginBottom: 10,
    },
    description: {
        fontSize: 14,
        color: '#555',
        marginBottom: 10,
    },
    loading: {
        fontSize: 18,
        color: '#888',
        textAlign: 'center',
        marginTop: 50,
    },
});

export default ProjectDetail;
