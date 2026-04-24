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
</script>