<template>
    <header class="header">
      <div class="logo">GYMARN</div>
      <nav class="nav">
        <ul :class="{ 'is-open': isNavOpen }">
          <li><a href="#">Accueil</a></li>
          <li><a href="#">À propos</a></li>
          <li><a href="#">Locations</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </nav>
      <a href="/login-register" class="btn-login">Se connecter</a>
      <button class="btn-hamburger" @click="toggleNav" aria-label="Toggle navigation">
        <span class="hamburger-icon"></span>
      </button>
    </header>
  </template>
  
  <script>
  export default {
    data() {
      return {
        isNavOpen: false
      };
    },
    methods: {
      toggleNav() {
        this.isNavOpen = !this.isNavOpen;
      }
    }
  };
  </script>
  
  <style scoped>
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 60px;
    background: transparent;
    color: white;
    backdrop-filter: blur(2px);
  }
  
  .logo {
    font-weight: bold;
    font-size: 24px;
    color: white;
  }
  
  .nav ul {
    list-style: none;
    display: flex;
    gap: 30px;
    margin: 0;
    padding: 0;
    transition: max-height 0.3s ease-in-out;
  }
  
  .nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: 500;
  }
  
  .btn-login {
    background-color: #3668E8;
    color: white;
    padding: 10px 18px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
  }
  
  .btn-hamburger {
    display: none;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    z-index: 1000;
  }
  
  .hamburger-icon {
    width: 25px;
    height: 3px;
    background-color: white;
    display: block;
    position: relative;
    transition: 0.3s;
  }
  
  .hamburger-icon:before,
  .hamburger-icon:after {
    content: "";
    width: 25px;
    height: 3px;
    background-color: white;
    position: absolute;
    transition: 0.3s;
  }
  
  .hamburger-icon:before {
    top: -8px;
  }
  
  .hamburger-icon:after {
    top: 8px;
  }
  
  .nav ul.is-open {
    display: block;
    max-height: 500px; /* Adjust based on the number of items */
  }
  
  @media (max-width: 768px) {
    .nav ul {
      display: none;
      width: 100%;
      flex-direction: column;
      background-color: rgba(0, 0, 0, 0.8);
      position: absolute;
      top: 60px; /* Adjust based on header height */
      left: 0;
      right: 0;
      padding: 10px 0;
    }
  
    .nav ul.is-open {
      display: flex;
    }
  
    .btn-hamburger {
      display: block;
    }
  }
  </style>
  