<template>
    <section class="categories-wrapper">
      <div class="categories">
        <div class="category">
          <img src="@/assets/time.png" alt="Free Coach Icon" class="category-icon" />
          FREE-COACH
        </div>
        <div class="category">
          <img src="@/assets/meditation.png" alt="Yoga Icon" class="category-icon" />
          YOGA
        </div>
        <div class="category">
          <img src="@/assets/haltere.png" alt="Weights Icon" class="category-icon" />
          WEIGHTS
        </div>
      </div>
    </section>
  </template>
  
  <style scoped>
  .categories-wrapper {
    position: relative;
    top: -80px; /* chevauche la hero section */
    display: flex;
    justify-content: center;
    z-index: 2;
  }
  
  .categories {
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 12px;
    padding: 30px 40px;
    display: flex;
    gap: 50px;
    align-items: center;
  }
  
  .category {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-weight: bold;
    text-align: center;
  }
  
  .category-icon {
    width: 40px;
    height: 40px;
    margin-bottom: 10px;
  }
  </style>
  