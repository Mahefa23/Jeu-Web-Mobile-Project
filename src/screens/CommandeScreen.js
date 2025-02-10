import React, { useState } from 'react';
import { Text, View, StyleSheet, TextInput, TouchableOpacity, ActivityIndicator, Image } from 'react-native';
import { ref, push, set } from 'firebase/database';
import { getAuth } from 'firebase/auth';
import { db } from '../../firebase/config';

const CommandeScreen = ({ route, navigation }) => {
  const { plat } = route.params;
  const [quantity, setQuantity] = useState(1);
  const [comments, setComments] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  // Calcul du prix total en fonction de la quantité
  const totalPrice = plat.prix * quantity;

  const handleOrder = async () => {
    const auth = getAuth();
    const user = auth.currentUser;

    if (!user) {
      console.error("Utilisateur non connecté");
      return;
    }

    const commandeData = {
      plat: plat.name,
      quantite: quantity,
      prix_total: totalPrice,
      ingredients: plat.ingredients.map(ingredient => ingredient.name),
      date: new Date().toISOString(),
      client_id: user.uid,
      client_email: user.email || "Email non disponible",
      status: "en cours",
    };

    try {
      setIsLoading(true); 
      const newOrderRef = push(ref(db, 'commande'));
      await set(newOrderRef, commandeData);

      console.log('Commande ajoutée avec succès');
      navigation.navigate('Paiement', { plat, quantity, comments });
    } catch (error) {
      console.error('Erreur lors de l\'ajout de la commande:', error);
    } finally {
      setIsLoading(false); 
    }
  };

  const increaseQuantity = () => {
    setQuantity(quantity + 1);
  };

  const decreaseQuantity = () => {
    if (quantity > 1) {
      setQuantity(quantity - 1);
    }
  };

  return (
    <View style={styles.container}>
      <Image source={{ uri: plat.image }} style={styles.image} />

      <Text style={styles.title}>{plat.name}</Text>
      <Text style={styles.description}>Temps de cuisson: {plat.cooking_time} s</Text>
      <Text style={styles.description}>
        Ingrédients : {plat.ingredients.map(ingredient => ingredient.name).join(' - ')}
      </Text>
      <Text style={styles.price}>{plat.prix} Ar</Text>

      <View style={styles.quantityContainer}>
        <TouchableOpacity onPress={decreaseQuantity} style={styles.button}>
          <Text style={styles.buttonText}>-</Text>
        </TouchableOpacity>
        <TextInput
          style={styles.input}
          placeholder="Quantité"
          keyboardType="numeric"
          value={String(quantity)}
          onChangeText={(text) => setQuantity(Math.max(1, parseInt(text, 10) || 1))}
        />
        <TouchableOpacity onPress={increaseQuantity} style={styles.button}>
          <Text style={styles.buttonText}>+</Text>
        </TouchableOpacity>
      </View>

      <Text style={styles.totalPrice}>Prix total: {totalPrice} Ar</Text>

      {isLoading ? (
        <ActivityIndicator size="large" color="#E18490" />
      ) : (
        <TouchableOpacity style={styles.orderButton} onPress={handleOrder}>
          <Text style={styles.buttonText}>Passer la commande</Text>
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
    marginTop: 25,
  },
  image: {
    width: '100%',
    height: 200,
    borderRadius: 8,
    marginBottom: 16,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 10,
  },
  description: {
    fontSize: 16,
    color: '#777',
    marginBottom: 8,
  },
  price: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#E18490',
    marginBottom: 16,
  },
  quantityContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
  },
  input: {
    height: 40,
    borderColor: '#ddd',
    borderWidth: 1,
    marginHorizontal: 10,
    paddingLeft: 8,
    width: 60,
    textAlign: 'center',
  },
  button: {
    backgroundColor: '#E18490',
    padding: 10,
    borderRadius: 5,
  },
  buttonText: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
  },
  totalPrice: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 16,
  },
  orderButton: {
    backgroundColor: '#E18490',
    padding: 14,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 20,
  },
});

export default CommandeScreen;
