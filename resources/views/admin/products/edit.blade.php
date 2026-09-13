@extends('admin.layouts.admin')

@section('title', 'Edit Produk ' . $product->name . ' - Fan Helmet')
@section('page_title', 'Edit Produk Helm')

@section('content')
    <div class="admin-card" style="max-width: 800px;">
        <div class="card-header">
            <h3 class="card-title">Edit: {{ $product->name }}</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                &larr; Kembali ke Daftar
            </a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Helm *</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="category">Kategori *</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="Full-Face" {{ old('category', $product->category) == 'Full-Face' ? 'selected' : '' }}>Full-Face</option>
                            <option value="Half-Face" {{ old('category', $product->category) == 'Half-Face' ? 'selected' : '' }}>Half-Face</option>
                            <option value="Full-Face Classic" {{ old('category', $product->category) == 'Full-Face Classic' ? 'selected' : '' }}>Full-Face Classic</option>
                            <option value="Modular" {{ old('category', $product->category) == 'Modular' ? 'selected' : '' }}>Modular</option>
                            <option value="Urban Half-Face" {{ old('category', $product->category) == 'Urban Half-Face' ? 'selected' : '' }}>Urban Half-Face</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="price">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" class="form-control" value="{{ old('price', (int)$product->price) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="subtitle">Slogan / Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $product->subtitle) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Saat Ini</label>
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 10px;">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 70px; height: 70px; object-fit: contain; border-radius: 8px; border: 1px solid var(--border-color); background:var(--bg-main);">
                        <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $product->image }}</span>
                    </div>
                    <label class="form-label" for="image">Ganti Foto (Upload File Baru)</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small style="color: var(--text-muted); display: block; margin-top: 4px;">Kosongkan jika tidak ingin mengganti foto helm.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilihan Ukuran Tersedia</label>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        @php $currentSizes = $product->sizes ?? []; @endphp
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.9rem;">
                                <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, old('sizes', $currentSizes)) ? 'checked' : '' }} style="accent-color: var(--primary);">
                                {{ $size }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="specification">Spesifikasi</label>
                    <textarea name="specification" id="specification" rows="3" class="form-control">{{ old('specification', $product->specification) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="materials">Material Bahan</label>
                    <textarea name="materials" id="materials" rows="2" class="form-control">{{ old('materials', $product->materials) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi Lengkap</label>
                    <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.92rem; font-weight: 600;">
                        <input type="checkbox" name="is_weekly_featured" value="1" {{ old('is_weekly_featured', $product->is_weekly_featured) ? 'checked' : '' }} style="accent-color: var(--primary); width: 18px; height: 18px;">
                        Tampilkan di Produk Unggulan Mingguan (Beranda)
                    </label>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 1rem;">
                        <i class='bx bx-check'></i> Perbarui Produk Helm
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
