@extends('layouts.app')

@section('title', 'Create Support Ticket')
@section('page-title', 'Create Support Ticket')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('member.support-tickets.index') }}" 
           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" 
           style="color: var(--text); border: 1px solid var(--border);">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Tickets
        </a>
    </div>

    <!-- Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="mb-6">
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-ticket mr-2" style="color: var(--green);"></i>Create New Support Ticket
                </h1>
                <p class="text-sm mt-1" style="color: var(--muted);">
                    Fill out the form below and we'll get back to you as soon as possible
                </p>
            </div>

            <form action="{{ route('member.support-tickets.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Issue Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium mb-2" style="color: var(--text);">
                            Issue Category <span class="text-red-600">*</span>
                        </label>
                        <select id="category" 
                                name="category" 
                                required
                                onchange="updateSubject()"
                                class="w-full px-4 py-2.5 border rounded-lg text-sm @error('category') border-red-500 @enderror" 
                                style="border-color: var(--border);">
                            <option value="">Select Issue Type</option>
                            <option value="delivery_missed" {{ old('category') == 'delivery_missed' ? 'selected' : '' }}>🚚 Missed Delivery</option>
                            <option value="delivery_late" {{ old('category') == 'delivery_late' ? 'selected' : '' }}>⏰ Late Delivery</option>
                            <option value="quality_issue" {{ old('category') == 'quality_issue' ? 'selected' : '' }}>🥛 Milk Quality Issue</option>
                            <option value="quantity_wrong" {{ old('category') == 'quantity_wrong' ? 'selected' : '' }}>📏 Wrong Quantity Delivered</option>
                            <option value="subscription_change" {{ old('category') == 'subscription_change' ? 'selected' : '' }}>📝 Change Subscription</option>
                            <option value="subscription_pause" {{ old('category') == 'subscription_pause' ? 'selected' : '' }}>⏸️ Pause/Resume Subscription</option>
                            <option value="subscription_cancel" {{ old('category') == 'subscription_cancel' ? 'selected' : '' }}>❌ Cancel Subscription</option>
                            <option value="payment_issue" {{ old('category') == 'payment_issue' ? 'selected' : '' }}>💳 Payment Issue</option>
                            <option value="address_change" {{ old('category') == 'address_change' ? 'selected' : '' }}>📍 Change Delivery Address</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>❓ Other Issue</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium mb-2" style="color: var(--text);">
                            Subject <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}" 
                               required
                               class="w-full px-4 py-2.5 border rounded-lg text-sm @error('subject') border-red-500 @enderror" 
                               style="border-color: var(--border);"
                               placeholder="Brief description of your issue">
                        @error('subject')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium mb-2" style="color: var(--text);">
                            Urgency Level <span class="text-red-600">*</span>
                        </label>
                        <select id="priority" 
                                name="priority" 
                                required
                                class="w-full px-4 py-2.5 border rounded-lg text-sm @error('priority') border-red-500 @enderror" 
                                style="border-color: var(--border);">
                            <option value="">Select Urgency</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - Can wait a few days</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium - Need response soon</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Need immediate attention</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent - Critical delivery issue</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium mb-2" style="color: var(--text);">
                            Describe Your Issue <span class="text-red-600">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="6" 
                                  required
                                  class="w-full px-4 py-2.5 border rounded-lg text-sm @error('message') border-red-500 @enderror" 
                                  style="border-color: var(--border);"
                                  placeholder="Please provide details about your delivery or subscription issue...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs mt-1" style="color: var(--muted);">
                            <i class="fa-solid fa-info-circle mr-1"></i>Include delivery date, time, and any other relevant details
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full py-3 rounded-lg font-bold text-sm transition-all hover:shadow-lg hover:opacity-90" 
                                style="background-color: var(--green); color: #fff;">
                            <i class="fa-solid fa-paper-plane mr-2"></i>Submit Support Request
                        </button>
                    </div>
                </div>
            </form>

            <script>
            function updateSubject() {
                const category = document.getElementById('category');
                const subject = document.getElementById('subject');
                const categoryText = category.options[category.selectedIndex].text.replace(/[🚚⏰🥛📏📝⏸️❌💳📍❓]\s/, '');
                
                if (category.value && !subject.value) {
                    subject.value = categoryText;
                }
            }
            </script>
        </div>

        <!-- Help Text -->
        <div class="mt-4 p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-lightbulb text-xl" style="color: var(--green);"></i>
                <div>
                    <p class="text-sm font-semibold mb-1" style="color: var(--text);">Tips for faster resolution:</p>
                    <ul class="text-xs space-y-1" style="color: var(--muted);">
                        <li>• Mention the specific delivery date and time</li>
                        <li>• Include your subscription plan details if relevant</li>
                        <li>• Provide your delivery address if it's an address-related issue</li>
                        <li>• For quality issues, describe the problem with the milk</li>
                        <li>• For missed deliveries, let us know if you were home</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Common Issues Quick Links -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="p-3 rounded-lg border" style="border-color: var(--border); background: white;">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-clock text-sm" style="color: var(--green);"></i>
                    <p class="text-xs font-bold" style="color: var(--text);">Delivery Hours</p>
                </div>
                <p class="text-xs" style="color: var(--muted);">Morning: 6 AM - 8 AM<br>Evening: 5 PM - 7 PM</p>
            </div>
            <div class="p-3 rounded-lg border" style="border-color: var(--border); background: white;">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-phone text-sm" style="color: var(--green);"></i>
                    <p class="text-xs font-bold" style="color: var(--text);">Emergency Contact</p>
                </div>
                <p class="text-xs" style="color: var(--muted);">For urgent delivery issues,<br>call: {{ config('app.phone', '+91 XXX XXX XXXX') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
