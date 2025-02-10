import React, { useState } from "react";
import {
  View,
  TextInput,
  StyleSheet,
  Text,
  TouchableOpacity,
  ActivityIndicator, 
} from "react-native";
import { auth } from "../../firebase/config";
import { signInWithEmailAndPassword } from "firebase/auth";

export default function LoginScreen({ navigation }) {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false); 

  const handleLogin = async () => {
    try {
      setLoading(true); 
      const cleanedEmail = email.trim();

      if (cleanedEmail === "") {
        alert("Veuillez entrer un email valide.");
        return;
      }
      await signInWithEmailAndPassword(auth, cleanedEmail, password);
      alert("Connexion réussie");
      navigation.navigate("Home");
    } catch (error) {
      alert(error.message);
    } finally {
      setLoading(false); // Désactivation du chargement
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Login</Text>
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
      <TouchableOpacity style={styles.button} onPress={handleLogin} disabled={loading}>
        {loading ? (
          <ActivityIndicator size="small" color="#fff" /> // Spinner pendant le chargement
        ) : (
          <Text style={styles.buttonText}>Se connecter</Text>
        )}
      </TouchableOpacity>
      <Text onPress={() => navigation.navigate("Register")} style={styles.link}>
        Pas encore de compte ? S'inscrire
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
