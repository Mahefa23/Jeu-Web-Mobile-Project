// Importer les modules nécessaires de Firebase
import { initializeApp } from 'firebase/app';
import { getAuth } from 'firebase/auth';
// import { getFirestore } from 'firebase/firestore';
import { getDatabase } from 'firebase/database'; 



// Config Firebase
const firebaseConfig = {
  apiKey: "AIzaSyDvXapJGj1GsNwsqdOL-DAqrJRVRHfy4BQ",
  authDomain: "mobile-restaurant-10c40.firebaseapp.com",
  projectId: "mobile-restaurant-10c40",
  storageBucket: "mobile-restaurant-10c40.firebasestorage.app",
  messagingSenderId: "960831621378",
  appId: "1:960831621378:web:81f726e4e1c21355f99a1b",
  measurementId: "G-YV3FKZ6TR5"
};

// Initialiser Firebase
const app = initializeApp(firebaseConfig);

// Obtenir l'authentification
const auth = getAuth(app);
const db = getDatabase(app); 

// Exporter les modules nécessaires
export { auth };
export {db};

