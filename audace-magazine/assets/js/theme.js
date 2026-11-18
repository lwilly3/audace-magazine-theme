/* Thème Audace Magazine : menu, mode sombre, barres de l'article, partage.
   Sans JavaScript, le site reste entièrement lisible ; seul le menu du téléphone demande ce script. */
(function () {
  var racine = document.documentElement;

  // Mode sombre : le choix du lecteur, sinon le réglage de l'appareil (posé dans l'en-tête avant l'affichage).
  document.querySelectorAll('[data-theme-bascule]').forEach(function (b) {
    b.addEventListener('click', function () {
      var t = racine.dataset.theme === 'sombre' ? 'clair' : 'sombre';
      racine.dataset.theme = t;
      try { localStorage.setItem('audace-theme', t); } catch (e) {}
    });
  });

  // Menu du téléphone et de la tablette.
  var tiroir = document.getElementById('tiroir');
  var ouvrir = document.querySelector('.entete .menu');
  if (tiroir && ouvrir) {
    tiroir.hidden = false;
    var basculer = function (ouvert) {
      tiroir.classList.toggle('ouvert', ouvert);
      ouvrir.setAttribute('aria-expanded', String(ouvert));
      document.body.style.overflow = ouvert ? 'hidden' : '';
      if (ouvert) tiroir.querySelector('.fermer').focus();
      else ouvrir.focus();
    };
    ouvrir.addEventListener('click', function () { basculer(true); });
    tiroir.querySelector('.fermer').addEventListener('click', function () { basculer(false); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && tiroir.classList.contains('ouvert')) basculer(false);
    });
  }

  // Copier le lien ; sur téléphone, la feuille de partage du système quand elle existe.
  document.querySelectorAll('[data-copier]').forEach(function (b) {
    b.addEventListener('click', function () {
      var url = b.dataset.copier || location.href;
      var fin = function () { b.classList.add('copie'); setTimeout(function () { b.classList.remove('copie'); }, 1600); };
      if (b.dataset.partage && navigator.share) {
        navigator.share({ title: b.dataset.partage, url: url }).catch(function () {});
      } else if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(fin, fin);
      }
    });
  });

  // Article : barre de progression, barre de partage, en-tête qui s'efface à la descente.
  var corps = document.querySelector('.article .corps');
  if (corps) {
    var barre = document.querySelector('.progression');
    var partage = document.querySelector('.barre-partage');
    var tete = document.querySelector('.entete');
    var dernier = window.scrollY;
    var attente = false;
    var suivre = function () {
      attente = false;
      var r = corps.getBoundingClientRect();
      var fait = Math.min(1, Math.max(0, (window.innerHeight * 0.5 - r.top) / r.height));
      if (barre) barre.style.transform = 'scaleX(' + fait + ')';
      if (partage) partage.classList.toggle('visible', r.top < window.innerHeight * 0.4 && fait < 1);
      if (tete) {
        if (window.scrollY > dernier + 6 && window.scrollY > 160) tete.classList.add('efface');
        else if (window.scrollY < dernier - 6 || window.scrollY <= 160) tete.classList.remove('efface');
      }
      dernier = window.scrollY;
    };
    window.addEventListener('scroll', function () {
      if (!attente) { attente = true; requestAnimationFrame(suivre); }
    }, { passive: true });
    suivre();
  }
})();
