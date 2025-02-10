import React from 'react';
import { createStackNavigator } from '@react-navigation/stack';
import SplashScreen from '../screens/SplashScreen';  
import LoginScreen from '../screens/LoginScreen';
import RegisterScreen from '../screens/RegisterScreen';
import HomeScreen from '../screens/HomeScreen';
import CommandeScreen from '../screens/CommandeScreen';
import PaiementScreen from '../screens/PaiementScreen';
import ConfirmationScreen from '../screens/ConfirmationScreen';


const Stack = createStackNavigator();

export default function AuthNavigator() {
  return (
    <Stack.Navigator initialRouteName="Splash">
      <Stack.Screen name="Splash" component={SplashScreen} options={{ headerShown: false }} />
      <Stack.Screen name="Login" component={LoginScreen}  options={{ headerShown: false }}/>
      <Stack.Screen name="Register" component={RegisterScreen}  options={{ headerShown: false }}/>
      <Stack.Screen name="Home" component={HomeScreen}  options={{ headerShown: false }}/>
      <Stack.Screen name="Commande" component={CommandeScreen} options={{ headerShown: false }}/>
      <Stack.Screen name="Paiement" component={PaiementScreen} options={{ headerShown: false }}/>
      <Stack.Screen name="Confirmation" component={ConfirmationScreen} options={{ headerShown: false }}/>


    </Stack.Navigator>
  );
}
