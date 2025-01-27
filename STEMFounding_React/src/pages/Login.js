import React, { useState } from 'react';
import { View, TextInput, Button, Alert } from 'react-native';
import { login } from '../services/projectService'; // Importa la función de login
import AsyncStorage from '@react-native-async-storage/async-storage'; // Asegúrate de que esto esté importado

const Login = ({ navigation, setUserLoggedIn }) => { // Recibe `setUserLoggedIn` como prop
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = async () => {
    try {
      const data = { email, password };
      const response = await login(data);

      // Almacenar el token en el estado o en AsyncStorage
      const { user, access_token } = response;
      Alert.alert('Success', 'Logged in successfully');
      console.log('User:', user);
      console.log('Token:', access_token);

      // Guardar el token en AsyncStorage
      await AsyncStorage.setItem('authToken', access_token);

      // Actualizar el estado para reflejar que el usuario está logueado
      setUserLoggedIn(true); // Cambia el estado a 'true' cuando el login sea exitoso

      // Navegar a la página MyProfile
      navigation.navigate('MyProfile');
    } catch (error) {
      Alert.alert('Error', 'Invalid credentials');
    }
  };

  return (
    <View>
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
      <Button title="Login" onPress={handleLogin} />
    </View>
  );
};

export default Login;
