<template>
    <section class="testimonials">
      <h2 class="section-title">Ils nous font confiance</h2>
  
      <div class="testimonials-grid">
        <div v-for="testimonial in testimonials" :key="testimonial.id" class="testimonial-card">
          <p class="quote">"{{ testimonial.text }}"</p>
          <div class="author-info">
            <img :src="testimonial.image" :alt="testimonial.name" class="author-img" />
            <div>
              <h4>{{ testimonial.name }}</h4>
              <span class="role">{{ testimonial.role }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const testimonials = [
    {
      id: 1,
      name: "Thomas L.",
      role: "Étudiant motivé",
      text: "Grâce à Gymarn, j’ai retrouvé confiance en moi et progressé comme jamais.",
      image: "https://randomuser.me/api/portraits/men/32.jpg",
    },
    {
      id: 2,
      name: "Sophie M.",
      role: "Coach certifiée",
      text: "Une plateforme simple, claire et efficace. J’y accompagne mes élèves tous les jours.",
      image: "https://randomuser.me/api/portraits/women/45.jpg",
    },
    {
      id: 3,
      name: "Alex R.",
      role: "Débutant motivé",
      text: "Je ne savais pas par où commencer, Gymarn m’a donné un vrai cadre et des conseils clairs.",
      image: "https://randomuser.me/api/portraits/men/58.jpg",
    },
  ];
  </script>
  
  <style scoped>
  .testimonials {
    background-color: #fff;
    padding: 80px 30px;
  }
  
  .section-title {
    text-align: center;
    font-size: 32px;
    margin-bottom: 50px;
    color: #3668e8;
    position: relative;
  }
  
  .section-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 3px;
    background-color: #3668e8;
    margin: 10px auto 0;
  }
  
  .testimonials-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
  }
  
  .testimonial-card {
    background-color: #f9f9f9;
    border: 1px solid #e0e0e0;
    padding: 25px;
    width: 320px;
    transition: transform 0.3s ease;
  }
  
  .testimonial-card:hover {
    transform: translateY(-5px);
  }
  
  .quote {
    font-style: italic;
    margin-bottom: 20px;
    color: #333;
  }
  
  .author-info {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .author-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #3668e8;
  }
  
  .role {
    font-size: 14px;
    color: #777;
  }
  </style>
  