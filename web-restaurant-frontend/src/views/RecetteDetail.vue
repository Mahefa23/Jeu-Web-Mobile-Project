<template>
    <Navbar />
    <div v-if="recetteData.recettes && recetteData.recettes.length > 0" class="recette-container">
      <h2>Recettes pour le plat : {{ recetteData.plat }}</h2>
      <div class="recette-grid">
        <div v-for="recette in recetteData.recettes" :key="recette.id" class="recette-card">
          <p>{{ recette.recetteText }}</p>
        </div>
      </div>
    </div>
    <div v-else class="loading-container">
      <p>Chargement des recettes...</p>
    </div>
  </template>
  
  <script>
  import Navbar from '@/components/Navbar.vue';
  const apiUrl = import.meta.env.VITE_API_URL;

  export default {
    data() {
      return {
        recetteData: {}, 
      };
    },
    created() {
      const platId = this.$route.params.id;
      this.fetchRecettes(platId);
    },
    methods: {
      async fetchRecettes(platId) {
        try {
          const response = await fetch(`${apiUrl}/api/recette/plat/${platId}`);
          if (response.ok) {
            const data = await response.json();
            this.recetteData = data;
          } else {
            console.error("Erreur lors de la récupération des recettes");
          }
        } catch (error) {
          console.error("Erreur réseau:", error);
        }
      },
    },
  };
  </script>
  
  <style scoped>

  .recette-container {
    font-family: Arial, Helvetica, sans-serif;
    padding: 50px;
    background-color: #f5f5f5;
    border-radius: 16px;
    max-width: 1200px;
    margin: 50px auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }
  
  h2 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
    letter-spacing: 1px;
  }
  
  /* Grid for Recipes */
  .recette-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
  }
  
  /* Card Styles */
  .recette-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
  }
  
  .recette-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
  }
  
  .recette-card p {
    font-size: 1.2rem;
    line-height: 1.8;
    color: #7f8c8d;
    margin-top: 10px;
    font-family: 'Arial', sans-serif;
  }
  
  /* Loading State */
  .loading-container {
    padding: 40px;
    text-align: center;
    font-size: 1.4rem;
    font-weight: 600;
    color: #16a085;
    animation: pulse 1.5s infinite ease-in-out;
  }
  
  @keyframes pulse {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0.6;
    }
  }
  </style>
  