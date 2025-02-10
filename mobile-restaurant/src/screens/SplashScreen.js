import React from 'react';
import { View, StyleSheet, Button, Image } from 'react-native';

export default function SplashScreen({ navigation }) {
  return (
    <View style={styles.container}>
      {/* Logo ou Icône */}
      <Image 
        source={require('../../assets/1.png')}  
        style={styles.logo}
      />
      <Button 
        title="Get Started"
        onPress={() => navigation.navigate('Login')}  
        color="#E18490"  
        style={styles.button}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#ffff',  
  },
  logo: {
    width: 200,  
    height: 200,  
    marginBottom: 40,  
  },
  button: {
    width: '100%', 
    padding: 25,  
    backgroundColor: '#E18490', 
    borderRadius: 5,  
  },
});
