<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Profile</title>

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

        .activation-content {
            box-shadow: 0 0px 45px -20px;
            margin-top: 40px;
            padding: 25px;
            border-radius: 15px;
        }

        .activation-content ul {
            display: flex;
            text-decoration: none;
            list-style: none;
            width: 100%;
            justify-content: space-evenly;
            padding-left: 0;
        }

        .activation-content li svg {
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

        .activation-content ul li:before {
            position: absolute;
            content: "";
            height: 3px;
            background: #36e;
            width: 150%;
            margin-top: 55px;
            margin-left: 75px;
            z-index: 1;
        }

        .activation-content ul li {
            position: relative;
            z-index: 2;
        }

        .activation-content ul li:nth-last-child(1):before {
            display: none;
        }

        li.opacity svg {
            color: #9bf;
            border: 2px solid #9bf;
        }

        .activation-content ul li.line-opacity:before {
            background: #9bf;
        }

        .strong {
            font-weight: bold;
        }

        .activation-content a {
            background: #36e;
            color: #fff;
            padding: 15px 25px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px 0;
            display: inline-block;
            transition: .3s;
        }

        .activation-content a:hover {
            background: #57f;
        }

        .text-center {
            text-align: center !important;
        }

        @media only screen and (min-width: 320px) and (max-width: 720px) {
            .activation-content li svg {
                height: 40px;
                padding: 10px;
            }

            .activation-content ul li:before {
                margin-top: 30px;
                margin-left: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="email-template">
        <div class="container">
            <div class="activation-content text-center">
                <h2 class="mb-4">Active Your Account</h2>
                <ul>
                    <li>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16">
                            <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
                        </svg>
                        <p class="strong mt-3">Create Account</p>
                    </li>
                    <li class="line-opacity">
                        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-envelope fa-w-16">
                            <path fill="currentColor" d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49-16.841 13.247-50.201 45.072-73.413 44.701-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646 21.857 17.205 60.134 55.186 103.062 54.955 42.717.231 80.509-37.199 103.053-54.947 49.528-38.783 82.032-64.401 104.947-82.653V400H48z"></path>
                        </svg>
                        <p class="strong mt-3">Verify Email</p>
                    </li>
                    <li class="opacity">
                        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="smile" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="svg-inline--fa fa-smile fa-w-16">
                            <path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 448c-110.3 0-200-89.7-200-200S137.7 56 248 56s200 89.7 200 200-89.7 200-200 200zm-80-216c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm4 72.6c-20.8 25-51.5 39.4-84 39.4s-63.2-14.3-84-39.4c-8.5-10.2-23.7-11.5-33.8-3.1-10.2 8.5-11.5 23.6-3.1 33.8 30 36 74.1 56.6 120.9 56.6s90.9-20.6 120.9-56.6c8.5-10.2 7.1-25.3-3.1-33.8-10.1-8.4-25.3-7.1-33.8 3.1z"></path>
                        </svg>
                        <p class="strong mt-3">Enjoy Service</p>
                    </li>
                </ul>
                <p>Thank you for registering with us. In order to active your account please click the button below</p>
                <a href="{{env('FRONTEND_URL') . '/active-account/' . Auth::user()->remember_token}}" target="_blank">Active Account</a>
            </div>
        </div>
    </div>
</body>

</html>