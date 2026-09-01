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

// ===== Live Header Search & Autocomplete =====
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('q');
    const searchDropdown = document.getElementById('search-dropdown');
    const searchForm = document.querySelector('form.search');
    let debounceTimeout = null;

    if (!searchInput || !searchDropdown || !searchForm) return;

    function escapeHtml(str) {
        return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function highlightMatch(text, query) {
        if (!query) return escapeHtml(text);
        const safeQuery = query.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        const regex = new RegExp('(' + safeQuery + ')', 'gi');
        return escapeHtml(text).replace(regex, '<span style="color:#b8801b; font-weight: 700; text-decoration: underline;">$1</span>');
    }

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();
        clearTimeout(debounceTimeout);

        if (query.length < 2) {
            searchDropdown.style.display = 'none';
            searchDropdown.innerHTML = '';
            return;
        }

        debounceTimeout = setTimeout(() => {
            fetch('/search/autocomplete?q=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    if (data.items.length === 0) {
                        searchDropdown.innerHTML = `
                            <div class="search-dropdown__message">
                                No wholesale products found matching "<strong>${escapeHtml(query)}</strong>"
                            </div>
                        `;
                        searchDropdown.style.display = 'block';
                        return;
                    }

                    let html = '';
                    data.items.forEach(item => {
                        html += `
                            <a href="${item.url}" class="search-dropdown__item">
                                <div class="search-dropdown__thumb">
                                    <img src="${item.image_url}" alt="${escapeHtml(item.name)}" onerror="this.src='/images/product1.png';" />
                                </div>
                                <div class="search-dropdown__info">
                                    <div class="search-dropdown__name">${highlightMatch(item.name, query)}</div>
                                    <div class="search-dropdown__meta">
                                        <span class="badge-sku">${escapeHtml(item.sku || '-')}</span>
                                        <span>${escapeHtml(item.category || '')}</span>
                                    </div>
                                </div>
                                <div class="search-dropdown__price-wrap">
                                    ${item.is_logged_in 
                                        ? `<span class="search-dropdown__price">${item.price || ''}</span>`
                                        : `<span class="search-dropdown__lock">🔒 Login</span>`
                                    }
                                </div>
                            </a>
                        `;
                    });

                    html += `
                        <div class="search-dropdown__footer">
                            <a href="${data.view_all}">
                                View all ${data.total} results for "${escapeHtml(query)}" &rarr;
                            </a>
                        </div>
                    `;

                    searchDropdown.innerHTML = html;
                    searchDropdown.style.display = 'block';
                })
                .catch(() => {});
        }, 220);
    });

    // Close dropdown on click outside
    document.addEventListener('click', function (e) {
        if (!searchForm.contains(e.target)) {
            searchDropdown.style.display = 'none';
        }
    });

    // Close dropdown on Escape key
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            searchDropdown.style.display = 'none';
        }
    });

    // If input re-focused and has value, show dropdown again
    searchInput.addEventListener('focus', function () {
        if (searchInput.value.trim().length >= 2 && searchDropdown.innerHTML.trim() !== '') {
            searchDropdown.style.display = 'block';
        }
    });
});

// ===== Frontend Multi-Image Product Card Slider (Smooth GPU Track) =====
function moveFrontendSlide(btnOrSlider, direction, targetIdx = null) {
    const slider = btnOrSlider.hasAttribute('data-slider') ? btnOrSlider : btnOrSlider.closest('[data-slider]');
    if (!slider) return;

    const track = slider.querySelector('.slider-track');
    if (!track) return;

    const total = parseInt(slider.dataset.total || '1', 10);
    if (total <= 1) return;

    let current = parseInt(slider.dataset.current || '0', 10);
    let next = targetIdx !== null ? targetIdx : (current + direction + total) % total;

    slider.dataset.current = next;
    track.style.transform = `translate3d(-${next * 100}%, 0, 0)`;
}

document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.product__media[data-slider]');
    sliders.forEach((slider, sliderIdx) => {
        const total = parseInt(slider.dataset.total || '1', 10);
        if (total <= 1) return;

        let intervalId = null;

        const startAuto = () => {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(() => {
                moveFrontendSlide(slider, 1);
            }, 4000);
        };

        const stopAuto = () => {
            if (intervalId) clearInterval(intervalId);
        };

        slider.addEventListener('mouseenter', stopAuto);
        slider.addEventListener('mouseleave', startAuto);

        // Staggered start timing
        setTimeout(startAuto, ((sliderIdx % 4) + 1) * 900);
    });
});

