<template>
    <div class="login-container">
      <h2>Đăng nhập</h2>
      <form @submit.prevent="handleLogin">
        <div>
          <label for="email">Email</label>
          <input type="email" v-model="email" required />
        </div>
        <div>
          <label for="password">Mật khẩu</label>
          <input type="password" v-model="password" required />
        </div>
        <button type="submit">Đăng nhập</button>
      </form>
    </div>
  </template>

  <script>
  import { signInWithEmailAndPassword } from "@/firebase";
  import { useRouter } from "vue-router";

  export default {
    data() {
      return {
        email: "",
        password: ""
      };
    },
    methods: {
      async handleLogin() {
        try {
          await signInWithEmailAndPassword(this.email, this.password);
          this.$router.push({ name: "dashboard" }); // Redirect to dashboard after login
        } catch (error) {
          console.error("Lỗi đăng nhập:", error);
          alert("Đăng nhập thất bại! Kiểm tra lại email hoặc mật khẩu.");
        }
      }
    }
  };
  </script>
