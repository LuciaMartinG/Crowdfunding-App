import React, { useState } from 'react';
import { View, TextInput, Button, Text, Alert, StyleSheet } from 'react-native';
import { register } from '../services/projectService';

const Register = ({ navigation }) => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [photo, setPhoto] = useState('');

  // El role es fijo y no editable
  const role = 'entrepreneur';

  const handleRegister = async () => {
    try {
      // Añadir el campo password_confirmation
      const data = {
        name,
        email,
        password,
        password_confirmation: password,  // Añadir la confirmación de la contraseña
        role,
        photo
      };
      
      // Verificar los datos antes de enviarlos
      console.log("Datos a enviar al backend:", data);
      
      // Realiza la solicitud de registro
      const response = await register(data);
  
      // Si todo va bien, muestra un mensaje de éxito
      Alert.alert('Success', 'Registered successfully');
      console.log('User:', response.user);
      console.log('Token:', response.access_token);
  
      // Navegar a la pantalla de login
      navigation.navigate('Login');
    } catch (error) {
      if (error.response) {
        // Mostrar detalles del error del servidor
        console.log("Error de validación:", error.response.data);
        Alert.alert('Error', `Validation Error: ${JSON.stringify(error.response.data.error)}`);
      } else {
        console.log("Error desconocido:", error);
        Alert.alert('Error', 'Error during registration');
      }
    }
  };
  

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Register</Text>

      <TextInput
        style={styles.input}
        placeholder="Name"
        value={name}
        onChangeText={setName}
      />

      <TextInput
        style={styles.input}
        placeholder="Email"
        value={email}
        onChangeText={setEmail}
        keyboardType="email-address"
      />

      <TextInput
        style={styles.input}
        placeholder="Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />

      <TextInput
        style={styles.input}
        placeholder="Photo URL"
        value={photo}
        onChangeText={setPhoto}
      />

      <Text style={styles.fixedText}>Role: {role}</Text>

      <Button title="Register" onPress={handleRegister} color="#578E7E" />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
    backgroundColor: '#F3F4F6',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#578E7E',
    marginBottom: 20,
  },
  input: {
    width: '100%',
    height: 50,
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 10,
    paddingHorizontal: 10,
    marginBottom: 15,
    backgroundColor: '#fff',
  },
  fixedText: {
    width: '100%',
    fontSize: 16,
    color: '#333',
    marginBottom: 20,
    textAlign: 'left',
  },
});

export default Register;
