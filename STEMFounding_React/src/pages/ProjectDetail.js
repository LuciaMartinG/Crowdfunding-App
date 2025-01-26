import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, Modal, TextInput } from 'react-native';
import { useRoute, useNavigation } from '@react-navigation/native';
import { getProjectById, getProjectUpdates, addUpdates } from '../services/projectService'; // Importar addUpdates

const ProjectDetail = () => {
    const route = useRoute();
    const navigation = useNavigation();
    const { id } = route.params;  // El ID del proyecto recibido desde la ruta

    const [project, setProject] = useState(null);
    const [updates, setUpdates] = useState([]);
    const [modalVisible, setModalVisible] = useState(false); // Estado para el modal
    const [newUpdate, setNewUpdate] = useState({
        title: '',
        description: '',
        image_url: ''
    });

    // Carga de los detalles del proyecto y actualizaciones
    useEffect(() => {
        async function fetchData() {
            try {
                const projectResponse = await getProjectById(id);
                setProject(projectResponse.data);

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
        if (!project || !project.max_investment) return 0;
        const percentage = (project.current_investment / project.max_investment) * 100;
        return Math.min(percentage, 100);
    };

    // Función para manejar el envío del formulario y llamar a la API
    const handleAddUpdate = async () => {
        try {
            // Preparamos los datos para enviar
            const updateData = {
                title: newUpdate.title || project.title, // Si el campo está vacío, se toma el valor del proyecto
                description: newUpdate.description || project.description,
                image_url: newUpdate.image_url || project.image_url,
            };

            // Llamamos a la API para agregar la actualización
            await addUpdates(id, updateData);

            // Actualizamos las actualizaciones en la UI
            const updatesResponse = await getProjectUpdates(id);
            setUpdates(updatesResponse.data);

            // Cerramos el modal después de agregar la actualización
            setModalVisible(false);
        } catch (error) {
            console.error("Error al agregar la actualización:", error);
        }
    };

    return (
        <ScrollView style={styles.container}>
            {project ? (
                <View>
                    <Image source={{ uri: project.image_url }} style={styles.image} />
                    <View style={styles.textContainer}>
                        <Text style={styles.title}>{project.title}</Text>
                        <Text style={styles.status}>Status: {project.state}</Text>
                        <Text style={styles.description}>{project.description}</Text>
                        <Text style={styles.subtitle}>
                            Money raised: {project.current_investment}€ / {project.max_investment}€
                        </Text>

                        <View style={styles.progressBar}>
                            <View style={[styles.progressFill, { width: `${calculateProgress()}%` }]} />
                        </View>

                        <Text style={styles.progressText}>
                            {calculateProgress().toFixed(2)}% funded
                        </Text>

                        {project.user_id === 22 && (
                            <View style={styles.buttonContainer}>
                                <TouchableOpacity
                                    style={styles.editButton}
                                    onPress={() => navigation.navigate('EditProject', { projectId: project.id })}
                                >
                                    <Text style={styles.editButtonText}>Edit Project</Text>
                                </TouchableOpacity>
                                <TouchableOpacity
                                    style={styles.investorsButton}
                                    onPress={() => navigation.navigate('Investors', { projectId: project.id })}
                                >
                                    <Text style={styles.investorsButtonText}>View Investors</Text>
                                </TouchableOpacity>
                            </View>
                        )}
                    </View>

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

                    {/* Botón para agregar una nueva actualización */}
                    <TouchableOpacity
                        style={styles.addUpdateButton}
                        onPress={() => setModalVisible(true)}
                    >
                        <Text style={styles.addUpdateButtonText}>Add Update</Text>
                    </TouchableOpacity>

                    {/* Modal para agregar actualización */}
                    <Modal
                        visible={modalVisible}
                        animationType="slide"
                        transparent={true}
                        onRequestClose={() => setModalVisible(false)}
                    >
                        <View style={styles.modalContainer}>
                            <View style={styles.modalContent}>
                                <Text style={styles.modalTitle}>Add Project Update</Text>

                                <TextInput
                                    style={styles.input}
                                    placeholder="Title"
                                    value={newUpdate.title}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, title: text })}
                                />
                                <TextInput
                                    style={styles.input}
                                    placeholder="Description"
                                    value={newUpdate.description}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, description: text })}
                                />
                                <TextInput
                                    style={styles.input}
                                    placeholder="Image URL"
                                    value={newUpdate.image_url}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, image_url: text })}
                                />

                                <TouchableOpacity
                                    style={styles.addUpdateButton}
                                    onPress={handleAddUpdate} // Llamar a la función para agregar la actualización
                                >
                                    <Text style={styles.addUpdateButtonText}>Add Update</Text>
                                </TouchableOpacity>
                                <TouchableOpacity
                                    style={styles.addUpdateButton}
                                    onPress={() => setModalVisible(false)}
                                >
                                    <Text style={styles.addUpdateButtonText}>Cancel</Text>
                                </TouchableOpacity>
                            </View>
                        </View>
                    </Modal>
                </View>
            ) : (
                <View style={styles.loadingContainer}>
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
    addUpdateButton: {
        backgroundColor: '#55877e',
        padding: 12,
        borderRadius: 8,
        alignItems: 'center',
        marginTop: 16,
    },
    addUpdateButtonText: {
        color: '#fff',
        fontSize: 16,
        fontWeight: 'bold',
    },
    modalContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: 'rgba(0, 0, 0, 0.5)',
    },
    modalContent: {
        backgroundColor: '#fff',
        padding: 20,
        borderRadius: 10,
        width: '80%',
    },
    modalTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        marginBottom: 10,
        textAlign: 'center',
    },
    input: {
        height: 40,
        borderColor: '#ddd',
        borderWidth: 1,
        borderRadius: 8,
        marginBottom: 10,
        paddingLeft: 10,
        fontSize: 16,
    },
});

export default ProjectDetail;
