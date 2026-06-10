<x-app-layout>
    <div class="container py-5 mx-auto">
        <div class="row justify-content-center flex">
            <div class="col-md-8 w-full max-w-2xl mx-auto">
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h5 class="text-xl font-bold text-gray-800">Form Booking Unit</h5>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('booking.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="kos_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kos / Properti</label>
                                <input type="text" name="kos_name" id="kos_name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('kos_name') }}" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="room_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kamar</label>
                                    <input type="text" name="room_type" id="room_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('room_type') }}" required>
                                </div>
                                <div>
                                    <label for="price_per_month" class="block text-sm font-medium text-gray-700 mb-1">Harga per Bulan (Rp)</label>
                                    <input type="number" name="price_per_month" id="price_per_month" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('price') }}" required>
                                </div>
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                <input type="text" name="location" id="location" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('location') }}" required>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tenant_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Penyewa</label>
                                    <input type="text" name="tenant_name" id="tenant_name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ auth()->user()->name }}" required>
                                </div>
                                <div>
                                    <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-1">Durasi Sewa (Bulan)</label>
                                    <select name="duration_months" id="duration_months" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="1">1 Bulan</option>
                                        <option value="3">3 Bulan</option>
                                        <option value="6">6 Bulan</option>
                                        <option value="12">12 Bulan</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Rencana Tanggal Masuk</label>
                                <input type="date" name="booking_date" id="booking_date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-md transition duration-150 ease-in-out">
                                    Lanjutkan ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
