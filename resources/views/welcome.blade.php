<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
 
        <link rel="stylesheet" href="/css/app.css">
        

    </head>
    <body>
        <div class="wrapper">
            <div class="header text-center">
                <span class="logo"></span>
                <h1>Adiagramm</h1>
                <p>Плагин для Laravel для рисования аэрологических диаграмм. ©F2re</p>
            </div>
        
        
            <div class="main flex-center position-ref full-height" id="app">
                
                <div class="content rounded bg-faded bg-white p-5">


                    <aero-component></aero-component>

                </div>
    
            </div>

        

            <div class="footer">
                <script src="/js/app.js"></script>
            </div>
        </div>
    </body>
</html>
