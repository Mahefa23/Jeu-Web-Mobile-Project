<template>
  <div class="login">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="user.email" required />
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="user.password" required />
      </div>
      <button type="submit" :disabled="isLoading">
        <span v-if="isLoading" class="spinner"></span>
        Login
      </button>
    </form>
  </div>
</template>


<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const apiUrl = import.meta.env.VITE_API_URL;
const user = ref({
  email: '',
  password: ''
});
const isLoading = ref(false);
const router = useRouter();

// Fonction de connexion
const login = async () => {
  isLoading.value = true;
  try {
    const response = await axios.post(`${apiUrl}/api/auth/login`, user.value);
    console.log('Réponse complète de l\'API :', response.data);  

    if (response.data.message === 'Login successful') {
      
      localStorage.setItem('user_id', response.data.user.id); 

      const roles = response.data.user.roles;
      if (roles && roles.includes('ROLE_ADMIN')) {
        router.push('/dashboard');
      } else {
        router.push('/frontoffice');
      }
    } else {
      alert('Échec de la connexion');
    }
  } catch (error) {
    alert('Erreur, veuillez réessayer');
    console.error(error);
  } finally {
    isLoading.value = false;
  }
};


</script>

<style scoped>
.login {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-family: Arial, Helvetica, sans-serif;
}

.login h2 {
  color: #141414;
  text-align: center;
  font-weight: 700;
}

form div {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #141414;
  font-weight: 500;
}

input {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.5rem;
  border-radius: 4px;
  border: 1px solid #ddd;
}

input:focus {
  border: none;
  outline: none;
}

button {
  width: 100%;
  padding: 0.75rem;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  position: relative;
}

button:hover {
  background-color: #45a049;
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
</style>
