@extends('layouts.public')

@section('title', $legalPage->title ?? 'Scanner')
@section('meta_description', 'Scanner')

@section('content')
    <main id="legalPage" class="scanner-page">
        <style>
            .scanner-page {
                min-height: 70vh;
                display: flex;
                align-items: center;
            }

            #qrcode canvas,
            #qrcode img {
                display: block;
                margin: 0 auto;
            }
        </style>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 text-center p-4" style="text-align: center;">

                        <h5 class="mb-3 text-center">Scan QR Code</h5>

                        <div id="qrcode" class="d-flex justify-content-center mb-3"></div>

                        <p class="text-muted text-center mb-0">
                            Scan to visit <strong style="text-decoration:underline">nulac.in</strong>
                        </p>

                    </div>
                </div>
            </div>
        </div>

        <!-- QR Script -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

        <script>
            new QRCode(document.getElementById("qrcode"), {
                text: "https://nulac.in/",
                width: 220,
                height: 220,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        </script>

    </main>
@endsection