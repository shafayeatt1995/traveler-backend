<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .email-template {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-content {
            box-shadow: 0 0px 45px -20px;
            margin-top: 40px;
            padding: 25px;
            border-radius: 15px;
        }

        .reset-content ul {
            display: flex;
            text-decoration: none;
            list-style: none;
            width: 100%;
            justify-content: space-evenly;
            padding-left: 0;
        }

        .reset-content li svg {
            display: inline-block;
            color: #36e;
            border: 2px solid #36e;
            border-radius: 5px;
            padding: 15px;
            position: relative;
            z-index: 2;
            background: #fff;
            height: 75px;
        }

        .reset-content ul li {
            position: relative;
            z-index: 2;
        }

        .strong {
            font-weight: bold;
        }

        .reset-content a {
            background: #36e;
            color: #fff;
            padding: 15px 25px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px 0;
            display: inline-block;
            transition: .3s;
        }

        .reset-content a:hover {
            background: #57f;
        }

        .text-center {
            text-align: center !important;
        }

        @media only screen and (min-width: 320px) and (max-width: 720px) {
            .reset-content li svg {
                height: 40px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="email-template">
        <div class="container">
            <div class="reset-content text-center">
                <h2 class="mb-4">Reset Password</h2>
                <ul>
                    <li>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="key" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-key fa-w-16">
                            <path fill="currentColor" d="M512 176.001C512 273.203 433.202 352 336 352c-11.22 0-22.19-1.062-32.827-3.069l-24.012 27.014A23.999 23.999 0 0 1 261.223 384H224v40c0 13.255-10.745 24-24 24h-40v40c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24v-78.059c0-6.365 2.529-12.47 7.029-16.971l161.802-161.802C163.108 213.814 160 195.271 160 176 160 78.798 238.797.001 335.999 0 433.488-.001 512 78.511 512 176.001zM336 128c0 26.51 21.49 48 48 48s48-21.49 48-48-21.49-48-48-48-48 21.49-48 48z"></path>
                        </svg>
                    </li>
                </ul>
                <h3>Hello {{$user->name}}</h3>
                <p>A request has been received to change the password for your account</p>
                <a href="{{env('FRONTEND_URL') . '/reset-password/' . $token}}" target="_blank">Reset Password</a>
                <p>If you did not initiate this request, please contact us imidiately.</p>
            </div>
        </div>
    </div>
</body>