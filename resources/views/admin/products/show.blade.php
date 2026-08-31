@extends('layouts.admin')

@section('title', 'Product Preview: ' . $product->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <!-- Top Action Bar & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Products Catalog
            </a>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $product->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    {{ $product->is_active ? '● ACTIVE' : '○ INACTIVE' }}
                </span>
                <span class="font-mono bg-slate-100 text-slate-700 text-xs px-2.5 py-0.5 rounded-md border border-slate-200 font-semibold">
                    SKU: {{ $product->sku }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('shop.product', $product->id) }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                View on Storefront
            </a>
            @if(auth()->check() && auth()->user()->hasPermission('ADMIN', 'Product', 'UPDATE'))
            <a href="{{ route('admin.products.edit', $product) }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Product
            </a>
            @endif
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT COLUMN: Media Gallery & Highlights (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Showcase Photo Box -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="relative h-72 bg-slate-50 rounded-xl border border-slate-200 overflow-hidden flex items-center justify-center p-4">
                    <!-- Stock Badge -->
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-md shadow-sm z-10 
                        {{ $product->total_stock > 10 ? 'bg-emerald-500/90 text-white' : ($product->total_stock > 0 ? 'bg-amber-500/90 text-white' : 'bg-rose-500/90 text-white') }}">
                        @if($product->total_stock > 10)
                            ● In Stock ({{ $product->total_stock }} {{ $product->unit->short_code ?? 'units' }})
                        @elseif($product->total_stock > 0)
                            ▲ Low Stock ({{ $product->total_stock }})
                        @else
                            ✕ Out of Stock
                        @endif
                    </span>

                    <img id="mainDisplayPhoto" src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                        class="max-w-full max-h-full object-contain transition-all duration-200"
                        onerror="this.onerror=null;this.src='{{ asset('images/product1.png') }}';">
                </div>

                <!-- Thumbnail Selector Strip -->
                @if($product->images->count() > 1)
                <div class="flex items-center gap-2.5 overflow-x-auto pb-1">
                    @foreach($product->images as $index => $img)
                    <button type="button" onclick="changeShowcasePhoto('{{ $img->url }}', this)"
                        class="thumbnail-btn w-16 h-16 rounded-xl bg-slate-50 border-2 {{ $index === 0 ? 'border-blue-600' : 'border-slate-200' }} hover:border-blue-400 p-1 flex-shrink-0 flex items-center justify-center transition-all overflow-hidden">
                        <img src="{{ $img->url }}" alt="Thumb" class="max-w-full max-h-full object-contain">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Wholesale Pricing Overview Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2.5">
                    Wholesale Key Metrics
                </h3>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                        <span class="text-slate-400 block text-[11px]">Base Unit Rate</span>
                        <span class="text-lg font-bold text-slate-900 mt-0.5 block">${{ number_format($product->base_price, 2) }}</span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                        <span class="text-slate-400 block text-[11px]">Unit Measure</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $product->unit->name ?? 'Piece' }} ({{ $product->unit->short_code ?? 'pc' }})</span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                        <span class="text-slate-400 block text-[11px]">Total Stock</span>
                        <span class="text-sm font-bold text-emerald-700 mt-1 block">{{ $product->total_stock }} Available</span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                        <span class="text-slate-400 block text-[11px]">Net Weight / Pack Size</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $product->formatted_weight ?: 'Standard' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Specifications, Tiers, Variants, Description (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- CARD 1: Department & Specifications -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">1</span>
                        Catalog Specifications
                    </h2>
                    <span class="text-[11px] text-slate-400 font-mono">ID #{{ $product->id }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Department / Category:</span>
                        <div class="font-bold text-slate-800 text-sm mt-0.5 flex items-center gap-1.5">
                            <span>{{ $product->category->name ?? 'Unassigned' }}</span>
                            @if($product->category && $product->category->parent)
                            <span class="text-slate-400 font-normal text-xs">({{ $product->category->parent->name }})</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">SKU Code:</span>
                        <span class="font-mono font-bold text-slate-900 text-sm mt-0.5 block">{{ $product->sku }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">Visibility Status:</span>
                        <span class="inline-flex items-center gap-1.5 font-bold text-xs mt-1 {{ $product->is_active ? 'text-emerald-700' : 'text-slate-600' }}">
                            <span class="w-2 h-2 rounded-full {{ $product->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            {{ $product->is_active ? 'Active on B2B Storefront' : 'Hidden from Catalog' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">Created Date:</span>
                        <span class="font-semibold text-slate-700 mt-1 block">{{ $product->created_at->format('F d, Y - h:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- CARD 2: Wholesale Volume Pricing Tiers -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">2</span>
                        Wholesale Volume Discount Tiers
                    </h2>
                    <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">
                        {{ $product->priceTiers->count() }} Tiers
                    </span>
                </div>

                @if($product->priceTiers->isNotEmpty())
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase text-[11px]">
                            <tr>
                                <th class="p-3">Order Quantity Bracket</th>
                                <th class="p-3">Discounted Unit Rate</th>
                                <th class="p-3 text-right">Buyer Benefit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($product->priceTiers as $tier)
                            @php
                                $savings = ($product->base_price > 0 && $tier->price < $product->base_price)
                                    ? round((($product->base_price - $tier->price) / $product->base_price) * 100)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-3 font-bold text-slate-800">
                                    {{ $tier->min_qty }} {{ $tier->max_qty ? '- ' . $tier->max_qty : '+ units (Master Tier)' }}
                                </td>
                                <td class="p-3 font-bold text-emerald-700 text-sm">
                                    ${{ number_format($tier->price, 2) }}
                                </td>
                                <td class="p-3 text-right">
                                    @if($savings > 0)
                                    <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                        Save {{ $savings }}%
                                    </span>
                                    @else
                                    <span class="bg-slate-100 text-slate-600 text-[11px] font-medium px-2 py-0.5 rounded">
                                        Tier Rate
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-center text-xs text-slate-400">
                    No quantity discount tiers configured for this product. Single base price applies.
                </div>
                @endif
            </div>

            <!-- CARD 3: Variants & Multi-Warehouse Stock -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">3</span>
                        Variants & Inventory Stock
                    </h2>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                        {{ $product->variants->count() }} Variants
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-600 uppercase text-[11px]">
                            <tr>
                                <th class="p-3">Variant SKU</th>
                                <th class="p-3">Size / Spec</th>
                                <th class="p-3">Color / Flavor</th>
                                <th class="p-3">Warehouse</th>
                                <th class="p-3 text-right">In-Stock Qty</th>
                                <th class="p-3 text-right">Low Alert</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($product->variants as $variant)
                            @php
                                $stock = $variant->stocks->first();
                                $qty = $stock ? $stock->quantity : 0;
                                $threshold = $stock ? $stock->threshold : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-3 font-mono font-bold text-slate-900">
                                    {{ $variant->variant_sku }}
                                </td>
                                <td class="p-3 text-slate-700">
                                    {{ $variant->size ?? 'Standard' }}
                                </td>
                                <td class="p-3 text-slate-700">
                                    {{ $variant->color ?? '-' }}
                                </td>
                                <td class="p-3 text-slate-500">
                                    {{ $stock && $stock->warehouse ? $stock->warehouse->name : 'Main Warehouse' }}
                                </td>
                                <td class="p-3 text-right">
                                    <span class="font-bold {{ $qty > $threshold ? 'text-emerald-700' : ($qty > 0 ? 'text-amber-600' : 'text-rose-600') }}">
                                        {{ $qty }} units
                                    </span>
                                </td>
                                <td class="p-3 text-right text-slate-500">
                                    {{ $threshold }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-slate-400 text-xs">No variant rows found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CARD 4: Description & Specifications Box -->
            @if($product->description)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-3">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wide flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">4</span>
                    Product Description & Technical Specifications
                </h2>
                <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed text-xs pt-1">
                    {!! $product->description !!}
                </div>
            </div>
            @endif

        </div>

    </div>
</div>

<script>
function changeShowcasePhoto(url, btn) {
    document.getElementById('mainDisplayPhoto').src = url;
    document.querySelectorAll('.thumbnail-btn').forEach(b => {
        b.classList.remove('border-blue-600');
        b.classList.add('border-slate-200');
    });
    btn.classList.remove('border-slate-200');
    btn.classList.add('border-blue-600');
}
</script>
@endsection