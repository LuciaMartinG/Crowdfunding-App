import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Image, Alert } from 'react-native';
import { updateUser } from '../services/projectService'; // Importa la función updateUser

const EditUser = ({ route, navigation }) => {
  const { user } = route.params; // Obtén los datos del usuario pasado desde MyProfile.js

  // Estados para los campos de entrada
  const [name, setName] = useState(user.name || '');
  const [email, setEmail] = useState(user.email || '');
  const [password, setPassword] = useState('');
  const [photo, setPhoto] = useState(user.photo || '');

  // Función para guardar los cambios
  const handleSaveChanges = async () => {
    const updatedUser = {
      id: user.id, // Usa el ID del usuario
      name,
      email,
      password: password || undefined, // Si no se ingresa nueva contraseña, se manda undefined
      photo: photo || user.photo, // Si no se ingresa una nueva URL, usa la anterior
    };

    console.log('Datos que se envían:', updatedUser);

    try {
      const response = await updateUser(updatedUser);
      console.log('User updated successfully:', response);

      // Al actualizar correctamente, redirige a MyProfile con los datos actualizados
      navigation.navigate('MyProfile', { updatedUser }); // Enviar datos actualizados a MyProfile

      // O si prefieres ir a la pantalla anterior:
      // navigation.goBack();
    } catch (error) {
      console.error('Error updating user:', error);
      Alert.alert('Error', 'No se pudieron guardar los cambios. Por favor, inténtelo de nuevo.');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Edit Profile</Text>

      {/* Campo para el nombre */}
      <Text style={styles.label}>Name</Text>
      <TextInput
        style={styles.input}
        value={name}
        onChangeText={setName}
        placeholder="Enter your name"
      />

      {/* Campo para el correo electrónico */}
      <Text style={styles.label}>Email</Text>
      <TextInput
        style={styles.input}
        value={email}
        onChangeText={setEmail}
        placeholder="Enter your email"
        keyboardType="email-address"
      />

      {/* Campo para la foto de perfil (URL) */}
      <Text style={styles.label}>Profile Photo URL</Text>
      <TextInput
        style={styles.input}
        value={photo}
        onChangeText={setPhoto}
        placeholder="Enter profile photo URL"
      />
      {photo ? (
        <Image source={{ uri: photo }} style={styles.photo} />
      ) : (
        <Text>No photo selected</Text>
      )}

      {/* Campo para la nueva contraseña (opcional) */}
      <Text style={styles.label}>New Password (optional)</Text>
      <TextInput
        style={styles.input}
        value={password}
        onChangeText={setPassword}
        placeholder="Enter new password"
        secureTextEntry
      />

      {/* Botón para guardar los cambios */}
      <Button title="Save Changes" onPress={handleSaveChanges} />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    backgroundColor: '#f9f9f9',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 16,
    textAlign: 'center',
  },
  label: {
    fontSize: 16,
    fontWeight: 'bold',
    marginTop: 12,
    marginBottom: 8,
  },
  input: {
    height: 40,
    borderColor: '#ccc',
    borderWidth: 1,
    borderRadius: 8,
    paddingHorizontal: 10,
    marginBottom: 16,
  },
  photo: {
    width: 100,
    height: 100,
    borderRadius: 8,
    marginBottom: 16,
  },
});

export default EditUser;
