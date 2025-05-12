<template>
    <section class="about-us">
      <div class="about-left"></div>
      <div class="about-right">
        <h2>Qui sommes-nous ?</h2>
        <div class="about-divider"></div>
        <p>
          Bienvenue chez <strong>GYMARN</strong>, la plateforme dédiée à votre transformation physique.
          Notre mission : vous motiver, vous encadrer et vous faire progresser à votre rythme.
        </p>
        <p>
          <strong>GYMARN</strong>, c’est l’alliance de coachs passionnés, de programmes personnalisés et d’une communauté engagée.
          Rejoignez-nous pour écrire votre propre histoire de dépassement.
        </p>
      </div>
    </section>
  </template>
  
  <style scoped>
  .about-us {
    display: flex;
    min-height: 500px;
    width: 100%;
  }
  
  .about-left {
    flex: 1;
    background-image: url('@/assets/bg3.jpg');
    background-size: cover;
    background-position: center;
    clip-path: polygon(0 0, 100% 0, 80% 100%, 0% 100%);
  }
  
  .about-right {
    flex: 1;
    padding: 60px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  
  .about-right h2 {
    font-size: 36px;
    margin-bottom: 10px;
    color: #3668e8;
  }
  
  .about-divider {
    width: 60px;
    height: 3px;
    background-color: #3668e8;
    margin-bottom: 30px;
  }
  
  .about-right p {
    font-size: 18px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 15px;
  }
  </style>
  