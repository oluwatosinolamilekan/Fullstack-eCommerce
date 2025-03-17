{{-- <div>
    @if ($message)
        <div class="fixed top-5 right-5 p-4 rounded-lg shadow-lg text-white"
            style="background-color: 
                {{ $type === 'success' ? 'green' : ($type === 'error' ? 'red' : ($type === 'warning' ? 'yellow' : 'blue')) }};">
            {{ $message }}
        </div>
    @endif

    <script>
        window.addEventListener('alert', event => {
            let alertBox = document.createElement('div');
            alertBox.className = "fixed top-5 right-5 p-4 rounded-lg shadow-lg text-white";
            alertBox.style.backgroundColor = event.detail.type === 'success' ? 'green' :
                (event.detail.type === 'error' ? 'red' :
                (event.detail.type === 'warning' ? 'yellow' : 'blue'));

            alertBox.innerText = event.detail.message;
            document.body.appendChild(alertBox);

            setTimeout(() => {
                alertBox.remove();
            }, 3000); // Remove after 3 seconds
        });
    </script>
</div> --}}
<div x-data="{ show: false, message: '', type: 'success' }"
     @alert.window="message = $event.detail.message; type = $event.detail.type; show = true; setTimeout(() => show = false, 3000)">
    <template x-if="show">
        <div class="fixed top-5 right-5 p-4 rounded-lg shadow-lg text-white"
             :class="{
                'bg-green-500': type === 'success',
                'bg-red-500': type === 'error',
                'bg-yellow-500': type === 'warning',
                'bg-blue-500': type === 'info'
             }">
            <span x-text="message"></span>
        </div>
    </template>
</div>
