@extends('frontend.layouts.app')

@section('title', 'Checkout — Carolina Prime Distributors')

@section('content')
<section class="section">
    <div class="container">
        
        @if(session('error'))
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="cart-layout" style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 30px; align-items: start;">
                
                <!-- Left: Shipping Addresses -->
                <div style="background: #fff; padding: 25px; border: 1px solid #e5e5e5; border-radius: 8px;">
                    <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px;">Delivery Address</h3>

                    @if($addresses->isNotEmpty())
                        <div style="margin-bottom: 20px;">
                            <label style="font-weight: 600; display: block; margin-bottom: 12px;">Choose a Saved Address:</label>
                            
                            @foreach($addresses as $addr)
                            <div style="border: 1px solid #ddd; padding: 12px 16px; border-radius: 6px; margin-bottom: 10px; display: flex; gap: 12px; align-items: start;">
                                <input type="radio" name="address_choice" value="saved" id="addr_{{ $addr->id }}" 
                                       onclick="toggleAddressSection('saved', {{ $addr->id }})" 
                                       {{ $loop->first && !old('address_choice') ? 'checked' : '' }} 
                                       {{ old('selected_address_id') == $addr->id ? 'checked' : '' }} 
                                       style="margin-top: 4px;">
                                <label for="addr_{{ $addr->id }}" style="cursor: pointer; font-size: 14px; flex-grow: 1;">
                                    <strong>{{ $addr->name }}</strong> &middot; {{ $addr->phone }}<br>
                                    <span style="color: #666;">{{ $addr->address_line_1 }}, {{ $addr->address_line_2 ? $addr->address_line_2 . ',' : '' }} {{ $addr->city }}, {{ $addr->state }} - {{ $addr->pincode }}</span>
                                </label>
                            </div>
                            @endforeach

                            <input type="hidden" name="selected_address_id" id="selected_address_id" value="{{ old('selected_address_id', $addresses->first()->id) }}">

                            <div style="border: 1px dashed #2d6a4f; background: #f4fbf7; padding: 12px 16px; border-radius: 6px; margin-top: 15px; display: flex; gap: 12px; align-items: center;">
                                <input type="radio" name="address_choice" value="new" id="addr_new_choice" 
                                       onclick="toggleAddressSection('new')" 
                                       {{ old('address_choice') === 'new' ? 'checked' : '' }}>
                                <label for="addr_new_choice" style="cursor: pointer; font-weight: 600; color: #2d6a4f; margin: 0;">
                                    + Add & Ship to a New Address
                                </label>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="address_choice" value="new">
                    @endif

                    <!-- Add New Address Form Fields -->
                    <div id="new-address-form" style="{{ $addresses->isNotEmpty() && old('address_choice') !== 'new' ? 'display: none;' : '' }} margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                        <h4 style="margin-top: 0; margin-bottom: 15px; font-size: 16px; color: #333;">Enter New Address Details:</h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name', $customer->name) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Phone Number *</label>
                                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Street Address (Line 1) *</label>
                            <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Apartment, Suite, Unit (Line 2 Optional)</label>
                            <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">City *</label>
                                <input type="text" name="city" value="{{ old('city') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">State *</label>
                                <input type="text" name="state" value="{{ old('state') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Pincode *</label>
                                <input type="text" name="pincode" value="{{ old('pincode') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>

                        <input type="hidden" name="country" value="India">
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div style="background: #fff; padding: 25px; border: 1px solid #e5e5e5; border-radius: 8px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px;">Order Summary</h3>
                    
                    <div style="max-height: 250px; overflow-y: auto; margin-bottom: 15px;">
                        @foreach($cartItems as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding: 8px 0; font-size: 14px;">
                            <div>
                                <strong>{{ $item->product->name }}</strong>
                                <div style="font-size: 12px; color: #777;">Qty: {{ $item->quantity }}</div>
                            </div>
                            <div>
                                @php
                                    $price = $item->product->base_price;
                                    if ($customer->customer_group_id) {
                                        $tier = $item->product->priceTiers->where('customer_group_id', $customer->customer_group_id)->first();
                                        if ($tier) $price = $tier->price;
                                    }
                                @endphp
                                ₹{{ number_format($price * $item->quantity, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="border-top: 2px solid #ddd; padding-top: 12px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
                            <span>Total Payable:</span>
                            <span style="color: #1a8917;">₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--primary btn--block" style="width: 100%; padding: 12px; font-size: 16px; cursor: pointer;">
                        Confirm & Place Order
                    </button>
                </div>

            </div>
        </form>
    </div>
</section>

<script>
function toggleAddressSection(type, addressId = null) {
    const newForm = document.getElementById('new-address-form');
    const selectedInput = document.getElementById('selected_address_id');
    
    if (type === 'new') {
        newForm.style.display = 'block';
    } else {
        newForm.style.display = 'none';
        if (selectedInput && addressId) {
            selectedInput.value = addressId;
        }
    }
}
</script>
@endsection