@vite(['resources/js/app.js'])

@yield('scripts')

<script>
    let timeout;

function resetTimer() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        document.querySelector('#lock-form').submit();
    }, 1800000);
}

document.onload = resetTimer;
document.onmousemove = resetTimer;
document.onkeypress = resetTimer;

document.querySelectorAll('.language-switcher').forEach(function (el) {
    el.addEventListener('click', function () {
        const lang = this.dataset.lang;
        const flag = this.dataset.flag;

        fetch('{{ route('language.switch') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ lang: lang })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update UI sebelum reload (opsional)
                document.getElementById('selected-language-image').src = flag;
                document.getElementById('selected-language-code').textContent = lang.toUpperCase();

                // Reload halaman agar semua terjemahan terupdate
                window.location.reload();
            }
        });
    });
});
</script>