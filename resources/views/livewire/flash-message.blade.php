<div
    x-data="{ show: false, message: '', type: 'success' }"
    x-show="show"
    x-init="
        (function() {
            Livewire.on('flashMessage', (data) => {
                message = data.message;
                type = data.type || 'success';
                show = true;
                setTimeout(() => { show = false }, 3000);
            });
        })();
    "
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed z-[1000] bottom-4 left-0 w-full"
>
    <div :class="{ 'bg-green-500': type === 'success', 'bg-red-500': type === 'error' }"
         class="px-4 py-3 mx-auto text-white rounded-lg shadow-md w-fit">
        <span x-text="message"></span>
    </div>
</div>
