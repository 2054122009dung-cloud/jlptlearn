<template>
    <div>
      <h2>Chào mừng bạn đến với trang Dashboard</h2>
      <button @click="logout">Đăng xuất</button>
    </div>
  </template>

  <script>
  import { signOut } from "@/firebase";
  import { useRouter } from "vue-router";

  export default {
    methods: {
      async logout() {
        try {
          await signOut();
          this.$router.push({ name: "login" }); // Chuyển hướng về trang login sau khi đăng xuất
        } catch (error) {
          console.error("Lỗi đăng xuất:", error);
        }
      }
    }
  };
  </script>
