<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga Barang</title>
    <style>
        /*
         * Kertas TnJ 108: 102mm × 78mm
         * Margin kertas: 2mm tiap sisi
         * Area cetak: 98mm × 74mm
         * Grid: 5 kolom × 8 baris
         * border-spacing: 1.5mm (gap antar label)
         */
        @page {
            margin: 2mm;
            size: 102mm 78mm;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
        }

        table.label-grid {
            border-collapse: separate;
            border-spacing: 1.5mm;
            width: 98mm;
            table-layout: fixed;
            page-break-after: always;
        }

        table.label-grid:last-child {
            page-break-after: auto;
        }

        table.label-grid td {
            width: 18mm;
            height: 7.5mm;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            padding: 0;
        }

        table.label-grid td div {
            margin: 0;
            padding: 0;
        }


        .nama {
            font-size: 3.5pt;
            font-weight: bold;
            color: #222;
            word-wrap: break-word;
        }

        .harga {
            font-size: 3.5pt;
            font-weight: bold;
            color: #222;
        }
    </style>
</head>
<body>
    @foreach($pages as $pageLabels)
        @php
            // Build lookup: grid[y][x] = barang
            $grid = [];
            foreach ($pageLabels as $label) {
                $grid[$label['y']][$label['x']] = $label['barang'];
            }
        @endphp
        <table class="label-grid">
            @for($row = 0; $row < 8; $row++)
                <tr>
                    @for($col = 0; $col < 5; $col++)
                        @if(isset($grid[$row][$col]))
                            @php $b = $grid[$row][$col]; @endphp
                            <td class="has-label">
                                <p class="nama">{{ $b->nama }}</p>
                                <p class="harga">Rp {{ number_format($b->harga, 0, '.', '.') }}</p>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endfor
                </tr>
            @endfor
        </table>
    @endforeach
</body>
</html>
