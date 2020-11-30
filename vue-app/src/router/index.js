import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/admin/Dashboard.vue'
import userDashboard from '../views/user/report.vue'
import userReports from '../views/user/ReportList.vue'
import PendingReports from '../views/admin/PedingReports.vue'
import ActiveUsers from '../views/admin/ActiveUsers.vue'
import MissingPerson from '../views/admin/MissingPerson'
import axios from "axios";
import User from '../apis/User'
import NotFound from '../views/NotFound.vue'
import store from '../store'
import MissingList from '../views/MissingPersonList'


Vue.use(VueRouter)

const routes = [
  {
    path: '/notFound',
    name: 'NotFound',
    component:NotFound
  },
        {
        path: '/missing',
        name: 'missing-list',
        component: MissingList,
         meta: { guestOnly: true }
      },
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guestOnly: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { guestOnly: true }
  },

  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { authOnly: true },
    beforeEnter: checkAdminRights,
    children: [
      {
        path: 'pending-reports',
        name: 'Pending Reports',
        component:PendingReports
      },
          {
        path: 'active-users',
        name: 'Active Users',
        component:ActiveUsers
      },
       {
        path: 'missing-person',
        name: 'Missing person',
        component:MissingPerson
      }
    ]
  },

  {
    path: '/report',
    name: 'report',
    component: userDashboard,
    meta:{authOnly:true, breadCrumb:'Report'},
    children: [
      {
        path: 'my-reports',
        name: 'My Reports',
        component: userReports,
        meta:{breadCrumb:'My-reports'}
      },

    ]
  },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

function isLoggedIn () {
  return localStorage.getItem('token')
}

function checkAdminRights(to, from, next) {
    // check if the user is admin
  User.auth()
    .then((res) => {
      if (res.data.is_admin) {
        next()
      } else {
          next({path:'/NotFound'});
      }
    });
}

router.beforeEach((to, from, next) => {
Vue.config.productionTip = false;
axios.interceptors.response.use(
  response => response,
  error => {
  if (error.response.status === 401) {
      store.commit('LOGIN', true)
      store.commit("AUTH_USER", null);
      localStorage.removeItem("token");
      router.push({ name: "Login" });
    } else {
      return Promise.reject(error);
    }
  }
);

axios.interceptors.request.use(function(config) {
  config.headers.common = {
    Authorization: `Bearer ${localStorage.getItem("token")}`,
    "Content-Type": "application/json",
    Accept: "application/json"
  };

  return config;
});


  if (to.matched.some(record => record.meta.authOnly)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!isLoggedIn()) {
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
    } else {
      next()
    }
  } else if (to.matched.some(record => record.meta.guestOnly)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (isLoggedIn()) {
      next({
        path: '/dashboard',
        query: { redirect: to.fullPath }
      })
    } else {
      next()
    }
  } else {
    next() // make sure to always call next()!
  }
})

export default router
