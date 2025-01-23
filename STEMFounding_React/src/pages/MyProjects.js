// src/pages/MyProjects.js
import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Image, ScrollView } from 'react-native';
import { getUserProjects } from '../services/projectService'; // Asegúrate de importar el servicio adecuado

const MyProjects = () => {
    const [projects, setProjects] = useState([]); // Almacenamos los proyectos
    const [loading, setLoading] = useState(true);  // Estado de carga
    const [error, setError] = useState(null);  // Para manejar los errores

    // Carga de los proyectos al montar el componente
    useEffect(() => {
        async function fetchProjects() {
            try {
                const response = await getUserProjects(22);  // Llamada a la API para obtener los proyectos del usuario con ID 22
                setProjects(response.data);  // Guarda los proyectos en el estado
                setLoading(false);  // Cambia el estado de carga a falso
            } catch (error) {
                setError('Error al cargar los proyectos');  // Muestra el error si ocurre uno
                setLoading(false);  // Cambia el estado de carga a falso
            }
        }

        fetchProjects();
    }, []);  // Solo se ejecuta una vez al montar el componente

    // Mostrar el contenido dependiendo del estado
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
        backgroundColor: '#f9f5e9',  // Fondo crema
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
});

export default MyProjects;
