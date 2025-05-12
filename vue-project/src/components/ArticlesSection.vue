<template>
    <section class="articles">
      <!-- Vague au-dessus -->
      <div class="wave"></div>
  
      <h2 class="section-title">Nos derniers articles</h2>
      <div class="title-underline"></div>
  
      <div class="articles-grid">
        <div v-for="article in articles" :key="article.id" class="article-card">
          <h3>{{ article.title }}</h3>
          <div class="card-underline"></div>
          <p>{{ article.description }}</p>
          <a href="#" class="read-more">Lire plus</a>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const articles = [
    {
      id: 1,
      title: "5 astuces pour rester motivé à la salle",
      description:
        "Découvrez nos conseils pratiques pour ne jamais perdre votre motivation à l'entraînement.",
    },
    {
      id: 2,
      title: "La nutrition du sportif débutant",
      description:
        "Comprenez les bases de la nutrition pour booster vos performances dès vos débuts.",
    },
    {
      id: 3,
      title: "Programme full-body ou split ?",
      description:
        "Nous comparons les deux approches pour vous aider à choisir le bon programme d'entraînement.",
    },
    {
      id: 4,
      title: "Comment bien s’échauffer avant une séance",
      description:
        "L’échauffement est essentiel pour éviter les blessures. Voici comment bien le faire.",
    },
    {
      id: 5,
      title: "Top 10 des exercices pour les abdos",
      description:
        "Sculptez votre sangle abdominale avec ces exercices incontournables.",
    },
    {
      id: 6,
      title: "Faut-il s’entraîner tous les jours ?",
      description:
        "On vous dit tout sur la récupération et l’importance du repos musculaire.",
    },
  ];
  </script>
  
  <style scoped>
  .articles {
    position: relative;
    padding: 100px 40px 60px;
    background-image: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url('@/assets/registerbg.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    overflow: hidden;
  }
  
  .wave {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1200 120%22 preserveaspectratio=%22none%22%3E%3Cpath d=%22M0 37c144 13 350 29 555 21 194-8 408-50 615 29 92 32 131 101 140 156h-1210z%22 fill=%22%233668E8%22/%3E%3C/svg%3E')
      no-repeat center bottom;
    background-size: 100% 100%;
    transform: rotate(180deg);
  }
  
  .section-title {
    font-size: 32px;
    color: #ffffff;
    text-align: center;
    margin-bottom: 10px;
  }
  
  .title-underline {
    width: 80px;
    height: 4px;
    background-color: #3668e8;
    margin: 0 auto 40px;
    border-radius: 2px;
  }
  
  .articles-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .article-card {
    background: white;
    color: #333;
    width: 30%;
    min-width: 300px;
    max-width: 360px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
    padding: 20px;
  }
  
  .article-card:hover {
    transform: translateY(-5px);
  }
  
  .article-card h3 {
    font-size: 20px;
    margin-bottom: 10px;
  }
  
  .card-underline {
    width: 50px;
    height: 3px;
    background-color: #3668e8;
    margin-bottom: 15px;
    border-radius: 2px;
  }
  
  .article-card p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
  }
  
  .read-more {
    color: #3668e8;
    text-decoration: none;
    font-weight: bold;
  }
  </style>
  