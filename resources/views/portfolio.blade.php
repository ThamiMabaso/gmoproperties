@extends('layouts.app')

@section('title', 'Our Portfolio - GMO Properties')

@section('content')
<!-- Portfolio Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold mb-12">OUR PORTFOLIO</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Portfolio Item 1 -->
            <div class="relative h-80 diagonal-frame group">
                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&h=800&fit=crop" 
                     alt="Interior Design" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                    <p class="text-white opacity-0 group-hover:opacity-100 transition-opacity font-bold text-xl">Modern Interiors</p>
                </div>
            </div>

            <!-- Portfolio Item 2 -->
            <div class="relative h-80 diagonal-frame group">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=800&fit=crop" 
                     alt="The Crest Building" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                    <p class="text-white opacity-0 group-hover:opacity-100 transition-opacity font-bold text-xl">The Crest</p>
                </div>
            </div>

            <!-- Portfolio Item 3 -->
            <div class="relative h-80 diagonal-frame group">
                <img src="https://images.unsplash.com/photo-1556912172-45b7abe8b7c8?w=600&h=800&fit=crop" 
                     alt="Modern Kitchen" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                    <p class="text-white opacity-0 group-hover:opacity-100 transition-opacity font-bold text-xl">Luxury Living</p>
                </div>
            </div>
        </div>

        <!-- Additional Portfolio Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <div class="relative h-96 diagonal-frame group">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop" 
                     alt="Student Accommodation" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                    <p class="text-white opacity-0 group-hover:opacity-100 transition-opacity font-bold text-xl">Student Residences</p>
                </div>
            </div>

            <div class="relative h-96 diagonal-frame group">
                <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop" 
                     alt="Residential Complex" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                    <p class="text-white opacity-0 group-hover:opacity-100 transition-opacity font-bold text-xl">Residential Complexes</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
