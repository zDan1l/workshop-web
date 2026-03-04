<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga Barang</title>
    <style>
        @page {
            margin: 5mm;  /* Margin tepi kertas */
            size: 210mm 160mm;  /* 21cm x 16cm */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
        }
        
        .page {
            position: relative;
            width: 200mm;  /* 210mm - (2 × 5mm margin) */
            height: 150mm; /* 160mm - (2 × 5mm margin) */
            page-break-after: always;
        }
        
        .page:last-child {
            page-break-after: auto;
        }
        
        .label-item {
            position: absolute;
            width: 38mm;  /* Label: 3cm */
            height: 18mm; /* Label: 2cm */
            padding: 0;
        }
        
        .label-content {
            width: 100%;
            height: 100%;
            border: 1pt solid #000;
            border-radius: 1.5mm;
            padding: 1.5mm;
            text-align: center;
            background: #ffffff;
            display: table;
        }
        
        .label-inner {
            display: table-cell;
            vertical-align: middle;
        }
        
        .label-content .nama {
            font-size: 7pt;
            font-weight: bold;
            margin-bottom: 0.5mm;
            color: #333;
            line-height: 1.1;
            max-height: 9mm;
            overflow: hidden;
            word-wrap: break-word;
        }
        
        .label-content .harga {
            font-size: 8pt;
            font-weight: bold;
            color: #d32f2f;
            margin-top: 0.5mm;
            padding: 0.5mm 1.5mm;
            border: 0.8pt solid #d32f2f;
            border-radius: 1mm;
            display: inline-block;
        }
        
        .label-content .id {
            font-size: 5pt;
            color: #999;
            margin-top: 0.5mm;
        }
    </style>
</head>
<body>
    @php
        // Ukuran per label dengan spacing (dalam mm)
        $labelWidth = 38;   // Lebar label: 38mm
        $labelHeight = 18;  // Tinggi label: 18mm
        $gapX = 2;          // Gap horizontal: 2mm
        $gapY = 2;          // Gap vertikal: 2mm
        
        // Total spacing per cell
        $cellWidth = $labelWidth + $gapX;   // 40mm per cell
        $cellHeight = $labelHeight + $gapY; // 20mm per cell
    @endphp
    
    @foreach($pages as $pageIndex => $pageLabels)
        <div class="page">
            @foreach($pageLabels as $label)
                @php
                    $x = $label['x'];
                    $y = $label['y'];
                    $barang = $label['barang'];
                    
                    // Hitung posisi absolute dengan gap
                    $left = $x * $cellWidth;
                    $top = $y * $cellHeight;
                @endphp
                
                <div class="label-item" style="left: {{ $left }}mm; top: {{ $top }}mm;">
                    <div class="label-content">
                        <div class="label-inner">
                            <div class="nama">{{ $barang->nama }}</div>
                            <div class="harga">Rp {{ number_format($barang->harga, 0, '.', '.') }}</div>
                            <div class="id">#{{ $barang->id_barang }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
