<div>
    <!-- component -->

    <div x-data="{ cartOpen: false, isOpen: false }" class="bg-white">
        <header>
            <div class="container px-6 py-3 mx-auto">
                <div class="flex items-center justify-between">
                    <div class="hidden w-full text-gray-600 md:flex md:items-center">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16.2721 10.2721C16.2721 12.4813 14.4813 14.2721 12.2721 14.2721C10.063 14.2721 8.27214 12.4813 8.27214 10.2721C8.27214 8.06298 10.063 6.27212 12.2721 6.27212C14.4813 6.27212 16.2721 8.06298 16.2721 10.2721ZM14.2721 10.2721C14.2721 11.3767 13.3767 12.2721 12.2721 12.2721C11.1676 12.2721 10.2721 11.3767 10.2721 10.2721C10.2721 9.16755 11.1676 8.27212 12.2721 8.27212C13.3767 8.27212 14.2721 9.16755 14.2721 10.2721Z"
                                fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.79417 16.5183C2.19424 13.0909 2.05438 7.39409 5.48178 3.79417C8.90918 0.194243 14.6059 0.054383 18.2059 3.48178C21.8058 6.90918 21.9457 12.6059 18.5183 16.2059L12.3124 22.7241L5.79417 16.5183ZM17.0698 14.8268L12.243 19.8965L7.17324 15.0698C4.3733 12.404 4.26452 7.97318 6.93028 5.17324C9.59603 2.3733 14.0268 2.26452 16.8268 4.93028C19.6267 7.59603 19.7355 12.0268 17.0698 14.8268Z"
                                fill="currentColor" />
                        </svg>
                        <span class="mx-1 text-sm">{{ $branch->name }}</span>
                    </div>
                    <div class="w-full text-2xl font-semibold text-gray-700 md:text-center">
                        <img style="width:150px" src="{{ Storage::url($branch->logo) }}" alt="" srcset="">
                    </div>
                    <div class="flex items-center justify-end w-full">
                        <button @click="cartOpen = !cartOpen" class="mx-4 text-gray-600 focus:outline-none sm:mx-0">
                            @livewire('cart-count')
                        </button>


                    </div>
                </div>

                <div class="relative max-w-lg mx-auto mt-6">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>

                    <input wire:model.live='search'
                        class="w-full py-2 pl-10 pr-4 border rounded-md focus:border-blue-500 focus:outline-none focus:shadow-outline"
                        type="text" placeholder="Search">
                </div>
            </div>
        </header>
        <div :class="cartOpen ? 'translate-x-0 ease-out' : 'translate-x-full ease-in'"
            class="fixed top-0 right-0 w-full h-full max-w-xs px-6 py-4 overflow-y-auto transition duration-300 transform bg-white border-l-2 border-gray-300">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-medium text-gray-700">Your cart</h3>
                <button @click="cartOpen = !cartOpen" class="text-gray-600 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <hr class="my-3">

            <div>
                @if ($cartData != null)

                    @forelse ($cartData as $key => $item)
                        @php
                            if ($item) {
                                $product = \App\Models\Product::find($item['product_id']);
                            }

                        @endphp

                        @if ($product)
                            <div class="flex justify-between mt-6">
                                <div class="flex">

                                    <img class="object-cover w-20 h-20 rounded"
                                        src="{{ Storage::url($product->image) }}" alt="">
                                    <div class="mx-3">
                                        <h3 class="text-sm font-bold text-gray-600">{{ $product->name }}</h3>
                                        <h3 class="text-sm font-bold text-red-400">Size: {{ $item['size'] }}</h3>
                                        <h3 class="text-sm font-bold text-red-400">
                                            {{ $item['quantity'] . ' X ' . number_format($product->price) }}</h3>
                                        <div class="flex items-center mt-2">

                                            <button wire:click="removeFromCart({{ $product->id }})"
                                                class="text-red-500 focus:outline-none focus:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-gray-600">Rs.
                                    {{ number_format($product->price * $item['quantity']) }}</span>
                            </div>
                        @endif

                    @empty
                        <p>Your cart is empty</p>
                    @endforelse
                    <!-- Display Total Price -->
                    <div class="mt-6">
                        <h3 class="text-xl font-medium text-gray-700">Total: Rs. {{ number_format($totalPrice) }}</h3>
                    </div>

                    <a href="{{ route('check-out') }}"
                        class="flex items-center justify-center px-3 py-2 mt-4 text-sm font-medium text-white uppercase bg-red-600 rounded hover:bg-red-500 focus:outline-none focus:bg-red-500">
                        <span>Chechout</span>
                        <svg class="w-5 h-5 mx-2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
        <main class="my-8">
            <div class="container px-6 mx-auto">
                <h3 class="text-2xl font-medium text-gray-700">All Products</h3>

                <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                    @forelse ($productsQuery as $product)
                        <div class="w-full max-w-sm mx-auto overflow-hidden rounded-md shadow-md"
                            wire:key="{{ $product->id }}">
                            <div class="flex items-end justify-end w-full bg-cover h-96"
                                style="background-image: url({{ Storage::url($product->image) }})">
                            </div>
                            <div class="px-5 py-3">
                                <h3 class="font-bold text-gray-700 uppercase">{{ $product->name }}</h3>
                                <span class="mt-2 font-bold text-blue-500">Rs.
                                    {{ number_format($product->price) }}</span>
                                <div class="mt-4">
                                    <label for="size" class="block text-sm text-gray-600">Size</label>
                                    <select id="size" required wire:model="selectedSize.{{ $product->id }}"
                                        class="w-full px-2 py-1 border rounded-md">
                                        @foreach ($product->variations as $key => $variation)
                                            <option value="{{ $variation }}">
                                                {{ $variation }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <label for="quantity" class="block mt-2 text-sm text-gray-600">Quantity</label>
                                    <input type="number" required id="quantity" placeholder="1"
                                        wire:model="selectedQuantity.{{ $product->id }}"
                                        class="w-full px-2 py-1 border rounded-md" min="1" max="10">

                                    <button wire:click="addToCart({{ $product->id }}).live"
                                        class="w-full px-4 py-2 mt-2 text-white bg-red-600 rounded-md hover:bg-red-500">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No products found</p>
                    @endforelse



                </div>
                <div class="flex justify-center pt-8">
                    {{ $productsQuery->links() }}
                </div>
            </div>
        </main>

        <footer class="bg-gray-200">
            <div class="container flex items-center justify-between px-6 py-3 mx-auto">
                <a href="#" class="text-xl font-bold text-gray-500 hover:text-gray-400">{{ $branch->name }}</a>
                <p class="py-2 text-gray-500 sm:py-0">Developed By <a href="https://zenax.info/">ZENAX</a></p>
            </div>
        </footer>
    </div>
</div>
