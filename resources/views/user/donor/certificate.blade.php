<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Donor Darah - {{ $donor->donor_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }

        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            border: 8px solid #c41e3a;
            border-radius: 15px;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            box-shadow: 0 0 30px rgba(196, 30, 58, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #c41e3a;
            padding-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: #c41e3a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .organization {
            font-size: 24px;
            font-weight: bold;
            color: #c41e3a;
            margin-bottom: 5px;
        }

        .sub-organization {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .certificate-title {
            font-size: 32px;
            font-weight: bold;
            color: #c41e3a;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .certificate-subtitle {
            font-size: 18px;
            color: #666;
            font-style: italic;
        }

        .content {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: rgba(196, 30, 58, 0.05);
            border-radius: 10px;
            border: 2px dashed #c41e3a;
        }

        .recipient-text {
            font-size: 20px;
            margin-bottom: 20px;
            color: #333;
        }

        .recipient-name {
            font-size: 36px;
            font-weight: bold;
            color: #c41e3a;
            margin: 20px 0;
            text-decoration: underline;
            text-decoration-color: #c41e3a;
            text-underline-offset: 8px;
        }

        .achievement-text {
            font-size: 18px;
            margin: 20px 0;
            line-height: 1.8;
            color: #333;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
            text-align: left;
        }

        .detail-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #c41e3a;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .detail-label {
            font-weight: bold;
            color: #c41e3a;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 16px;
        }

        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 50px;
            text-align: center;
        }

        .signature-block {
            padding: 20px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
            color: #333;
        }

        .signature-line {
            border-bottom: 2px solid #333;
            margin-bottom: 10px;
            height: 2px;
        }

        .signature-name {
            font-weight: bold;
            color: #c41e3a;
        }

        .signature-position {
            font-style: italic;
            color: #666;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #c41e3a;
            font-size: 12px;
            color: #666;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            background: #f0f0f0;
            border: 2px solid #c41e3a;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #c41e3a;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 2px 10px rgba(196, 30, 58, 0.3);
        }

        .print-button:hover {
            background: #a01729;
        }

        @media print {
            .print-button {
                display: none;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .certificate-container {
                max-width: none;
                margin: 0;
                padding: 20px;
                border: 6px solid #c41e3a;
                box-shadow: none;
            }
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }

        .decorative-border {
            border: 2px solid #c41e3a;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            background: linear-gradient(45deg, #fff 0%, #f8f9fa 100%);
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Cetak Sertifikat
    </button>

    <div class="certificate-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">PMI</div>
            <div class="organization">PALANG MERAH INDONESIA</div>
            <div class="sub-organization">Kota Bandung</div>
            <div class="certificate-title">Sertifikat Donor Darah</div>
            <div class="certificate-subtitle">Certificate of Blood Donation</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="recipient-text">
                Dengan ini menyatakan bahwa
            </div>
            
            <div class="recipient-name">
                {{ strtoupper($donor->user->name) }}
            </div>
            
            <div class="achievement-text">
                Telah dengan sukarela mendonorkan darahnya untuk kemanusiaan<br>
                dan berkontribusi dalam menyelamatkan nyawa sesama
            </div>

            @if(in_array($donor->status, ['approved', 'completed']))
                <div class="status-badge">✅ DONOR DISETUJUI</div>
            @endif
        </div>

        <!-- Details -->
        <div class="decorative-border">
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Kode Donor</div>
                    <div class="detail-value">{{ $donor->donor_code }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Tanggal Pendaftaran</div>
                    <div class="detail-value">{{ $donor->created_at->format('d F Y') }}</div>
                </div>
                
                @if($donor->donation_date)
                <div class="detail-item">
                    <div class="detail-label">Tanggal Donor</div>
                    <div class="detail-value">{{ $donor->donation_date->format('d F Y') }}</div>
                </div>
                @endif
                
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        @if($donor->status === 'approved')
                            Disetujui untuk Donor
                        @elseif($donor->status === 'completed')
                            Donor Selesai
                        @else
                            {{ ucfirst($donor->status) }}
                        @endif
                    </div>
                </div>
                
                @if($donor->next_eligible_date)
                <div class="detail-item">
                    <div class="detail-label">Donor Berikutnya</div>
                    <div class="detail-value">{{ $donor->next_eligible_date->format('d F Y') }}</div>
                </div>
                @endif
                
                <div class="detail-item">
                    <div class="detail-label">Lokasi</div>
                    <div class="detail-value">PMI Kota Bandung</div>
                </div>
            </div>
        </div>

        <!-- Appreciation Message -->
        <div class="decorative-border" style="text-align: center; font-style: italic;">
            <p style="font-size: 16px; color: #c41e3a; font-weight: bold; margin-bottom: 10px;">
                "Setetes Darah Anda, Sejuta Harapan Bagi Mereka"
            </p>
            <p style="font-size: 14px; color: #666;">
                Terima kasih atas kerelaan dan kepedulian Anda terhadap sesama.<br>
                Kontribusi Anda sangat berarti untuk menyelamatkan nyawa.
            </p>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-block">
                <div class="signature-title">Petugas Donor</div>
                <div class="signature-line"></div>
                <div class="signature-name">dr. Ahmad Hidayat</div>
                <div class="signature-position">Dokter Penanggung Jawab</div>
            </div>
            
            <div class="signature-block">
                <div class="signature-title">Ketua PMI</div>
                <div class="signature-line"></div>
                <div class="signature-name">Dr. Siti Nurhaliza</div>
                <div class="signature-position">Ketua PMI Kota Bandung</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="qr-code">
                QR CODE<br>
                <small>{{ $donor->donor_code }}</small>
            </div>
            <p>
                Sertifikat ini diterbitkan secara elektronik oleh sistem PMI Kota Bandung<br>
                Tanggal cetak: {{ now()->format('d F Y H:i') }} WIB
            </p>
            <p style="margin-top: 10px; font-weight: bold; color: #c41e3a;">
                Jl. Aceh No. 79, Bandung 40117 | Telp: (022) 4264265 | www.pmibandung.org
            </p>
        </div>
    </div>

    <script>
        // Auto print when opened in new window
        if (window.location.search.includes('autoprint=1')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }

        // Print function
        function printCertificate() {
            window.print();
        }

        // Keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
