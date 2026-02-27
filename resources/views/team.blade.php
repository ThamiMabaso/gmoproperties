@extends('layouts.app')

@section('title', 'Meet Our Team - GMO Properties')

@section('content')
<!-- Team Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold text-center mb-16">MEET OUR TEAM</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
            <!-- Team Member 1 -->
            <div class="text-center">
                <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden border-4 border-gmo-gold">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop" 
                         alt="Kabelo Mokoena" 
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-lg mb-1">Kabelo Mokoena</h3>
                <p class="text-gray-600 text-sm">Principal Agent</p>
            </div>

            <!-- Team Member 2 -->
            <div class="text-center">
                <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden border-4 border-gmo-gold">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop" 
                         alt="Frederick Modise" 
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-lg mb-1">Frederick Modise</h3>
                <p class="text-gray-600 text-sm">CEO & Chief Operating Officer</p>
            </div>

            <!-- Team Member 3 -->
            <div class="text-center">
                <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden border-4 border-gmo-gold">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&h=200&fit=crop" 
                         alt="Kagiso" 
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-lg mb-1">Kagiso</h3>
                <p class="text-gray-600 text-sm">Legal & Compliance</p>
            </div>

            <!-- Team Member 4 -->
            <div class="text-center">
                <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden border-4 border-gmo-gold">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop" 
                         alt="Aisha" 
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-lg mb-1">Aisha</h3>
                <p class="text-gray-600 text-sm">Operations & Finance</p>
            </div>

            <!-- Team Member 5 -->
            <div class="text-center">
                <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden border-4 border-gmo-gold">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&h=200&fit=crop" 
                         alt="Precious" 
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-lg mb-1">Precious</h3>
                <p class="text-gray-600 text-sm">Head of Property Manager</p>
            </div>

            <!-- Team Member 6 -->
            <div class="text-center">
                <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden border-4 border-gmo-gold">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200&h=200&fit=crop" 
                         alt="Thabo" 
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-lg mb-1">Thabo</h3>
                <p class="text-gray-600 text-sm">Head of Marketing & Campus Liaison</p>
            </div>
        </div>
    </div>
</section>
@endsection
