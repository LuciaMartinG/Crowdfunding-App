// src/pages/MyProjects.js
import React from 'react';
import { View, Text, StyleSheet } from 'react-native';

const MyProjects = () => {
    return (
        <View style={styles.container}>
            <Text style={styles.title}>My Projects</Text>
            {/* Aquí puedes agregar la lista de proyectos o cualquier otro contenido */}
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#f9f5e9',
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#333',
    },
});

export default MyProjects;
