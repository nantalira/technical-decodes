<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Golek Gawe</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4154f1 0%, #5969f3 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .message {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 30px;
        }

        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #4154f1 0%, #5969f3 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(65, 84, 241, 0.3);
            transition: all 0.3s ease;
        }

        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(65, 84, 241, 0.4);
        }

        .alternative-link {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-family: Monaco, Consolas, monospace;
            font-size: 14px;
            color: #6c757d;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }

        .warning-icon {
            display: inline-block;
            margin-right: 8px;
            font-weight: bold;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }

        .footer p {
            margin: 5px 0;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #4154f1;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background-color: #dee2e6;
            margin: 30px 0;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .content {
                padding: 30px 20px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .reset-button {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Reset Password</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo {{ $user->name }},
            </div>

            <div class="message">
                <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>Golek Gawe Job Portal</strong>.
                </p>

                <p>Jika Anda yang melakukan permintaan ini, silakan klik tombol di bawah untuk mereset password Anda:
                </p>
            </div>

            <!-- Reset Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔑 Reset Password Sekarang
                </a>
            </div>

            <div class="message">
                <p>Atau Anda dapat menyalin dan menempel link berikut ke browser Anda:</p>
            </div>

            <div class="alternative-link">
                {{ $resetUrl }}
            </div>

            <div class="warning">
                <span class="warning-icon">⚠️</span>
                <strong>Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Link ini hanya berlaku selama <strong>{{ env('PASSWORD_RESET_TOKEN_EXPIRE_HOURS', 24) }}
                            jam</strong></li>
                    <li>Link hanya dapat digunakan <strong>satu kali</strong></li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                    <li>Password lama Anda tetap aman sampai Anda membuat yang baru</li>
                    <li>Email ini dikirim dari IP: <code>{{ request()->ip() }}</code></li>
                    <li>Waktu permintaan: <strong>{{ now()->format('d M Y H:i:s') }} WIB</strong></li>
                </ul>
            </div>

            <div class="divider"></div>

            <div class="message">
                <p><strong>Tips Keamanan:</strong></p>
                <ul>
                    <li>Gunakan password yang kuat (minimal 8 karakter)</li>
                    <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                    <li>Jangan gunakan password yang sama dengan akun lain</li>
                    <li>Simpan password di tempat yang aman</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Golek Gawe Job Portal</strong></p>
            <p>Platform pencarian kerja terpercaya di Indonesia</p>

            <div class="social-links">
                <a href="#">📧 Kontak Kami</a>
                <a href="#">🌐 Website</a>
                <a href="#">📱 Mobile App</a>
            </div>

            <div class="divider"></div>

            <p style="font-size: 12px; color: #999;">
                Email ini dikirim secara otomatis, mohon jangan membalas email ini.
            </p>
            <p style="font-size: 12px; color: #999;">
                © {{ date('Y') }} Golek Gawe Job Portal. All rights reserved.
            </p>
            <p style="font-size: 12px; color: #999;">
                Alamat: Jl. Teknologi No. 123, Jakarta, Indonesia
            </p>
        </div>
    </div>
</body>

</html>
