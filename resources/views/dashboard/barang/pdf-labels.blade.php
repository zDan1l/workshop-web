<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga Barang</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            width: 210mm;
            height: 297mm;
        }
        
        .page {
            width: 210mm;
            height: 297mm;
            padding: 8mm 5mm;
            position: relative;
        }
        
        .label-grid {
            display: table;
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        
        .label-row {
            display: table-row;
            height: 12.5%; /* 100% / 8 rows */
        }
        
        .label-cell {
            display: table-cell;
            width: 20%; /* 100% / 5 columns */
            padding: 2mm;
            vertical-align: middle;
            text-align: center;
            border: 0.5pt dashed #ddd;
        }
        
        .label-content {
            width: 100%;
            height: 100%;
            border: 1.5pt solid #000;
            border-radius: 3mm;
            padding: 2mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #ffffff;
        }
        
        .label-content .nama {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 2mm;
            color: #333;
            text-align: center;
            line-height: 1.2;
            max-height: 15mm;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        
        .label-content .harga {
            font-size: 12pt;
            font-weight: bold;
            color: #d32f2f;
            margin-top: 1mm;
            padding: 1mm 3mm;
            border: 1pt solid #d32f2f;
            border-radius: 2mm;
            background: #fff;
        }
        
        .label-content .id {
            font-size: 6pt;
            color: #999;
            margin-top: 1mm;
        }
        
        .label-cell.empty {
            border: none;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="label-grid">
            @php
                $labelsByPosition = [];
                foreach ($labels as $label) {
                    $key = $label['y'] . '_' . $label['x'];
                    $labelsByPosition[$key] = $label['barang'];
                }
            @endphp
            
            @for($y = 0; $y < 8; $y++)
                <div class="label-row">
                    @for($x = 0; $x < 5; $x++)
                        @php
                            $key = $y . '_' . $x;
                            $barang = $labelsByPosition[$key] ?? null;
                        @endphp
                        
                        <div class="label-cell {{ $barang ? '' : 'empty' }}">
                            @if($barang)
                                <div class="label-content">
                                    <div class="nama">{{ $barang->nama }}</div>
                                    <div class="harga">Rp {{ number_format($barang->harga, 0, '.', '.') }}</div>
                                    <div class="id">#{{ $barang->id_barang }}</div>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            @endfor
        </div>
    </div>
</body>
</html>
