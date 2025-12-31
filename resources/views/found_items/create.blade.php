<form method="POST" action="{{ route('found-items.store') }}">
    @csrf

    <input name="item_name" placeholder="Nama barang">
    <input type="date" name="found_date">
    <input name="location" placeholder="Lokasi">
    <textarea name="description"></textarea>

    <button>Simpan</button>
</form>
