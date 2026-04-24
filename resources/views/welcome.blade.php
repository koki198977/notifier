<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>REALDEV - CLI</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div id="app" class="container">
                <h1 class="mt-4">REALDEV - CLI</h1>
		<h6>Cli: {{env('LARAVEL_COD_COMERCIO')}}</h6>
                <h6>Version: 1.0.0</h6>
                <h6>Estado: 
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                </h6>
                <hr>
                <p id="last" class="mb-5 bg-light text-primary"></p>
                <hr>
                <div id="text"> </div>
            </div>
        </div>
    </body>

    <script src="https://realdev.cl:6001/socket.io/socket.io.js"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        const list = [];
        const client_name = "REAL004"

        Echo.channel('voucher-precuenta.' + client_name)
            .listen('.VoucherEvent', data => {
                console.log(data.data)
                list.push(data.data)
                document.getElementById('text').innerHTML = JSON.stringify(list.reverse());
                document.getElementById('last').innerHTML = JSON.stringify(data.data);
                axios.post('/api/pre_cuenta', data.data)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(error);
                });
            })

        Echo.channel('voucher-ticket.' + client_name)
            .listen('.VoucherEvent', data => {
                console.log(data.data)
                list.push(data.data)
                document.getElementById('text').innerHTML = JSON.stringify(list.reverse());
                document.getElementById('last').innerHTML = JSON.stringify(data.data);
                axios.post('/api/solicita_ticket', data.data)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(error);
                });
            })
        
        Echo.channel('voucher-happy.' + client_name)
            .listen('.VoucherEvent', data => {
                console.log(data.data)
                list.push(data.data)
                document.getElementById('text').innerHTML = JSON.stringify(list.reverse());
                document.getElementById('last').innerHTML = JSON.stringify(data.data);
                axios.post('/api/solicita_happy', data.data)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(error);
                });
            })

        Echo.channel('voucher-sii.' + client_name)
            .listen('.VoucherEvent', data => {
                console.log(data.data)
                list.push(data.data)
                document.getElementById('text').innerHTML = JSON.stringify(list.reverse());
                document.getElementById('last').innerHTML = JSON.stringify(data.data);
                axios.post('/api/solicita_boleta_electronica', data.data)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(error);
                });
            })
    </script>
</html>
