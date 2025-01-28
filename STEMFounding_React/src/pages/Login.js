import React, { useState } from 'react';
import { View, TextInput, Button, Alert, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { login } from '../services/projectService';
import AsyncStorage from '@react-native-async-storage/async-storage';

const Login = ({ navigation, setUserLoggedIn }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = async () => {
    try {
      const data = { email, password };
      const response = await login(data);

      const { user, access_token } = response;

      if (user.role !== 'entrepreneur') {
        Alert.alert('Access Denied', 'Only users with the role "entrepreneur" can log in.');
        return;
      }

      await AsyncStorage.setItem('authToken', access_token);
      setUserLoggedIn(true);
      navigation.navigate('MyProfile');
    } catch (error) {
      console.error('Error en el login:', error);
      const errorMessage = error?.response?.data?.error || `An error occurred (${error.response?.status || 'unknown'})`;
      Alert.alert('Login Failed', errorMessage);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Welcome Back!</Text>
      <Text style={styles.subtitle}>Please log in to your account</Text>

      <TextInput
        style={styles.input}
        placeholder="Email"
        placeholderTextColor="#aaa"
        value={email}
        onChangeText={setEmail}
        keyboardType="email-address"
        autoCapitalize="none"
      />
      <TextInput
        style={styles.input}
        placeholder="Password"
        placeholderTextColor="#aaa"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />

      <TouchableOpacity style={styles.button} onPress={handleLogin}>
        <Text style={styles.buttonText}>Login</Text>
      </TouchableOpacity>

      <Text style={styles.footerText}>
        Don't have an account?{' '}
        <Text style={styles.link} onPress={() => navigation.navigate('Register')}>
          Sign Up
        </Text>
      </Text>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f5f5f5',
    padding: 20,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 10,
  },
  subtitle: {
    fontSize: 16,
    color: '#666',
    marginBottom: 20,
  },
  input: {
    width: '100%',
    height: 50,
    backgroundColor: '#fff',
    borderRadius: 10,
    paddingHorizontal: 15,
    fontSize: 16,
    marginBottom: 15,
    borderColor: '#ddd',
    borderWidth: 1,
  },
  button: {
    width: '100%',
    height: 50,
    backgroundColor: '#007bff',
    borderRadius: 10,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 10,
  },
  buttonText: {
    fontSize: 18,
    color: '#fff',
    fontWeight: 'bold',
  },
  footerText: {
    fontSize: 14,
    color: '#666',
    marginTop: 20,
  },
  link: {
    color: '#007bff',
    fontWeight: 'bold',
  },
});

export default Login;
