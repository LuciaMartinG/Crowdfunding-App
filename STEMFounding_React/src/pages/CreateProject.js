import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Alert } from 'react-native';
import { postInsertProject } from '../services/projectService';
import { useNavigation } from '@react-navigation/native';
import AsyncStorage from '@react-native-async-storage/async-storage';

const CreateProject = () => {
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [imageUrl, setImageUrl] = useState('');
    const [videoUrl, setVideoUrl] = useState('');
    const [minInvestment, setMinInvestment] = useState('');
    const [maxInvestment, setMaxInvestment] = useState('');
    const [limitDate, setLimitDate] = useState('');
    const [token, setToken] = useState(null);

    const navigation = useNavigation();

    useEffect(() => {
        const fetchToken = async () => {
            try {
                const userString = await AsyncStorage.getItem('user');
                const user = JSON.parse(userString);
                if (user && user.access_token) {
                    setToken(user.access_token);
                } else {
                    Alert.alert('Error', 'User not authenticated.');
                }
            } catch (error) {
                console.error('Error retrieving token:', error);
            }
        };
        fetchToken();
    }, []);

    const handleSubmit = async () => {
        if (!title || !description || !imageUrl || !videoUrl || !minInvestment || !maxInvestment || !limitDate) {
            Alert.alert('Error', 'Please fill out all fields.');
            return;
        }

        try {
            const projectData = {
                title,
                description,
                image_url: imageUrl,
                video_url: videoUrl,
                min_investment: parseFloat(minInvestment),
                max_investment: parseFloat(maxInvestment),
                limit_date: limitDate
            };
            
            console.log('Sending project data:', projectData);

            const response = await postInsertProject(projectData, token);
            console.log(response);

            if (response.status === 201 || response.status === 200) {
                Alert.alert('Success', 'Project created successfully!');
                navigation.navigate('Projects');
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
