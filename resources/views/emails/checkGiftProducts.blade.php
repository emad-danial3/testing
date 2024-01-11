<html>
<head>
</head>
<body>
<style>
    table {
        border: 2px solid #000;
        width: 80%;
        text-align: center;
    }
</style>
<br>
<br>
<h2 style='color:rgb(0, 148, 167)'>Dears, </h2>
<h2 style='color:rgb(0, 148, 167)'>The products below less than 500 pieces </h2>

<h2 style='color:red'>please increase stock as soon as possible  </h2>

<table class="table table-striped">
    <thead>
    <tr>
        <th>الصنف</th>
        <th>كود اوركل</th>
        <th>باركود</th>
        <th>الكمية الحالية</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['products'] as $row)
        <tr>
            <td>{{$row->full_name}}</td>
            <td>{{$row->oracle_short_code}}</td>
            <td>{{$row->barcode}}</td>
            <td>{{$row->quantity}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<br>

</body>
</html>
