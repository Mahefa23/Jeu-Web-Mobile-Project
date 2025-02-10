<template>
  <div class="edit-ingredient">
    <h1>Modifier l'Ingrédient</h1>
    <form @submit.prevent="updateIngredient">
      <div>
        <label for="name">Nom de l'ingrédient :</label>
        <input type="text" v-model="ingredient.name" id="name" required />
      </div>
      <div>
        <label for="stock">Stock :</label>
        <input type="number" v-model="ingredient.stock" id="stock" required />
      </div>
      <button type="submit" :disabled="isLoading">
        <span v-if="isLoading" class="spinner"></span>Mettre à jour
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";

const apiUrl = import.meta.env.VITE_API_URL;
const isLoading = ref(false);
const ingredient = ref({
  name: "",
  stock: 0,
});
const route = useRoute();
const router = useRouter();

const fetchIngredient = async () => {
  try {
    const response = await axios.get(
      `${apiUrl}/api/ingredient/${route.params.id}`
    );
    ingredient.value = response.data;
  } catch (error) {
    console.error("Erreur lors de la récupération de l'ingrédient:", error);
  }
};

onMounted(fetchIngredient);

// Fonction pour mettre à jour un ingrédient
const updateIngredient = async () => {
  isLoading.value = true;
  try {
    await axios.put(
       `${apiUrl}/api/ingredient/${route.params.id}/edit`,
      ingredient.value
    );
    router.push({ name: "ingredients" });
  } catch (error) {
    console.error("Erreur lors de la mise à jour de l'ingrédient:", error);
    alert("Erreur lors de la mise à jour");
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
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f9f9f9;
  font-family: "Arial", sans-serif;
}

.edit-ingredient {
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
form {
    gap: 15px;
  }

  form input,
  button {
    font-size: 14px;
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
  .edit-ingredient {
    padding: 20px;
    margin: 20px;
  }

}
</style>
