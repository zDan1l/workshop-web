<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga Barang</title>
    <style>
        @page {
            margin: 3mm;
            size: 102mm 78mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        /*
         * Kertas TnJ 108: 102mm × 78mm
         * Margin kertas: 3mm tiap sisi
         * Area cetak: 96mm × 72mm
         * Grid: 5 kolom × 8 baris
         * Cell : 19.2mm × 9mm
         * Label: 17.2mm × 7.4mm (cell dikurangi gap)
         * Gap  : 2mm horizontal, 1.6mm vertikal (terlihat jelas)
         */
        .page {
            position: relative;
            width: 96mm;
            height: 72mm;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .label-item {
            position: absolute;
            width: 19.2mm;  /* 96mm / 5 */
            height: 9mm;    /* 72mm / 8 */
            padding: 0.8mm 1mm; /* gap: 1.6mm vertikal, 2mm horizontal */
        }

        .label-content {
            width: 100%;
            height: 100%;
            border: 0.5pt solid #444;
            border-radius: 0.6mm;
            text-align: center;
            background: #fff;
            display: table;
        }

        .label-inner {
            display: table-cell;
            vertical-align: middle;
            padding: 0.2mm 0.3mm;
        }

        .nama {
            font-size: 4pt;
            font-weight: bold;
            color: #222;
            line-height: 1.1;
            margin-bottom: 0.2mm;
            overflow: hidden;
            word-wrap: break-word;
        }

        .harga {
            font-size: 4.5pt;
            font-weight: bold;
            color: #c62828;
            padding: 0.1mm 0.5mm;
            border: 0.3pt solid #c62828;
            border-radius: 0.4mm;
            display: inline-block;
        }
    </style>
</head>
<body>
    @php
        $cellW = 19.2;  // 96mm / 5 kolom
        $cellH = 9;     // 72mm / 8 baris
    @endphp

    @foreach($pages as $pageLabels)
        <div class="page">
            @foreach($pageLabels as $label)
                @php
                    $left = $label['x'] * $cellW;
                    $top  = $label['y'] * $cellH;
                    $b    = $label['barang'];
                @endphp
                <div class="label-item" style="left:{{ $left }}mm; top:{{ $top }}mm;">
                    <div class="label-content">
                        <div class="label-inner">
                            <div class="nama">{{ $b->nama }}</div>
                            <div class="harga">Rp {{ number_format($b->harga, 0, '.', '.') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
