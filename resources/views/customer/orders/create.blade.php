<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Pesanan Laundry Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('customer.orders.store') }}" id="orderForm">
                        @csrf

                        {{-- Kontainer untuk item layanan --}}
                        <div id="service_items_container">
                            {{-- Render item yang ada dari old input (jika validasi gagal) --}}
                            @if(is_array(old('items')) && count(old('items')) > 0)
                                @foreach(old('items') as $index => $oldItem)
                                    <div class="service-item grid grid-cols-12 gap-x-4 gap-y-2 items-end mb-4 border-b dark:border-gray-700 pb-4"
                                        data-index="{{ $index }}">
                                        {{-- Kolom Pilih Layanan --}}
                                        <div class="col-span-12 sm:col-span-5">
                                            <label for="service_id_{{ $index }}"
                                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Pilih Layanan') }}</label>
                                            <select id="service_id_{{ $index }}" name="items[{{ $index }}][service_id]"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm service-select"
                                                required>
                                                <option value="">-- Pilih Layanan --</option>
                                                {{-- Opsi akan diisi oleh JS, tapi untuk old() kita render dari PHP --}}
                                                @if(isset($services) && $services->count() > 0)
                                                    @foreach ($services as $service)
                                                        <option value="{{ $service->id }}" data-price="{{ $service->harga }}"
                                                            data-unit="{{ $service->satuan }}"
                                                            {{ (isset($oldItem['service_id']) && $oldItem['service_id'] == $service->id) ? 'selected' : '' }}>
                                                            {{ $service->nama_layanan }} (Rp
                                                            {{ number_format($service->harga, 0, ',', '.') }}/{{ $service->satuan }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('items.' . $index . '.service_id')
                                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- Kolom Kuantitas --}}
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="quantity_{{ $index }}"
                                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kuantitas') }}</label>
                                            <input id="quantity_{{ $index }}" name="items[{{ $index }}][quantity]" type="number"
                                                step="0.1" min="0.1" value="{{ $oldItem['quantity'] ?? 1 }}"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm quantity-input"
                                                required />
                                            @error('items.' . $index . '.quantity')
                                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- Kolom Subtotal --}}
                                        <div class="col-span-10 sm:col-span-3">
                                            <label
                                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Subtotal') }}</label>
                                            <p
                                                class="mt-1 block w-full p-2.5 border border-gray-300 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-300 rounded-md shadow-sm subtotal-text">
                                                Rp 0</p>
                                        </div>
                                        {{-- Tombol Hapus --}}
                                        <div
                                            class="col-span-2 sm:col-span-1 flex items-center sm:items-end justify-end sm:justify-start">
                                            <button type="button"
                                                class="remove-item-btn p-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md shadow-sm">
                                                X
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            {{-- Akhir dari render old('items') --}}
                        </div>

                        <div class="mt-4">
                            <button type="button" id="add_item_btn"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('+ Tambah Layanan Lain') }}
                            </button>
                        </div>

                        <hr class="my-6 dark:border-gray-700">

                        <div class="mt-4">
                            <label for="catatan_pelanggan"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Catatan Tambahan (Opsional)') }}</label>
                            <textarea id="catatan_pelanggan" name="catatan_pelanggan" rows="3"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('catatan_pelanggan') }}</textarea>
                            @error('catatan_pelanggan')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 text-right">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Estimasi Harga:
                                <span id="grand_total_text"
                                    class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Rp 0</span>
                            </h3>
                        </div>

                        <div class="mt-8 flex items-center justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Buat Pesanan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Di suatu tempat di Blade, sebelum @push('scripts') --}}
    <div id="services-data-container" data-services="{{
    json_encode(
        isset($services) && $services->count() > 0 ?
        $services->map(function ($service) {
            return [
                'id' => (string) $service->id,
                'name' => $service->nama_layanan,
                'price' => (float) $service->harga,
                'unit' => $service->satuan,
            ];
        })->values()->all() // ->all() untuk mendapatkan array PHP murni
        : []
    )
}}"></div>

    {{-- Template untuk item layanan baru (disembunyikan dengan style) --}}
    <template id="service_item_template">
        <div class="service-item grid grid-cols-12 gap-x-4 gap-y-2 items-end mb-4 border-b dark:border-gray-700 pb-4"
            data-index="__INDEX__">
            <div class="col-span-12 sm:col-span-5">
                <label for="service_id___INDEX__"
                    class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Pilih Layanan') }}</label>
                <select id="service_id___INDEX__" name="items[__INDEX__][service_id]"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm service-select"
                    required>
                    <option value="">-- Pilih Layanan --</option>
                    {{-- Opsi akan diisi oleh JavaScript dari data servicesData --}}
                </select>
            </div>
            <div class="col-span-12 sm:col-span-3">
                <label for="quantity___INDEX__"
                    class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Kuantitas') }}</label>
                <input id="quantity___INDEX__" name="items[__INDEX__][quantity]" type="number" step="0.1" min="0.1"
                    value="1"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm quantity-input"
                    required />
            </div>
            <div class="col-span-10 sm:col-span-3">
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Subtotal') }}</label>
                <p
                    class="mt-1 block w-full p-2.5 border border-gray-300 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-300 rounded-md shadow-sm subtotal-text">
                    Rp 0</p>
            </div>
            <div class="col-span-2 sm:col-span-1 flex items-center sm:items-end justify-end sm:justify-start">
                <button type="button"
                    class="remove-item-btn p-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md shadow-sm">
                    X
                </button>
            </div>
        </div>
    </template>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded',function() {
                const itemsContainer=document.getElementById('service_items_container');
                const addItemBtn=document.getElementById('add_item_btn');
                const grandTotalText=document.getElementById('grand_total_text');
                const templateNode=document.getElementById('service_item_template');

                if(!itemsContainer||!addItemBtn||!grandTotalText||!templateNode) {
                    console.error('Satu atau lebih elemen penting UI (itemsContainer, addItemBtn, grandTotalText, service_item_template) tidak ditemukan. Cek ID elemen.');
                    return;
                }
                const itemTemplateHtml=templateNode.innerHTML;

                const servicesDataContainer=document.getElementById('services-data-container');
                let servicesData=[]; // Default ke array kosong

                if(servicesDataContainer&&servicesDataContainer.dataset.services) {
                    try {
                        servicesData=JSON.parse(servicesDataContainer.dataset.services);
                    } catch(e) {
                        console.error('Error parsing services data from HTML attribute:',e);
                        // Anda bisa menambahkan fallback atau penanganan error di sini
                    }
                } else {
                    console.warn('Elemen services-data-container atau atribut data-services tidak ditemukan.');
                }
                console.log('Services Data from HTML attribute:',servicesData);
                let itemIndexCounter=0; // Counter untuk indeks item baru

                function updateGrandTotal() {
                    let grandTotal=0;
                    itemsContainer.querySelectorAll('.service-item').forEach(itemRow => {
                        const subtotalEl=itemRow.querySelector('.subtotal-text'); // Ambil elemen <p> subtotal
                        if(subtotalEl) {
                            const subtotalText=subtotalEl.textContent;
                            // Parsing yang lebih robust untuk angka dari string "Rp xxx.yyy" atau "Rp xxx"
                            const subtotalValue=parseFloat(subtotalText.replace(/Rp\s*|\./g,"").replace(',','.'))||0;
                            // Penjelasan:
                            // 1. subtotalText.replace(/Rp\s*|\./g, ""): Menghapus "Rp ", spasi setelahnya, dan semua titik (pemisah ribuan).
                            //    Contoh: "Rp 11.000" menjadi "11000"
                            //             "Rp 5.500" menjadi "5500"
                            // 2. .replace(',', '.'): Jika Anda menggunakan koma sebagai pemisah desimal di input, ubah ke titik.
                            //    (Untuk kasus Rupiah biasanya tidak ada desimal di subtotal/total, jadi ini mungkin tidak krusial)
                            // 3. parseFloat(...) || 0: Konversi ke float, jika gagal (NaN), anggap 0.
                            grandTotal+=subtotalValue;
                        }
                    });
                    // Format grandTotal ke Rupiah dengan pemisah ribuan
                    grandTotalText.textContent='Rp '+new Intl.NumberFormat('id-ID',{minimumFractionDigits: 0,maximumFractionDigits: 0}).format(grandTotal);
                }

                function calculateSubtotal(itemRow) {
                    const serviceSelect=itemRow.querySelector('.service-select');
                    const quantityInput=itemRow.querySelector('.quantity-input');
                    const subtotalEl=itemRow.querySelector('.subtotal-text');

                    if(!serviceSelect||!quantityInput||!subtotalEl) return;

                    const selectedOption=serviceSelect.options[serviceSelect.selectedIndex];
                    const price=(selectedOption&&selectedOption.dataset.price)? parseFloat(selectedOption.dataset.price):0;
                    const quantity=parseFloat(quantityInput.value)||0;

                    const subtotal=price*quantity;
                    // Format subtotal dengan benar saat ditampilkan
                    subtotalEl.textContent='Rp '+new Intl.NumberFormat('id-ID',{minimumFractionDigits: 0,maximumFractionDigits: 0}).format(subtotal);
                    updateGrandTotal(); // Panggil updateGrandTotal setelah subtotal dihitung
                }


                function toggleRemoveButtonsVisibility() {
                    const items=itemsContainer.querySelectorAll('.service-item');
                    items.forEach((item) => {
                        const removeBtn=item.querySelector('.remove-item-btn');
                        if(removeBtn) {
                            removeBtn.classList.toggle('hidden',items.length<=1);
                        }
                    });
                }

                function addServiceItemEventListeners(itemRow) {
                    const serviceSelect=itemRow.querySelector('.service-select');
                    const quantityInput=itemRow.querySelector('.quantity-input');
                    const removeBtn=itemRow.querySelector('.remove-item-btn');

                    if(serviceSelect) serviceSelect.addEventListener('change',() => calculateSubtotal(itemRow));
                    if(quantityInput) quantityInput.addEventListener('input',() => calculateSubtotal(itemRow));

                    if(removeBtn) {
                        removeBtn.addEventListener('click',function() {
                            itemRow.remove();
                            updateGrandTotal();
                            toggleRemoveButtonsVisibility();
                            if(itemsContainer.querySelectorAll('.service-item').length===0&&addItemBtn) { // Pastikan addItemBtn ada
                                addNewServiceItem(true); // Tambah satu jika semua dihapus
                            }
                        });
                    }
                }

                function addNewServiceItem(isInitial=false) {
                    // Tentukan indeks baru berdasarkan jumlah item yang sudah ada di kontainer
                    // Ini memastikan jika ada item dari old(), indeks berikutnya benar
                    const newIndex=itemsContainer.querySelectorAll('.service-item').length>0?
                        Math.max(...Array.from(itemsContainer.querySelectorAll('.service-item')).map(el => parseInt(el.dataset.index)||0))+1
                        :0;
                    itemIndexCounter=newIndex; // Update counter global jika perlu, atau gunakan newIndex langsung

                    const newItemHtmlWithIndex=itemTemplateHtml.replace(/__INDEX__/g,newIndex);
                    const tempDiv=document.createElement('div');
                    tempDiv.innerHTML=newItemHtmlWithIndex.trim();
                    const newItemRow=tempDiv.firstChild;

                    // Isi select options
                    const serviceSelect=newItemRow.querySelector('.service-select');
                    if(serviceSelect) {
                        // Hapus opsi placeholder lama dari template jika ada (opsional, tergantung template)
                        // serviceSelect.options.length = 0; // Hapus semua opsi lama
                        // const placeholderOption = document.createElement('option');
                        // placeholderOption.value = "";
                        // placeholderOption.textContent = "-- Pilih Layanan --";
                        // serviceSelect.appendChild(placeholderOption); // Tambahkan placeholder lagi

                        servicesData.forEach(service => {
                            const option=document.createElement('option');
                            option.value=service.id;
                            option.textContent=`${service.name} (Rp ${new Intl.NumberFormat('id-ID').format(service.price)}/${service.unit})`;
                            option.dataset.price=service.price;
                            option.dataset.unit=service.unit;
                            serviceSelect.appendChild(option);
                        });
                    }

                    itemsContainer.appendChild(newItemRow);
                    addServiceItemEventListeners(newItemRow);
                    toggleRemoveButtonsVisibility(); // Selalu panggil ini setelah menambah/menghapus
                    updateGrandTotal();
                }

                if(addItemBtn) {
                    addItemBtn.addEventListener('click',function() {
                        addNewServiceItem(false);
                    });
                }

                // Inisialisasi untuk item yang sudah ada (dirender oleh Blade dari old() atau item default)
                const existingItems=itemsContainer.querySelectorAll('.service-item');
                if(existingItems.length>0) {
                    existingItems.forEach((itemRow,index) => {
                        // Perbarui data-index jika belum sesuai (misalnya jika old() membuat indeks tidak berurutan)
                        itemRow.dataset.index=index;
                        // Perbarui ID dan name untuk konsistensi jika perlu (terutama jika old() tidak lengkap)
                        itemRow.querySelectorAll('[id]').forEach(el => {if(el.id.includes('_')) el.id=el.id.replace(/_\d+$/,'_'+index);});
                        itemRow.querySelectorAll('[name]').forEach(el => {if(el.name.includes('[')) el.name=el.name.replace(/\[\d+\]/,'['+index+']');});
                        itemRow.querySelectorAll('label[for]').forEach(el => {if(el.htmlFor.includes('_')) el.htmlFor=el.htmlFor.replace(/_\d+$/,'_'+index);});


                        addServiceItemEventListeners(itemRow);
                        const serviceSelect=itemRow.querySelector('.service-select');
                        const quantityInput=itemRow.querySelector('.quantity-input');

                        // Jika ini adalah item yang dirender dari old(), opsinya sudah diisi oleh Blade.
                        // Kita hanya perlu memastikan subtotal dihitung.
                        if(serviceSelect&&quantityInput&&serviceSelect.value&&quantityInput.value) {
                            calculateSubtotal(itemRow);
                        }
                    });
                } else {
                    // Jika tidak ada item dari old(), tambahkan satu item awal secara otomatis.
                    addNewServiceItem(true);
                }

                toggleRemoveButtonsVisibility();
                updateGrandTotal();
            });
        </script>
    @endpush
</x-app-layout>