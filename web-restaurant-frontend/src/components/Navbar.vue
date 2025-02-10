<template>
  <header class="navbar">
    <div class="container">
      <div class="logo">
        <router-link to="/dashboard">SakafoBe</router-link>
      </div>

      <nav :class="{'navigation': true, 'open': isMenuOpen}">
        <router-link to="/ingredients" class="nav-link">Ingrédients</router-link>
        <router-link to="/plats" class="nav-link">Plats</router-link>
        <a href="#" @click="logout" class="nav-link" :disabled="isLoading">   <span v-if="isLoading" class="spinner"></span>Déconnexion</a>
      </nav>

      <div class="hamburger" @click="toggleMenu">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router'; 

const apiUrl = import.meta.env.VITE_API_URL;
const isLoading = ref(false);
const isMenuOpen = ref(false);
const router = useRouter(); 

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const logout = async () => {
  isLoading.value = true;
  try {
    const userId = localStorage.getItem('user_id');
    if (!userId) {
      console.error('Utilisateur non connecté');
      return;
    }
    await axios.post(`${apiUrl}/api/auth/logout`, {
      user_id: userId,  
    });

    localStorage.removeItem('user_id');

    router.push('/');
  } catch (error) {
    console.error('Erreur lors de la déconnexion:', error);
  }
  finally {
    isLoading.value = false;
  }
};

</script>
 
  <style scoped>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: 'Arial', sans-serif;
  }
  
  /* Navbar styles */
  .navbar {
    background-color: rgba(44, 62, 80); 
    color: #fff;
    padding: 20px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
    font-family: Arial, Helvetica, sans-serif;
  }
  
  .navbar:hover {
    background-color: rgba(44, 62, 80, 1); 
  }
  
  .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
  }
  
  .logo a {
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    text-decoration: none;
  }
  
  .navigation {
    display: flex;
    gap: 20px;
    transition: all 0.3s ease;
  }
  
  .nav-link {
    color: #fff;
    font-size: 18px;
    text-decoration: none;
    transition: color 0.3s;
  }
  
  .nav-link:hover {
    color: #16a085;
    text-decoration: underline;
    text-decoration: none;
  }
  
  .hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
  }
  
  .hamburger span {
    width: 25px;
    height: 3px;
    background-color: #fff;
    border-radius: 5px;
    transition: transform 0.3s ease;
  }
  
  .hamburger.open span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }
  
  .hamburger.open span:nth-child(2) {
    opacity: 0;
  }
  
  .hamburger.open span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
  }
  
  button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.spinner {
  position: absolute;
  left: 50%;
  top: 50%;
  margin-left: -12px;
  margin-top: -12px;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #4caf50;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
  @media (max-width: 768px) {
    .navigation {
      display: none;
      flex-direction: column;
      gap: 15px;
    }
  
    .navigation.open {
      display: flex;
    }
  
    .hamburger {
      display: flex;
    }
  
    .nav-link {
      font-size: 20px;
    }
  }
  </style>
  