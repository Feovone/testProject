@extends('layout')
@section('content')
    <div class="container ">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark ">
            <th>Product Name</th>
            <th>Collection</th>
            <th>Count (how many sales for this product)</th>
            <th>Gross Margin (%)</th>
            <th>Total Income (based on sales)</th>
            </thead>
            <tbody>
            @foreach($products as $key=>$product)
                <tr>
                    <td> {{$product->name}} </td>
                    <td>  {{$product->collection}}  </td>
                    <td>  {{$product->count}}  </td>
                    <td>  {{$productsGrossMargin[$key]['gross_margin']}}  </td>
                    <td>  {{$product->total_income/100}}  </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
