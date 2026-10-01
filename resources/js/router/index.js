import { createRouter, createWebHistory } from "vue-router";
import Login from "@/views/Login.vue";
import Dashboard from "@/views/Dashboard.vue";
import { getAuth, onAuthStateChanged } from "firebase/auth";
import { auth } from "@/firebase";

// Kiểm tra trạng thái đăng nhập và chuyển hướng nếu chưa đăng nhập
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/login",
      name: "login",
      component: Login,
      meta: { requiresAuth: false }
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: Dashboard,
      meta: { requiresAuth: true }
    }
  ]
});

// Guard để kiểm tra quyền truy cập cho các route yêu cầu đăng nhập
router.beforeEach((to, from, next) => {
  const user = getAuth().currentUser;
  if (to.matched.some(record => record.meta.requiresAuth) && !user) {
    next({ name: "login" }); // Nếu chưa đăng nhập, chuyển hướng đến trang login
  } else {
    next();
  }
});

export default router;
