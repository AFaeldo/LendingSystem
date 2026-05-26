@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i class="ti ti-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif
