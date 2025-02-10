import React, { useState } from 'react';
import { Text, View, StyleSheet, TouchableOpacity, ActivityIndicator } from 'react-native';

const ConfirmationScreen = ({ route, navigation }) => {
  const { plat, quantity, totalPrice } = route.params;

  const [isLoading, setIsLoading] = useState(false);

  const handleHomeNavigation = () => {
    setIsLoading(true);  
    setTimeout(() => {
      setIsLoading(false); 
      navigation.navigate('Home');
    }, 2000);  
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Merci pour votre commande!</Text>
      <View style={styles.detailsContainer}>
        <Text style={styles.detailText}>Plat: <Text style={styles.detailValue}>{plat.name}</Text></Text>
        <Text style={styles.detailText}>Quantité: <Text style={styles.detailValue}>{quantity}</Text></Text>
        <Text style={styles.detailText}>Total payé: <Text style={styles.detailValue}>{totalPrice} €</Text></Text>
      </View>
      <Text style={styles.footerText}>Votre commande sera préparée sous peu.</Text>
      
      {isLoading ? (
        <ActivityIndicator size="large" color="#E18490" />
      ) : (
        <TouchableOpacity style={styles.button} onPress={handleHomeNavigation}>
          <Text style={styles.buttonText}>Retour à l'accueil</Text>
        </TouchableOpacity>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: '#f9f9f9',
    marginTop: 30,
    justifyContent: 'center',
  },
  title: {
    fontSize: 28,
    fontWeight: '700',
    color: '#4a4a4a',
    marginBottom: 24,
    textAlign: 'center',
  },
  detailsContainer: {
    backgroundColor: '#ffffff',
    padding: 20,
    borderRadius: 12,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowRadius: 10,
    elevation: 5,
    marginBottom: 30,
  },
  detailText: {
    fontSize: 18,
    color: '#333',
    marginBottom: 10,
  },
  detailValue: {
    fontWeight: '600',
    color: '#E18490', 
  },
  footerText: {
    fontSize: 16,
    color: '#888',
    textAlign: 'center',
    fontStyle: 'italic',
  },
  button: {
    marginTop: 20,
    padding: 12,
    backgroundColor: '#E18490',
    borderRadius: 8,
    alignItems: 'center',
  },
  buttonText: {
    fontSize: 18,
    color: '#fff',
    fontWeight: '600',
  },
});

export default ConfirmationScreen;
