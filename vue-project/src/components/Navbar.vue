<template>
    <nav class="navbar">
      <div class="logo">
        <a href="/">GymArn</a>
      </div>
      <ul class="nav-links">
        <li><a href="/">Accueil</a></li>
        <li><a href="#articles">Articles</a></li>
        <li><a href="#programmes">Programmes</a></li>
        <li><a href="/login">Connexion</a></li>
        <li><a href="/register">Inscription</a></li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    name: 'Navbar'
  }
  </script>
  
  <style scoped>
   /* Navbar fixe */
   .navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background-color: #0a0a23;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 191, 255, 0.3);
  }
  
  .logo a {
    color: #00bfff;
    font-size: 24px;
    font-weight: bold;
    text-decoration: none;
  }
  
  .nav-links {
    list-style: none;
    display: flex;
    gap: 25px;
  }
  
  .nav-links a {
    color: #f0f0f0;
    text-decoration: none;
    font-size: 18px;
  }
  
  .nav-links a:hover {
    color: #00bfff;
    text-decoration: underline;
  }
  </style>
  