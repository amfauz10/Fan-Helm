@extends('admin.layouts.admin')

@section('title', 'Pesan Kontak - Fan Helmet')
@section('page_title', 'Pesan & Pertanyaan Pelanggan')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h3 class="card-title">Kotak Masuk Pesan ({{ $messages->total() }})</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pengirim</th>
                        <th>Kontak</th>
                        <th>Isi Pesan</th>
                        <th>Waktu Masuk</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--text-title);">{{ $msg->first_name }} {{ $msg->last_name }}</div>
                            </td>
                            <td>
                                <div><a href="mailto:{{ $msg->email }}" style="color: var(--primary);">{{ $msg->email }}</a></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $msg->phone }}</div>
                            </td>
                            <td>
                                <div style="font-size: 0.9rem; line-height: 1.5; max-width: 400px; color: var(--text-title);">
                                    {{ $msg->message }}
                                </div>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">
                                {{ $msg->created_at->diffForHumans() }}<br>
                                <span style="font-size: 0.75rem;">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}" target="_blank" class="btn btn-secondary btn-sm" title="Balas via WhatsApp">
                                        <i class='bx bxl-whatsapp' style="color:#10b981; font-size:1.1rem;"></i>
                                    </a>
                                    <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus Pesan">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Belum ada pesan masuk dari formulir kontak.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 16px;">
        {{ $messages->links() }}
    </div>
@endsection
