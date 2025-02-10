<template>
  <Navbar />
  <div class="dashboard">
    <section class="stats">
      <h2>Tableau de Statistiques</h2>
      <div class="stat-table">
        <table v-if="!isLoading">
          <thead>
            <tr>
              <th>Montant total des ventes</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="sales">{{ totalSales }}Ar</td>
            </tr>
          </tbody>
        </table>
       
      </div>
    </section>

    <section class="paiements-details">
      <h3>Détails des Ventes</h3>
      <div v-if="isLoading" class="spinner-container">
        <span class="spinner"></span>
      </div>
      <div v-else>
        <div v-if="paiements.length > 0">
          <table class="paiement-table">
            <thead>
              <tr>
                <th>Plat</th>
                <th>Quantité</th>
                <th>Statut</th>
                <th>Total</th>
                <th>Utilisateur</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="paiement in paiements" :key="paiement.date">
                <td>{{ paiement.plat }}</td>
                <td>{{ paiement.quantity }}</td>
                <td>{{ paiement.status }}</td>
                <td>{{ paiement.totalPrice }}Ar</td>
                <td>{{ paiement.userEmail }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else>
          <p>Aucun paiement disponible.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '@/components/Navbar.vue';
import axios from 'axios';

const apiUrl = import.meta.env.VITE_API_URL;
const paiements = ref([]);
const totalSales = ref(0);
const isLoading = ref(true);

onMounted(async () => {
  try {
    const response = await axios.get(`${apiUrl}/api/paiements`);
    if (response.data.totalSales) {
      totalSales.value = response.data.totalSales;
    }
    if (response.data.paiements) {
      paiements.value = response.data.paiements;
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des statistiques:", error);
  } finally {
    isLoading.value = false;
  }
});
</script>

<style scoped>
.dashboard {
  font-family: Arial, sans-serif;
  padding: 20px;
  background-color: #f4f4f9;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

.stats, .paiements-details {
  margin-top: 30px;
}

.stat-table, .paiement-table {
  width: 100%;
  border-collapse: collapse;
}

.stat-table table, .paiement-table table {
  background-color: #fff;
  border-radius: 8px;
}

.stat-table th, .paiement-table th, .stat-table td, .paiement-table td {
  padding: 15px;
  text-align: left;
  font-size: 16px;
  color: #333;
}

.stat-table th, .paiement-table th {
  background-color: #16a085;
  color: white;
  font-size: 18px;
}

.stat-table td, .paiement-table td {
  background-color: #fafafa;
  border-bottom: 1px solid #ddd;
}

.paiement-table tr:nth-child(even) td {
  background-color: #f1f1f1;
}

.paiement-table td {
  font-size: 14px;
  color: #555;
}

.paiement-table tr:hover {
  background-color: #f0f0f0;
}

.sales {
  font-weight: 700;
}

.spinner-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px;
}

.spinner {
  border: 3px solid #f3f3f3;
  border-top: 3px solid #16a085;
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
  .stat-table, .paiement-table {
    font-size: 14px;
  }

  .stat-table th, .paiement-table th, .stat-table td, .paiement-table td {
    padding: 10px;
  }
}
</style>
