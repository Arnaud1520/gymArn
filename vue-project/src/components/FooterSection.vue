<template>
  <footer class="footer">
    <h3>PRÊT POUR LA <span>PROCHAINE</span> LEÇON ?</h3>
    
    <div class="footer-separator"></div>

    <div class="footer-bottom">
      <p>📧 info@gymarn.com</p>
      <p><a href="#">Politique de confidentialité</a> – <a href="#">Conditions générales</a></p>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: #121212;
  color: #ffffff;
  text-align: center;
  padding: 40px 20px;
  font-family: 'Segoe UI', sans-serif;
  box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.4);
}

.footer h3 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 15px;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.footer span {
  color: #3668E8; /* Jaune FitnessPark */
}

.footer-separator {
  width: 80px;
  height: 4px;
  background-color: #3668E8;
  margin: 0 auto 25px auto;
  border-radius: 2px;
}

.footer-bottom p {
  margin: 8px 0;
  font-size: 14px;
}

.footer-bottom a {
  color: #3668E8;
  text-decoration: none;
  transition: color 0.3s;
}

.footer-bottom a:hover {
  color: #3668E8;
}
</style>
