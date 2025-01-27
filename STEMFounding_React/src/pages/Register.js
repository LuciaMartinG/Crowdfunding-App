import React, { useState } from 'react';
import { View, TextInput, Button, Text, Alert } from 'react-native';
import { register } from '../services/projectService'; // Importa la función de register

const Register = ({ navigation }) => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [role, setRole] = useState('');
  const [photo, setPhoto] = useState('');

  const handleRegister = async () => {
    try {
      const data = { name, email, password, role, photo };
      const response = await register(data);

      // Si el registro es exitoso, puedes hacer login automáticamente o mostrar un mensaje
      Alert.alert('Success', 'Registered successfully');
      console.log('User:', response.user);
      console.log('Token:', response.access_token);

      // Navegar a login o directamente a la pantalla principal
      navigation.navigate('Login');  // O directamente 'Home'
    } catch (error) {
      Alert.alert('Error', 'Error during registration');
    }
  };

  return (
    <View>
      <TextInput
        placeholder="Name"
        value={name}
        onChangeText={setName}
      />
      <TextInput
        placeholder="Email"
        value={email}
        onChangeText={setEmail}
      />
      <TextInput
        placeholder="Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />
      <TextInput
        placeholder="Role"
        value={role}
        onChangeText={setRole}
      />
      <TextInput
        placeholder="Photo URL"
        value={photo}
        onChangeText={setPhoto}
      />
      <Button title="Register" onPress={handleRegister} />
    </View>
  );
};

export default Register;
