/* ============================================================
   LION BARTENDER — front-end app
   Cart (localStorage) · drawer · product scent picker · set builder ·
   shop filters · checkout · reveal · nav.
   ============================================================ */
(function () {
  'use strict';

  var D = window.LB_DATA || { products: {}, scents: {} };
  var CART_KEY = 'lb_cart';

  // wp_localize_script ép mọi giá trị thành chuỗi → ép lại về số để tránh nối chuỗi.
  D.freeShipMin = Number(D.freeShipMin) || 299000;
  D.shipFee = Number(D.shipFee) || 25000;
  Object.keys(D.products || {}).forEach(function (k) {
    var p = D.products[k];
    p.price = Number(p.price) || 0;
    p.oldPrice = Number(p.oldPrice) || 0;
  });

  /* ---------- helpers ---------- */
  function $(sel, root) { return (root || document).querySelector(sel); }
  function $all(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }
  function fmt(n) { return Number(n).toLocaleString('vi-VN') + 'đ'; }

  var ICON = {
    cart: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 4h2l2.4 12.2a1 1 0 0 0 1 .8h8.7a1 1 0 0 0 1-.8L21 8H6"/><circle cx="9" cy="20" r="1.3"/><circle cx="18" cy="20" r="1.3"/></svg>',
    close: '<svg width="22" height="22" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" fill="none"><path d="M6 6l12 12M18 6L6 18"/></svg>',
    arrow: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
    check: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>'
  };

  /* ---------- cart store ---------- */
  function getCart() {
    try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
    catch (e) { return []; }
  }
  function saveCart(c) {
    localStorage.setItem(CART_KEY, JSON.stringify(c));
    updateCount();
    renderDrawer();
  }
  function cartCount() { return getCart().reduce(function (a, it) { return a + it.qty; }, 0); }
  function subtotalOf(cart) {
    return cart.reduce(function (a, it) {
      var p = D.products[it.slug];
      return a + (p ? p.price * it.qty : 0);
    }, 0);
  }
  function shipOf(sub) { return (sub >= D.freeShipMin || sub === 0) ? 0 : D.shipFee; }

  function addToCart(slug, scent, qty, silent) {
    qty = qty || 1;
    var p = D.products[slug];
    if (!p) return;
    if (!scent || (p.scents.indexOf(scent) === -1)) scent = p.scents[0];
    var key = slug + '__' + scent;
    var cart = getCart();
    var ex = cart.filter(function (i) { return i.key === key; })[0];
    if (ex) ex.qty += qty;
    else cart.push({ key: key, slug: slug, scent: scent, qty: qty });
    saveCart(cart);
    if (!silent) {
      var sn = D.scents[scent] ? D.scents[scent].name : '';
      toast('Đã thêm ' + p.name + ' — ' + sn);
    }
  }
  function setQty(key, q) {
    var cart = getCart();
    if (q <= 0) cart = cart.filter(function (i) { return i.key !== key; });
    else cart.forEach(function (i) { if (i.key === key) i.qty = q; });
    saveCart(cart);
  }
  function removeItem(key) { setQty(key, 0); }

  function itemImg(slug, scent) {
    var p = D.products[slug];
    if (p && p.images && p.images[scent]) return p.images[scent];
    return '';
  }

  /* ---------- count badge ---------- */
  function updateCount() {
    var el = $('#lb-cart-count');
    if (!el) return;
    var n = cartCount();
    el.textContent = n;
    if (n > 0) el.removeAttribute('hidden'); else el.setAttribute('hidden', '');
  }

  /* ---------- toast ---------- */
  var toastTimer;
  function toast(msg) {
    var t = $('#lb-toast'), m = $('#lb-toast-msg');
    if (!t || !m) return;
    m.textContent = msg;
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { t.classList.remove('show'); }, 2400);
  }

  /* ---------- cart drawer ---------- */
  var drawerOpen = false;
  function openDrawer() { drawerOpen = true; renderDrawer(); }
  function closeDrawer() { drawerOpen = false; renderDrawer(); }

  function renderDrawer() {
    var root = $('#lb-drawer-root');
    if (!root) return;
    if (!drawerOpen) { root.innerHTML = ''; return; }

    var cart = getCart();
    var sub = subtotalOf(cart);
    var ship = shipOf(sub);
    var count = cart.reduce(function (a, it) { return a + it.qty; }, 0);

    var body;
    if (cart.length === 0) {
      body = '<div class="drawer__empty">' +
        '<div style="opacity:.4;margin:0 auto 16px;width:40px">' + ICON.cart.replace('width="18" height="18"', 'width="40" height="40"') + '</div>' +
        '<p>Giỏ hàng đang trống.</p>' +
        '<button class="btn btn--ghost btn--sm" data-go-shop style="margin-top:18px">Bắt đầu mua sắm</button></div>';
    } else {
      body = cart.map(function (it) {
        var p = D.products[it.slug]; if (!p) return '';
        var sn = D.scents[it.scent] ? D.scents[it.scent].name : '';
        return '<div class="litem">' +
          '<div class="litem__img"><img src="' + itemImg(it.slug, it.scent) + '" alt=""></div>' +
          '<div>' +
            '<div class="litem__name">' + p.name + '</div>' +
            '<div class="litem__scent">' + sn + '</div>' +
            '<div class="litem__qty">' +
              '<button data-dec="' + it.key + '">−</button><span>' + it.qty + '</span><button data-inc="' + it.key + '">+</button>' +
            '</div>' +
          '</div>' +
          '<div>' +
            '<div class="litem__price">' + fmt(p.price * it.qty) + '</div>' +
            '<button class="litem__rm" data-rm="' + it.key + '">Xóa</button>' +
          '</div>' +
        '</div>';
      }).join('');
    }

    var foot = cart.length === 0 ? '' :
      '<div class="drawer__foot">' +
        '<div class="drawer__row"><span class="lbl">Tạm tính</span><span>' + fmt(sub) + '</span></div>' +
        '<div class="drawer__row"><span class="lbl">Phí giao hàng</span><span>' + (ship === 0 ? 'Miễn phí' : fmt(ship)) + '</span></div>' +
        '<div class="drawer__total"><span class="lbl">Tổng cộng</span><span class="val">' + fmt(sub + ship) + '</span></div>' +
        '<button class="btn btn--gold btn--block btn--lg" data-checkout>Thanh toán ' + ICON.arrow + '</button>' +
      '</div>';

    root.innerHTML =
      '<div class="drawer-scrim" data-close></div>' +
      '<aside class="drawer">' +
        '<div class="drawer__head">' +
          '<span class="drawer__title">Giỏ hàng (' + count + ')</span>' +
          '<button class="icon-btn" data-close style="width:38px;height:38px">' + ICON.close + '</button>' +
        '</div>' +
        '<div class="drawer__body">' + body + '</div>' +
        foot +
      '</aside>';
  }

  document.addEventListener('click', function (e) {
    var t = e.target;
    // open cart
    if (t.closest && t.closest('#lb-cart-btn')) { e.preventDefault(); openDrawer(); return; }
    // drawer interactions
    var el;
    if ((el = t.closest && t.closest('[data-close]'))) { closeDrawer(); return; }
    if ((el = t.closest && t.closest('[data-go-shop]'))) { window.location.href = D.shopUrl; return; }
    if ((el = t.closest && t.closest('[data-checkout]'))) { window.location.href = D.checkoutUrl; return; }
    if ((el = t.closest && t.closest('[data-inc]'))) { var k = el.getAttribute('data-inc'); var i = find(k); setQty(k, (i ? i.qty : 0) + 1); return; }
    if ((el = t.closest && t.closest('[data-dec]'))) { var k2 = el.getAttribute('data-dec'); var i2 = find(k2); setQty(k2, (i2 ? i2.qty : 0) - 1); return; }
    if ((el = t.closest && t.closest('[data-rm]'))) { removeItem(el.getAttribute('data-rm')); return; }
  });
  function find(key) { return getCart().filter(function (i) { return i.key === key; })[0]; }

  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && drawerOpen) closeDrawer(); });

  /* ---------- nav: solidify + mobile menu ---------- */
  function initNav() {
    var nav = $('#lb-nav');
    if (nav && nav.querySelector('.nav__bar')) {
      // chỉ áp dụng hiệu ứng trong suốt trên trang chủ; trang khác luôn solid
      var isHome = document.body.classList.contains('home');
      var onScroll = function () {
        if (!isHome || window.scrollY > 24) nav.classList.add('nav--solid');
        else nav.classList.remove('nav--solid');
      };
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    }
    var btn = $('#lb-menu-btn'), menu = $('#lb-mobile-menu');
    if (btn && menu) {
      btn.addEventListener('click', function () {
        var open = !menu.hasAttribute('hidden');
        if (open) menu.setAttribute('hidden', ''); else menu.removeAttribute('hidden');
      });
    }
  }

  /* ---------- reveal on scroll ---------- */
  function initReveal() {
    var els = $all('.reveal');
    if (!els.length) return;
    if (!('IntersectionObserver' in window)) { els.forEach(function (el) { el.classList.add('in'); }); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.12 });
    els.forEach(function (el) { io.observe(el); });
  }

  /* ---------- product detail page ---------- */
  function initProduct() {
    var root = $('#lb-product');
    if (!root) return;
    var slug = root.getAttribute('data-slug');
    var p = D.products[slug];
    if (!p) return;
    var scent = root.getAttribute('data-scent');
    var qty = 1;

    var img = $('#lb-pd-img'), glow = $('#lb-pd-glow'), sel = $('#lb-pd-sel'),
        desc = $('#lb-pd-desc'), notes = $('#lb-pd-notes'), qtyVal = $('#lb-pd-qtyval'),
        addTotal = $('#lb-pd-addtotal');

    function setScent(s) {
      if (!D.scents[s]) return;
      scent = s;
      root.setAttribute('data-scent', s);
      var sd = D.scents[s];
      if (img && p.images[s]) { img.style.opacity = '0'; setTimeout(function () { img.src = p.images[s]; img.style.opacity = '1'; }, 150); }
      if (glow) glow.style.background = 'radial-gradient(circle, ' + sd.color + ', transparent 65%)';
      if (sel) sel.textContent = sd.name + ' — ' + sd.tone;
      if (desc) desc.textContent = sd.desc;
      if (notes) notes.innerHTML = (sd.notes || []).map(function (n) { return '<span class="note-tag">' + n + '</span>'; }).join('');
      $all('.lb-spill', root).forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-scent') === s); });
      $all('.lb-thumb', root).forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-scent') === s); });
      // cập nhật url (không reload)
      if (history.replaceState) {
        var u = new URL(window.location.href); u.searchParams.set('scent', s); history.replaceState({}, '', u);
      }
    }
    function setQtyVal(n) { qty = Math.max(1, n); if (qtyVal) qtyVal.textContent = qty; if (addTotal) addTotal.textContent = fmt(p.price * qty); }

    $all('.lb-spill', root).forEach(function (b) { b.addEventListener('click', function () { setScent(b.getAttribute('data-scent')); }); });
    $all('.lb-thumb', root).forEach(function (b) { b.addEventListener('click', function () { setScent(b.getAttribute('data-scent')); }); });

    var qtyBox = $('#lb-pd-qty');
    if (qtyBox) qtyBox.addEventListener('click', function (e) {
      var b = e.target.closest('button'); if (!b) return;
      setQtyVal(qty + parseInt(b.getAttribute('data-q'), 10));
    });

    var add = $('#lb-pd-add');
    if (add) add.addEventListener('click', function () { addToCart(slug, scent, qty); });
    var buy = $('#lb-pd-buy');
    if (buy) buy.addEventListener('click', function () { addToCart(slug, scent, qty, true); window.location.href = D.checkoutUrl; });
  }

  /* ---------- shop filters ---------- */
  function initShop() {
    var bar = $('#lb-filters'), grid = $('#lb-shop-grid');
    if (!bar || !grid) return;
    bar.addEventListener('click', function (e) {
      var chip = e.target.closest('.chip'); if (!chip) return;
      var f = chip.getAttribute('data-filter');
      $all('.chip', bar).forEach(function (c) { c.classList.toggle('is-active', c === chip); });
      $all('.pcard', grid).forEach(function (card) {
        var show = (f === 'all' || card.getAttribute('data-type') === f);
        card.style.display = show ? '' : 'none';
      });
    });
  }

  /* ---------- scent page: set builder ---------- */
  function initSetBuilder() {
    var sb = $('#lb-setbuilder');
    if (!sb) return;
    var scent = $('#lb-scent').getAttribute('data-scent');
    var cards = $all('.lb-qbuy', sb);
    var bar = $('#lb-setbar');
    var qtys = {}; // slug -> n

    function render() {
      var items = 0, price = 0, old = 0;
      cards.forEach(function (card) {
        var slug = card.getAttribute('data-slug');
        var n = qtys[slug] || 0;
        var addBtn = $('.qbuy__add', card);
        var stepper = $('[data-stepper]', card);
        var span = $('[data-qty]', card);
        card.classList.toggle('is-picked', n > 0);
        if (n > 0) {
          addBtn.setAttribute('hidden', '');
          stepper.removeAttribute('hidden');
          if (span) span.textContent = n;
          items += n;
          price += n * parseInt(card.getAttribute('data-price'), 10);
          old += n * parseInt(card.getAttribute('data-old'), 10);
        } else {
          addBtn.removeAttribute('hidden');
          stepper.setAttribute('hidden', '');
        }
      });
      // select-all / clear labels
      var allBtn = $('#lb-set-all'), clearBtn = $('#lb-set-clear');
      var allPicked = cards.every(function (c) { return (qtys[c.getAttribute('data-slug')] || 0) > 0; });
      if (allBtn) allBtn.textContent = allPicked ? 'Mỗi món 1' : 'Chọn trọn bộ';
      if (clearBtn) { if (items > 0) clearBtn.removeAttribute('hidden'); else clearBtn.setAttribute('hidden', ''); }
      // floating bar
      if (items > 0) {
        bar.removeAttribute('hidden');
        $('#lb-setbar-count').textContent = items + ' sản phẩm';
        $('#lb-setbar-price').textContent = fmt(price);
        var oldEl = $('#lb-setbar-old');
        if (old > price) { oldEl.textContent = fmt(old); oldEl.removeAttribute('hidden'); }
        else oldEl.setAttribute('hidden', '');
      } else {
        bar.setAttribute('hidden', '');
      }
    }
    function set(slug, n) { qtys[slug] = Math.max(0, n); render(); }

    cards.forEach(function (card) {
      var slug = card.getAttribute('data-slug');
      card.addEventListener('click', function (e) {
        var b = e.target.closest('button'); if (!b) return;
        var act = b.getAttribute('data-act');
        if (act === 'add') set(slug, 1);
        else if (act === 'inc') set(slug, (qtys[slug] || 0) + 1);
        else if (act === 'dec') set(slug, (qtys[slug] || 0) - 1);
      });
    });

    var allBtn = $('#lb-set-all');
    if (allBtn) allBtn.addEventListener('click', function () {
      var allPicked = cards.every(function (c) { return (qtys[c.getAttribute('data-slug')] || 0) > 0; });
      cards.forEach(function (c) { var s = c.getAttribute('data-slug'); qtys[s] = qtys[s] || 1; });
      // if already all picked, leave as "mỗi món 1" (no-op besides ensure >=1)
      render();
    });
    var clearBtn = $('#lb-set-clear');
    if (clearBtn) clearBtn.addEventListener('click', function () { qtys = {}; render(); });

    var addAll = $('#lb-setbar-add');
    if (addAll) addAll.addEventListener('click', function () {
      cards.forEach(function (c) {
        var s = c.getAttribute('data-slug'); var n = qtys[s] || 0;
        if (n > 0) addToCart(s, scent, n, true);
      });
      qtys = {}; render();
      openDrawer();
    });

    render();
  }

  /* ---------- checkout page ---------- */
  function initCheckout() {
    var root = $('#lb-checkout');
    if (!root) return;
    var cart = getCart();
    var empty = $('#lb-co-empty'), main = $('#lb-co-main');

    if (cart.length === 0) {
      if (empty) empty.removeAttribute('hidden');
      if (main) main.setAttribute('hidden', '');
      return;
    }
    if (main) main.removeAttribute('hidden');
    if (empty) empty.setAttribute('hidden', '');

    var sub = subtotalOf(cart), ship = shipOf(sub), total = sub + ship;

    // summary items
    var itemsEl = $('#lb-co-items');
    itemsEl.innerHTML = cart.map(function (it) {
      var p = D.products[it.slug]; if (!p) return '';
      var sn = D.scents[it.scent] ? D.scents[it.scent].name : '';
      return '<div class="litem" style="grid-template-columns:60px 1fr auto;padding:14px 0">' +
        '<div class="litem__img" style="width:60px;height:68px"><img src="' + itemImg(it.slug, it.scent) + '" alt=""></div>' +
        '<div>' +
          '<div class="litem__name" style="font-size:13px">' + p.name + '</div>' +
          '<div class="litem__scent" style="font-size:13px">' + sn + ' × ' + it.qty + '</div>' +
        '</div>' +
        '<div class="litem__price" style="font-size:15px">' + fmt(p.price * it.qty) + '</div>' +
      '</div>';
    }).join('');

    $('#lb-co-subtotal').textContent = fmt(sub);
    $('#lb-co-ship').textContent = ship === 0 ? 'Miễn phí' : fmt(ship);
    $('#lb-co-total').textContent = fmt(total);
    $('#lb-co-btntotal').textContent = fmt(total);
    var hint = $('#lb-co-freehint');
    if (hint && sub < D.freeShipMin) { hint.textContent = 'Mua thêm ' + fmt(D.freeShipMin - sub) + ' để được miễn phí giao hàng.'; hint.removeAttribute('hidden'); }

    // payment selection
    var pays = $('#lb-co-pays'), payInput = $('#lb-co-pay');
    if (pays) pays.addEventListener('click', function (e) {
      var opt = e.target.closest('.pay-opt'); if (!opt) return;
      $all('.pay-opt', pays).forEach(function (o) { o.classList.toggle('is-active', o === opt); });
      payInput.value = opt.getAttribute('data-pay');
    });

    // submit
    var form = $('#lb-co-form');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = $('#lb-co-submit');
      btn.disabled = true;
      var fd = new FormData(form);
      fd.append('action', 'lb_place_order');
      fd.append('nonce', D.nonce);
      fd.append('items', JSON.stringify(getCart().map(function (i) { return { slug: i.slug, scent: i.scent, qty: i.qty }; })));
      fetch(D.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res && res.success) {
            localStorage.removeItem(CART_KEY);
            window.location.href = res.data.redirect || D.successUrl;
          } else {
            btn.disabled = false;
            toast((res && res.data && res.data.message) || 'Có lỗi xảy ra, thử lại nhé.');
          }
        })
        .catch(function () { btn.disabled = false; toast('Lỗi kết nối, thử lại nhé.'); });
    });
  }

  /* ---------- boot ---------- */
  document.addEventListener('DOMContentLoaded', function () {
    updateCount();
    initNav();
    initReveal();
    initProduct();
    initShop();
    initSetBuilder();
    initCheckout();
  });

})();
