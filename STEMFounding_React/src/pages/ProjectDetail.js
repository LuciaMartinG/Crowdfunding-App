import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Image, ScrollView } from 'react-native';
import { useRoute } from '@react-navigation/native';
import { getProjectById } from '../services/projectService';

const ProjectDetail = () => {
    const route = useRoute();
    const { id } = route.params;  // Recibe el ID desde los parámetros de la ruta

    const [project, setProject] = useState(null); // Almacenamos los detalles del proyecto

    // Carga de los detalles del proyecto al montar el componente
    useEffect(() => {
        async function getProjectDetails() {
            try {
                const projectResponse = await getProjectById(id);
                setProject(projectResponse.data);
            } catch (error) {
                console.error('Error al cargar los detalles del proyecto:', error);
            }
        }

        getProjectDetails();
    }, [id]);

    // Función para calcular el progreso
    const calculateProgress = () => {
        if (!project || !project.max_investment) return 0;  // Protege contra errores si los datos no son válidos
        const percentage = (project.current_investment / project.max_investment) * 100;
        return Math.min(percentage, 100);  // Limita el porcentaje a un máximo de 100
    };

    return (
        <ScrollView style={styles.container}>
            {project ? (
                <View>
                    {/* Imagen del proyecto */}
                    <Image source={{ uri: project.image_url }} style={styles.image} />
                    
                    <View style={styles.textContainer}>
                        {/* Título del proyecto */}
                        <Text style={styles.title}>{project.title}</Text>

                        {/* Estado del proyecto */}
                        <Text style={styles.status}>Status: {project.state}</Text>

                        {/* Descripción del proyecto */}
                        <Text style={styles.description}>{project.description}</Text>

                        {/* Información de inversión */}
                        <Text style={styles.subtitle}>
                            Money raised: {project.current_investment}€ / {project.max_investment}€
                        </Text>

                        {/* Barra de progreso */}
                        <View style={styles.progressBar}>
                            <View
                                style={[
                                    styles.progressFill,
                                    { width: `${calculateProgress()}%` },  // Ajusta el ancho de la barra
                                ]}
                            />
                        </View>

                        {/* Porcentaje del progreso */}
                        <Text style={styles.progressText}>
                            {calculateProgress().toFixed(2)}% funded
                        </Text>
                    </View>
                </View>
            ) : (
                <View style={styles.loadingContainer}>
                    {/* Mensaje de carga mientras se obtiene el proyecto */}
                    <Text style={styles.loading}>Loading project details...</Text>
                </View>
            )}
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f9f5e9',  // Fondo crema
        padding: 16,
    },
    image: {
        width: '100%',
        height: 200,
        borderRadius: 10,
        marginBottom: 16,
    },
    textContainer: {
        alignItems: 'center',
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#333',
        marginBottom: 8,
    },
    status: {
        fontSize: 16,
        color: '#55877e',  // Verde para el estado
        marginBottom: 10,
    },
    description: {
        fontSize: 14,
        color: '#555',
        marginBottom: 10,
        textAlign: 'center',
    },
    subtitle: {
        fontSize: 16,
        color: '#333',
        marginBottom: 8,
    },
    progressBar: {
        width: '100%',
        height: 20,
        backgroundColor: '#e0e0df',
        borderRadius: 10,
        overflow: 'hidden',
        marginBottom: 8,
    },
    progressFill: {
        height: '100%',
        backgroundColor: '#55877e',
    },
    progressText: {
        fontSize: 14,
        color: '#333',
    },
    loadingContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        marginTop: 50,
    },
    loading: {
        fontSize: 18,
        color: '#888',
    },
});

export default ProjectDetail;
