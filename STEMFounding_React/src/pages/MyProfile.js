import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, Button } from "react-native";
import { getUserById } from "../services/projectService"; // Importación correcta

const MyProfile = ({ navigation }) => {
  const userId = 22; // ID del entrepreneur
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true); // Estado para manejar la carga

  useEffect(() => {
    const fetchUserData = async () => {
        try {
          const response = await getUserById(userId);
          console.log("API Response:", response); // Verifica la estructura de los datos
          setUser(response.data); // Ahora "response" contiene los datos del usuario
        } catch (error) {
          console.error("Error fetching user data:", error);
        } finally {
          setLoading(false); // Deja de cargar
        }
      };
      

    fetchUserData();
  }, []);

  if (loading) {
    return <Text>Loading...</Text>;
  }

  if (!user) {
    return <Text>No user data available.</Text>;
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>My Profile</Text>
      <View style={styles.card}>
        <Text style={styles.label}>Name:</Text>
        <Text style={styles.value}>{user.name}</Text>

        <Text style={styles.label}>Email:</Text>
        <Text style={styles.value}>{user.email}</Text>

        <Text style={styles.label}>Role:</Text>
        <Text style={styles.value}>{user.role}</Text>

        <Text style={styles.label}>Current Balance:</Text>
        <Text style={styles.value}>€{user.balance}</Text>
      </View>

      {/* Botón para navegar a EditUser y pasar los datos del usuario */}
      <Button 
        title="Edit Profile" 
        onPress={() => navigation.navigate('EditUser', { user: user })} 
      />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    backgroundColor: "#f9f9f9",
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 16,
    textAlign: "center",
  },
  card: {
    backgroundColor: "#fff",
    borderRadius: 8,
    padding: 16,
    elevation: 4,
  },
  label: {
    fontSize: 16,
    fontWeight: "bold",
    color: "gray",
    marginTop: 8,
  },
  value: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 8,
    color: "#333",
  },
});

export default MyProfile;
