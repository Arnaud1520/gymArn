<template>
    <section class="hero">
      <img src="@/assets/loginbg1.jpg" alt="Boxeur" class="hero-img" />
      <div class="wave"></div> <!-- L'élément pour l'effet de vague -->
      <div class="hero-text">
        <h1>
          BAS-TOI <br />
          COMME UN <span>CHAMPION</span>
        </h1>
        <p class="hero-subtext">
          Transforme-toi. Dépasse-toi. Le moment, c’est maintenant.
        </p>
        <button class="hero-button">Je démarre</button>
      </div>
    </section>
  </template>
  
  <style scoped>
  .hero {
    position: relative;
    height: 600px;
    width: 100%;
    overflow: hidden;
    background-color: black;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .hero-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.6;
  }
  
  .wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px; /* Hauteur de la vague */
    background: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1200 120%22 preserveaspectratio=%22none%22%3E%3Cpath d=%22M0 37c144 13 350 29 555 21 194-8 408-50 615 29 92 32 131 101 140 156h-1210z%22 fill=%22%233668E8%22/%3E%3C/svg%3E') no-repeat center bottom;
    background-size: 100% 100%; /* Faire en sorte que la vague prenne toute la largeur */
  }
  
  .hero-text {
    position: relative;
    z-index: 1;
    color: white;
    text-align: center;
    padding: 0 20px;
  }
  
  .hero h1 {
    font-size: 56px;
    font-weight: bold;
    line-height: 1.2;
    margin-bottom: 20px;
  }
  
  .hero h1 span {
    color: #3668E8;
  }
  
  .hero-subtext {
    font-size: 18px;
    margin-bottom: 30px;
    color: #f0f0f0;
  }
  
  .hero-button {
    background-color: #3668E8;
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .hero-button:hover {
    background-color: #2f5bd1;
  }
  </style>
  