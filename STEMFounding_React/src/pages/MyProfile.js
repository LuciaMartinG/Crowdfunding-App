import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, Button, Modal, TextInput, Alert } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { getUserData, updateUserBalance } from "../services/projectService"; 

const MyProfile = ({ navigation }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [modalVisible, setModalVisible] = useState(false);
  const [amount, setAmount] = useState("");

  useEffect(() => {
    const fetchUserData = async () => {
      try {
        const response = await getUserData();
        console.log(response); 
        setUser(response);
      } catch (error) {
        console.error("Error fetching user data:", error);
      } finally {
        setLoading(false);
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
    const numericAmount = parseFloat(amount);
    
    if (isNaN(numericAmount) || numericAmount <= 0 || numericAmount > user.balance) {
      Alert.alert("Error", "Invalid amount.");
      return;
    }
    
    const balanceData = {
      id: user.id.toString(),
      amount: numericAmount.toString(),
      transaction_type: "withdrawal",
    };
    
    try {
      await updateUserBalance(balanceData);
      Alert.alert("Success", `You have withdrawn €${numericAmount}`);
      setModalVisible(false);
      setAmount("");
      setUser({ ...user, balance: user.balance - numericAmount });
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

        <Button title="Modify Balance" onPress={() => setModalVisible(true)} />
      </View>

      <Button title="Edit Profile" onPress={() => navigation.navigate('EditUser', { user })} />

      <Modal animationType="slide" transparent={true} visible={modalVisible} onRequestClose={() => setModalVisible(false)}>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Withdraw Balance</Text>
            <TextInput style={styles.input} placeholder="Enter amount" keyboardType="numeric" value={amount} onChangeText={setAmount} />
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
