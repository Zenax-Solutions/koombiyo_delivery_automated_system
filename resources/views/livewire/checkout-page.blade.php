<div>

    <div class="flex items-center justify-center">
        <div class="mx-auto w-full max-w-[550px] md:w-full bg-white">
            <form wire:submit.prevent="placeOrder" class="py-6 px-9">
                <div class="w-full px-2 py-4 mb-5 bg-white border border-gray-200 shadow-md rounded-xl shadow-gray-100">
                    <div class="flex items-center justify-between px-2 text-base font-medium text-gray-700">
                        <div>Cart</div>
                    </div>
                    <div class="mt-4">
                        <div class="flex max-h-[400px] w-full flex-col overflow-y-scroll">
                            @foreach ($cart as $productId => $item)
                                @php
                                    $product = \App\Models\Product::find($item['product_id']);
                                @endphp
                                <div
                                    class="group flex items-center gap-x-5 rounded-md px-2.5 py-2 transition-all duration-75 hover:bg-green-100">
                                    <div
                                        class="flex items-center text-black bg-gray-200 rounded-lg group-hover:bg-green-200">
                                        <img style="width:120px; object-fit: cover; height:120px; border-radius: 8px;"
                                            src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                    </div>
                                    <div class="flex flex-col items-start justify-between font-light text-gray-600">
                                        <p class="text-[15px] font-bold">{{ $product->name }}</p>
                                        <span class="text-xs font-bold text-blue-400">Rs. {{ $product->price }}</span>
                                        <span class="text-xs font-bold">Quantity: {{ $item['quantity'] }}</span>
                                        <button type="button" wire:click="removeFromCart({{ $productId }})"
                                            class="text-red-500">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="w-full px-4 py-5 mt-6 bg-gray-100 border border-gray-300 rounded-lg shadow-md">
                        <h1 class="text-lg font-bold text-black">Total Price: <span class="text-red-400">
                                Rs.{{ number_format($totalPrice) }}</span>
                        </h1>
                        <h1 class="text-lg font-bold text-black">Delivery Charges: <span class="text-red-400">
                                Rs.{{ number_format(env('DELIVERY_CHARGES', 0)) }}</span>
                        </h1>
                        <p>(All Island Delivery 🚚)</p>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="mb-5 text-green-500">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">Enter Your Full Name:</label>
                    <input type="text" wire:model="name" placeholder="John Doe" required
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                </div>

                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">Enter Your Address:</label>
                    <textarea wire:model="address" required placeholder="John Doe"
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"></textarea>
                </div>

                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">Select Your District:</label>
                    <select wire:model.live="district"
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                        <option selected>Choose a district</option>
                        @if (isset($branch->api_key) && $branch->api_enable == true)
                            @foreach ($districtList as $list)
                                <option value="{{ $list['district_id'] }}">{{ $list['district_name'] }}</option>
                            @endforeach
                        @else
                            @foreach ($districtList as $list)
                                <option value="{{ $list->id }}">{{ $list->name_en }}</option>
                            @endforeach
                        @endif


                    </select>
                </div>

                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">Select Your City:</label>
                    <select wire:model="city"
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                        <option selected>Choose a city</option>

                        @if (isset($branch->api_key) && $branch->api_enable == true)
                            @foreach ($cityList as $list)
                                <option value="{{ $list['city_id'] }}">{{ $list['city_name'] }}</option>
                            @endforeach
                        @else
                            @foreach ($cityList as $list)
                                <option value="{{ $list->id }}">{{ $list->name_en }}</option>
                            @endforeach

                        @endif
                    </select>
                </div>

                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">Enter Your Personal Contact
                        No:</label>
                    <input type="tel" wire:model="primaryContact" placeholder="07X-XXXX-XXX" required
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                </div>

                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">Enter Your Second Contact No:</label>
                    <input type="text" wire:model="secondaryContact" placeholder="optional"
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                </div>

                <div>
                    <button type="submit"
                        class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none">Place
                        Your Order</button>

                </div>

                <div
                    class="w-full px-8 py-3 mt-4 text-base font-semibold text-center text-white bg-green-400 rounded-md outline-none hover:shadow-form">
                    <a href="{{ route('order-form', ['branch' => session()->has('slug') ? session('slug') : '/']) }}">Back
                        to Shop</a>
                </div>
            </form>
        </div>
    </div>


</div>
