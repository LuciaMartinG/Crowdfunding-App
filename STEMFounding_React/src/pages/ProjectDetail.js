import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, Image, ScrollView, TouchableOpacity, Modal, TextInput, Button, Alert } from "react-native";
import { useRoute, useNavigation } from "@react-navigation/native";
import { getProjectById, getProjectUpdates, addUpdates, deleteUpdate } from "../services/projectService"; // Importar la función addUpdates

const ProjectDetail = () => {
    const route = useRoute();
    const navigation = useNavigation();
    const { id } = route.params; // Recibe el ID desde los parámetros de la ruta

    const [project, setProject] = useState(null); // Almacenamos los detalles del proyecto
    const [updates, setUpdates] = useState([]); // Almacenamos las actualizaciones del proyecto

    const [modalVisible, setModalVisible] = useState(false); // Estado para manejar la visibilidad del modal
    const [newUpdate, setNewUpdate] = useState({ // Estado para manejar los valores del formulario de actualización
        title: '',
        description: '',
        image_url: ''
    });

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

    const handleAddUpdate = async () => {
        // Verificar que al menos title y description estén presentes
        if (!newUpdate.title || !newUpdate.description) {
            Alert.alert('Error', 'Title and description are required.');
            return;
        }
    
        // Preparar los datos a enviar solo con title y description
        const updateData = {
            title: newUpdate.title,
            description: newUpdate.description,
        };
    
        // Solo añadir image_url si tiene valor
        if (newUpdate.image_url) {
            updateData.image_url = newUpdate.image_url;
        }
    
        try {
            // Llamar a la API para agregar la actualización
            const response = await addUpdates(id, updateData);
    
            if (response.status === 200) {
                // Si la actualización es exitosa, obtener el proyecto actualizado y las nuevas actualizaciones
                const updatedProjectResponse = await getProjectById(id);
                setProject(updatedProjectResponse.data); // Actualizar el proyecto
    
                const updatesResponse = await getProjectUpdates(id);
                setUpdates(updatesResponse.data); // Actualizar las actualizaciones
    
                // Cerrar el modal y limpiar los campos
                setModalVisible(false);
                setNewUpdate({ title: '', description: '', image_url: '' });
            } else {
                throw new Error('Failed to add update.');
            }
        } catch (error) {
            console.error("Error during API call:", error);
            Alert.alert('Error', 'There was an issue adding the update: ' + error.message);
        }
    };
    
    const handleDeleteUpdate = async (updateId) => {
        try {
            // Llamada al método para eliminar la actualización
            const response = await deleteUpdate(updateId);
            if (response.status === 200) {
                console.log('Update deleted successfully');
                // Actualizar la lista de actualizaciones
                const updatedUpdates = updates.filter(update => update.id !== updateId);
                setUpdates(updatedUpdates);
            }
        } catch (error) {
            console.error('Error deleting update:', error);
            Alert.alert('Error', 'There was an issue deleting the update.');
        }
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

                                    {/* Botón de borrar actualización */}
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

                    {/* Botón para abrir el modal */}
                    <TouchableOpacity style={styles.addButton} onPress={() => setModalVisible(true)}>
                        <Text style={styles.addButtonText}>Add Update</Text>
                    </TouchableOpacity>

                    {/* Modal */}
                    <Modal
                        animationType="slide"
                        transparent={true}
                        visible={modalVisible}
                        onRequestClose={() => setModalVisible(false)} // Cerrar el modal al presionar el botón de retroceso
                    >
                        <View style={styles.modalOverlay}>
                            <View style={styles.modalContent}>
                                <Text style={styles.modalTitle}>Add Update</Text>

                                {/* Campo para el título */}
                                <TextInput
                                    style={styles.input}
                                    placeholder="Title (required)"
                                    value={newUpdate.title}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, title: text })}
                                />

                                {/* Campo para la descripción */}
                                <TextInput
                                    style={styles.input}
                                    placeholder="Description (required)"
                                    value={newUpdate.description}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, description: text })}
                                />

                                {/* Campo para la URL de la imagen */}
                                <TextInput
                                    style={styles.input}
                                    placeholder="Image URL (optional)"
                                    value={newUpdate.image_url}
                                    onChangeText={(text) => setNewUpdate({ ...newUpdate, image_url: text })}
                                />

                                <View style={styles.buttonContainer}>
                                    <Button title="Add Update" onPress={handleAddUpdate} />
                                    <Button title="Close" onPress={() => setModalVisible(false)} />
                                </View>
                            </View>
                        </View>
                    </Modal>
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
    addButton: {
        backgroundColor: '#55877e',
        padding: 10,
        borderRadius: 8,
        alignItems: 'center',
        marginTop: 20,
    },
    addButtonText: {
        color: '#fff',
        fontSize: 16,
        fontWeight: 'bold',
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
    modalOverlay: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: 'rgba(0, 0, 0, 0.5)', // Fondo oscuro transparente
    },
    modalContent: {
        backgroundColor: 'white',
        padding: 20,
        borderRadius: 10,
        width: '80%',
        alignItems: 'center',
    },
    modalTitle: {
        fontSize: 20,
        marginBottom: 10,
    },
    input: {
        height: 40,
        width: '100%',
        borderColor: '#ccc',
        borderWidth: 1,
        borderRadius: 5,
        paddingHorizontal: 10,
        marginBottom: 20,
    },
    buttonContainer: {
        flexDirection: 'column', // Cambiado a 'column' para que los botones estén apilados verticalmente
        alignItems: 'center',    // Centrar los botones horizontalmente
        width: '100%',           // Para que ocupe todo el ancho disponible
        marginTop: 20,           // Agregar un poco de margen superior para separarlo de otros elementos
    },

    editButton: {
        backgroundColor: '#55877e', // Fondo verde
        paddingVertical: 12, // Añadir relleno para hacerlo más grande
        paddingHorizontal: 20, // Añadir relleno para equilibrar el tamaño
        borderRadius: 8, // Bordes redondeados
        alignItems: 'center', // Centrar el texto
        marginBottom: 10, // Espaciado entre los botones
        width: '80%', // Ancho del 80% para que sea más destacado
        alignSelf: 'center', // Centrar el botón horizontalmente
    },

    investorsButton: {
        backgroundColor: '#55877e', // Fondo verde, el mismo color para consistencia
        paddingVertical: 12, // Tamaño adecuado para hacerlo visualmente atractivo
        paddingHorizontal: 20, // Espaciado en los lados
        borderRadius: 8, // Bordes redondeados
        alignItems: 'center', // Centrar el texto
        marginBottom: 10, // Espaciado entre los botones
        width: '80%', // Ancho del 80% para que sea prominente
        alignSelf: 'center', // Centrar el botón horizontalmente
    },
    editButtonText: {
        color: '#fff', // Color blanco para el texto
        fontSize: 16, // Tamaño de fuente adecuado
        fontWeight: 'bold', // Hacer el texto en negrita
    },
    investorsButtonText: {
        color: '#fff', // Texto blanco
        fontSize: 16, // Tamaño adecuado
        fontWeight: 'bold', // Hacer el texto en negrita
    },
    deleteButton: {
        backgroundColor: '#d9534f', // Rojo
        paddingVertical: 8,
        paddingHorizontal: 15,
        borderRadius: 5,
        alignItems: 'center',
        marginTop: 10,
    },
    deleteButtonText: {
        color: '#fff',
        fontSize: 14,
        fontWeight: 'bold',
    },
});

export default ProjectDetail;
