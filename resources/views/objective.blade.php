@extends('layouts.app')

@section('title', 'Our Objectives - GMO Properties')

@section('content')
<!-- Objectives Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Left Side - Images -->
            <div class="space-y-6">
                <div class="relative h-80 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=500&fit=crop" 
                         alt="Building Facade" 
                         class="w-full h-full object-cover">
                </div>
                <div class="relative h-80 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=500&fit=crop" 
                         alt="Modern Architecture" 
                         class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="space-y-8">
                <div class="text-right">
                    <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold mb-4">OUR OBJECTIVE</h2>
                    <h3 class="text-2xl font-bold text-gmo-gold">Objective</h3>
                </div>

                <!-- Short Term Objectives -->
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">01</div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-2">Short Term Objectives (0-12 Months)</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700 ml-4">
                                <li>Onboard 50+ property companies to the platform</li>
                                <li>Achieve 100% POPIA compliance certification</li>
                                <li>Implement AI-powered tenant screening and document processing</li>
                                <li>Launch mobile applications for iOS and Android</li>
                                <li>Integrate with major payment gateways (PayFast, PayGate)</li>
                                <li>Establish partnerships with 10+ universities and educational institutions</li>
                                <li>Reduce tenant onboarding time by 60% through automation</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Medium Term Objectives -->
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">02</div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-2">Medium Term Objectives (1-3 Years)</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700 ml-4">
                                <li>Scale to manage 500+ properties across South Africa</li>
                                <li>Expand services to commercial property management</li>
                                <li>Develop predictive analytics for maintenance and tenant retention</li>
                                <li>Launch marketplace for contractors and service providers</li>
                                <li>Implement blockchain-based contract management</li>
                                <li>Achieve ISO 27001 certification for information security</li>
                                <li>Establish presence in 5 major South African cities</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Long Term Objectives -->
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">03</div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-2">Long Term Objectives (3-5 Years)</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700 ml-4">
                                <li>Become the market leader in property management technology in South Africa</li>
                                <li>Expand operations to other African countries</li>
                                <li>Develop AI-powered property valuation and investment analysis tools</li>
                                <li>Launch property investment platform for individual investors</li>
                                <li>Establish strategic partnerships with major real estate developers</li>
                                <li>Build a comprehensive ecosystem of property-related services</li>
                                <li>Achieve 10,000+ active properties under management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
