@extends('frontend.layouts.app')

@section('title', 'B2B Quick Order Matrix | Carolina Prime Distributors')

@section('content')
<div class="quick-order-page" style="background: #f8fafc; min-height: 85vh; padding: 36px 0 60px 0;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 16px;">

        <!-- 1. Header Banner -->
        <div style="background: linear-gradient(135deg, #0b2212 0%, #144523 100%); border-radius: 16px; padding: 28px 32px; color: #ffffff; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(20, 69, 35, 0.25); position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 2; max-width: 750px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.2);">
                    <span style="color: #f59e0b;">⚡</span> High-Speed Wholesale Ordering
                </div>
                <h1 style="font-size: 26px; font-weight: 800; margin: 0 0 6px 0; line-height: 1.2; font-family: 'Inter', sans-serif;">
                    Multi-Item Quick Order Table
                </h1>
                <p style="font-size: 13.5px; color: rgba(255,255,255,0.85); margin: 0; line-height: 1.5;">
                    Search products by SKU or Name. As soon as you select an item, a new row is automatically added for instant, continuous ordering with live volume tier pricing.
                </p>
            </div>
            
            <div style="position: absolute; right: 24px; bottom: -20px; opacity: 0.08; font-size: 160px; font-weight: 900; user-select: none; pointer-events: none;">
                B2B
            </div>
        </div>

        <!-- 2. Interactive Multi-Item Quick Order Card -->
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 24px; margin-bottom: 24px; position: relative; z-index: 10;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div>
                    <h2 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">Order Items Matrix</h2>
                    <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">Fill in an item below — next line will auto-appear automatically.</p>
                </div>
                
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="addGridRow()"
                        style="background: #144523; color: #ffffff; font-size: 12px; font-weight: 700; padding: 7px 14px; border-radius: 8px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(20,69,35,0.2);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>+ Add Row Manually</span>
                    </button>
                    <button type="button" onclick="clearAllRows()"
                        style="background: #f8fafc; color: #dc2626; font-size: 12px; font-weight: 600; padding: 7px 12px; border-radius: 8px; border: 1px solid #fee2e2; cursor: pointer;">
                        Reset
                    </button>
                </div>
            </div>

            <!-- Table Container (overflow visible + minimum height for floating dropdown) -->
            <div style="position: relative; min-height: 280px; overflow: visible;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 0 8px;" id="quickOrderTable">
                    <thead>
                        <tr style="color: #64748b; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; text-align: left;">
                            <th style="padding: 8px 12px; width: 45px;">#</th>
                            <th style="padding: 8px 12px; min-width: 340px;">Search Product / SKU</th>
                            <th style="padding: 8px 12px; width: 140px;">Stock Status</th>
                            <th style="padding: 8px 12px; width: 140px;">Unit Price</th>
                            <th style="padding: 8px 12px; width: 120px;">Order Qty</th>
                            <th style="padding: 8px 12px; width: 130px;">Line Total</th>
                            <th style="padding: 8px 12px; width: 45px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="quickOrderTbody">
                        <!-- Default 1 Row generated by JS -->
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 14px;">
                <button type="button" onclick="addGridRow()"
                    style="background: #f0fdf4; color: #144523; border: 1.5px dashed #16a34a; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>+ Add Another Row</span>
                </button>
            </div>
        </div>

        <!-- 3. Sticky Bottom Wholesale Total & Submit Bar -->
        <div id="gridSummaryBar" style="background: #ffffff; border-radius: 16px; border: 1.5px solid #144523; padding: 20px 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; gap: 24px;">
                <div>
                    <span style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Selected SKUs</span>
                    <span id="summarySkuCount" style="font-size: 20px; font-weight: 800; color: #0f172a;">0 Items</span>
                </div>
                <div style="border-left: 1px solid #e2e8f0; padding-left: 24px;">
                    <span style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Units</span>
                    <span id="summaryUnitCount" style="font-size: 20px; font-weight: 800; color: #0f172a;">0 Units</span>
                </div>
                <div style="border-left: 1px solid #e2e8f0; padding-left: 24px;">
                    <span style="display: block; font-size: 11px; font-weight: 700; color: #144523; text-transform: uppercase;">Wholesale Order Total</span>
                    <span id="summaryGrandTotal" style="font-size: 24px; font-weight: 900; color: #144523;">$0.00</span>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="button" id="btnSubmitGrid" onclick="submitGridOrder()"
                    style="background: #144523; hover:background: #0b2212; color: #ffffff; font-size: 15px; font-weight: 800; padding: 12px 32px; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(20, 69, 35, 0.3); transition: all 0.2s ease;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span>Add All to Cart &rarr;</span>
                </button>
            </div>
        </div>

    </div>
</div>

<style>
.qo-row {
    background: #ffffff;
    transition: all 0.15s ease;
}
.qo-row:hover {
    background: #f8fafc;
}
.qo-input {
    width: 100%;
    padding: 9px 12px;
    font-size: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    outline: none;
    transition: all 0.15s ease;
}
.qo-input:focus {
    border-color: #144523;
    box-shadow: 0 0 0 3px rgba(20, 69, 35, 0.1);
}
.qo-autocomplete-wrap {
    position: relative;
}
.qo-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    width: 100%;
    min-width: 360px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    box-shadow: 0 15px 30px -5px rgba(0,0,0,0.22), 0 0 0 1px rgba(0,0,0,0.05);
    z-index: 99999;
    max-height: 260px;
    overflow-y: auto;
}
.qo-dropdown-item {
    padding: 10px 14px;
    font-size: 12.5px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
}
.qo-dropdown-item:hover, .qo-dropdown-item.active {
    background: #f0fdf4;
}
.qo-dropdown-item:last-child {
    border-bottom: none;
}
</style>

<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
let rowCounter = 0;

function createGridRowHtml(rowId) {
    return `
    <tr class="qo-row" id="row-${rowId}" data-row-id="${rowId}">
        <td style="padding: 10px 12px; font-weight: 700; color: #94a3b8; font-size: 12px;" class="row-number-cell">${rowId}</td>
        
        <!-- Search & Product Selection -->
        <td style="padding: 6px 12px; position: relative;">
            <div class="qo-autocomplete-wrap">
                <input type="text" class="qo-input search-input" placeholder="Search SKU (e.g. VAP-ELF, ELQ-NAS) or Product Name..."
                    autocomplete="off" oninput="handleSkuSearch(this, ${rowId})" onfocus="handleSkuSearch(this, ${rowId})" onkeydown="handleKeyNavigation(event, this, ${rowId})" />
                <input type="hidden" class="hidden-product-id" />
                <div class="qo-dropdown" style="display: none;"></div>
            </div>
            <div class="product-preview" style="display: none; align-items: center; gap: 8px; margin-top: 5px;">
                <img class="preview-img" src="" style="width: 26px; height: 26px; object-fit: contain; border-radius: 4px; border: 1px solid #e2e8f0; background: #f8fafc;" />
                <span class="preview-name" style="font-size: 11px; font-weight: 700; color: #0f172a; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></span>
            </div>
        </td>

        <!-- Stock Status -->
        <td style="padding: 6px 12px;">
            <span class="stock-badge" style="font-size: 11px; font-weight: 700; color: #94a3b8;">--</span>
        </td>

        <!-- Unit Price -->
        <td style="padding: 6px 12px;">
            <div class="price-box" style="font-size: 13px; font-weight: 800; color: #0f172a;">$0.00</div>
            <span class="tier-pill" style="display: none; font-size: 9px; font-weight: 700; color: #15803d; background: #dcfce7; padding: 1px 5px; border-radius: 4px;">Bulk Rate</span>
        </td>

        <!-- Quantity -->
        <td style="padding: 6px 12px;">
            <input type="number" class="qo-input qty-input" value="1" min="1" disabled onchange="handleQtyChange(${rowId})" oninput="handleQtyChange(${rowId})" style="width: 90px; text-align: center;" />
        </td>

        <!-- Line Total -->
        <td style="padding: 6px 12px;">
            <span class="line-total" style="font-size: 14px; font-weight: 900; color: #144523;">$0.00</span>
        </td>

        <!-- Delete Row -->
        <td style="padding: 6px 12px; text-align: center;">
            <button type="button" onclick="deleteRow(${rowId})" style="background: none; border: none; color: #94a3b8; hover:color: #dc2626; cursor: pointer; font-size: 18px; line-height: 1;">
                &times;
            </button>
        </td>
    </tr>
    `;
}

function addGridRow() {
    rowCounter++;
    const tbody = document.getElementById('quickOrderTbody');
    tbody.insertAdjacentHTML('beforeend', createGridRowHtml(rowCounter));
    reindexRowNumbers();
}

function deleteRow(rowId) {
    const rows = document.querySelectorAll('#quickOrderTbody .qo-row');
    if (rows.length <= 1) {
        // Clear row content instead of deleting last remaining row
        const row = document.getElementById(`row-${rowId}`);
        if (row) {
            row.querySelector('.search-input').value = '';
            row.querySelector('.hidden-product-id').value = '';
            row.querySelector('.product-preview').style.display = 'none';
            row.querySelector('.stock-badge').textContent = '--';
            row.querySelector('.stock-badge').style.color = '#94a3b8';
            row.querySelector('.price-box').textContent = '$0.00';
            row.querySelector('.tier-pill').style.display = 'none';
            row.querySelector('.qty-input').value = '1';
            row.querySelector('.qty-input').disabled = true;
            row.querySelector('.line-total').textContent = '$0.00';
            recalculateGridTotals();
        }
        return;
    }

    const row = document.getElementById(`row-${rowId}`);
    if (row) {
        row.remove();
        reindexRowNumbers();
        recalculateGridTotals();
    }
}

function reindexRowNumbers() {
    const rows = document.querySelectorAll('#quickOrderTbody .qo-row');
    rows.forEach((row, index) => {
        const cell = row.querySelector('.row-number-cell');
        if (cell) cell.textContent = index + 1;
    });
}

function clearAllRows() {
    document.getElementById('quickOrderTbody').innerHTML = '';
    rowCounter = 0;
    addGridRow(); // 1 default row
    recalculateGridTotals();
}

let searchDebounceTimer = null;
let currentDropdownItems = [];

function handleSkuSearch(inputEl, rowId) {
    const query = inputEl.value.trim();
    const dropdown = inputEl.parentElement.querySelector('.qo-dropdown');

    clearTimeout(searchDebounceTimer);
    if (query.length < 1) {
        dropdown.style.display = 'none';
        return;
    }

    dropdown.innerHTML = '<div style="padding: 10px 14px; font-size: 12px; color: #64748b; font-weight: 600;">🔍 Searching wholesale catalog...</div>';
    dropdown.style.display = 'block';

    searchDebounceTimer = setTimeout(() => {
        fetch(`{{ route('shop.quick-order.search') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(items => {
                currentDropdownItems = items;
                dropdown.innerHTML = '';
                if (items.length === 0) {
                    dropdown.innerHTML = '<div style="padding: 10px 14px; font-size: 12px; color: #94a3b8; font-weight: 600;">No matching active products found.</div>';
                    dropdown.style.display = 'block';
                    return;
                }

                items.forEach((p, idx) => {
                    const itemEl = document.createElement('div');
                    itemEl.className = 'qo-dropdown-item' + (idx === 0 ? ' active' : '');
                    itemEl.setAttribute('data-index', idx);
                    itemEl.innerHTML = `
                        <img src="${p.image}" style="width: 32px; height: 32px; object-fit: contain; border-radius: 6px; border: 1px solid #e2e8f0; flex-shrink: 0; background: #ffffff;" />
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 13px; line-height: 1.3;">${p.name}</div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                SKU: <strong style="color: #144523;">${p.sku}</strong> &middot; Stock: <strong>${p.stock}</strong> &middot; Base: <strong>$${p.base_price.toFixed(2)}</strong>
                            </div>
                        </div>
                    `;
                    itemEl.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectProductForRow(rowId, p);
                        dropdown.style.display = 'none';
                    });
                    dropdown.appendChild(itemEl);
                });
                dropdown.style.display = 'block';
            })
            .catch(err => {
                dropdown.innerHTML = '<div style="padding: 10px 14px; font-size: 12px; color: #dc2626;">Error loading search results.</div>';
            });
    }, 150);
}

function handleKeyNavigation(e, inputEl, rowId) {
    const dropdown = inputEl.parentElement.querySelector('.qo-dropdown');
    if (dropdown.style.display !== 'block') return;

    const items = dropdown.querySelectorAll('.qo-dropdown-item');
    if (!items.length) return;

    let activeIdx = -1;
    items.forEach((item, idx) => {
        if (item.classList.contains('active')) activeIdx = idx;
    });

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        const nextIdx = (activeIdx + 1) % items.length;
        items.forEach(i => i.classList.remove('active'));
        items[nextIdx].classList.add('active');
        items[nextIdx].scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        const prevIdx = (activeIdx - 1 + items.length) % items.length;
        items.forEach(i => i.classList.remove('active'));
        items[prevIdx].classList.add('active');
        items[prevIdx].scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (activeIdx >= 0 && currentDropdownItems[activeIdx]) {
            selectProductForRow(rowId, currentDropdownItems[activeIdx]);
            dropdown.style.display = 'none';
        } else if (currentDropdownItems.length > 0) {
            selectProductForRow(rowId, currentDropdownItems[0]);
            dropdown.style.display = 'none';
        }
    } else if (e.key === 'Escape') {
        dropdown.style.display = 'none';
    }
}

// Close dropdowns on outside click
document.addEventListener('click', (e) => {
    if (!e.target.closest('.qo-autocomplete-wrap')) {
        document.querySelectorAll('.qo-dropdown').forEach(d => d.style.display = 'none');
    }
});

function selectProductForRow(rowId, product) {
    const row = document.getElementById(`row-${rowId}`);
    if (!row) return;

    const inputSearch = row.querySelector('.search-input');
    const inputHiddenId = row.querySelector('.hidden-product-id');
    const previewDiv = row.querySelector('.product-preview');
    const previewImg = row.querySelector('.preview-img');
    const previewName = row.querySelector('.preview-name');
    const stockBadge = row.querySelector('.stock-badge');
    const qtyInput = row.querySelector('.qty-input');

    inputSearch.value = `${product.sku} - ${product.name}`;
    inputHiddenId.value = product.id;
    
    previewImg.src = product.image;
    previewName.textContent = product.name;
    previewDiv.style.display = 'flex';

    if (product.stock > 0) {
        stockBadge.textContent = `● ${product.stock} In Stock`;
        stockBadge.style.color = '#15803d';
    } else {
        stockBadge.textContent = `○ Out of Stock`;
        stockBadge.style.color = '#dc2626';
    }

    qtyInput.disabled = false;
    if (parseInt(qtyInput.value) < 1) qtyInput.value = 1;

    handleQtyChange(rowId);

    // AUTO-ADD NEXT ROW: If this is the last row in the table, automatically spawn the next empty row!
    const allRows = document.querySelectorAll('#quickOrderTbody .qo-row');
    const lastRow = allRows[allRows.length - 1];
    if (lastRow && lastRow.id === `row-${rowId}`) {
        addGridRow();
    }
}

function handleQtyChange(rowId) {
    const row = document.getElementById(`row-${rowId}`);
    if (!row) return;

    const productId = row.querySelector('.hidden-product-id').value;
    const qty = parseInt(row.querySelector('.qty-input').value) || 1;

    if (!productId) return;

    fetch(`{{ route('shop.quick-order.calculatePrice') }}?product_id=${productId}&quantity=${qty}`)
        .then(res => res.json())
        .then(data => {
            const priceBox = row.querySelector('.price-box');
            const tierPill = row.querySelector('.tier-pill');
            const lineTotal = row.querySelector('.line-total');
            const stockBadge = row.querySelector('.stock-badge');

            priceBox.textContent = `$${data.unit_price.toFixed(2)}`;
            lineTotal.textContent = `$${data.line_total.toFixed(2)}`;

            if (data.tier_discount) {
                tierPill.style.display = 'inline-block';
                tierPill.textContent = `Tier: -$${data.savings_unit.toFixed(2)}/unit`;
            } else {
                tierPill.style.display = 'none';
            }

            if (!data.in_stock) {
                stockBadge.textContent = `⚠️ Low: ${data.stock} Available`;
                stockBadge.style.color = '#d97706';
            } else {
                stockBadge.textContent = `● ${data.stock} In Stock`;
                stockBadge.style.color = '#15803d';
            }

            recalculateGridTotals();
        });
}

function recalculateGridTotals() {
    let totalSkus = 0;
    let totalUnits = 0;
    let grandTotal = 0;

    document.querySelectorAll('#quickOrderTbody .qo-row').forEach(row => {
        const productId = row.querySelector('.hidden-product-id').value;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        const lineTotalText = row.querySelector('.line-total').textContent.replace('$', '');
        const lineTotal = parseFloat(lineTotalText) || 0;

        if (productId && qty > 0) {
            totalSkus++;
            totalUnits += qty;
            grandTotal += lineTotal;
        }
    });

    document.getElementById('summarySkuCount').textContent = `${totalSkus} Items`;
    document.getElementById('summaryUnitCount').textContent = `${totalUnits} Units`;
    document.getElementById('summaryGrandTotal').textContent = `$${grandTotal.toFixed(2)}`;
}

function submitGridOrder() {
    const items = [];
    document.querySelectorAll('#quickOrderTbody .qo-row').forEach(row => {
        const productId = row.querySelector('.hidden-product-id').value;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        if (productId && qty > 0) {
            items.push({
                product_id: productId,
                quantity: qty
            });
        }
    });

    if (items.length === 0) {
        alert('Please select at least one product with quantity to add to cart.');
        return;
    }

    const btn = document.getElementById('btnSubmitGrid');
    btn.disabled = true;
    btn.innerHTML = 'Adding to Cart...';

    fetch(`{{ route('shop.quick-order.addBulk') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ items })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.cart_url;
        } else {
            alert(data.message || 'Failed to add items.');
            btn.disabled = false;
            btn.innerHTML = 'Add All to Cart &rarr;';
        }
    })
    .catch(err => {
        alert('An error occurred. Please make sure you are logged in.');
        btn.disabled = false;
        btn.innerHTML = 'Add All to Cart &rarr;';
    });
}

// Initial 1 Row Load
document.addEventListener('DOMContentLoaded', () => {
    addGridRow(); // exactly 1 initial row
});
</script>
@endsection
