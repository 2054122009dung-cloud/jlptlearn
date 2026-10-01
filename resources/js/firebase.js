// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAuth, signInWithPopup, GoogleAuthProvider } from "firebase/auth";  // Đảm bảo có getAuth
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyA2Xy5XzW7V51SVgaKAb5agPg1E-Ghy6vQ",
  authDomain: "jlptlearn-e72e3.firebaseapp.com",
  projectId: "jlptlearn-e72e3",
  storageBucket: "jlptlearn-e72e3.firebasestorage.app",
  messagingSenderId: "428439685765",
  appId: "1:428439685765:web:7bb3c20902abf934eb77ec",
  measurementId: "G-4Q6F8TK2SE"
};

// Initialize Firebase
// Khởi tạo Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

// Hàm đăng nhập với Google
window.loginWithGoogle = function () {
    signInWithPopup(auth, provider)
        .then((result) => {
            result.user.getIdToken().then(idToken => {
                fetch("/firebase-login", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ idToken })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert("Đăng nhập thất bại!");
                    }
                });
            });
        })
        .catch(error => {
            console.error("Lỗi đăng nhập:", error);
        });
};
