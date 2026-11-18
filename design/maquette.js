/* Maquettes Audace Magazine, troisième passe : en-tête et pied partagés,
   mode sombre, typographie des titres, partage.
   Dans le thème : en-tête et pied en PHP ; classes de titre et espaces
   insécables calculées en PHP ; seules les barres de l'article restent en JS. */
(function () {
  var racine = document.documentElement;
  var page = document.body.dataset.page || '';

  // Mode sombre : réglage du lecteur s'il existe, sinon celui de l'appareil.
  var choix = null;
  try { choix = localStorage.getItem('audace-theme'); } catch (e) {}
  var demande = new URLSearchParams(location.search).get('theme');
  if (demande === 'sombre' || demande === 'clair') choix = demande;
  racine.dataset.theme = choix || (matchMedia('(prefers-color-scheme: dark)').matches ? 'sombre' : 'clair');
  function basculer() {
    var t = racine.dataset.theme === 'sombre' ? 'clair' : 'sombre';
    racine.dataset.theme = t;
    try { localStorage.setItem('audace-theme', t); } catch (e) {}
  }

  var rubriques = ['Politique', 'Économie', 'Société', 'Diplomatie', 'Culture', 'Sport', 'Environnement'];
  var liens = rubriques.map(function (r) { return '<li><a href="rubrique.html">' + r + '</a></li>'; }).join('');
  var svg = {
    loupe: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.500-3.500"/></svg>',
    menu: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8h16M4 16h16"/></svg>',
    croix: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>',
    lune: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 14.500A8 8 0 0 1 9.500 4 8 8 0 1 0 20 14.500z"/></svg>',
    lecture: '<svg class="jouer" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.500v13l10.500-6.500z"/></svg><svg class="pause" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 5h3.500v14H7zM13.500 5H17v14h-3.500z"/></svg>'
  };
  var logo = '<img src="assets/logo-origine.png" alt="Audace Magazine" width="1024" height="399">';
  // La radio est une marque sœur : un simple lien vers son site, pas de lecteur ici.
  var lienRadio = '<a class="lien-radio" href="https://radioaudace.com/ecouter/" target="_blank" rel="noopener">Radio Audace 106.8 FM <span aria-hidden="true">↗</span></a>';

  var entete = document.querySelector('[data-entete]');
  if (entete) {
    entete.outerHTML =
      '<div class="bandeau-maquette">Maquette, troisième passe · <a href="index.html">sommaire et choix</a></div>' +
      '<header class="entete"><div class="cadre">' +
        '<a class="logo" href="accueil.html" aria-label="Audace Magazine, accueil">' + logo + '</a>' +
        '<nav class="rubriques" aria-label="Rubriques"><ul>' + liens + '</ul></nav>' +
        '<a class="icone recherche" href="recherche.html" aria-label="Rechercher">' + svg.loupe + '</a>' +
        '<button class="icone theme" data-theme-bascule aria-label="Passer en mode clair ou sombre">' + svg.lune + '</button>' +
        '<a class="bouton" href="kiosque.html"' + (page === 'kiosque' ? ' aria-current="page"' : '') + '><span class="article-le">Le&nbsp;</span>magazine</a>' +
        '<button class="icone menu" aria-label="Ouvrir le menu" aria-controls="tiroir">' + svg.menu + '</button>' +
      '</div></header>' +
      '<div class="tiroir" id="tiroir">' +
        '<div class="haut"><a class="logo" href="accueil.html">' + logo.replace('<img', '<img style="height:38px;width:auto"') + '</a>' +
          '<button class="icone fermer" aria-label="Fermer le menu">' + svg.croix + '</button></div>' +
        '<ul>' + liens + '</ul>' +
        '<div class="actions"><a class="bouton grand" href="kiosque.html">Le magazine</a></div>' +
        '<div class="secondaires">' + lienRadio + '<a href="recherche.html">Rechercher</a><a href="#">Mon compte</a><a href="#">Contact</a>' +
          '<button data-theme-bascule>Mode clair / sombre</button></div>' +
      '</div>';
    var tiroir = document.getElementById('tiroir');
    document.querySelector('.entete .menu').addEventListener('click', function () { tiroir.classList.add('ouvert'); });
    tiroir.querySelector('.fermer').addEventListener('click', function () { tiroir.classList.remove('ouvert'); });
    document.querySelectorAll('[data-theme-bascule]').forEach(function (b) { b.addEventListener('click', basculer); });
  }

  var pied = document.querySelector('[data-pied]');
  if (pied) {
    pied.className = 'pied';
    pied.innerHTML =
      '<div class="cadre">' +
        '<div class="marque"><a class="logo" href="accueil.html">' + logo + '</a>' +
          '<p>L’Afrique autrement : politique, économie et société, du Cameroun et du continent.</p>' + lienRadio + '</div>' +
        '<div><h2>Rubriques</h2><ul>' + liens + '</ul></div>' +
        '<div><h2>Le magazine</h2><ul>' +
          '<li><a href="kiosque.html">Le dernier numéro</a></li>' +
          '<li><a href="kiosque.html#numeros">Tous les numéros</a></li>' +
          '<li><a href="#">Mon compte</a></li></ul></div>' +
        '<div><h2>La rédaction</h2><ul>' +
          '<li><a href="#">À propos</a></li><li><a href="#">Contact</a></li>' +
          '<li><a href="#">Mentions légales</a></li><li><a href="#">Conditions de vente</a></li>' +
          '<li><a href="#">Données personnelles</a></li></ul></div>' +
        '<div class="bas">© 2026 Audace Media Group · Audace Magazine et Radio Audace 106.8 FM</div>' +
      '</div>';
  }

  // Titres : la taille dépend de la longueur (la largeur de colonne est gérée en CSS).
  document.querySelectorAll('h1[data-ajuste]').forEach(function (h) {
    var n = h.textContent.trim().length;
    h.classList.add(n <= 55 ? 'court' : n <= 90 ? 'moyen' : 'long');
  });
  // Typographie française : pas de ligne qui commence par « : », « ? », « ! », « ; » ou « » »,
  // et pas de coupure aux traits d'union des titres.
  function typo(n, titre) {
    if (n.nodeType === 3) {
      var t = n.nodeValue.replace(/[  ]([:;?!»])/g, ' $1').replace(/«[  ]/g, '« ');
      if (titre) t = t.replace(/(\S)-(\S)/g, '$1‑$2');
      n.nodeValue = t;
    } else if (n.nodeType === 1 && n.tagName !== 'SCRIPT' && n.tagName !== 'STYLE') {
      var estTitre = titre || /^H[1-4]$/.test(n.tagName);
      n.childNodes.forEach(function (c) { typo(c, estTitre); });
    }
  }
  var main = document.querySelector('main');
  if (main) typo(main, false);

  // Liseuse. Dans le thème, chaque page sera une image préparée à l'avance à partir du PDF,
  // chargée une à une, les deux voisines préchargées pour que la transition ne montre jamais
  // une page blanche. Ici, seule la couverture est réelle ; les autres pages sont figurées
  // à l'échelle d'une vraie page imprimée (le texte courant y est donc aussi petit).
  //
  // Deux constructions :
  // - page simple (téléphone, tablette verticale) : des planches d'une page qui glissent ;
  //   le liseré de la tranche fait partie de la planche, il bouge donc avec elle.
  // - double page (à partir de 1000 px) : un « livre » de deux moitiés. La couverture est une
  //   double page dont la moitié gauche est vide, la dernière page une double page dont la moitié
  //   droite est vide ; le livre fermé est décalé d'une demi-page pour rester centré. Tranches,
  //   dos et volet sont des enfants du livre : tout ce qui bouge bouge ensemble.
  var liseuse = document.querySelector('.liseuse');
  if (liseuse) {
    var total = parseInt(liseuse.dataset.total, 10);
    var cle = 'audace-page-' + liseuse.dataset.numero;
    var pile = liseuse.querySelector('.pile');
    var scene = liseuse.querySelector('.scene');
    var compteur = liseuse.querySelector('[data-compteur]');
    var curseur = liseuse.querySelector('[data-curseur]');
    var boutonZoom = liseuse.querySelector('[data-zoom]');
    var boutonPlein = liseuse.querySelector('[data-plein]');
    var calme = matchMedia('(prefers-reduced-motion: reduce)').matches;
    var DUREE = 280;
    var zooms = [1, 1.8, 2.8], zoom = 0, courant = 1, occupe = false;
    var titres = {};
    document.querySelectorAll('a[data-page]').forEach(function (a) {
      titres[a.dataset.page] = [a.dataset.rubrique, a.querySelector('.t').textContent];
    });
    var faux = 'Texte de démonstration, à la taille réelle d’une page imprimée. Sur un téléphone, ces lignes sont trop petites pour être lues sans agrandir : c’est ce que la liseuse doit résoudre avec le zoom. ';
    var fabriquer = function (n) {
      var p = document.createElement('div');
      p.className = 'page';
      if (n === 1) { p.innerHTML = '<img src="' + liseuse.dataset.couverture + '" alt="Couverture" draggable="false">'; return p; }
      var t = titres[n];
      p.className += ' fictive';
      p.innerHTML = (t ? '<small>' + t[0] + '</small><b>' + t[1] + '</b>' : '') +
        '<div class="colonnes">' + new Array(t ? 9 : 13).join('<p>' + faux + '</p>') + '</div><em>' + n + '</em>';
      return p;
    };
    var double = function () { return liseuse.clientWidth >= 1000; };
    // En double page : la couverture seule à droite, puis les paires 2-3, 4-5…, la dernière seule à gauche.
    var caler = function (n) {
      n = Math.max(1, Math.min(total, n));
      return double() && n > 1 && n % 2 === 1 ? n - 1 : n;
    };
    var moities = function (n) {
      if (n === 1) return { g: null, d: 1 };
      return { g: n, d: n + 1 <= total ? n + 1 : null };
    };
    var voisin = function (n, sens) {
      var v = double() ? (sens > 0 ? (n === 1 ? 2 : n + 2) : (n === 2 ? 1 : n - 2)) : n + sens;
      return v < 1 || v > total ? null : v;
    };
    var lu = function (n) { return Math.max(0, Math.min(1, (n - 1) / (total - 1))); };
    // Décalage du livre, en pourcentage de sa largeur : fermé, il est poussé d'une demi-page pour rester centré.
    var decal = function (n) { var m = moities(n); return zoom ? 0 : m.g === null ? -25 : m.d === null ? 25 : 0; };
    // Tout le contenu d'une page figurée est proportionnel à sa largeur (--u = 1 % de la page).
    var echelle = function () {
      pile.querySelectorAll('.page').forEach(function (p) {
        var l = p.offsetWidth;
        if (l) p.style.setProperty('--u', l / 100 + 'px');
      });
    };
    var remplir = function (moitie, n) {
      moitie.innerHTML = '';
      moitie.classList.toggle('vide', n === null);
      if (n !== null) moitie.appendChild(fabriquer(n));
    };
    // Le volume : épaisseur des tranches et présence du dos, pour la position n.
    var volume = function (livre, n) {
      var m = moities(n);
      livre.style.setProperty('--lu', lu(n).toFixed(3));
      livre.classList.toggle('mag-debut', m.g === null);
      livre.classList.toggle('mag-fin', m.d === null);
    };
    var construire = function (n) {
      if (!double()) {
        var pl = document.createElement('div');
        pl.className = 'planche';
        pl.style.setProperty('--lu', lu(n).toFixed(3));
        pl.appendChild(fabriquer(n));
        return pl;
      }
      var livre = document.createElement('div');
      livre.className = 'livre';
      livre.innerHTML = '<span class="tranche gauche" aria-hidden="true"></span><span class="tranche droite" aria-hidden="true"></span>' +
        '<div class="moitie gauche"></div><div class="moitie droite"></div><span class="dos" aria-hidden="true"></span>';
      var m = moities(n);
      remplir(livre.querySelector('.moitie.gauche'), m.g);
      remplir(livre.querySelector('.moitie.droite'), m.d);
      livre.style.setProperty('--decal', decal(n) + '%');
      volume(livre, n);
      return livre;
    };
    var montrer = function (n, sansHistorique) {
      n = caler(n);
      courant = n;
      pile.innerHTML = '';
      pile.appendChild(construire(n));
      echelle();
      var m = moities(n);
      compteur.textContent = 'Page ' + n + (double() && m.g !== null && m.d !== null ? '–' + (n + 1) : '') + ' sur ' + total;
      curseur.value = n;
      liseuse.querySelector('[data-prec]').disabled = voisin(n, -1) === null;
      liseuse.querySelector('[data-suiv]').disabled = voisin(n, 1) === null;
      scene.scrollTo(0, 0);
      if (!sansHistorique) {
        try { history.replaceState(null, '', n > 1 ? '#page=' + n : location.pathname + location.search); } catch (e) {}
        try { localStorage.setItem(cle, n); } catch (e) {}
      }
    };

    // --- Transitions. Une transition préparée expose avance(p), p allant de 0 (position actuelle)
    // à 1 (position cible), la liste des éléments qui bougent, et depart() à appeler quand elle se conclut.
    var preparer = function (sens) {
      var cible = voisin(courant, sens);
      var actuel = pile.firstElementChild;
      var rien = function () {};
      if (cible === null) {
        // Butée : c'est l'ensemble (page, tranches, dos) qui résiste, puis revient.
        var l = (double() ? actuel.offsetWidth / 2 : actuel.offsetWidth) * 0.16;
        return { cible: null, sens: sens, course: l * 5, mobiles: [actuel], depart: rien,
          avance: function (p) { actuel.style.setProperty('--pousse', (-sens * p * l) + 'px'); },
          nettoyer: function () { actuel.style.setProperty('--pousse', '0px'); } };
      }
      if (!double()) {
        // Page simple : glissement latéral, chaque planche emporte son liseré.
        var entrante = construire(cible);
        pile.appendChild(entrante);
        echelle();
        var ecart = (actuel.offsetWidth + entrante.offsetWidth) / 2 + 28;
        return { cible: cible, sens: sens, course: ecart * 0.85, mobiles: [actuel, entrante], depart: rien,
          avance: function (p) {
            actuel.style.setProperty('--pousse', (-sens * p * ecart) + 'px');
            entrante.style.setProperty('--pousse', (sens * (1 - p) * ecart) + 'px');
          },
          nettoyer: function () { entrante.remove(); actuel.style.setProperty('--pousse', '0px'); } };
      }
      // Double page : pliure à la reliure, partout, couverture et dernière page comprises.
      // Un volet à deux faces pivote autour du centre : la page qui part au recto, la page qui arrive au verso.
      // Dessous, la moitié découverte montre déjà la page cible (ou le vide, si le magazine se ferme).
      var a = moities(courant), b = moities(cible);
      var cote = sens > 0 ? 'droite' : 'gauche';
      var dessous = actuel.querySelector('.moitie.' + cote);
      var volet = document.createElement('div');
      volet.className = 'volet ' + cote;
      var recto = document.createElement('div'); recto.className = 'face';
      var verso = document.createElement('div'); verso.className = 'face verso';
      recto.appendChild(fabriquer(sens > 0 ? a.d : a.g));
      verso.appendChild(fabriquer(sens > 0 ? b.g : b.d));
      volet.appendChild(recto); volet.appendChild(verso);
      remplir(dessous, sens > 0 ? b.d : b.g);
      actuel.appendChild(volet);
      echelle();
      var d0 = decal(courant), d1 = decal(cible);
      return { cible: cible, sens: sens, course: actuel.offsetWidth / 2, mobiles: [volet, actuel],
        // L'épaisseur des tranches et le dos changent pendant que la page tourne, pas avant.
        depart: function (aller) { volume(actuel, aller ? cible : courant); },
        avance: function (p) {
          volet.style.transform = 'rotateY(' + (-sens * p * 180) + 'deg)';
          volet.style.setProperty('--ombre', (Math.sin(p * Math.PI) * 0.2).toFixed(3));
          actuel.style.setProperty('--decal', (d0 + (d1 - d0) * p) + '%');
        },
        nettoyer: function () { montrer(courant, true); } };
    };
    var conclure = function (t, aller) {
      occupe = true;
      var cibleReelle = aller && t.cible !== null;
      t.mobiles.forEach(function (m) { m.classList.add('en-mouvement'); });
      t.depart(cibleReelle);
      requestAnimationFrame(function () { t.avance(cibleReelle ? 1 : 0); });
      setTimeout(function () {
        t.mobiles.forEach(function (m) { m.classList.remove('en-mouvement'); });
        if (cibleReelle) montrer(t.cible); else t.nettoyer();
        occupe = false;
      }, DUREE + 30);
    };
    // Flèches et clavier : transition complète. Curseur et sommaire : saut direct, sans animation.
    var tourner = function (sens) {
      if (occupe) return;
      var v = voisin(courant, sens);
      if (calme || zoom) {
        if (v !== null) { montrer(v); pile.firstElementChild.classList.add('fondu'); }
        return;
      }
      var t = preparer(sens);
      t.avance(0);
      void pile.offsetWidth;
      if (v === null) {
        // Butée au clavier : une poussée douce, aller puis retour, sans à-coup.
        occupe = true;
        t.mobiles[0].classList.add('en-mouvement');
        requestAnimationFrame(function () { t.avance(0.6); });
        setTimeout(function () { t.avance(0); }, DUREE / 2);
        setTimeout(function () { t.mobiles[0].classList.remove('en-mouvement'); t.nettoyer(); occupe = false; }, DUREE + 60);
        return;
      }
      conclure(t, true);
    };

    var zoomer = function (i) {
      zoom = i % zooms.length;
      pile.style.setProperty('--zoom', zooms[zoom]);
      liseuse.classList.toggle('zoome', zoom > 0);
      boutonZoom.querySelector('span').textContent = zoom ? '× ' + String(zooms[zoom]).replace('.', ',') : 'Zoom';
      montrer(courant, true);
    };
    liseuse.querySelector('[data-prec]').addEventListener('click', function () { tourner(-1); });
    liseuse.querySelector('[data-suiv]').addEventListener('click', function () { tourner(1); });
    curseur.addEventListener('input', function () { if (!occupe) montrer(parseInt(curseur.value, 10)); });
    boutonZoom.addEventListener('click', function () { if (!occupe) zoomer(zoom + 1); });
    scene.addEventListener('dblclick', function () { if (!occupe) zoomer(zoom ? 0 : 1); });
    liseuse.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowRight') tourner(1);
      if (e.key === 'ArrowLeft') tourner(-1);
      if (e.key === 'Escape') plein(false);
    });

    // --- Glissement du doigt : la page suit le doigt. Seul un geste nettement horizontal compte ;
    // les bords de l'écran sont exclus (retour du navigateur sur iPhone) ; rien si la page est agrandie.
    var geste = null;
    scene.addEventListener('touchstart', function (e) {
      geste = null;
      if (occupe || zoom || e.touches.length !== 1) return;
      var x = e.touches[0].clientX;
      if (x < 24 || x > innerWidth - 24) return;
      geste = { x: x, y: e.touches[0].clientY, t: Date.now(), axe: null, trans: null, dx: 0 };
    }, { passive: true });
    scene.addEventListener('touchmove', function (e) {
      if (!geste) return;
      if (e.touches.length !== 1) { if (geste.trans) geste.trans.nettoyer(); geste = null; return; }
      var dx = e.touches[0].clientX - geste.x, dy = e.touches[0].clientY - geste.y;
      if (!geste.axe) {
        if (Math.abs(dx) < 10 && Math.abs(dy) < 10) return;
        geste.axe = Math.abs(dx) > Math.abs(dy) * 1.5 ? 'x' : 'y';
      }
      if (geste.axe !== 'x') return;     // geste vertical : c'est un défilement
      geste.dx = dx;
      if (calme) return;
      var sens = dx < 0 ? 1 : -1;
      if (!geste.trans || geste.trans.sens !== sens) {
        if (geste.trans) geste.trans.nettoyer();
        geste.trans = preparer(sens);
      }
      geste.trans.avance(Math.min(1, Math.abs(dx) / geste.trans.course));
    }, { passive: true });
    scene.addEventListener('touchend', function () {
      if (!geste) return;
      var g = geste; geste = null;
      if (g.axe !== 'x') return;
      var vitesse = Math.abs(g.dx) / Math.max(1, Date.now() - g.t);        // px par ms
      if (calme) { if (Math.abs(g.dx) > 50) tourner(g.dx < 0 ? 1 : -1); return; }
      if (!g.trans) return;
      conclure(g.trans, Math.abs(g.dx) > scene.clientWidth / 4 || (vitesse > 0.5 && Math.abs(g.dx) > 24));
    });
    scene.addEventListener('touchcancel', function () { if (geste && geste.trans) conclure(geste.trans, false); geste = null; });

    // Plein écran sans l'API du navigateur (absente sur iPhone) : la liseuse recouvre la page.
    var plein = function (oui) {
      liseuse.classList.toggle('plein', oui);
      document.body.style.overflow = oui ? 'hidden' : '';
      boutonPlein.querySelector('span').textContent = oui ? 'Fermer' : 'Plein écran';
      montrer(courant, true);
    };
    boutonPlein.addEventListener('click', function () { if (!occupe) plein(!liseuse.classList.contains('plein')); });
    document.querySelectorAll('a[data-page]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        montrer(parseInt(a.dataset.page, 10));
        liseuse.scrollIntoView({ behavior: calme ? 'auto' : 'smooth', block: 'start' });
        liseuse.focus({ preventScroll: true });
      });
    });
    addEventListener('resize', function () { if (!occupe) montrer(courant, true); });
    var demandee = parseInt((location.hash.match(/page=(\d+)/) || [])[1], 10);
    var gardee = null;
    try { gardee = parseInt(localStorage.getItem(cle), 10); } catch (e) {}
    var reprise = liseuse.querySelector('[data-reprendre]');
    if (!demandee && gardee > 1 && reprise) {
      reprise.hidden = false;
      reprise.textContent = 'Reprendre à la page ' + gardee;
      reprise.addEventListener('click', function () { montrer(gardee); reprise.hidden = true; });
    }
    zoom = 0;
    pile.style.setProperty('--zoom', 1);
    montrer(demandee || 1, true);
    // Pour les essais : fige une transition à mi-course, ou la joue jusqu'à une position donnée.
    liseuse.essai = function (sens, p, depart) { var t = preparer(sens); if (depart) t.depart(true); t.avance(p); return t; };
  }

  // Article : barre de progression, barre de partage, en-tête qui s'efface à la descente.
  var corps = document.querySelector('.corps');
  if (corps) {
    var barre = document.querySelector('.progression');
    var partage = document.querySelector('.barre-partage');
    var tete = document.querySelector('.entete');
    var dernier = scrollY;
    var suivre = function () {
      var r = corps.getBoundingClientRect();
      var fait = Math.min(1, Math.max(0, (innerHeight * 0.5 - r.top) / r.height));
      if (barre) barre.style.transform = 'scaleX(' + fait + ')';
      if (partage) partage.classList.toggle('visible', r.top < innerHeight * 0.4 && fait < 1);
      if (tete) {
        if (scrollY > dernier + 6 && scrollY > 160) tete.classList.add('efface');
        else if (scrollY < dernier - 6 || scrollY <= 160) tete.classList.remove('efface');
      }
      dernier = scrollY;
    };
    addEventListener('scroll', suivre, { passive: true });
    suivre();
    document.querySelectorAll('[data-copier]').forEach(function (b) {
      b.addEventListener('click', function () {
        var fin = function () { b.classList.add('copie'); setTimeout(function () { b.classList.remove('copie'); }, 1600); };
        if (navigator.share && matchMedia('(max-width: 719px)').matches) navigator.share({ title: document.title, url: location.href }).catch(function () {});
        else if (navigator.clipboard) navigator.clipboard.writeText(location.href).then(fin, fin);
      });
    });
  }
})();
