@extends('admin.layouts.admin')

@section('title', 'Tambah Produk Baru - Fan Helmet')
@section('page_title', 'Tambah Produk Helm Baru')

@section('content')
    <div class="admin-card" style="max-width: 800px;">
        <div class="card-header">
            <h3 class="card-title">Form Informasi Helm</h3>
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

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Helm *</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Track Star V2" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="category">Kategori *</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="Full-Face" {{ old('category') == 'Full-Face' ? 'selected' : '' }}>Full-Face</option>
                            <option value="Half-Face" {{ old('category') == 'Half-Face' ? 'selected' : '' }}>Half-Face</option>
                            <option value="Full-Face Classic" {{ old('category') == 'Full-Face Classic' ? 'selected' : '' }}>Full-Face Classic</option>
                            <option value="Modular" {{ old('category') == 'Modular' ? 'selected' : '' }}>Modular</option>
                            <option value="Urban Half-Face" {{ old('category') == 'Urban Half-Face' ? 'selected' : '' }}>Urban Half-Face</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="price">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" placeholder="Contoh: 950000" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="subtitle">Slogan / Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle') }}" placeholder="Contoh: Aerodinamis tinggi untuk sirkuit">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Foto Produk Helm (Upload File)</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small style="color: var(--text-muted); display: block; margin-top: 4px;">Format: JPG, PNG, WEBP. Maks 3MB. Jika dikosongkan akan menggunakan foto default.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilihan Ukuran Tersedia</label>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.9rem;">
                                <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, old('sizes', ['M', 'L', 'XL'])) ? 'checked' : '' }} style="accent-color: var(--primary);">
                                {{ $size }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="specification">Spesifikasi</label>
                    <textarea name="specification" id="specification" rows="3" class="form-control" placeholder="Warna, bobot, jenis visor, penguncian...">{{ old('specification') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="materials">Material Bahan</label>
                    <textarea name="materials" id="materials" rows="2" class="form-control" placeholder="Contoh: Plastik ABS Thermoplastic kuat dan tahan benturan...">{{ old('materials') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi Lengkap</label>
                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Penjelasan lengkap helm...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.92rem; font-weight: 600;">
                        <input type="checkbox" name="is_weekly_featured" value="1" {{ old('is_weekly_featured', 1) ? 'checked' : '' }} style="accent-color: var(--primary); width: 18px; height: 18px;">
                        Tampilkan di Produk Unggulan Mingguan (Beranda)
                    </label>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 1rem;">
                        <i class='bx bx-save'></i> Simpan Produk Helm
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
