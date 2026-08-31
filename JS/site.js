/* ============================================================
   CANRISK — site.js
   Funciones compartidas por todas las páginas informativas:
   - Resalta la página actual en la navbar y en el sidebar.
   - Muestra un texto junto a la foto de perfil cuando el
     usuario ya inició sesión (lee "userSession" de localStorage).
   - Configura el botón de cambio de idioma (ES <-> EN).
   ============================================================ */

(function () {
  "use strict";

  // Mapa ES <-> EN. Las rutas son relativas a la raíz del proyecto.
  var LANG_MAP = {
    "index.html": "HTML/INGLES/PrincipalING.html",
    "principal.html": "HTML/INGLES/PrincipalING.html",
    "aboutus.html": "HTML/INGLES/aboutusENG.html",
    "contacto.html": "HTML/INGLES/ContactoING.html",
    "cancer-intro.html": "HTML/INGLES/cancer-introING.html",
    "cancer.html": "HTML/INGLES/CancerING.html",
    "psycho-help.html": "HTML/INGLES/psychohelpING.html",
    "help.html": "HTML/INGLES/helpING.html",
    "ficha1.html": "HTML/INGLES/helpING.html",
    "quizz.html": "HTML/INGLES/quizzING.html",
    "faq.html": "HTML/INGLES/faqING.html",
    "Index.html": "INICIO/Index.html",
    "Faq.N.html": "INICIO/Faq.N.html",

    "principaling.html": "HTML/ESPANOL/Principal.html",
    "aboutuseng.html": "HTML/ESPANOL/aboutus.html",
    "contactoing.html": "HTML/ESPANOL/Contacto.html",
    "cancer-introing.html": "HTML/ESPANOL/cancer-intro.html",
    "cancering.html": "HTML/ESPANOL/cancer.html",
    "psychohelping.html": "HTML/ESPANOL/psycho-help.html",
    "helping.html": "HTML/ESPANOL/help.html",
    "quizzing.html": "HTML/ESPANOL/quizz.html",
    "faqing.html": "HTML/ESPANOL/faq.html",
    "IndexING.html": "INICIO/IndexING.html",
    "Faq.N-ING.html": "INICIO/Faq.N-ING.html",
  };

  function currentBasename() {
    var path = window.location.pathname;
    var last = path.substring(path.lastIndexOf("/") + 1);
    try { last = decodeURIComponent(last); } catch (e) { /* noop */ }
    return last.toLowerCase() || "index.html";
  }

  function rootPrefix() {
    return /\/HTML\/(ESPANOL|INGLES)\//i.test(window.location.pathname) ? "../../" : "";
  }

  /* ---------- 1. Resaltar la página actual (navbar + sidebar) ---------- */
  function highlightActivePage() {
    var current = currentBasename();

    document.querySelectorAll(".Info-nav a, .sidebar-list a").forEach(function (a) {
      var href = a.getAttribute("href");
      if (!href) return;
      var base = href.substring(href.lastIndexOf("/") + 1).toLowerCase();
      if (base === current) {
        a.classList.add("active");
        var boxII = a.closest(".box-II");
        if (boxII) boxII.classList.add("active");
      }
    });
  }

  /* ---------- 2. Texto de sesión junto a la foto de perfil ---------- */
  function setupSessionIndicator() {
    var photo = document.querySelector(".Photo");
    if (!photo) return;

    var session = null;
    try { session = JSON.parse(localStorage.getItem("userSession")); } catch (e) { /* noop */ }
    if (!session || !session.username) return;

    var isEnglish = (document.documentElement.lang || "es").toLowerCase().indexOf("en") === 0;

    var wrapper = document.createElement("button");
    wrapper.type = "button";
    wrapper.className = "session-text show";
    wrapper.title = isEnglish ? "Click to log out" : "Haz clic para cerrar sesión";

    var greeting = document.createElement("span");
    greeting.className = "session-greeting";
    greeting.textContent = isEnglish ? "Signed in as" : "Sesión iniciada como";

    var user = document.createElement("span");
    user.className = "session-user";
    user.textContent = session.username;

    var logout = document.createElement("span");
    logout.className = "session-logout";
    logout.textContent = isEnglish ? "Log out" : "Cerrar sesión";

    wrapper.appendChild(greeting);
    wrapper.appendChild(user);
    wrapper.appendChild(logout);

    wrapper.addEventListener("click", function () {
      localStorage.removeItem("userSession");
      window.location.reload();
    });

    photo.appendChild(wrapper);
  }

  /* ---------- 3. Botón de cambio de idioma ---------- */
  function setupLangSwitch() {
    var btn = document.getElementById("langSwitch");
    if (!btn) return;

    var current = currentBasename();
    var target = LANG_MAP[current];

    if (!target) {
      var isEnglish = (document.documentElement.lang || "es").toLowerCase().indexOf("en") === 0;
      target = isEnglish ? "HTML/ESPANOL/Principal.html" : "HTML/INGLES/PrincipalING.html";
    }

    btn.setAttribute("href", rootPrefix() + target);
    btn.textContent = target.indexOf("/INGLES/") !== -1 ? "EN" : "ES";
  }

  document.addEventListener("DOMContentLoaded", function () {
    highlightActivePage();
    setupSessionIndicator();
    setupLangSwitch();
  });

  function setupLangSwitchNL() {
    var btn = document.getElementById("langSwitch");
    if (!btn) return;

    var current = currentBasename();
    var target = LANG_MAP[current];

    if(!target) {
      var isEnglish = (document.documentElement.lang || "es").toLowerCase().indexOf("en") === 0;
      target = isEnglish ? "INICIO/index.html" : "INICIO/IndexING.html";
    }

    btn.setAttribute("href", rootPrefix() + target);
    btn.textContent = target.indexOf("/INGLES/") !== -1 ? "EN" : "ES";
  }
})();
