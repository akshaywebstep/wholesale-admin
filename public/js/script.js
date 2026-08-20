// Wholesale — small progressive-enhancement behaviours.

// Mobile navigation toggle
const navToggle = document.querySelector(".navtoggle");
const navbar = document.getElementById("navbar");

if (navToggle && navbar) {
  navToggle.addEventListener("click", () => {
    const open = navbar.classList.toggle("is-open");
    navToggle.setAttribute("aria-expanded", String(open));
  });

  // Close mobile nav when clicking any link inside navbar
  navbar.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      if (navbar.classList.contains("is-open")) {
        navbar.classList.remove("is-open");
        navToggle.setAttribute("aria-expanded", "false");
      }
    });
  });
}

// Product category filter
const chips = document.querySelectorAll(".chip");
const products = document.querySelectorAll(".product");
const emptyState = document.getElementById("empty-state");

chips.forEach((chip) => {
  chip.addEventListener("click", () => {
    chips.forEach((c) => c.classList.remove("is-active"));
    chip.classList.add("is-active");

    const filter = chip.dataset.filter;
    let visible = 0;

    products.forEach((product) => {
      const match = filter === "all" || product.dataset.cat === filter;
      product.classList.toggle("is-hidden", !match);
      if (match) visible += 1;
    });

    if (emptyState) emptyState.hidden = visible !== 0;
  });
});

// FAQ Accordion Toggle
const accordionTriggers = document.querySelectorAll(".accordion__trigger");

accordionTriggers.forEach((trigger) => {
  trigger.addEventListener("click", () => {
    const item = trigger.closest(".accordion__item");
    const content = item.querySelector(".accordion__content");
    const isOpen = item.classList.contains("is-active");

    // Close other accordion items
    document.querySelectorAll(".accordion__item").forEach((otherItem) => {
      if (otherItem !== item) {
        otherItem.classList.remove("is-active");
        const otherTrigger = otherItem.querySelector(".accordion__trigger");
        const otherContent = otherItem.querySelector(".accordion__content");
        if (otherTrigger) otherTrigger.setAttribute("aria-expanded", "false");
        if (otherContent) otherContent.hidden = true;
      }
    });

    // Toggle current item
    item.classList.toggle("is-active", !isOpen);
    trigger.setAttribute("aria-expanded", String(!isOpen));
    if (content) content.hidden = isOpen;
  });
});

// Initialize Swiper Carousels
if (typeof Swiper !== "undefined") {
  // Brands Marquee Carousel
  new Swiper(".brands-swiper", {
    slidesPerView: 2,
    spaceBetween: 12,
    loop: true,
    autoplay: {
      delay: 2200,
      disableOnInteraction: false,
    },
    breakpoints: {
      480: { slidesPerView: 3, spaceBetween: 14 },
      768: { slidesPerView: 5, spaceBetween: 16 },
      1024: { slidesPerView: 7, spaceBetween: 18 },
    },
  });

  // Retailer Reviews Swiper Slider
  new Swiper(".reviews-swiper", {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: true,
    grabCursor: true,
    autoplay: {
      delay: 4500,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".reviews-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".reviews-next",
      prevEl: ".reviews-prev",
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 24,
      },
    },
  });
}

// Solution Tabs Switching Handler
const solutionTabs = document.querySelectorAll(".solution-tab");
const solutionPanels = document.querySelectorAll(".solution-panel");

if (solutionTabs.length > 0) {
  solutionTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const targetId = tab.dataset.target;

      solutionTabs.forEach((t) => {
        t.classList.remove("is-active");
        t.setAttribute("aria-selected", "false");
      });

      solutionPanels.forEach((panel) => {
        panel.classList.remove("is-active");
        panel.hidden = true;
      });

      tab.classList.add("is-active");
      tab.setAttribute("aria-selected", "true");

      const targetPanel = document.getElementById(targetId);
      if (targetPanel) {
        targetPanel.classList.add("is-active");
        targetPanel.hidden = false;
      }
    });
  });
}


// ===== Cart: "Add to Order" buttons + header badge sync =====
document.addEventListener('DOMContentLoaded', function () {
    const csrfMeta  = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : '';

    function updateCartBadge(count) {
        const badge = document.getElementById('cart-count');
        if (badge) badge.textContent = count;
    }

    // Sync badge with server on every page load
    fetch('/cart/count')
        .then(res => res.json())
        .then(data => updateCartBadge(data.cart_count))
        .catch(() => {});

    // Attach handler to every "Add to Order" button on product cards
    document.querySelectorAll('.product[data-product-id]').forEach(function (card) {
        const addBtn = card.querySelector('.btn--outline.btn--block');
        if (!addBtn) return;

        addBtn.addEventListener('click', function () {
            const productId = card.dataset.productId;
            if (!productId) return;

            addBtn.disabled = true;
            const originalText = addBtn.textContent;
            addBtn.textContent = 'Adding...';

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 }),
            })
            .then(res => res.json().then(body => ({ status: res.status, body })))
            .then(({ status, body }) => {
                if (status === 401) {
                    window.location.href = body.redirect;
                    return;
                }

                if (body.success) {
                    updateCartBadge(body.cart_count);
                    addBtn.textContent = 'Added ✓';
                    setTimeout(function () {
                        addBtn.textContent = originalText;
                        addBtn.disabled = false;
                    }, 1200);
                } else {
                    addBtn.textContent = originalText;
                    addBtn.disabled = false;
                    alert(body.message || 'Something went wrong.');
                }
            })
            .catch(function () {
                addBtn.textContent = originalText;
                addBtn.disabled = false;
            });
        });
    });
});

