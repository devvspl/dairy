<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isAdminNotification ? 'New Contact Inquiry' : 'Thank You for Contacting Us' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f6f8f2;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #293879 0%, #1e2a5a 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .email-header p {
            font-size: 15px;
            opacity: 0.9;
            margin: 0;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #293879;
            margin-bottom: 20px;
        }
        .message-text {
            font-size: 15px;
            color: #5c6b55;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .info-card {
            background: linear-gradient(180deg, #ffffff, #f6f8f2);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .info-card h2 {
            font-size: 16px;
            font-weight: 700;
            color: #293879;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1cc24;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #293879;
            min-width: 120px;
            font-size: 14px;
        }
        .info-value {
            color: #5c6b55;
            flex: 1;
            font-size: 14px;
            word-break: break-word;
        }
        .message-box {
            background: #fbfcf8;
            border-left: 4px solid #f1cc24;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .message-box p {
            color: #5c6b55;
            font-size: 14px;
            line-height: 1.7;
            margin: 0;
            white-space: pre-wrap;
        }
        .cta-button {
            display: inline-block;
            background: #d85f0f;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            background: #f1cc24;
            color: #1f2a1a !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(216, 95, 15, 0.3);
        }
        .email-footer {
            background: #f6f8f2;
            padding: 30px;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
        }
        .email-footer p {
            font-size: 13px;
            color: #5c6b55;
            margin: 8px 0;
        }
        .email-footer a {
            color: #293879;
            text-decoration: none;
            font-weight: 600;
        }
        .email-footer a:hover {
            color: #d85f0f;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
            margin: 30px 0;
        }
        .badge {
            display: inline-block;
            background: rgba(241, 204, 36, 0.15);
            color: #7a5b00;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-header {
                padding: 30px 20px;
            }
            .email-header h1 {
                font-size: 24px;
            }
            .email-body {
                padding: 30px 20px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-label {
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>{{ $isAdminNotification ? '📬 New Contact Inquiry' : '✓ Message Received' }}</h1>
            <p>{{ $isAdminNotification ? 'A new inquiry has been submitted' : 'Thank you for reaching out to us' }}</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            @if($isAdminNotification)
                <!-- Admin Notification -->
                <div class="greeting">New Inquiry Alert</div>
                <p class="message-text">
                    You have received a new contact inquiry from <strong>{{ $inquiry->name }}</strong>. 
                    Please review the details below and respond promptly.
                </p>

                <div class="info-card">
                    <h2>Contact Information</h2>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $inquiry->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><a href="mailto:{{ $inquiry->email }}" style="color: #293879; text-decoration: none;">{{ $inquiry->email }}</a></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value"><a href="tel:{{ $inquiry->phone }}" style="color: #293879; text-decoration: none;">{{ $inquiry->phone }}</a></span>
                    </div>
                    @if($inquiry->subject)
                        <div class="info-row">
                            <span class="info-label">Subject:</span>
                            <span class="info-value">{{ $inquiry->subject }}</span>
                        </div>
                    @endif
                    @if($inquiry->plan_id && $inquiry->plan)
                        <div class="info-row">
                            <span class="info-label">Plan:</span>
                            <span class="info-value">{{ $inquiry->plan->name }}</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Submitted:</span>
                        <span class="info-value">{{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}</span>
                    </div>
                </div>

                <div class="info-card">
                    <h2>Message</h2>
                    <div class="message-box">
                        <p>{{ $inquiry->message }}</p>
                    </div>
                </div>

                <div style="text-align: center;">
                    <a href="{{ url('/admin/contact-inquiries/' . $inquiry->id) }}" class="cta-button">
                        View in Admin Panel
                    </a>
                </div>

            @else
                <!-- Customer Confirmation -->
                <div class="greeting">Hello {{ $inquiry->name }}! 👋</div>
                <p class="message-text">
                    Thank you for contacting us! We've received your message and our team will review it shortly. 
                    We typically respond within 24 hours during business days.
                </p>

                <div class="info-card">
                    <h2>Your Inquiry Details</h2>
                    <div class="info-row">
                        <span class="info-label">Reference ID:</span>
                        <span class="info-value"><span class="badge">#{{ str_pad($inquiry->id, 6, '0', STR_PAD_LEFT) }}</span></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $inquiry->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $inquiry->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $inquiry->phone }}</span>
                    </div>
                    @if($inquiry->subject)
                        <div class="info-row">
                            <span class="info-label">Subject:</span>
                            <span class="info-value">{{ $inquiry->subject }}</span>
                        </div>
                    @endif
                    @if($inquiry->plan_id && $inquiry->plan)
                        <div class="info-row">
                            <span class="info-label">Plan:</span>
                            <span class="info-value">{{ $inquiry->plan->name }}</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Submitted:</span>
                        <span class="info-value">{{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}</span>
                    </div>
                </div>

                <div class="info-card">
                    <h2>Your Message</h2>
                    <div class="message-box">
                        <p>{{ $inquiry->message }}</p>
                    </div>
                </div>

                <div class="divider"></div>

                <p class="message-text">
                    In the meantime, feel free to explore our products and learn more about what we offer.
                </p>

                <div style="text-align: center;">
                    <a href="{{ url('/products') }}" class="cta-button">
                        Browse Our Products
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>
                <a href="{{ url('/') }}">Visit Website</a> • 
                <a href="{{ url('/contact') }}">Contact Us</a> • 
                <a href="{{ url('/products') }}">Products</a>
            </p>
            <p style="margin-top: 16px; font-size: 12px; color: #9ca3af;">
                This email was sent from {{ config('app.name') }}. 
                @if(!$isAdminNotification)
                    If you didn't submit this inquiry, please ignore this email.
                @endif
            </p>
        </div>
    </div>
</body>
</html>
