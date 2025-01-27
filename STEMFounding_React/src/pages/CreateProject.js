import React, { useState } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Alert } from 'react-native';
import { postInsertProject } from '../services/projectService'; // Asegúrate de que postInsertProject esté bien importado
import { useNavigation } from '@react-navigation/native'; // Para navegar después de la creación

const CreateProject = () => {
    // Establecer el estado para los campos del formulario
    // const [userId, setUserId] = useState('');
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [imageUrl, setImageUrl] = useState('');
    const [videoUrl, setVideoUrl] = useState('');
    const [minInvestment, setMinInvestment] = useState('');
    const [maxInvestment, setMaxInvestment] = useState('');
    const [limitDate, setLimitDate] = useState('');
    // const [currentInvestment, setCurrentInvestment] = useState('');

    const navigation = useNavigation(); // Hook para navegar después de crear el proyecto

    // Función para manejar el envío del formulario
    const handleSubmit = async () => {
        // Verificar si todos los campos están completos
        if (
            // !userId ||
            !title || 
            !description || 
            !imageUrl || 
            !videoUrl || 
            !minInvestment || 
            !maxInvestment || 
            !limitDate
            // !currentInvestment
        ) {
            Alert.alert('Error', 'Please fill out all fields.');
            return;
        }

        try {
            // Preparar los datos del proyecto
            const projectData = {
                // userId: userId,
                title,
                description,
                image_url: imageUrl,
                video_url: videoUrl,
                min_investment: parseFloat(minInvestment),
                max_investment: parseFloat(maxInvestment),
                limit_date: limitDate
                // current_investment: parseFloat(currentInvestment)
            };
            console.log(projectData);


            // Llamar a la función postInsertProject desde projectService
            const response = await postInsertProject(projectData);
console.log(response);
            // Si la respuesta es exitosa, navegar a la pantalla de Projects
            if (response.status === 201 || response.status === 200) {
                Alert.alert('Success', 'Project created successfully!');
                navigation.navigate('Projects'); // Cambia 'Projects' por el nombre correcto de la pantalla de proyectos
            } else {
                Alert.alert('Error', 'Failed to create project.');
            }
        } catch (error) {
            console.error(error);
            Alert.alert('Error', 'Something went wrong. Please try again.');
        }
    };

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Create New Project</Text>
            
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
            
            <TextInput
                style={styles.input}
                placeholder="Min Investment"
                value={minInvestment}
                onChangeText={setMinInvestment}
                keyboardType="numeric"
            />
            
            <TextInput
                style={styles.input}
                placeholder="Max Investment"
                value={maxInvestment}
                onChangeText={setMaxInvestment}
                keyboardType="numeric"
            />
            
            <TextInput
                style={styles.input}
                placeholder="Limit Date"
                value={limitDate}
                onChangeText={setLimitDate}
            />

            {/* <TextInput
                style={styles.input}
                placeholder="Current investment"
                value={currentInvestment}
                onChangeText={setCurrentInvestment}
            /> */}

            <Button title="Create Project" onPress={handleSubmit} />
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

export default CreateProject;
