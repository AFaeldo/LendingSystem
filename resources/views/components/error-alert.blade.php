@if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i class="ti ti-alert-circle text-red-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-red-800 mb-2">Validation Errors</h3>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
