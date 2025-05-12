<template>
  <div class="home">
    <HeaderSection />
    <HeroSection />
    <CategoriesSection />
    <AboutSection />
    <ArticlesSection/>
    <Testimonials/>
    <FooterSection />
  </div>
</template>

<script setup>
import ArticlesSection from '@/components/ArticlesSection.vue'
import FooterSection from '@/components/FooterSection.vue'
import Testimonials from '@/components/Testimonials.vue'
import AboutSection from '../components/AboutSection.vue'
import CategoriesSection from '../components/CategoriesSection.vue'
import HeaderSection from '../components/HeaderSection.vue'
import HeroSection from '../components/HeroSection.vue'
</script>

<style scoped>
.home {
  font-family: 'Arial', sans-serif;
}
</style>
