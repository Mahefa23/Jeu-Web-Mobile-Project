<template>
  <Navbar />
  <div class="plats">
    <h2>Gérer les Plats</h2>
    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Temps de cuisson</th>
          <th>Prix</th>
          <th>Ingrédients</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="plat in plats" :key="plat.id">
          <td class="plat">
            <router-link :to="{ name: 'recette-detail', params: { id: plat.id }}">
              {{ plat.name }}
            </router-link>
          </td>
          <td>{{ plat.cooking_time }} s</td>
          <td>{{ plat.prix }} Ar</td>
          <td>{{ plat.ingredients.join(' - ') }}</td>
          <td class="actions">
            <button @click="editPlat(plat.id)">Modifier</button>
            <button @click="deletePlat(plat.id)">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Navbar from '@/components/Navbar.vue';

const apiUrl = import.meta.env.VITE_API_URL;
const plats = ref([]);
const router = useRouter();

const fetchPlats = async () => {
  try {
    const response = await axios.get(`${apiUrl}/api/plat/`);
    plats.value = response.data.map(plat => ({
      ...plat,
      ingredients: Object.values(plat.ingredients).map(ingredient => ingredient.name) || [] 
    }));
  } catch (error) {
    console.error('Erreur lors de la récupération des plats:', error);
  }
};

const deletePlat = async (id) => {
  const confirmation = confirm('Êtes-vous sûr de vouloir supprimer ce plat ?');
  if (confirmation) {
    try {
      await axios.delete(`${apiUrl}/api/plat/${id}/delete`);
      fetchPlats();  
      alert('Plat supprimé avec succès!');
    } catch (error) {
      console.error('Erreur lors de la suppression du plat:', error);
    }
  }
};

const editPlat = (id) => {
  router.push({ name: 'edit-plat', params: { id } });
};

// Appeler la fonction au montage du composant
onMounted(() => {
  fetchPlats();
});
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
}

h2 {
  text-align: center;
}

.plats {
  padding: 55px;
  font-family: Arial, sans-serif;
  width: 100%;
  max-width: none;
  margin: 0 auto;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  margin-top: 120px;
}

.plat a {
  text-decoration: none;
  color: #405061;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  table-layout: fixed;
}

table, th, td {
  border: 1px solid #ddd;
}

td {
  font-size: .9rem;
}

th, td {
  padding: 12px;
  text-align: center;
}

th {
  background-color: #f4f4f4;
  color: #141414;
  font-weight: 700;
}

.actions button {
  padding: 5px 10px;
  margin: 5px;
  background-color: #16a085;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.actions button:hover {
  background-color: #1abc9c;
}

@media (max-width: 768px) {
  th, td {
    font-size: 12px;
  }
}
</style>
