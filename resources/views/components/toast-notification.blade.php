<!-- Toast Notification -->
<div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2">
    @if(session('success'))
        <div class="toast bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg min-w-[300px] max-w-md animate-slide-in">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button class="ml-4 text-green-700 hover:text-green-900" onclick="$(this).closest('.toast').fadeOut()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg min-w-[300px] max-w-md animate-slide-in">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Error!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button class="ml-4 text-red-700 hover:text-red-900" onclick="$(this).closest('.toast').fadeOut()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="toast bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg min-w-[300px] max-w-md animate-slide-in">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul class="text-sm mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="ml-4 text-red-700 hover:text-red-900" onclick="$(this).closest('.toast').fadeOut()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>

<style>
@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>

<script>
$(document).ready(function() {
    // Auto hide toasts after 5 seconds
    setTimeout(function() {
        $('.toast').each(function(index) {
            const $toast = $(this);
            setTimeout(function() {
                $toast.fadeOut('slow', function() {
                    $(this).remove();
                });
            }, index * 200); // Stagger the fade out
        });
    }, 5000);
});
</script>