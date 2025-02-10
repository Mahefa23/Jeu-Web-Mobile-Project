<template>
  <Navbar />
  <div class="ingredients">
    <h2>Gérer les Ingrédients</h2>
    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Stock</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="ingredient in ingredients" :key="ingredient.id">
          <td>{{ ingredient.name }}</td>
          <td>{{ ingredient.stock }} unités</td>
          <td class="actions">
            <button @click="editIngredient(ingredient.id)">Modifier</button>
            <button @click="deleteIngredient(ingredient.id)">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const apiUrl = import.meta.env.VITE_API_URL;
const ingredients = ref([]);
const router = useRouter();

// Fonction pour récupérer les ingrédients depuis l'API
const fetchIngredients = async () => {
  try {
    const response = await axios.get(`${apiUrl}/api/ingredient/`);
    ingredients.value = response.data;
  } catch (error) {
    console.error('Erreur lors de la récupération des ingrédients:', error);
  }
};

// Fonction pour supprimer un ingrédient
const deleteIngredient = async (id) => {
  try {
    await axios.delete(`${apiUrl}/api/ingredient/${id}/delete`);
    fetchIngredients();
    alert('Ingrédient supprimé avec succès!');
  } catch (error) {
    console.error('Erreur lors de la suppression de l\'ingrédient:', error);
  }
};

// Fonction pour rediriger vers la page de modification de l'ingrédient
const editIngredient = (id) => {
  router.push({ name: 'edit-ingredient', params: { id } });
};

onMounted(() => {
  fetchIngredients();
});
</script>

<style scoped>

h2 {
  text-align: center;
  font-size: 1.7rem;
}

/* Container styles */
.ingredients {
  padding: 55px;
  font-family: Arial, sans-serif;
  width: 75%;
  max-width: none;
  margin: 0 auto;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  margin-top: 140px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  table-layout: fixed;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

table, th, td {
  border: 1px solid #ddd;
}

th, td {
  padding: 12px;
  text-align: center;
  white-space: nowrap;
}

th {
  background-color: #16a085;
  color: white;
  font-weight: 700;
}

.actions button {
  padding: 8px 16px;
  margin: 5px;
  background-color: #16a085;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.actions button:hover {
  background-color: #1abc9c;
}

@media (max-width: 768px) {
  th, td {
    font-size: 12px;
    white-space: nowrap;
  }

  .actions button {
    padding: 5px 10px;
  }
}
</style>
