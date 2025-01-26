import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity } from 'react-native';
import { useRoute, useNavigation } from '@react-navigation/native';
import { getProjectById, getProjectUpdates } from '../services/projectService'; // Importar getProjectUpdates

const ProjectDetail = () => {
    const route = useRoute();
    const navigation = useNavigation();
    const { id } = route.params; // Recibe el ID desde los parámetros de la ruta

    const [project, setProject] = useState(null); // Almacenamos los detalles del proyecto
    const [updates, setUpdates] = useState([]); // Almacenamos las actualizaciones del proyecto

    // Carga de los detalles del proyecto y actualizaciones al montar el componente
    useEffect(() => {
        async function fetchData() {
            try {
                // Obtener los detalles del proyecto
                const projectResponse = await getProjectById(id);
                setProject(projectResponse.data);

                // Obtener las actualizaciones del proyecto
                const updatesResponse = await getProjectUpdates(id);
                setUpdates(updatesResponse.data);
            } catch (error) {
                console.error('Error al cargar los datos del proyecto:', error);
            }
        }

        fetchData();
    }, [id]);

    // Función para calcular el progreso
    const calculateProgress = () => {
        if (!project || !project.max_investment) return 0; // Protege contra errores si los datos no son válidos
        const percentage = (project.current_investment / project.max_investment) * 100;
        return Math.min(percentage, 100); // Limita el porcentaje a un máximo de 100
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
                                    { width: `${calculateProgress()}%` }, // Ajusta el ancho de la barra
                                ]}
                            />
                        </View>

                        {/* Porcentaje del progreso */}
                        <Text style={styles.progressText}>
                            {calculateProgress().toFixed(2)}% funded
                        </Text>

                        {/* Botones para Editar Proyecto y Ver Inversores si el proyecto es del usuario con ID 22 */}
                        {project.user_id === 22 && (
                            <View style={styles.buttonContainer}>
                                {/* Botón Editar Proyecto */}
                                <TouchableOpacity
                                    style={styles.editButton}
                                    onPress={() => navigation.navigate('EditProject', { projectId: project.id })}
                                >
                                    <Text style={styles.editButtonText}>Edit Project</Text>
                                </TouchableOpacity>

                                {/* Botón Ver Inversores */}
                                <TouchableOpacity
                                    style={styles.investorsButton}
                                    onPress={() => navigation.navigate('Investors', { projectId: project.id })}
                                >
                                    <Text style={styles.investorsButtonText}>View Investors</Text>
                                </TouchableOpacity>
                            </View>
                        )}
                    </View>

                    {/* Sección de actualizaciones */}
                    <View style={styles.updatesContainer}>
                        <Text style={styles.updatesTitle}>Project Updates</Text>
                        {updates.length > 0 ? (
                            updates.map((update) => (
                                <View key={update.id} style={styles.updateCard}>
                                    <Text style={styles.updateTitle}>{update.title}</Text>
                                    <Text style={styles.updateDescription}>{update.description}</Text>
                                    <Text style={styles.updateDate}>
                                        Updated on: {new Date(update.updated_at).toLocaleDateString()}
                                    </Text>
                                </View>
                            ))
                        ) : (
                            <Text style={styles.noUpdatesText}>No updates available for this project.</Text>
                        )}
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
        backgroundColor: '#f9f5e9', // Fondo crema
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
        color: '#55877e', // Verde para el estado
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
    buttonContainer: {
        marginTop: 16,
        width: '100%',
        alignItems: 'center',
    },
    editButton: {
        backgroundColor: '#55877e',
        padding: 12,
        borderRadius: 8,
        alignItems: 'center',
        marginBottom: 8,
        width: '100%',
    },
    editButtonText: {
        color: '#fff',
        fontSize: 16,
        fontWeight: 'bold',
    },
    investorsButton: {
        backgroundColor: '#2a9d8f',
        padding: 12,
        borderRadius: 8,
        alignItems: 'center',
        width: '100%',
    },
    investorsButtonText: {
        color: '#fff',
        fontSize: 16,
        fontWeight: 'bold',
    },
    updatesContainer: {
        marginTop: 20,
    },
    updatesTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        marginBottom: 10,
        color: '#333',
    },
    updateCard: {
        padding: 15,
        borderRadius: 10,
        backgroundColor: '#fff',
        marginBottom: 10,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
        elevation: 3,
    },
    updateTitle: {
        fontSize: 16,
        fontWeight: 'bold',
        marginBottom: 5,
        color: '#333',
    },
    updateDescription: {
        fontSize: 14,
        marginBottom: 5,
        color: '#555',
    },
    updateDate: {
        fontSize: 12,
        color: '#888',
    },
    noUpdatesText: {
        fontSize: 14,
        color: '#888',
        textAlign: 'center',
    },
});

export default ProjectDetail;
