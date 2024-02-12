<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <style>
        h1, h2, h3, h4, h5, h6 {color: #000000}
        h2 {font-size: 14px}
        p {font-size: 12px}
    </style>
    <title>Download Laporan Order</title>
</head>
<body>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.4/jspdf.plugin.autotable.min.js"></script>
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.min.js"></script>

    <div class="container" id="layout-detail">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-right" style>Laporan Order</h1>
                <table class="table" width="100%" border="1" cellspacing="0">
                    <thead>
                        <tr bgcolor="#cccccc">
                            <th>No.</th>
                            <th>Client Name</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1 @endphp
                        @foreach($order as $o)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $o->nama_client }}</td>
                            <td>{{ $o->nama_item }}</td>
                            <td>{{ $o->harga_item }}</td>
                            <td>{{ $o->tanggal_order }}</td>
                        </tr>
                        @php $no++ @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var element = document.getElementById('layout-detail');
        var opt = {
        margin:       [2, 2, 2.54, 2.54],
        filename:     "Laporan Order.pdf",
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'cm', format: 'a4', orientation: 'portrait' }
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save().then(() => {
                    // window.top.close();
        });
    </script>
</body>
</html>