@extends('admin.layout')

@section('title', 'Giỏ hàng nhân viên')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Giỏ hàng nhân viên</h3>

    @if($cart->items->isEmpty())
    <div class="alert alert-info">Giỏ hàng đang trống.</div>
    @else
    <table class="table table-bordered align-middle text-center">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Ảnh</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                <td>
                    @if($item->product && $item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                    <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" style="max-height: 50px;">
                    @elseif($item->product && $item->product->image_url)
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="max-height: 50px;">
                    @else
                    N/A
                    @endif
                </td>
                <td>{{ number_format($item->product->price ?? 0, 0, ',', '.') }} VND</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }} VND</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Tổng cộng</th>
                <th>
                    {{ number_format($cart->items->reduce(function ($carry, $item) {
                        return $carry + (($item->product->price ?? 0) * $item->quantity);
                    }, 0), 0, ',', '.') }} VND
                </th>
            </tr>
        </tfoot>
    </table>
    @endif

    <a href="{{ route('employee.sales') }}" class="btn btn-secondary">Quay lại trang bán hàng</a>
</div>
@endsection
