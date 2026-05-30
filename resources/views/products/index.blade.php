@extends('layouts.app')

@section('title', 'Inventory Gudang - INGU')

@section('content')
<!-- Header -->
<div align="center">
    <h2><font face="Arial" color="#000080">PRODUCT CATALOG</font></h2>
    <hr width="30%">
</div>

<!-- Search Box -->
<div class="search-box">
    <form method="GET" action="{{ route('products.index') }}">
        <font face="Arial" size="2">Cari Produk:</font>
        <input type="text" name="search" value="{{ request('search') }}" size="30">
        <input type="submit" value="CARI" class="btn">
        @if(request('search'))
            <a href="{{ route('products.index') }}" class="btn">RESET</a>
        @endif
    </form>
</div>

<!-- Products Grid - Card Layout -->
<div align="center">
    @forelse($products as $product)
        @php
            $goods = App\Models\Goods::on('sqlsrv_master')->where('ItemID', $product->item_id)->first();
            $imageUrl = $product->image && file_exists(storage_path('app/public/' . $product->image))
                ? asset('storage/' . $product->image)
                : 'https://ui-avatars.com/api/?background=000080&color=fff&name=' . urlencode($product->name);
        @endphp

        <div class="card">
            <div class="card-image">
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
            </div>
            <div class="card-title">{{ $product->name }}</div>
            <div class="card-itemid">ID: {{ $product->item_id }}</div>
            <div class="card-detail">
                <b>Barang:</b> {{ $goods->ItemName ?? '-' }}<br>
                <b>Bahan:</b> {{ $goods->bahan ?? '-' }}<br>
                <b>Warna:</b> {{ $goods->warnac ?? '-' }}
            </div>
            <a href="{{ route('products.show', $product->id) }}" class="card-button">Lihat Detail →</a>
        </div>
    @empty
        <div align="center" style="padding: 50px;">
            <b>❌ TIDAK ADA PRODUK ❌</b>
        </div>
    @endforelse
</div>

<div class="clear"></div>

<!-- Info -->
<div align="center" style="margin-top: 10px;">
    <font size="1" face="Arial">
        Total Produk: <b>{{ $products->total() }}</b>
        | Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
    </font>
</div>

<!-- Pagination -->
<div class="pagination">
    {{ $products->links() }}
</div>
@endsection
