import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'; 
import Ingredients from '../views/Ingredients.vue';
import Plats from '../views/Plats.vue';
import EditIngredient from '../views/editIngredient.vue';
import EditPlat from '../views/editPlat.vue';
import Dashboard from '../views/dashboard.vue';
import RecetteDetail from '../views/RecetteDetail.vue';
import Frontoffice from '../views/Frontoffice.vue';

function isAuthenticated() {
  return !!localStorage.getItem('authToken');
}


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login,
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      beforeEnter: (to, from, next) => {
        if (!isAuthenticated()) {
          next('/');
        } else {
          next();
        }
      },
    },  
    
    {
      path: '/frontoffice',
      name: 'FrontOffice',
      component: Frontoffice,
      beforeEnter: (to, from, next) => {
        if (!isAuthenticated()) {
          next('/');
        } else {
          next();
        }
      },
    },
    {
      path: '/ingredients',
      name: 'ingredients',
      component: Ingredients,
    },
    {
      path: '/ingredient/:id/edit', 
      name: 'edit-ingredient',
      component: EditIngredient
    },
    {
      path: '/plats',
      name: 'plats',
      component: Plats,
    },
    {
      path: '/plats/:id/edit',  
      name: 'edit-plat',  
      component: EditPlat,     
    },
   
    {
      path: '/recettes/:id',  
      name: 'recette-detail',
      component: RecetteDetail
    },
    {
      
    }
    
  
    // {
    //   path: '/about',
    //   name: 'about',
    //   // route level code-splitting
    //   // this generates a separate chunk (About.[hash].js) for this route
    //   // which is lazy-loaded when the route is visited.
    //   component: () => import('../views/AboutView.vue'),
    // },
  ],
})

export default router
