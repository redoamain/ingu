@extends('layouts.app')

@section('title', $product->name . ' - INGU')

@section('content')
<div align="center">
    <a href="{{ route('products.index') }}" class="btn">← Kembali ke Daftar</a>
</div>

<br>

<div align="center">
    <div style="width: 80%; border: 1px solid #c0c0c0; padding: 20px;">

        @php
            $imageUrl = $product->image && file_exists(storage_path('app/public/' . $product->image))
                ? asset('storage/' . $product->image)
                : 'https://ui-avatars.com/api/?background=000080&color=fff&name=' . urlencode($product->name);
        @endphp

        <!-- Gambar -->
        <div align="center">
            <img src="{{ $imageUrl }}" width="200" style="border: 2px solid #c0c0c0; padding: 5px;">
        </div>

        <br>

        <!-- Informasi Produk -->
        <table width="100%" cellpadding="8">
            <tr>
                <td width="30%"><b>Item ID</b></td>
                <td><b>:</b></td>
                <td>{{ $product->item_id }}</td>
            

            </tr>
            <tr>
                <td><b>Nama Produk</b></td>
                <td><b>:</b></td>
                <td colspan="3">{{ $product->name }}</td>
            </tr>
            <tr>
                <td><b>Nama Barang Wincp</b></td>
                <td><b>:</b></td>
                <td colspan="3">{{ $goods->ItemName ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Bahan</b></td>
                <td><b>:</b></td>
                <td colspan="3">{{ $goods->bahan ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Warna</b></td>
                <td><b>:</b></td>
                <td colspan="3">{{ $goods->warnac ?? '-' }}</td>
            </tr>
        </table>

        <hr>

        <!-- Spesifikasi -->
        <div align="left">
            <b><font face="Arial">📝 SPESIFIKASI</font></b><br>
            <font size="2">{{ $goods->Spec ?? '-' }}</font>
        </div>

        <hr>

        <!-- Deskripsi -->
        <div align="left">
            <b><font face="Arial">📄 DESKRIPSI</font></b><br>
            <font size="2">{{ $product->description ?? '-' }}</font>
        </div>

        @if($product->additional_info)
        <hr>
        <div align="left">
            <b><font face="Arial">ℹ️ KETERANGAN</font></b><br>
            <font size="2">{{ $product->additional_info }}</font>
        </div>
        @endif

                    @if($product->status == 'active')
                    <hr>
                    <div>
                    </div>
                    <font color="green"><b>AKTIF</b></font>
                    @else
                    <font color="red"><b>TIDAK AKTIF</b></font>

                    @endif

    </div>
</div>

<br>

<div align="center">
    <a href="{{ route('products.index') }}" class="btn">← Kembali ke Daftar</a>
</div>
@endsection
