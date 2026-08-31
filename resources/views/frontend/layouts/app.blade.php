<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- CSRF Token for AJAX Requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Carolina Prime Distributors')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>

<body>
    @include('frontend.partials.topbar')
    @include('frontend.partials.header')

    <main id="top">
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    <!-- Quick View Modal -->
    <div id="quickViewModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div
            style="background: #fff; border-radius: 10px; max-width: 750px; width: 100%; position: relative; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <button id="closeQuickView" type="button"
                style="position: absolute; right: 15px; top: 12px; font-size: 24px; border: none; background: transparent; cursor: pointer;">&times;</button>

            <div style="display: grid; grid-template-columns: 300px 1fr; gap: 25px; align-items: start;">
                <!-- Modal Media -->
                <div style="border: 1px solid #eee; border-radius: 8px; padding: 10px; text-align: center;">
                    <img id="qv-image" src="" alt=""
                        style="width: 100%; height: 260px; object-fit: contain; display: block;" />
                </div>

                <!-- Modal Details -->
                <div>
                    <p id="qv-category" style="color: #888; font-size: 12px; text-transform: uppercase; margin: 0;"></p>
                    <h2 id="qv-name" style="font-size: 20px; margin: 6px 0;"></h2>
                    <p style="color: #666; font-size: 13px; margin-bottom: 12px;">SKU: <strong id="qv-sku">-</strong> &middot; In stock</p>

                    <div id="qv-price-wrap" style="margin-bottom: 15px;"></div>

                    <div id="qv-tiers-wrap" style="margin-bottom: 15px; font-size: 13px;"></div>

                    <div style="margin-top: 15px;">
                        <a id="qv-order-link" href="#" class="btn btn--primary btn--block"
                            style="text-align: center; display: block; text-decoration: none; padding: 10px 0;">
                            View Full Details & Order &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}" defer></script>

    <!-- Global Cart Sync & Quick View Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Header Cart Count Synchronizer
            const badge = document.getElementById('cart-count');
            if (badge) {
                fetch('{{ route("cart.count") }}')
                    .then(res => res.json())
                    .then(data => {
                        if (typeof data.cart_count !== 'undefined') {
                            badge.textContent = data.cart_count;
                        }
                    })
                    .catch(err => console.error('Cart sync error:', err));
            }

            // 2. Global Quick View Modal Handler
            const modal       = document.getElementById('quickViewModal');
            const closeBtn    = document.getElementById('closeQuickView');
            const qvImage     = document.getElementById('qv-image');
            const qvCategory  = document.getElementById('qv-category');
            const qvName      = document.getElementById('qv-name');
            const qvSku       = document.getElementById('qv-sku');
            const qvPriceWrap = document.getElementById('qv-price-wrap');
            const qvTiersWrap = document.getElementById('qv-tiers-wrap');
            const qvOrderLink = document.getElementById('qv-order-link');

            function openModal() { modal.style.display = 'flex'; }
            function closeModal() { modal.style.display = 'none'; }

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (modal) modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.product__quick');
                if (!btn) return;

                const productId = btn.dataset.id;
                const originalText = btn.innerText;
                btn.innerText = 'Loading...';

                fetch(`/product/${productId}/quick-view`)
                    .then(res => res.json())
                    .then(data => {
                        btn.innerText = originalText;
                        if (!data.success) return;

                        const p = data.product;
                        qvImage.src = data.image_url;
                        qvImage.alt = p.name;
                        qvName.textContent = p.name;
                        qvCategory.textContent = p.category ? p.category.name : '';
                        qvSku.textContent = p.sku || '-';
                        qvOrderLink.href = data.product_url;

                        // Price rendering
                        if (data.is_logged_in) {
                            qvPriceWrap.innerHTML = `<h3 style="color: #1a8917; margin: 0; font-size: 22px;">$${data.price} <span style="font-size: 13px; color: #666; font-weight: normal;">/ unit</span></h3>`;
                        } else {
                            qvPriceWrap.innerHTML = `<a href="${data.login_url}" style="color: #d97706; text-decoration: underline; font-size: 14px; font-weight: 600;">Log In To See Price</a>`;
                        }

                        // Wholesale tier pricing rendering
                        if (p.price_tiers && p.price_tiers.length > 0 && data.is_logged_in) {
                            let tiersHtml = `<div style="background:#f8f9fa; padding:10px; border-radius:6px; border:1px solid #eee;"><strong>Wholesale Bulk Discounts:</strong><ul style="margin:5px 0 0 18px; padding:0;">`;
                            p.price_tiers.forEach(t => {
                                tiersHtml += `<li>${t.min_qty} - ${t.max_qty} units: <strong>$${t.price}</strong></li>`;
                            });
                            tiersHtml += `</ul></div>`;
                            qvTiersWrap.innerHTML = tiersHtml;
                        } else {
                            qvTiersWrap.innerHTML = '';
                        }

                        openModal();
                    })
                    .catch(err => {
                        btn.innerText = originalText;
                        console.error('Quick view error:', err);
                    });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>