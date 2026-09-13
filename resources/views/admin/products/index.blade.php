@extends('admin.layouts.admin')

@section('title', 'Kelola Produk - Fan Helmet')
@section('page_title', 'Daftar Katalog Produk Helm')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h3 class="card-title">Semua Produk Helm ({{ $products->total() }})</h3>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class='bx bx-plus-circle'></i> Tambah Produk Baru
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Gambar</th>
                        <th>Nama Helm</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Ukuran</th>
                        <th>Unggulan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>
                                <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 6px; background: var(--bg-main); border: 1px solid var(--border-color);">
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-title);">{{ $p->name }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">Slug: {{ $p->slug }}</div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $p->category }}</span>
                            </td>
                            <td>
                                <strong style="color: var(--primary);">{{ $p->formatted_price }}</strong>
                            </td>
                            <td>
                                @if(!empty($p->sizes))
                                    @foreach($p->sizes as $s)
                                        <span style="display:inline-block; font-size:0.75rem; padding:1px 6px; background:var(--bg-main); border-radius:4px; font-weight:600; margin-right:2px;">{{ $s }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if($p->is_weekly_featured)
                                    <span class="badge badge-success">Ya (Mingguan)</span>
                                @else
                                    <span class="badge badge-secondary">Tidak</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('product.show', $p->slug) }}" target="_blank" class="btn btn-secondary btn-sm" title="Lihat di Toko">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-secondary btn-sm" title="Edit Produk">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $p->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Belum ada data produk helm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 16px;">
        {{ $products->links() }}
    </div>
@endsection
