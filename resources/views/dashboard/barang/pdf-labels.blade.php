<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Barang</title>
    <style>
        @page {
            margin: 3mm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .page-container {
            width: 100%;
            margin-bottom: 5mm;
        }

        table.label-grid {
            border-collapse: separate;
            border-spacing: 2mm;
            width: auto;
            table-layout: auto;
        }

        table.label-grid td {
            text-align: center;
            vertical-align: middle;
            padding: 2mm;
            border: 1px solid #000;
            page-break-inside: avoid;
        }

        .label-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1mm;
        }

        .barcode-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .barcode-wrapper svg {
            display: block;
            width: 100% !important;
            height: auto !important;
            max-height: 12mm;
        }

        .kode-barang {
            font-size: 8pt;
            font-weight: bold;
            color: #000;
            font-family: 'Courier New', monospace;
        }

        .harga {
            font-size: 9pt;
            font-weight: bold;
            color: #e74c3c;
        }
        .text-card{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1mm;
        }
    </style>
</head>
<body>
    @foreach($pages as $pageLabels)
        @php
            $grid = [];
            $maxRow = 0;
            foreach ($pageLabels as $label) {
                $grid[$label['y']][$label['x']] = $label;
                if ($label['y'] > $maxRow) {
                    $maxRow = $label['y'];
                }
            }
        @endphp

        <div class="page-container">
            <table class="label-grid">
                @for($row = 0; $row <= $maxRow; $row++)
                    <tr>
                        @for($col = 0; $col < 5; $col++)
                            @if(isset($grid[$row][$col]))
                                @php
                                    $labelData = $grid[$row][$col];
                                    $b = $labelData['barang'];
                                    $barcodeHtml = $labelData['barcode'] ?? '';
                                    $displayCode = $labelData['displayCode'] ?? '';
                                @endphp
                                <td>
                                    <div class="label-content">
                                        @if(!empty($barcodeHtml))
                                            <div class="barcode-wrapper">{!! $barcodeHtml !!}</div>
                                        @endif
                                        <div class="text-card">
                                            <p class="kode-barang">{{ $displayCode }}</p>
                                            <p class="harga">Rp{{ number_format($b->harga, 0, '.', '.') }}</p>
                                        </div>
                                    </div>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </table>
        </div>
    @endforeach
</body>
</html>
