import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, Button, Modal, TextInput, Alert } from "react-native";
import { getUserById, updateUserBalance } from "../services/projectService"; // Importación de la función updateUserBalance

const MyProfile = ({ navigation }) => {
  const userId = 22; // ID del entrepreneur
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true); // Estado para manejar la carga

  const [modalVisible, setModalVisible] = useState(false); // Estado para manejar la visibilidad del modal
  const [amount, setAmount] = useState(""); // Estado para manejar la cantidad a retirar

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

  const handleWithdraw = async () => {
    const numericAmount = parseFloat(amount); // Convertir la cantidad ingresada a número
  
    // Validar que el monto sea positivo, no mayor al saldo disponible, y no NaN
    if (isNaN(numericAmount) || numericAmount <= 0 || numericAmount > user.balance) {
      Alert.alert("Error", "Invalid amount.");
      return;
    }
  
    // Objeto que se enviará a la API
    const balanceData = {
      id: user.id.toString(), // Convertir el ID a string
      amount: numericAmount.toString(), // Convertir el monto a string
      transaction_type: "withdrawal", // Tipo de transacción fijo
    };
  
    try {
      const response = await updateUserBalance(balanceData); // Llamada a la API
      Alert.alert("Success", `You have withdrawn €${numericAmount}`);
      setModalVisible(false);
      setAmount("");
  
      // Actualizar el balance local
      const updatedBalance = user.balance - numericAmount;
      setUser({ ...user, balance: updatedBalance });
    } catch (error) {
      Alert.alert("Error", "There was an issue processing your withdrawal.");
    }
  };
  

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

        {/* Botón para abrir el modal */}
        <Button
          title="Modify Balance"
          onPress={() => setModalVisible(true)} // Mostrar el modal al presionar el botón
        />
      </View>

      {/* Botón para navegar a EditUser y pasar los datos del usuario */}
      <Button
    title="Edit Profile"
    onPress={() => navigation.dispatch({
        ...navigation.navigate('EditUser', { user: user }),
    })}
/>

      {/* Modal */}
      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)} // Cerrar el modal al presionar el botón de retroceso
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Withdraw Balance</Text>

            {/* Campo para ingresar la cantidad a retirar */}
            <TextInput
              style={styles.input}
              placeholder="Enter amount"
              keyboardType="numeric"
              value={amount}
              onChangeText={setAmount}
            />

            <View style={styles.buttonContainer}>
              <Button title="Withdraw" onPress={handleWithdraw} />
              <Button title="Close" onPress={() => setModalVisible(false)} />
            </View>
          </View>
        </View>
      </Modal>
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
  modalOverlay: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "rgba(0, 0, 0, 0.5)", // Fondo oscuro transparente
  },
  modalContent: {
    backgroundColor: "white",
    padding: 20,
    borderRadius: 10,
    width: "80%",
    alignItems: "center",
  },
  modalTitle: {
    fontSize: 20,
    marginBottom: 10,
  },
  input: {
    height: 40,
    width: "100%",
    borderColor: "#ccc",
    borderWidth: 1,
    borderRadius: 5,
    paddingHorizontal: 10,
    marginBottom: 20,
  },
  buttonContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    width: "100%",
  },
});

export default MyProfile;
