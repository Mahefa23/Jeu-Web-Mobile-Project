<template>
    <div class="edit-plat">
      <h1>Modifier le Plat</h1>
      <form @submit.prevent="updatePlat">
        <div>
          <label for="name">Nom du plat</label>
          <input type="text" id="name" v-model="plat.name" required />
        </div>
        <div>
          <label for="cooking_time">Temps de cuisson (en secondes)</label>
          <input type="number" id="cooking_time" v-model="plat.cooking_time" required />
        </div>
        <div>
          <label for="prix">Prix (en Ar)</label>
          <input type="number" id="prix" v-model="plat.prix" required />
        </div>
        
        <!-- Section des ingrédients -->
        <div>
  <label>Ingrédients</label>
  <div class="ingredients-container">
    <div v-for="ingredient in ingredients" :key="ingredient.id" class="ingredient-item">
      <input
        type="checkbox"
        :value="ingredient.id"
        v-model="plat.ingredients"
        class="ingredient-checkbox"
      />
      <span class="ingredient-name">{{ ingredient.name }}</span>
    </div>
  </div>
</div>
  
        <button type="submit">Mettre à jour</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  
  const apiUrl = import.meta.env.VITE_API_URL;
  const router = useRouter();
  const route = useRoute();
  const plat = ref({
    name: '',
    cooking_time: '',
    recette: '',
    prix: '',
    ingredients: [] 
  });
    const ingredients = ref([]);
    
  // Récupérer les détails du plat à modifier
  const fetchPlat = async () => {
  const platId = route.params.id;
  try {
    const response = await axios.get(`${apiUrl}/api/plat/${platId}`);
    plat.value = response.data;
    plat.value.ingredients = plat.value.ingredients.map(ingredient => ingredient.id);
  } catch (error) {
    console.error('Erreur lors de la récupération du plat:', error);
  }
};

  
  // Récupérer la liste des ingrédients disponibles
  const fetchIngredients = async () => {
    try {
      const response = await axios.get(`${apiUrl}/api/ingredient/`);
      ingredients.value = response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des ingrédients:', error);
    }
  };
  
  onMounted(() => {
    fetchPlat();
    fetchIngredients();
  });
  
  // Fonction pour mettre à jour le plat
  const updatePlat = async () => {
  const platId = route.params.id;
  try {
    console.log(plat.value.ingredients); 
    await axios.put(`http://172.50.104.26:8000/api/plat/${platId}/edit`, plat.value);
    alert('Plat mis à jour avec succès!');
    router.push({ name: 'plats' });
  } catch (error) {
    console.error('Erreur lors de la mise à jour du plat:', error);
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
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f9f9f9;
    font-family: 'Arial', sans-serif;
  }
  
  .edit-plat {
    padding: 40px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 0 auto;
    width: 100%;
    font-family: Arial, Helvetica, sans-serif;
    margin-top: 50px;
  }
  
  h1 {
    text-align: center;
    color: #333;
    font-size: 24px;
    margin-bottom: 30px;
  }
  
  form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  form div {
    display: flex;
    flex-direction: column;
  }
  
  form label {
    font-size: 16px;
    margin-bottom: 8px;
    color: #555;
  }
  
  form input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.3s ease;
  }
  
  
  form input:focus {
    border-color: #16a085;
    box-shadow: 0 0 5px rgba(22, 160, 133, 0.5);
  }
  
  button {
    padding: 12px;
    background-color: #16a085;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }
  
  button:hover {
    background-color: #1abc9c;
    transform: scale(1.05);
  }
  
  button:active {
    transform: scale(1);
  }
  
  .ingredients-container span {
    color:#1414145b;
  }
  
  @media (max-width: 768px) {
    .edit-plat {
      padding: 20px;
      margin: 20px;
    }
  
    form {
      gap: 15px;
    }
  
    form input,
    button {
      font-size: 14px;
    }
  }
  </style>
  