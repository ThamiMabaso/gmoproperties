@extends('layouts.app')

@section('title', 'Thank You - GMO Properties')

@section('content')
<!-- Thank You Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Side - Content -->
            <div class="space-y-6">
                <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold">THANK YOU</h2>
                
                <div class="space-y-4 text-gray-700">
                    <p class="leading-relaxed">
                        Thank you for choosing GMO Properties as your real estate partner. We are honored that you have 
                        placed your trust in us to manage your property needs. Your confidence in our team and services 
                        drives us to deliver excellence in everything we do.
                    </p>
                    <p class="leading-relaxed">
                        We are committed to providing you with transparent, efficient, and personalized service that exceeds 
                        your expectations. Our team of experienced professionals is dedicated to ensuring that your property 
                        management experience is seamless, from tenant onboarding to financial reporting and maintenance management.
                    </p>
                    <p class="leading-relaxed">
                        If you have any questions or need assistance, please don't hesitate to reach out to us. We're here 
                        to help and look forward to building a long-lasting partnership with you.
                    </p>
                </div>

                <div class="pt-6">
                    <a href="{{ route('home') }}" 
                       class="inline-block bg-gmo-gold text-black px-8 py-3 font-bold hover:bg-opacity-90 transition-colors">
                        Return to Home
                    </a>
                </div>
            </div>

            <!-- Right Side - Images -->
            <div class="space-y-6">
                <div class="relative h-64 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&h=400&fit=crop" 
                         alt="Modern Building" 
                         class="w-full h-full object-cover">
                </div>
                <div class="relative h-64 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=400&fit=crop" 
                         alt="Residential Complex" 
                         class="w-full h-full object-cover">
                </div>
                <div class="relative h-64 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop" 
                         alt="Property Facade" 
                         class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
