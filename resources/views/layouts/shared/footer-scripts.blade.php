@vite(['resources/js/app.js', 'resources/js/utility/lock-screen.js'])

@yield('scripts')

<script>
    window.AppEnum = {
        orderBloodStatus: @json(\App\Support\StatusEnumJs::OrderBloodStatus()),
        incomingBloodStatus: @json(\App\Support\StatusEnumJs::IncomingBloodStatus())
    };
</script>