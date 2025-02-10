import React from 'react';
import { View, Text, Image, TouchableOpacity, StyleSheet } from 'react-native';

const PlatCard = ({ plat, onPress }) => {
  return (
    <View style={styles.card}>
      <Image source={{ uri: plat.image }} style={styles.image} />
      <View style={styles.cardContent}>
        <Text style={styles.cardTitle}>{plat.name}</Text>
        <Text style={styles.cardDescription}>Temps de cuisson: {plat.cooking_time} s</Text>
        {/* <Text style={styles.cardDescription}>{plat.recette}</Text> */}
        <Text style={styles.cardPrice}>{plat.prix}Ar</Text>
        <Text style={styles.cardDescription}>
        Ingrédients : {plat.ingredients.map(ingredient => ingredient.name).join(' - ')}
      </Text>
        <TouchableOpacity onPress={onPress} style={styles.button}>
          <Text style={styles.buttonText}>Commander</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  card: {
    flexDirection: 'row',
    backgroundColor: '#f8f8f8',
    borderRadius: 8,
    marginBottom: 15,
    elevation: 3,
    overflow: 'hidden',
  },
  image: {
    width: 120,
    height: 120,
    borderTopLeftRadius: 8,
    borderBottomLeftRadius: 8,
  },
  cardContent: {
    flex: 1,
    padding: 10,
    justifyContent: 'space-between',
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
  },
  cardDescription: {
    fontSize: 14,
    color: '#777',
    marginVertical: 5,
  },
  cardPrice: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#E18490',
  },
  button: {
    backgroundColor: '#E18490',
    padding: 10,
    borderRadius: 5,
  },
  buttonText: {
    color: 'white',
    textAlign: 'center',
    fontWeight: 'bold',
  },
});

export default PlatCard;
