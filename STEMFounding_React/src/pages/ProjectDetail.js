import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, Modal, TextInput, Button, Alert } from "react-native";
import { useRoute, useNavigation } from "@react-navigation/native";
import { getProjectById, getProjectUpdates, addUpdates, deleteUpdate, editUpdate } from "../services/projectService"; // Importar la función updateUpdate

const ProjectDetail = () => {
    const route = useRoute();
    const navigation = useNavigation();
    const { id } = route.params;

    const [project, setProject] = useState(null);
    const [updates, setUpdates] = useState([]);
    const [modalVisible, setModalVisible] = useState(false);
    const [newUpdate, setNewUpdate] = useState({
        title: '',
        description: '',
        image_url: ''
    });

    const [updateToEdit, setUpdateToEdit] = useState(null); // Estado para manejar la actualización seleccionada para editar

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

    const calculateProgress = () => {
        if (!project || !project.max_investment) return 0;
        const percentage = (project.current_investment / project.max_investment) * 100;
        return Math.min(percentage, 100);
    };

    const handleAddUpdate = async () => {
        if (!newUpdate.title || !newUpdate.description) {
            Alert.alert('Error', 'Title and description are required.');
            return;
        }

        const updateData = {
            title: newUpdate.title,
            description: newUpdate.description,
        };

        if (newUpdate.image_url) {
            updateData.image_url = newUpdate.image_url;
        }

        try {
            let response;
            if (updateToEdit) {
                response = await editUpdate(updateToEdit.id, updateData); // Actualizar la actualización
            } else {
                response = await addUpdates(id, updateData); // Agregar nueva actualización
            }

            if (response.status === 200) {
                const updatedProjectResponse = await getProjectById(id);
                setProject(updatedProjectResponse.data);

                const updatesResponse = await getProjectUpdates(id);
                setUpdates(updatesResponse.data);

                setModalVisible(false);
                setNewUpdate({ title: '', description: '', image_url: '' });
                setUpdateToEdit(null); // Resetear el estado de la actualización que estamos editando
            } else {
                throw new Error('Failed to add or update update.');
            }
        } catch (error) {
            console.error("Error during API call:", error);
            Alert.alert('Error', 'There was an issue adding or updating the update: ' + error.message);
        }
    };

    const handleDeleteUpdate = async (updateId) => {
        try {
            const response = await deleteUpdate(updateId);
            if (response.status === 200) {
                const updatedUpdates = updates.filter(update => update.id !== updateId);
                setUpdates(updatedUpdates);
            }
        } catch (error) {
            console.error('Error deleting update:', error);
            Alert.alert('Error', 'There was an issue deleting the update.');
        }
    };

    const handleEditUpdate = (updateId) => {
        const selectedUpdate = updates.find((update) => update.id === updateId);
        if (selectedUpdate) {
            setUpdateToEdit(selectedUpdate);
            setNewUpdate({ title: selectedUpdate.title, description: selectedUpdate.description, image_url: selectedUpdate.image_url });
            setModalVisible(true); // Abrir el modal para editar
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
                            <View
                                style={[
                                    styles.progressFill,
                                    { width: `${calculateProgress()}%` },
                                ]}
                            />
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

                                    {project.user_id === 22 && (
                                        <TouchableOpacity
                                            style={styles.editButton}
                                            onPress={() => handleEditUpdate(update.id)}
                                        >
                                            <Text style={styles.deleteButtonText}>Edit Update</Text>
                                        </TouchableOpacity>
                                    )}

                                    {project.user_id === 22 && (
                                        <TouchableOpacity
                                            style={styles.deleteButton}
                                            onPress={() => handleDeleteUpdate(update.id)}
                                        >
                                            <Text style={styles.deleteButtonText}>Delete Update</Text>
                                        </TouchableOpacity>
                                    )}
                                </View>
                            ))
                        ) : (
                            <Text style={styles.noUpdatesText}>No updates available for this project.</Text>
                        )}
                    </View>

                    <TouchableOpacity style={styles.addButton} onPress={() => setModalVisible(true)}>
                        <Text style={styles.addButtonText}>Add Update</Text>
                    </TouchableOpacity>

                    <Modal
                        animationType="slide"
                        transparent={true}
                        visible={modalVisible}
                        onRequestClose={() => setModalVisible(false)}
                    >
                        <View style={styles.modalOverlay}>
                            <View style={styles.modalContent}>
                                <Text style={styles.modalTitle}>{updateToEdit ? 'Edit Update' : 'Add Update'}</Text>

                                <TextInput
                                    style={styles.input}
                                    placeholder="Title (required)"
                                    value={newUpdate.title}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, title: text })}
                                />

                                <TextInput
                                    style={styles.input}
                                    placeholder="Description (required)"
                                    value={newUpdate.description}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, description: text })}
                                />

                                <TextInput
                                    style={styles.input}
                                    placeholder="Image URL (optional)"
                                    value={newUpdate.image_url}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, image_url: text })}
                                />

                                <View style={styles.buttonContainer}>
                                    <Button title={updateToEdit ? 'Update Update' : 'Add Update'} onPress={handleAddUpdate} />
                                    <Button title="Close" onPress={() => setModalVisible(false)} />
                                </View>
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
        backgroundColor: '#f9f5e9',
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
        color: '#55877e',
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
        backgroundColor: '#77c4d3',
    },
    progressText: {
        textAlign: 'center',
        fontSize: 16,
        color: '#333',
    },
    buttonContainer: {
        flexDirection: 'row',
        justifyContent: 'center',
        marginTop: 10,
    },
    editButton: {
        backgroundColor: '#578E7E',
        padding: 10,
        marginRight: 10,
        borderRadius: 5,
    },
    deleteButton: {
        backgroundColor: '#f44336',
        padding: 10,
        borderRadius: 5,
    },
    editButtonText: {
        color: '#fff',
        fontSize: 16,
    },
    deleteButtonText: {
        color: '#fff',
        fontSize: 16,
    },
    updatesContainer: {
        marginTop: 20,
    },
    updatesTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        marginBottom: 10,
    },
    updateCard: {
        backgroundColor: '#fff',
        padding: 15,
        marginBottom: 10,
        borderRadius: 8,
        shadowColor: '#000',
        shadowOpacity: 0.1,
        shadowRadius: 5,
        elevation: 3,
    },
    updateTitle: {
        fontSize: 16,
        fontWeight: 'bold',
    },
    updateDescription: {
        fontSize: 14,
        marginVertical: 5,
    },
    updateDate: {
        fontSize: 12,
        color: '#777',
    },
    noUpdatesText: {
        fontSize: 16,
        color: '#555',
        textAlign: 'center',
    },
    addButton: {
        backgroundColor: '#008CBA',
        padding: 10,
        borderRadius: 5,
        marginTop: 20,
        alignItems: 'center',
    },
    addButtonText: {
        color: '#fff',
        fontSize: 16,
    },
    modalOverlay: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: 'rgba(0, 0, 0, 0.5)',
    },
    modalContent: {
        backgroundColor: '#fff',
        width: '80%',
        padding: 20,
        borderRadius: 10,
    },
    modalTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        marginBottom: 10,
    },
    input: {
        width: '100%',
        padding: 10,
        borderWidth: 1,
        borderRadius: 5,
        marginBottom: 10,
        borderColor: '#ccc',
    },
    loadingContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    loading: {
        fontSize: 18,
        color: '#555',
    },
    investorsButton: {
        backgroundColor: '#578E7E',
        padding: 10,
        marginRight: 10,
        borderRadius: 5,
    },
    investorsButtonText: {
        color: '#fff',
        fontSize: 16,
    },
});

export default ProjectDetail;
