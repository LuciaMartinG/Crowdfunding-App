import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Alert } from 'react-native';
import { updateProject } from '../services/projectService'; // Importa la función para actualizar el proyecto
import { useNavigation, useRoute } from '@react-navigation/native'; // Para obtener parámetros y navegar después de la edición
import { getProjectById } from '../services/projectService';

const EditProject = ({ route }) => {
    const navigation = useNavigation(); // Hook para navegar después de editar el proyecto
    // const route = useRoute(); // Obtener parámetros pasados a la pantalla

    const projectId = route.params.projectId; // Asegúrate de recibir `projectId`

    // Estado para los campos del formulario
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [imageUrl, setImageUrl] = useState('');
    const [videoUrl, setVideoUrl] = useState('');

    console.log(projectId)

    const fetchProjectData = async () => {
        try {

            const response = await getProjectById(projectId); // Debes definir esta función en tu servicio
            const project = response.data;

            setTitle(project.title);
            setDescription(project.description);
            setImageUrl(project.image_url);
            setVideoUrl(project.video_url);
        } catch (error) {
            Alert.alert('Error', 'Project data not found');
            navigation.goBack(); // Regresa si no se encuentran los datos
        }
    };

    // Efecto para obtener datos del proyecto a editar
    useEffect(() => {
        
            fetchProjectData();
        
    }, []);

    // Función para manejar la actualización del proyecto
    const handleUpdate = async () => {
        if (!title || !description || !imageUrl || !videoUrl) {
            Alert.alert('Error', 'Please fill out all fields.');
            return;
        }

        try {
            const updatedData = {
                id: projectId,
                title,
                description,
                image_url: imageUrl,
                video_url: videoUrl,
            };

            const response = await updateProject(updatedData);

            if (response.status === 200) {
                Alert.alert('Success', 'Project updated successfully!');
                navigation.navigate('Projects');
            } else {
                Alert.alert('Error', 'Failed to update the project.');
            }
        } catch (error) {
            console.error(error);
            Alert.alert('Error', 'Something went wrong. Please try again.');
        }
    };

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Edit Project</Text>

            <TextInput
                style={styles.input}
                placeholder="Project Title"
                value={title}
                onChangeText={setTitle}
            />

            <TextInput
                style={styles.input}
                placeholder="Description"
                value={description}
                onChangeText={setDescription}
            />

            <TextInput
                style={styles.input}
                placeholder="Image URL"
                value={imageUrl}
                onChangeText={setImageUrl}
            />

            <TextInput
                style={styles.input}
                placeholder="Video URL"
                value={videoUrl}
                onChangeText={setVideoUrl}
            />

            <Button title="Update Project" onPress={handleUpdate} />
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 20,
        backgroundColor: '#f9f5e9',
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        marginBottom: 20,
        textAlign: 'center',
    },
    input: {
        height: 40,
        borderColor: '#ccc',
        borderWidth: 1,
        borderRadius: 5,
        marginBottom: 15,
        paddingHorizontal: 10,
    },
});

export default EditProject;
