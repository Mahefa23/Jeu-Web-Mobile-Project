import React, { useState } from 'react';
import { Text, View, StyleSheet, Button, ActivityIndicator, TouchableOpacity } from 'react-native';
import { getDatabase, ref, push, set } from 'firebase/database';
import { getAuth } from 'firebase/auth';

const PaiementScreen = ({ route, navigation }) => {
  const { plat, quantity, comments } = route.params;
  const totalPrice = plat.prix * quantity;
  
  const [isLoading, setIsLoading] = useState(false);

  const handlePayment = async () => {
    const auth = getAuth();
    const user = auth.currentUser;

    if (!user) {
      console.error('Utilisateur non connecté');
      return;
    }

    const paymentData = {
      plat: plat.name,
      quantity: quantity,
      totalPrice: totalPrice,
      userId: user.uid,
      userEmail: user.email || 'Email non disponible',
      date: new Date().toISOString(),
      status: 'effectué', 
    };

    try {
      setIsLoading(true); 
      const db = getDatabase();
      const paymentRef = push(ref(db, 'paiement'));
      await set(paymentRef, paymentData);

      console.log('Paiement effectué et enregistré dans Firebase');
      navigation.navigate('Confirmation', { plat, quantity, totalPrice });
    } catch (error) {
      console.error('Erreur lors de l\'enregistrement du paiement:', error);
    } finally {
      setIsLoading(false); 
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Récapitulatif de la commande</Text>
      <Text>Plat: {plat.name}</Text>
      <Text>Quantité: {quantity}</Text>
      <Text>Commentaires: {comments}</Text>
      <Text>Total: {totalPrice} Ar</Text>
      
      {isLoading ? (
        <ActivityIndicator size="large" color="#E18490" />
      ) : (
        <TouchableOpacity style={styles.button} onPress={handlePayment}>
          <Text style={styles.buttonText}>Payer</Text>
        </TouchableOpacity>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    backgroundColor: '#fff',
    marginTop: 45,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 16,
    textAlign: 'center',
    marginTop: 70,
  },
  button: {
    marginTop: 20,
    backgroundColor: '#E18490',
    paddingVertical: 12,
    paddingHorizontal: 20,
    borderRadius: 5,
    alignItems: 'center',
  },
  buttonText: {
    color: 'white',
    fontSize: 18,
    fontWeight: 'bold',
  }
});

export default PaiementScreen;
