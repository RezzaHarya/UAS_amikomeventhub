<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket — {{ $event->title }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    
                    <!-- Header Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%); padding: 40px 40px 32px; text-align: center;">
                            <h1 style="margin: 0 0 8px; color: #ffffff; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">
                                🎉 Pembayaran Berhasil!
                            </h1>
                            <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 15px;">
                                Berikut adalah E-Ticket resmi Anda
                            </p>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 32px 40px 0;">
                            <p style="margin: 0; color: #334155; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $transaction->customer_name }}</strong>,<br>
                                Terima kasih telah membeli tiket melalui <strong>AMIKOM Event Hub</strong>. Simpan email ini sebagai bukti tiket Anda.
                            </p>
                        </td>
                    </tr>

                    <!-- Ticket Card -->
                    <tr>
                        <td style="padding: 24px 40px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; overflow: hidden;">
                                
                                <!-- Event Title Bar -->
                                <tr>
                                    <td style="background: linear-gradient(135deg, #4f46e5, #6366f1); padding: 20px 24px;">
                                        <h2 style="margin: 0; color: #ffffff; font-size: 20px; font-weight: 700;">
                                            🎫 {{ $event->title }}
                                        </h2>
                                    </td>
                                </tr>

                                <!-- Ticket Details -->
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <!-- Order ID -->
                                            <tr>
                                                <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Order ID</span><br>
                                                    <span style="color: #1e293b; font-size: 16px; font-weight: 800; font-family: monospace;">{{ $transaction->order_id }}</span>
                                                </td>
                                            </tr>
                                            <!-- Nama Pemesan -->
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Nama Pemesan</span><br>
                                                    <span style="color: #1e293b; font-size: 15px; font-weight: 600;">{{ $transaction->customer_name }}</span>
                                                </td>
                                            </tr>
                                            <!-- Tanggal -->
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">📅 Tanggal & Waktu</span><br>
                                                    <span style="color: #1e293b; font-size: 15px; font-weight: 600;">{{ $event->date->translatedFormat('l, d F Y — H:i') }} WIB</span>
                                                </td>
                                            </tr>
                                            <!-- Lokasi -->
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                                    <span style="color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">📍 Lokasi</span><br>
                                                    <span style="color: #1e293b; font-size: 15px; font-weight: 600;">{{ $event->location }}</span>
                                                </td>
                                            </tr>
                                            <!-- Total Bayar -->
                                            <tr>
                                                <td style="padding: 12px 0;">
                                                    <span style="color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">💰 Total Pembayaran</span><br>
                                                    <span style="color: #4f46e5; font-size: 20px; font-weight: 800;">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- QR Code Section -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <!-- Dashed Separator (tiket look) -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="border-top: 3px dashed #cbd5e1;"></td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 4px; color: #64748b; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">
                                QR Code Tiket Anda
                            </p>
                            <p style="margin: 0 0 20px; color: #94a3b8; font-size: 13px;">
                                Tunjukkan QR Code ini kepada panitia saat memasuki acara
                            </p>

                            <!-- QR Code Image -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto; background: #ffffff; border: 3px solid #e2e8f0; border-radius: 16px; padding: 16px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <img src="{{ $qrUrl }}" alt="QR Code Tiket" width="200" height="200" style="display: block; border-radius: 8px;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Important Notice -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 12px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.6;">
                                            <strong>⚠️ Penting:</strong> Simpan email ini baik-baik. QR Code di atas adalah tiket masuk resmi Anda. Setiap QR Code hanya berlaku untuk 1 orang dan 1 kali scan.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 28px 40px; text-align: center;">
                            <p style="margin: 0 0 8px; color: #ffffff; font-size: 16px; font-weight: 700;">
                                AMIKOM Event Hub
                            </p>
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                                Universitas AMIKOM Yogyakarta<br>
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->
            </td>
        </tr>
    </table>
    <!-- End Wrapper -->

</body>
</html>
