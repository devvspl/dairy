@extends('layouts.app')

@section('title', 'Milk Prices')
@section('page-title', 'Milk Prices')

@section('content')
<div class="space-y-4 lg:space-y-6">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: var(--green);">
        <i class="fa-solid fa-check-circle text-xl" style="color: var(--green);"></i>
        <div class="flex-1"><p class="text-sm" style="color: var(--text);">{{ session('success') }}</p></div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Add New --}}
        <div class="bg-white rounded-xl shadow-sm border p-5" style="border-color: var(--border);">
            <h2 class="font-bold text-base mb-4" style="color: var(--text);">Add Milk Price</h2>
            <form method="POST" action="{{ route('admin.milk-prices.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color: var(--text);">Milk Type Key</label>
                    <input type="text" name="milk_type" placeholder="e.g. cow, buffalo, toned, full_fat" required
                        class="w-full px-3 py-2 text-sm border rounded-lg" style="border-color: var(--border);">
                    <p class="text-[10px] mt-0.5" style="color: var(--muted);">Lowercase, no spaces.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color: var(--text);">Display Label</label>
                    <input type="text" name="label" placeholder="e.g. Cow Milk (A2)" required
                        class="w-full px-3 py-2 text-sm border rounded-lg" style="border-color: var(--border);">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color: var(--text);">Price per Litre (₹)</label>
                    <input type="number" name="price_per_litre" step="0.01" min="0" required
                        class="w-full px-3 py-2 text-sm border rounded-lg" style="border-color: var(--border);">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color: var(--text);">
                        <i class="fa-solid fa-clock mr-1" style="color:var(--green);"></i>Order Cutoff Time
                    </label>
                    <input type="time" name="cutoff_time" value="20:00" required
                        class="w-full px-3 py-2 text-sm border rounded-lg" style="border-color: var(--border);">
                    <p class="text-[10px] mt-0.5" style="color: var(--muted);">Orders placed after this time → next day delivery</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="is_active_new" checked class="rounded">
                    <label for="is_active_new" class="text-xs font-semibold" style="color: var(--text);">Active</label>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg text-sm font-bold text-white" style="background: var(--green);">
                    Add Price
                </button>
            </form>
        </div>

        {{-- Existing prices --}}
        <div class="bg-white rounded-xl shadow-sm border p-5" style="border-color: var(--border);">
            <h2 class="font-bold text-base mb-4" style="color: var(--text);">Current Prices</h2>
            @forelse($prices as $price)
            <div class="py-3 border-b last:border-0" style="border-color: var(--border);">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="text-sm font-semibold" style="color: var(--text);">{{ $price->label }}</p>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $price->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $price->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-xs" style="color: var(--muted);">{{ $price->milk_type }}</p>
                        <div class="flex flex-wrap gap-x-3 gap-y-0.5 mt-1 text-xs" style="color: var(--muted);">
                            <span><i class="fa-solid fa-tag mr-1" style="color:var(--green);"></i>₹{{ number_format($price->price_per_litre,2) }}/L</span>
                            <span><i class="fa-solid fa-clock mr-1" style="color:var(--green);"></i>Cutoff: {{ \Carbon\Carbon::parse($price->cutoff_time)->format('h:i A') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <button onclick="openEdit({{ $price->id }}, '{{ addslashes($price->label) }}', {{ $price->price_per_litre }}, '{{ substr($price->cutoff_time,0,5) }}', {{ $price->is_active ? 'true' : 'false' }})"
                            class="text-xs px-2 py-1 rounded border" style="border-color: var(--border); color: var(--muted);">Edit</button>
                        <form method="POST" action="{{ route('admin.milk-prices.destroy', $price) }}" onsubmit="return confirm('Delete this price?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs px-2 py-1 rounded border border-red-200 text-red-500">Del</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-sm text-center py-8" style="color: var(--muted);">No prices configured yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <h3 class="font-bold text-base mb-4" style="color: var(--text);">Edit Milk Price</h3>
        <form id="editForm" method="POST" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold mb-1" style="color: var(--text);">Display Label</label>
                <input type="text" name="label" id="editLabel" required class="w-full px-3 py-2 text-sm border rounded-lg" style="border-color: var(--border);">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color: var(--text);">Price per Litre (₹)</label>
                <input type="number" name="price_per_litre" id="editPrice" step="0.01" min="0" required class="w-full px-3 py-2 text-sm border rounded-lg" style="border-color: var(--border);">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color: var(--text);">
                    <i class="fa-solid fa-clock mr-1" style="color:var(--green);"></i>Cutoff Time
                </label>
                <input type="time" name="cutoff_time" id="editCutoff" required class="w-full px-3 py-2 text-sm border rounded-lg" style="border-color: var(--border);">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="editActive" class="rounded">
                <label for="editActive" class="text-xs font-semibold" style="color: var(--text);">Active</label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeEdit()" class="flex-1 py-2 rounded-lg border text-sm" style="border-color: var(--border); color: var(--muted);">Cancel</button>
                <button type="submit" class="flex-1 py-2 rounded-lg text-sm font-bold text-white" style="background: var(--green);">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, label, price, cutoff, active) {
    document.getElementById('editLabel').value   = label;
    document.getElementById('editPrice').value   = price;
    document.getElementById('editCutoff').value  = cutoff;
    document.getElementById('editActive').checked  = active;
    document.getElementById('editForm').action     = '/admin/milk-prices/' + id;
    const m = document.getElementById('editModal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeEdit() {
    const m = document.getElementById('editModal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
</script>
@endsection
