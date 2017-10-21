<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            {!! Form::open(['method' => 'post', 'url' => '/api/v1/video/', 'files'=> true]) !!}
            <input type="hidden" name="data[channel_id]" placeholder='' value='96d45b60-8a37-11e7-98b1-b1ee787722c5'/>
            <input type="hidden" name="token" placeholder='name' value='34c20680-7c58-11e7-863a-1921972472b0-34c208e0-7c58-11e7-a745-e56cb7818b15'/>
            <input type="hidden" name="client_id" placeholder='name' value='Android_12345'/>
                <input type="text" name="data[name]" placeholder='name'/>
                <input type="text" name="data[type]" value='general'/>
                <input type="text" name="data[url]" placeholder="url" value='general'/>
                <input type="text" name="data[privacy_option_id]" placeholder="url" value='ef83a4f0-81ef-11e7-a3ca-45ec697a2f8a'/>
                <input type="file" name="thumbnail"/>
                <input type="file" name="video"/>
                {!! Form::submit('Upload!') !!}
            {!! Form::close() !!}
        </div>
    </body>
</html>
