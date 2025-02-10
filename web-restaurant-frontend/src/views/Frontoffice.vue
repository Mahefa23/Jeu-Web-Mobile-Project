<template>
    <Navbar />
    <div class="dashboard">
      <!-- Cartes pour les liens -->
      <div class="card-container">
        <div 
          class="card" 
          :class="{ active: activeTab === 'commandes' }" 
          @click="activeTab = 'commandes'"
        >
          <h3>Liste des commandes en cours</h3>
        </div>
        <div 
          class="card" 
          :class="{ active: activeTab === 'clients' }" 
          @click="activeTab = 'clients'"
        >
          <h3>Liste des clients</h3>
        </div>
      </div>
  
      <section class="stats">
        <div v-if="activeTab === 'commandes'">
          <h2>Commandes en Cours</h2>
          <div v-if="commandesEnCours.length > 0">
            <table class="paiement-table">
              <thead>
                <tr>
                  <th>Client</th>
                  <th>Plat</th>
                  <th>Date de commande</th>
                  <th>Quantité</th>
                  <th>Statut</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="commande in commandesEnCours" :key="commande.date">
                  <td>{{ commande.client_email }}</td>
                  <td>{{ commande.plat }}</td>
                  <td>{{ formatDate(commande.date) }}</td>
                  <td>{{ commande.quantite }}</td>
                  <td>{{ commande.status }}</td>
                  <td>{{ commande.prix_total }}Ar</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else>
            <p>Aucune commande en cours.</p>
          </div>
        </div>
        <div v-if="activeTab === 'clients'">
  <h2>Liste des Clients</h2>
  <div v-if="clients.length > 0">
    <table class="paiement-table">
      <thead>
        <tr>
          <th>UID</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="client in clients" :key="client.uid">
          <td>{{ client.uid }}</td>
          <td>{{ client.email }}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div v-else>
    <p>Aucun client trouvé.</p>
  </div>
</div>
      </section>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import Navbar from '@/components/frontoffice/Navbar.vue';
  import axios from 'axios';
  
  const apiUrl = import.meta.env.VITE_API_URL;
  const commandesEnCours = ref([]);
  const clients = ref([]);
  const activeTab = ref('commandes'); 
  
// Fonction pour formater la date
function formatDate(dateString) {
  const date = new Date(dateString);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
}
  
  // Récupérer les commandes en cours
  onMounted(async () => {
    try {
      const response = await axios.get(`${apiUrl}/api/commandes`);
      if (response.data.commande) {
        commandesEnCours.value = response.data.commande;
      }
    } catch (error) {
      console.error("Erreur lors de la récupération des commandes en cours:", error);
    }
    try {
    const response = await axios.get(`${apiUrl}/api/users`);
    if (response.data.users) { 
      clients.value = response.data.users;
    } else {
      console.error("Unexpected response structure:", response.data);
    }
  } catch (error) {
    console.error("Error fetching clients:", error.response || error);
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
 
 h2 {
   margin-top: 55px;
 }
 
 .card-container {
   display: flex;
   gap: 20px;
   margin-top: 30px;
 }
 
 .card {
   flex: 1;
   padding: 20px;
   background-color: #fff;
   border-radius: 8px;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
   cursor: pointer;
   transition: background-color 0.3s ease;
 }
 
 .card:hover {
   background-color: #f0f0f0;
 }
 
 .card.active {
   background-color: #16a085;
   color: white;
 }
 
 .stats {
   margin-top: 30px;
 }
 
 .paiement-table {
   width: 100%;
   border-collapse: collapse;
 }
 
 .paiement-table th, .paiement-table td {
   padding: 15px;
   text-align: left;
   font-size: 16px;
   color: #333;
 }
 
 .paiement-table th {
   background-color: #16a085;
   color: white;
   font-size: 18px;
 }
 
 .paiement-table td {
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
 
 @media (max-width: 768px) {
   .paiement-table {
     font-size: 14px;
   }
 
   .paiement-table th, .paiement-table td {
     padding: 10px;
   }
 }
 </style>