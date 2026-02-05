<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>invoice</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="bg-gray-50 min-h-screen py-10">
            @yield('content')
        </div>
        <!-- Logo Preview Script & Table Logic -->
        @yield('script')
    </body>
</html>
