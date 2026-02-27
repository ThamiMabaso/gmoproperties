@extends('layouts.app')

@section('title', 'Contact Us - GMO Properties')

@section('content')
<!-- Contact Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Left Side - Image -->
            <div class="relative h-96 lg:h-[500px] diagonal-frame">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop" 
                     alt="Contact Building" 
                     class="w-full h-full object-cover">
            </div>

            <!-- Right Side - Contact Information -->
            <div class="space-y-8">
                <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold text-right">OUR CONTACT</h2>
                
                <div class="space-y-6">
                    <!-- Phone -->
                    <div class="flex items-center justify-end space-x-4">
                        <div class="text-right">
                            <p class="font-bold text-lg mb-1">Phone</p>
                            <p class="text-gray-700">+27 81 011 5441</p>
                        </div>
                        <div class="w-12 h-12 bg-gmo-gold flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Website -->
                    <div class="flex items-center justify-end space-x-4">
                        <div class="text-right">
                            <p class="font-bold text-lg mb-1">Website</p>
                            <a href="https://www.gmoproperties.co.za" target="_blank" class="text-gray-700 hover:text-gmo-gold transition-colors">www.gmoproperties.co.za</a>
                        </div>
                        <div class="w-12 h-12 bg-gmo-gold flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center justify-end space-x-4">
                        <div class="text-right">
                            <p class="font-bold text-lg mb-1">Email</p>
                            <a href="mailto:info@gmoproperties.co.za" class="text-gray-700 hover:text-gmo-gold transition-colors">info@gmoproperties.co.za</a>
                        </div>
                        <div class="w-12 h-12 bg-gmo-gold flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-center justify-end space-x-4">
                        <div class="text-right">
                            <p class="font-bold text-lg mb-1">Head Office</p>
                            <p class="text-gray-700">52F, Lillian Ngoyi Street<br>Pretoria Central</p>
                        </div>
                        <div class="w-12 h-12 bg-gmo-gold flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gmo-gold mb-6 text-right">Send us a Message</h3>
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="name" placeholder="Your Name" required
                                   class="w-full px-4 py-3 border border-gray-300 focus:border-gmo-gold focus:ring-2 focus:ring-gmo-gold outline-none">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="email" name="email" placeholder="Your Email" required
                                   class="w-full px-4 py-3 border border-gray-300 focus:border-gmo-gold focus:ring-2 focus:ring-gmo-gold outline-none">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="tel" name="phone" placeholder="Your Phone (Optional)"
                                   class="w-full px-4 py-3 border border-gray-300 focus:border-gmo-gold focus:ring-2 focus:ring-gmo-gold outline-none">
                        </div>
                        <div>
                            <textarea name="message" rows="5" placeholder="Your Message" required
                                      class="w-full px-4 py-3 border border-gray-300 focus:border-gmo-gold focus:ring-2 focus:ring-gmo-gold outline-none"></textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-right">
                            <button type="submit" 
                                    class="bg-gmo-gold text-black px-8 py-3 font-bold hover:bg-opacity-90 transition-colors">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
