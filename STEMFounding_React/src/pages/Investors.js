import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView } from 'react-native';
import { getProjectInvestors } from '../services/projectService';
import { useNavigation, useRoute } from '@react-navigation/native';

const Investors = () => {
    const [investors, setInvestors] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const navigation = useNavigation();
    const route = useRoute();
    const { projectId } = route.params; // Obtener el projectId de los parámetros

    useEffect(() => {
        async function fetchInvestors() {
            try {
                const response = await getProjectInvestors(projectId); // Llamada al servicio
                
                // Depuración: ver qué datos devuelve la API
                console.log("API Response:", response);

                // Verifica si los datos están dentro de 'data' y si existe 'investors'
                if (response && response.data && Array.isArray(response.data.investors)) {
                    setInvestors(response.data.investors); // Asumiendo que los inversores están dentro de 'data.investors'
                } else {
                    setInvestors([]); // Si no hay inversores, asignamos un array vacío
                }
                setLoading(false);
            } catch (error) {
                console.error("Error loading investors:", error); // Log de error para depurar
                setError('Error loading investors');
                setLoading(false);
            }
        }

        fetchInvestors();
    }, [projectId]);

    if (loading) {
        return (
            <View style={styles.container}>
                <Text style={styles.title}>Loading investors...</Text>
            </View>
        );
    }

    if (error) {
        return (
            <View style={styles.container}>
                <Text style={styles.title}>{error}</Text>
                <TouchableOpacity style={styles.backButton} onPress={() => navigation.goBack()}>
                    <Text style={styles.backButtonText}>Go Back</Text>
                </TouchableOpacity>
            </View>
        );
    }

    return (
        <ScrollView style={styles.container}>
            <Text style={styles.title}>Investors</Text>
            {investors.length > 0 ? (
                investors.map((investor, index) => (
                    <View key={index} style={styles.investorCard}>
                        <Text style={styles.investorName}>Name: {investor.user}</Text> {/* Nombre del inversor */}
                        <Text style={styles.investmentAmount}>Investment: €{investor.investment_amount}</Text>
                    </View>
                ))
            ) : (
                <Text style={styles.noInvestors}>No investors found for this project.</Text>
            )}
            <TouchableOpacity style={styles.backButton} onPress={() => navigation.goBack()}>
                <Text style={styles.backButtonText}>Go Back</Text>
            </TouchableOpacity>
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 16,
        backgroundColor: '#f9f5e9',
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#333',
        textAlign: 'center',
        marginBottom: 20,
    },
    investorCard: {
        backgroundColor: '#fff',
        padding: 16,
        marginBottom: 12,
        borderRadius: 10,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 5,
        elevation: 3,
    },
    investorName: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#333',
    },
    investmentAmount: {
        fontSize: 14,
        color: '#555',
        marginTop: 5,
    },
    noInvestors: {
        fontSize: 16,
        color: '#888',
        textAlign: 'center',
        marginTop: 50,
    },
    backButton: {
        marginTop: 20,
        backgroundColor: '#007BFF',
        padding: 10,
        borderRadius: 8,
    },
    backButtonText: {
        color: '#fff',
        fontSize: 14,
        textAlign: 'center',
    },
});

export default Investors;
