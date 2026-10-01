import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { auth } from "./firebase";

// Lắng nghe trạng thái người dùng khi thay đổi
auth.onAuthStateChanged((user) => {
  if (user) {
    console.log("User logged in:", user);
  } else {
    console.log("User logged out");
  }
});

createApp(App)
  .use(router)
  .mount("#app");
