@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<!-- Header Section -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Products</h1>
        <p class="text-sm text-slate-500 mt-1">Manage your catalog, stock, and pricing details.</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-blue-200 active:scale-[0.98]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Add Product
    </a>
</div>

<!-- Success Alert -->
@if(session('success'))
<div
    class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- Product Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($products as $product)
    @php
    // Calculate total stock across all variants
    $totalStock = $product->variants ? $product->variants->flatMap->stocks->sum('quantity') : 0;
    @endphp

    <div
        class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col overflow-hidden group">

        <!-- Card Top: Image Scroll & Status Badges -->
        <div class="relative h-60 bg-slate-50/80 border-b border-slate-100 overflow-hidden group/carousel">

            <!-- Stock Status Badge (Top Left) -->
            <span
                class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold backdrop-blur-md shadow-sm z-10 pointer-events-none 
                    {{ $totalStock > 5 ? 'bg-emerald-500/90 text-white' : ($totalStock > 0 ? 'bg-amber-500/90 text-white' : 'bg-rose-500/90 text-white') }}">
                @if($totalStock > 5)
                In Stock ({{ $totalStock }})
                @elseif($totalStock > 0)
                Low Stock ({{ $totalStock }})
                @else
                Out of Stock
                @endif
            </span>

            <!-- Active/Inactive Badge (Top Right) -->
            <span
                class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-semibold backdrop-blur-md shadow-sm z-10 pointer-events-none {{ $product->is_active ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-200' : 'bg-slate-500/10 text-slate-600 border border-slate-200' }}">
                {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>

            @if($product->images->isNotEmpty())
            <!-- Horizontal Image Scroll Container -->
            <div
                class="product-carousel flex overflow-x-auto snap-x snap-mandatory h-full w-full scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                @foreach($product->images as $image)
                <div class="w-full h-full flex-shrink-0 snap-center p-3 flex items-center justify-center">
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}"
                        class="max-w-full max-h-full object-contain pointer-events-none">
                </div>
                @endforeach
            </div>

            @if($product->images->count() > 1)
            <div
                class="absolute bottom-2 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10 bg-slate-900/40 backdrop-blur-md px-2.5 py-1 rounded-full">
                @foreach($product->images as $index => $img)
                <span class="w-1.5 h-1.5 rounded-full bg-white/60"></span>
                @endforeach
            </div>

            <span
                class="absolute bottom-2 right-2 text-[10px] bg-slate-800/60 text-white px-1.5 py-0.5 rounded backdrop-blur-sm pointer-events-none">
                {{ $product->images->count() }} Images
            </span>
            @endif
            @else
            <div class="h-full flex flex-col items-center justify-center text-slate-300 gap-1">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                <span class="text-xs font-medium text-slate-400">No Image</span>
            </div>
            @endif
        </div>

        <!-- Card Body -->
        <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between text-xs text-slate-400 mb-1.5">
                    <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-600">SKU:
                        {{ $product->sku }}</span>
                    <span
                        class="truncate max-w-[100px] text-right font-medium text-slate-500">{{ $product->category->name ?? 'Uncategorized' }}</span>
                </div>

                <h3
                    class="font-semibold text-slate-800 text-base leading-snug line-clamp-1 group-hover:text-blue-600 transition-colors">
                    {{ $product->name }}
                </h3>
            </div>

            <!-- Price & Actions Footer -->
            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Price</span>
                    <span class="text-lg font-bold text-slate-900">₹{{ number_format($product->base_price, 2) }}</span>
                </div>

                <div class="flex items-center gap-1">
                    <!-- Edit Button -->
                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                        title="Edit Product">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                    </a>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Delete Product">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">No products found</h3>
        <p class="text-sm text-slate-500 mt-1">Get started by adding a new product to your inventory.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $products->links() }}
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousels = document.querySelectorAll('.product-carousel');

    carousels.forEach(carousel => {
        const items = Array.from(carousel.children);
        if (items.length <= 1) return;

        // 1st image ki clone copy banakar end me add karein (Seamless Loop ke liye)
        const firstClone = items[0].cloneNode(true);
        carousel.appendChild(firstClone);

        let currentIndex = 0;
        let autoScrollTimer;
        const totalOriginalItems = items.length;

        const slideNext = () => {
            currentIndex++;
            const itemWidth = carousel.clientWidth;

            carousel.scrollTo({
                left: currentIndex * itemWidth,
                behavior: 'smooth'
            });

            if (currentIndex === totalOriginalItems) {
                setTimeout(() => {
                    carousel.scrollTo({
                        left: 0,
                        behavior: 'instant'
                    });
                    currentIndex = 0;
                }, 500); 
            }
        };

        const startTimer = () => {
            autoScrollTimer = setInterval(slideNext, 2500); // Har 2.5 sec me slide hoga
        };

        startTimer();

        // Hover karne par pause ho jaye
        const parentCard = carousel.closest('.group\\/carousel');
        if (parentCard) {
            parentCard.addEventListener('mouseenter', () => clearInterval(autoScrollTimer));
            parentCard.addEventListener('mouseleave', () => startTimer());
        }
    });
});
</script>
@endsection