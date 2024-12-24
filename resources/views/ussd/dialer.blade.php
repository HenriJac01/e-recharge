<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simulateur USSD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .phone-screen {
            background: #000;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            min-height: 200px;
            margin-bottom: 20px;
            white-space: pre-wrap;
            font-family: monospace;
        }

        .keypad {
            max-width: 300px;
            margin: 0 auto;
        }

        .keypad-btn {
            width: 60px;
            height: 60px;
            margin: 5px;
            border-radius: 30px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Simulateur USSD</h2>

                <div class="phone-screen" id="screen">

                </div>

                <div class="keypad">
                    <div class="d-flex justify-content-center mb-2">
                        <button class="btn btn-light keypad-btn" onclick="addDigit('1')">1</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('2')">2</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('3')">3</button>
                    </div>
                    <div class="d-flex justify-content-center mb-2">
                        <button class="btn btn-light keypad-btn" onclick="addDigit('4')">4</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('5')">5</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('6')">6</button>
                    </div>
                    <div class="d-flex justify-content-center mb-2">
                        <button class="btn btn-light keypad-btn" onclick="addDigit('7')">7</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('8')">8</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('9')">9</button>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-light keypad-btn" onclick="addDigit('*')">*</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('0')">0</button>
                        <button class="btn btn-light keypad-btn" onclick="addDigit('#')">#</button>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-success me-2" onclick="sendUSSD()">Envoyer</button>
                        <button class="btn btn-danger" onclick="clearScreen()">Effacer</button>
                    </div>
                </div>

                <div class="mt-4">
                    <h4>Codes USSD disponibles:</h4>
                    <ul class="list-group">
                        @foreach ($operators as $operator)
                            <li class="list-group-item">{{ $operator->name }}: {{ $operator->ussd_code }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentInput = '';
        let sessionId = null;
        let phoneNumber = '';
        let isFirstRequest = true;

        function addDigit(digit) {
            currentInput += digit;
            updateScreen(currentInput);
        }

        function clearScreen() {
            currentInput = '';
            sessionId = null;
            isFirstRequest = true;
            updateScreen('Bienvenue! Composez un code USSD');
        }

        function updateScreen(text) {
            document.getElementById('screen').innerText = text;
        }

        function sendUSSD() {
            if (!phoneNumber) {
                phoneNumber = prompt("Veuillez entrer votre numéro de téléphone (10 chiffres):");
                if (!phoneNumber || !/^[0-9]{10}$/.test(phoneNumber)) {
                    alert("Numéro de téléphone invalide!");
                    phoneNumber = '';
                    return;
                }
            }

            if (!currentInput && isFirstRequest) {
                alert("Veuillez entrer un code USSD");
                return;
            }

            const requestData = {
                phone_number: phoneNumber,
                session_id: sessionId
            };

            if (isFirstRequest) {
                requestData.ussd_code = currentInput;
                requestData.input = null;
            } else {
                requestData.ussd_code = '*144#'; // Code par défaut
                requestData.input = currentInput;
            }

            $.ajax({
                url: '{{ route('ussd.process') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: requestData,
                success: function(response) {
                    if (response.status === 'error') {
                        updateScreen(response.message || 'Une erreur est survenue');
                        sessionId = null;
                        isFirstRequest = true;
                        setTimeout(clearScreen, 3000);
                        return;
                    }

                    updateScreen(response.data.message);
                    if (response.data.session_id) {
                        sessionId = response.data.session_id;
                    }
                    if (response.data.end) {
                        setTimeout(clearScreen, 5000);
                        sessionId = null;
                        isFirstRequest = true;
                    } else {
                        isFirstRequest = false;
                    }
                    currentInput = '';
                },
                error: function(xhr) {
                    let errorMessage = 'Une erreur est survenue';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    updateScreen(errorMessage);
                    sessionId = null;
                    isFirstRequest = true;
                    setTimeout(clearScreen, 3000);
                }
            });
        }
    </script>
</body>

</html>
