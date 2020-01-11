<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #table-content {
            margin: 0 auto;
            border-collapse: collapse;
            border: 1.5px solid #7FA0C1;
            width: 40%;
        }

        text-center {
            align-content: center;
        }

        #content {
            padding: 10px 30px 50px 30px;
        }

        .titulo{
            font-size: 12pt;
            font-family: sans-serif ;
        }

    </style>
</head>
<body>
<div class="text-center">
    <table id="table-content" cellpadding="0" cellspacing="0">
        <tbody>
        <tr class="header" >
            <td>
                <img width="250" src="{{env('APP_URL')}}/images/postergris.png" />
            </td>
        </tr>
        <tr>
            <td id="content" colspan="2">
                @yield('content')
            </td>
        </tr>
        <tr>
            <td>
                <a href="{{env('APP_URL').'/unsuscribe/'.$email}}" target="_blank">Dejar de recibir correos del RETO ACTON</a>
            </td>
        </tr>
        <tr class="footer">
            <td colspan="2">
                <img width="200" src="{{env('APP_URL')}}/img/header.png" style="float: right">
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>