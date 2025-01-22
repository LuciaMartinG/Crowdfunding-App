import { useEffect, useState } from "react";
import { getProjectList } from '../services/projectService';
import { StyleSheet, Text, View, ScrollView, TouchableOpacity } from 'react-native';


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
                style={styles.projectItem}
                onPress={() => navigation.navigate('ProjectDetail', { id: project.id })} // Navegar al detalle del proyecto
            >
                <Text style={styles.projectTitle}>{project.title}</Text>
            </TouchableOpacity>
            ))}
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 20,
        backgroundColor: '#fff',
    },
    projectItem: {
        marginBottom: 15,
        padding: 10,
        borderWidth: 1,
        borderColor: '#ccc',
        borderRadius: 5,
    },
    projectTitle: {
        fontSize: 18,
        fontWeight: 'bold',
    },
});

export default Project;
