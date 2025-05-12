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
import Testimonials from '@/components/Home/Testimonials.vue'
import HeaderSection from '../components/HeaderSection.vue'
import AboutSection from '../components/Home/AboutSection.vue'
import CategoriesSection from '../components/Home/CategoriesSection.vue'
import HeroSection from '../components/Home/HeroSection.vue'
</script>

<style scoped>
.home {
  font-family: 'Arial', sans-serif;
}
</style>
