<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Donor Darah - {{ $donor->donor_code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&family=Dancing+Script:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.4;
            color: #2c3e50;
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }

        /* A4 Landscape dimensions: 297mm x 210mm */
        .certificate-container {
            width: 297mm;
            height: 210mm;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 0;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            flex-direction: column;
            border: 3px solid #c0392b;
        }

        /* Decorative corner elements */
        .corner-decoration {
            position: absolute;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #c0392b, #e74c3c);
            z-index: 1;
        }

        .corner-decoration.top-left {
            top: 0;
            left: 0;
            clip-path: polygon(0 0, 100% 0, 0 100%);
        }

        .corner-decoration.top-right {
            top: 0;
            right: 0;
            clip-path: polygon(100% 0, 100% 100%, 0 0);
        }

        .corner-decoration.bottom-left {
            bottom: 0;
            left: 0;
            clip-path: polygon(0 0, 100% 100%, 0 100%);
        }

        .corner-decoration.bottom-right {
            bottom: 0;
            right: 0;
            clip-path: polygon(100% 0, 100% 100%, 0 100%);
        }

        /* Ornamental borders */
        .ornamental-border {
            position: absolute;
            border: 2px solid #c0392b;
            border-radius: 15px;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            pointer-events: none;
        }

        .ornamental-border::before {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border: 1px solid #e74c3c;
            border-radius: 20px;
            opacity: 0.5;
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #2c3e50;
            padding: 20mm 25mm 15mm;
            text-align: center;
            position: relative;
            border-bottom: 3px solid #c0392b;
            z-index: 2;
        }

        .header-ornament {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 20px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 20'%3E%3Cpath d='M0,10 Q50,0 100,10 T200,10' stroke='%23c0392b' stroke-width='2' fill='none'/%3E%3Cpath d='M0,10 Q50,20 100,10 T200,10' stroke='%23e74c3c' stroke-width='1' fill='none'/%3E%3C/svg%3E") no-repeat center;
            background-size: contain;
        }

        .logo-container {
            margin-bottom: 15px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(192, 57, 43, 0.3);
            border: 4px solid #ffffff;
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            inset: -8px;
            border: 2px solid #e74c3c;
            border-radius: 50%;
            opacity: 0.3;
        }

        .organization {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: #c0392b;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .sub-organization {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 20px;
            font-weight: 400;
        }

        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin: 15px 0 8px;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
        }

        .certificate-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #c0392b, #e74c3c, #c0392b);
            border-radius: 2px;
        }

        .certificate-subtitle {
            font-size: 14px;
            color: #95a5a6;
            font-style: italic;
            font-weight: 300;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            padding: 15mm 25mm;
            gap: 20mm;
            position: relative;
            z-index: 2;
        }

        .left-section {
            flex: 1.3;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-section {
            flex: 0.7;
            display: flex;
            flex-direction: column;
            gap: 12mm;
        }

        /* Award Section */
        .award-section {
            text-align: center;
            margin-bottom: 15mm;
            position: relative;
        }

        .award-section::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 80px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23f39c12'%3E%3Cpath d='M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z'/%3E%3C/svg%3E") no-repeat center;
            background-size: contain;
            opacity: 0.1;
        }

        .recipient-text {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10mm;
            font-weight: 400;
            font-style: italic;
        }

        .recipient-name {
            font-family: 'Dancing Script', cursive;
            font-size: 42px;
            font-weight: 700;
            color: #c0392b;
            margin: 10mm 0;
            position: relative;
            display: inline-block;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .recipient-name::before {
            content: '"';
            position: absolute;
            left: -30px;
            top: -10px;
            font-size: 60px;
            color: #e74c3c;
            opacity: 0.3;
            font-family: 'Playfair Display', serif;
        }

        .recipient-name::after {
            content: '"';
            position: absolute;
            right: -30px;
            bottom: -10px;
            font-size: 60px;
            color: #e74c3c;
            opacity: 0.3;
            font-family: 'Playfair Display', serif;
        }

        .achievement-text {
            font-size: 14px;
            line-height: 1.7;
            color: #34495e;
            margin-bottom: 10mm;
            font-weight: 400;
            text-align: justify;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        /* Details Section */
        .details-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e74c3c;
            border-radius: 15px;
            padding: 20px;
            position: relative;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.1);
        }

        .details-section::before {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            background: linear-gradient(45deg, #c0392b, #e74c3c, #c0392b);
            border-radius: 15px;
            z-index: -1;
            padding: 1px;
        }

        .details-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: #c0392b;
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }

        .details-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background: #e74c3c;
            border-radius: 1px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .detail-item {
            background: white;
            padding: 12px 15px;
            border-radius: 10px;
            border-left: 4px solid #c0392b;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .detail-label {
            font-weight: 600;
            color: #c0392b;
            margin-bottom: 4px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: #2c3e50;
            font-size: 13px;
            font-weight: 500;
        }

        /* Appreciation Message */
        .appreciation {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px dashed #e74c3c;
            color: #2c3e50;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            position: relative;
            margin-top: 10mm;
        }

        .appreciation::before {
            content: '❤️';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 10px;
            font-size: 20px;
        }

        .appreciation-quote {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            font-style: italic;
            color: #c0392b;
        }

        .appreciation-text {
            font-size: 12px;
            line-height: 1.6;
            font-weight: 400;
            color: #34495e;
        }

        /* Signatures */
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 15px;
        }

        .signature-block {
            text-align: center;
            position: relative;
        }

        .signature-title {
            font-weight: 600;
            color: #7f8c8d;
            margin-bottom: 40px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .signature-line {
            border-bottom: 2px solid #bdc3c7;
            margin-bottom: 10px;
            position: relative;
        }

        .signature-line::before {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 20%;
            right: 20%;
            height: 1px;
            background: #c0392b;
            opacity: 0.3;
        }

        .signature-line::after {
            content: '✓';
            position: absolute;
            right: 10px;
            bottom: 3px;
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }

        .signature-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .signature-position {
            font-style: italic;
            color: #7f8c8d;
            font-size: 11px;
            font-weight: 300;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 12mm 25mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #c0392b, #e74c3c, #c0392b);
        }

        .qr-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .qr-code {
            width: 55px;
            height: 55px;
            background: white;
            border: 3px solid #c0392b;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #2c3e50;
            font-weight: 600;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .qr-info {
            font-size: 10px;
            opacity: 0.8;
            line-height: 1.3;
        }

        .footer-info {
            text-align: center;
            flex: 1;
        }

        .footer-text {
            font-size: 11px;
            opacity: 0.9;
            line-height: 1.4;
            margin-bottom: 6px;
        }

        .footer-contact {
            font-weight: 600;
            color: #e74c3c;
            font-size: 12px;
        }

        .print-date {
            text-align: right;
            font-size: 10px;
            opacity: 0.8;
            line-height: 1.3;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #c0392b, #e74c3c);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(192, 57, 43, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(192, 57, 43, 0.4);
        }

        /* Print Styles */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                background: white;
                padding: 0;
                margin: 0;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .print-button {
                display: none !important;
            }

            .certificate-container {
                width: 297mm;
                height: 210mm;
                margin: 0;
                box-shadow: none;
                page-break-inside: avoid;
            }

            .header,
            .footer,
            .status-badge,
            .corner-decoration,
            .logo {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }

        /* Responsive */
        @media screen and (max-width: 1200px) {
            body {
                padding: 10px;
            }

            .certificate-container {
                width: 100%;
                height: auto;
                max-width: 1000px;
                aspect-ratio: 297/210;
            }

            .main-content {
                flex-direction: column;
                gap: 20px;
            }

            .left-section,
            .right-section {
                flex: none;
            }
        }

        @media screen and (max-width: 768px) {
            .certificate-title {
                font-size: 28px;
            }

            .recipient-name {
                font-size: 36px;
            }

            .signatures {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .footer {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .print-button {
                top: 15px;
                right: 15px;
                padding: 12px 20px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Cetak Sertifikat
    </button>

    <div class="certificate-container">
        <!-- Corner Decorations -->
        <div class="corner-decoration top-left"></div>
        <div class="corner-decoration top-right"></div>
        <div class="corner-decoration bottom-left"></div>
        <div class="corner-decoration bottom-right"></div>
        
        <!-- Ornamental Border -->
        <div class="ornamental-border"></div>

        <!-- Header -->
        <div class="header">
            <div class="header-ornament"></div>
            <div class="logo-container">
                <div class="logo">PMI</div>
                <div class="organization">PALANG MERAH INDONESIA</div>
                <div class="sub-organization">Kota Bandung</div>
            </div>
            <div class="certificate-title">Sertifikat Donor Darah</div>
            <div class="certificate-subtitle">Certificate of Blood Donation</div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="left-section">
                <div class="award-section">
                    <div class="recipient-text">
                        Dengan bangga kami berikan penghargaan kepada
                    </div>
                    
                    <div class="recipient-name">
                        {{ $donor->user->name }}
                    </div>
                    
                    <div class="achievement-text">
                        Yang telah dengan tulus dan sukarela mendonorkan darahnya untuk kemanusiaan.
                        Kontribusi mulia ini membantu menyelamatkan nyawa dan memberikan harapan baru
                        bagi mereka yang membutuhkan. Kerelaan Anda adalah wujud nyata dari semangat
                        kemanusiaan yang luhur.
                    </div>

                    @if(in_array($donor->status, ['approved', 'completed']))
                        <div class="status-badge">
                            <span>✅</span>
                            <span>DONOR DISETUJUI & DIVERIFIKASI</span>
                        </div>
                    @endif
                </div>

                <!-- Appreciation Message -->
                <div class="appreciation">
                    <div class="appreciation-quote">
                        "Setetes Darah Anda, Sejuta Harapan Bagi Mereka"
                    </div>
                    <div class="appreciation-text">
                        Terima kasih atas kerelaan dan kepedulian luar biasa Anda terhadap sesama. 
                        Setiap tetes darah yang Anda sumbangkan adalah anugerah kehidupan bagi yang membutuhkan.
                        Anda adalah pahlawan tanpa tanda jasa yang telah membuktikan bahwa kemanusiaan masih hidup.
                    </div>
                </div>
            </div>

            <div class="right-section">
                <!-- Details -->
                <div class="details-section">
                    <div class="details-title">Informasi Donor</div>
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
                            <div class="detail-label">Status Donor</div>
                            <div class="detail-value">
                                @if($donor->status === 'approved')
                                    ✅ Disetujui untuk Donor
                                @elseif($donor->status === 'completed')
                                    🎉 Donor Berhasil Diselesaikan
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
                            <div class="detail-label">Lokasi Donor</div>
                            <div class="detail-value">🏥 PMI Kota Bandung</div>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="signatures">
                    <div class="signature-block">
                        <div class="signature-title">Petugas Medis</div>
                        <div class="signature-line"></div>
                        <div class="signature-name">dr. Ahmad Hidayat, Sp.PK</div>
                        <div class="signature-position">Dokter Penanggung Jawab Donor Darah</div>
                    </div>
                    
                    <div class="signature-block">
                        <div class="signature-title">Pimpinan Unit</div>
                        <div class="signature-line"></div>
                        <div class="signature-name">Dr. Siti Nurhaliza, M.Kes</div>
                        <div class="signature-position">Ketua PMI Kota Bandung</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="qr-section">
                <div class="qr-code">
                    <div style="font-weight: bold;">QR</div>
                    <div>{{ substr($donor->donor_code, -4) }}</div>
                </div>
                <div class="qr-info">
                    Sertifikat Digital<br>
                    Terverifikasi PMI
                </div>
            </div>
            
            <div class="footer-info">
                <div class="footer-text">
                    Sertifikat ini diterbitkan secara elektronik oleh Sistem Informasi PMI Kota Bandung
                </div>
                <div class="footer-contact">
                    Jl. Aceh No. 79, Bandung 40117 | Telp: (022) 4264265 | www.pmibandung.org
                </div>
            </div>

            <div class="print-date">
                Dicetak pada:<br>
                {{ now()->format('d F Y') }}<br>
                {{ now()->format('H:i') }} WIB
            </div>
        </div>
    </div>

    <script>
        // Auto print when opened with autoprint parameter
        if (window.location.search.includes('autoprint=1')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 1000);
            };
        }

        // Keyboard shortcut for printing
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });

        // Optimize for printing
        window.addEventListener('beforeprint', function() {
            document.body.style.margin = '0';
            document.body.style.padding = '0';
        });

        // Add some interactive effects for screen viewing
        document.addEventListener('DOMContentLoaded', function() {
            const detailItems = document.querySelectorAll('.detail-item');
            detailItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>
