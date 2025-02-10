import React, { useState } from "react";
import {
  View,
  TextInput,
  StyleSheet,
  Text,
  TouchableOpacity,
  ActivityIndicator, // Import du spinner
} from "react-native";
import { auth } from "../../firebase/config";
import { createUserWithEmailAndPassword } from "firebase/auth";

export default function RegisterScreen({ navigation }) {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false); // État de chargement

  const handleRegister = async () => {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) {
      alert("Email invalide. Veuillez entrer un email valide.");
      return;
    }
    if (password.length < 6) {
      alert("Le mot de passe doit comporter au moins 6 caractères.");
      return;
    }

    try {
      setLoading(true); // Activation du chargement
      await createUserWithEmailAndPassword(auth, email, password);
      alert("Compte créé avec succès");
      navigation.navigate("Login");
    } catch (error) {
      alert(error.message);
    } finally {
      setLoading(false); // Désactivation du chargement
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Register</Text>
      <TextInput
        style={styles.input}
        placeholder="Email"
        value={email}
        onChangeText={setEmail}
      />
      <TextInput
        style={styles.input}
        placeholder="Mot de passe"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />
      {/* Bouton personnalisé */}
      <TouchableOpacity style={styles.button} onPress={handleRegister} disabled={loading}>
        {loading ? (
          <ActivityIndicator size="small" color="#fff" /> // Spinner pendant le chargement
        ) : (
          <Text style={styles.buttonText}>S'inscrire</Text>
        )}
      </TouchableOpacity>
      <Text onPress={() => navigation.navigate("Login")} style={styles.link}>
        Déjà inscrit ? Se connecter
      </Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: "center", padding: 16 },
  input: {
    height: 40,
    borderColor: "gray",
    borderWidth: 1,
    marginBottom: 12,
    padding: 10,
  },
  link: { color: "gray", marginTop: 12 },
  button: {
    backgroundColor: "#E18490",
    padding: 12,
    borderRadius: 5,
    alignItems: "center",
    marginBottom: 12,
  },
  title: {
    fontSize: 40,
    marginBottom: 25,
    fontWeight: 600,
  },
  buttonText: {
    color: "white",
    fontSize: 16,
  },
});
