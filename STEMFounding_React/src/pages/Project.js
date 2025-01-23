import { useEffect, useState } from "react";
import { getProjectList } from '../services/projectService';
import { StyleSheet, Text, View, ScrollView, TouchableOpacity, Image } from 'react-native';

function Project({ navigation }) {

    const [projectList, setProjectList] = useState([]);

    useEffect(() => {
        getProjectList()
            .then((response) => setProjectList(response.data))
            .catch((error) => console.error(error));
    }, []);

    return (
        <ScrollView style={styles.container}>
            {projectList.map((project) => (
                <TouchableOpacity
                    key={project.id}
                    style={styles.card}
                    onPress={() => navigation.navigate('ProjectDetail', { id: project.id })} // Navegar al detalle del proyecto
                >
                    <Image
                        source={{ uri: project.image_url }}
                        style={styles.cardImage}
                    />
                    <View style={styles.cardContent}>
                        <Text style={styles.cardTitle}>{project.title}</Text>
                        <Text style={styles.cardDescription}>{project.description}</Text>
                    </View>
                </TouchableOpacity>
            ))}
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 20,
        backgroundColor: '#f9f5e9', // Fondo crema para la aplicación
    },
    card: {
        backgroundColor: '#fff',
        borderRadius: 10,
        marginBottom: 20,
        overflow: 'hidden',
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.2,
        shadowRadius: 4,
        elevation: 3, // Para sombra en Android
    },
    cardImage: {
        width: '100%',
        height: 150,
    },
    cardContent: {
        padding: 15,
    },
    cardTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#333',
        marginBottom: 10,
    },
    cardDescription: {
        fontSize: 14,
        color: '#666',
    },
});

export default Project;
