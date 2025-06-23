<x-admin-layout>
    <div class="pt-24 sm:pt-4 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex justify-center">
                <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                    Manajemen Panti Asuhan
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                <div class="flex items-center mb-4">
                    <label for="filterStatus" class="mr-2 text-sm text-gray-700">Filter Status:</label>
                    <select id="filterStatus" class="rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50 text-sm">
                        <option value="all">All</option>
                        <option value="verified">Verified</option>
                        <option value="unverified">Unverified</option>
                    </select>
                </div>
                <div id="panti-list" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center py-12 border-2 border-dashed rounded-xl col-span-full">
                        <i class="fa-solid fa-house-chimney-user text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Memuat data panti asuhan...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Panti -->
    <div id="pantiDetailModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Detail Panti Asuhan</h3>
                <button data-modal-close="pantiDetailModal" class="text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>
            </div>
            <div class="p-4 md:p-6 flex-grow overflow-auto space-y-4">
                <div class="flex flex-col items-center">
                    <img id="modalFotoProfil" class="w-100 object-cover border mb-2" src="" alt="Foto Panti">
                    <p id="modalNamaPanti" class="font-bold text-xl text-gray-800"></p>
                    <p id="modalKontak" class="text-xs text-gray-500"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Alamat:</p>
                    <p id="modalAlamat" class="text-gray-700"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Deskripsi:</p>
                    <p id="modalDeskripsi" class="text-gray-700"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Verifikasi:</p>
                    <span id="modalStatusVerifikasi" class="px-2 py-1 text-xs font-semibold rounded-full"></span>
                </div>
                <div class="flex items-center gap-2">
                    <img id="modalUserAvatar" class="w-8 h-8 rounded-full object-cover border" src="" alt="Pengurus">
                    <div>
                        <p class="font-semibold text-gray-800" id="modalUserName"></p>
                        <p class="text-xs text-gray-500" id="modalUserEmail"></p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nomor Rekening:</p>
                    <p id="modalNomorRekening" class="text-gray-700"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Bank:</p>
                    <p id="modalBank" class="text-gray-700"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Dokumen Verifikasi:</p>
                    <a id="modalDokumenVerifikasi" href="#" target="_blank" class="text-primary-green underline"></a>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Dibuat: <span id="modalCreatedAt"></span></p>
                    <p class="text-xs text-gray-400">Diupdate: <span id="modalUpdatedAt"></span></p>
                </div>
                <!-- Form update status & delete -->
                <form id="updateStatusForm" class="flex items-center justify-between gap-2 mt-4">
    <input type="hidden" id="modalPantiId">
    
    <!-- Bagian kiri (select) -->
    <div class="flex-1 mb-6">
        <select id="modalUpdateStatus" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50 text-sm">
            <option value="verified">Verified</option>
            <option value="unverified">Unverified</option>
        </select>
    </div>
    
    <!-- Bagian kanan (button group) -->
    <div class="flex items-center gap-2 mb-6">
        <button type="button" id="deletePantiBtn" class="px-3 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 transition flex items-center" title="Hapus Panti">
            <i class="fa-solid fa-trash mr-2"></i> Delete
        </button>
        <button type="submit" id="updateStatusBtn" class="px-3 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700 transition flex items-center" title="Update Status">
            <i class="fa-solid fa-check mr-1"></i> Update
        </button>
    </div>
</form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const pantiListContainer = document.getElementById('panti-list');
        const pantiDetailModal = document.getElementById('pantiDetailModal');
        const updateStatusForm = document.getElementById('updateStatusForm');
        const updateStatusBtn = document.getElementById('updateStatusBtn');
        const deletePantiBtn = document.getElementById('deletePantiBtn');
        const modalUpdateStatus = document.getElementById('modalUpdateStatus');
        const modalPantiId = document.getElementById('modalPantiId');

        let currentStatus = 'all';

        document.getElementById('filterStatus').addEventListener('change', function() {
            currentStatus = this.value;
            fetchPantis();
        });

        async function fetchPantis() {
            try {
                let url = '/admin/panti/api';
                if (currentStatus && currentStatus !== 'all') {
                    url += '?status=' + encodeURIComponent(currentStatus);
                }
                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                pantiListContainer.innerHTML = '';

                const pantis = data.pantis ?? [];
                if (pantis.length > 0) {
                    pantis.forEach(panti => {
                        const statusClass = getStatusClass(panti.status_verifikasi);
                        const fotoUrl = panti.foto_profil_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(panti.nama_panti);

                        pantiListContainer.innerHTML += `
                        <div class="cursor-pointer bg-white border rounded-2xl shadow-sm p-6 flex flex-col items-center hover:bg-gray-50 transition" data-panti-id="${panti.id}">
                            <img src="${fotoUrl}" alt="Foto Panti" class="w-60 object-cover border mb-3">
                            <p class="font-bold text-lg text-gray-800 text-center">${panti.nama_panti}</p>
                            <p class="text-xs text-gray-500 mb-2">${panti.kontak || '-'}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <img src="${panti.user.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(panti.user.name)}" alt="Pengurus" class="w-8 h-8 rounded-full object-cover border">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">${panti.user.name}</p>
                                    <p class="text-xs text-gray-500">${panti.user.email}</p>
                                </div>
                            </div>
                            <span class="mt-3 px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                ${getStatusLabel(panti.status_verifikasi)}
                            </span>
                        </div>
                        `;
                    });
                    addPantiCardListeners();
                } else {
                    pantiListContainer.innerHTML = `
                        <div class="text-center py-12 border-2 border-dashed rounded-xl col-span-full">
                            <i class="fa-solid fa-house-chimney-user text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada panti asuhan.</p>
                        </div>
                    `;
                }
            } catch (error) {
                pantiListContainer.innerHTML = `
                    <div class="text-center py-12 text-red-500 col-span-full">
                        <p>Gagal memuat data panti asuhan. Silakan coba lagi.</p>
                    </div>
                `;
            }
        }

        // Helper: status class
        function getStatusClass(status) {
            switch (status) {
                case 'verified': return 'bg-green-100 text-green-800';
                case 'unverified': return 'bg-gray-100 text-gray-700';
                default: return 'bg-gray-100 text-gray-700';
            }
        }
        // Helper: status label
        function getStatusLabel(status) {
            switch (status) {
                case 'verified': return 'Verified';
                case 'unverified': return 'Unverified';
                default: return status;
            }
        }

        // Card click: open modal
        function addPantiCardListeners() {
            document.querySelectorAll('#panti-list > div[data-panti-id]').forEach(card => {
                card.addEventListener('click', function() {
                    const pantiId = this.dataset.pantiId;
                    openPantiDetail(pantiId);
                });
            });
        }

        // Fetch and show detail modal
        async function openPantiDetail(pantiId) {
            try {
                const response = await fetch(`/admin/panti/${pantiId}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                const panti = data.panti;

                document.getElementById('modalFotoProfil').src = panti.foto_profil_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(panti.nama_panti);
                document.getElementById('modalNamaPanti').textContent = panti.nama_panti;
                document.getElementById('modalKontak').textContent = panti.kontak || '-';
                document.getElementById('modalAlamat').textContent = panti.alamat || '-';
                document.getElementById('modalDeskripsi').textContent = panti.deskripsi || '-';
                document.getElementById('modalStatusVerifikasi').textContent = getStatusLabel(panti.status_verifikasi);
                document.getElementById('modalStatusVerifikasi').className = 'px-2 py-1 text-xs font-semibold rounded-full ' + getStatusClass(panti.status_verifikasi);
                document.getElementById('modalUserAvatar').src = panti.user.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(panti.user.name);
                document.getElementById('modalUserName').textContent = panti.user.name;
                document.getElementById('modalUserEmail').textContent = panti.user.email || '-';
                document.getElementById('modalNomorRekening').textContent = panti.nomor_rekening || '-';
                document.getElementById('modalBank').textContent = panti.bank || '-';
                document.getElementById('modalDokumenVerifikasi').href = panti.dokumen_verifikasi || '#';
                document.getElementById('modalDokumenVerifikasi').textContent = panti.dokumen_verifikasi ? 'Lihat Dokumen' : '-';
                document.getElementById('modalCreatedAt').textContent = panti.created_at ? new Date(panti.created_at).toLocaleString('id-ID', {
                                        day: 'numeric',
                                        month: 'long',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: false
                                    }) : '-';
                document.getElementById('modalUpdatedAt').textContent = panti.updated_at ? new Date(panti.updated_at).toLocaleString('id-ID', {
                                        day: 'numeric',
                                        month: 'long',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: false
                                    }) : '-';

                // Set form values
                modalPantiId.value = panti.id;
                modalUpdateStatus.value = panti.status_verifikasi;

                pantiDetailModal.classList.remove('hidden');
            } catch (error) {
                alert('Gagal memuat detail panti.');
            }
        }

        // Update status form submit
updateStatusForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = modalPantiId.value;
    const status = modalUpdateStatus.value;
    let url = `/admin/panti/${id}/${status}`;
    updateStatusBtn.disabled = true;
    updateStatusBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Updating...';

    try {
        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await response.json();
        if (!response.ok) {
            alert(data.message || 'Gagal update status');
        } else {
            alert(data.message || 'Status berhasil diupdate!');
            pantiDetailModal.classList.add('hidden');
            fetchPantis();
        }
    } catch (err) {
        alert('Gagal update status');
    } finally {
        updateStatusBtn.disabled = false;
        updateStatusBtn.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Update';
    }
});

        // Delete panti
        deletePantiBtn.addEventListener('click', async function() {
            if (!confirm('Yakin ingin menghapus panti ini?')) return;
            const id = modalPantiId.value;
            deletePantiBtn.disabled = true;
            deletePantiBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            try {
                const response = await fetch(`/admin/panti/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (!response.ok) throw new Error('Gagal menghapus panti');
                alert('Panti berhasil dihapus!');
                pantiDetailModal.classList.add('hidden');
                fetchPantis();
            } catch (err) {
                alert('Gagal menghapus panti');
            } finally {
                deletePantiBtn.disabled = false;
                deletePantiBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
            }
        });

        // Close modal
        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', function() {
                const modalId = this.dataset.modalClose;
                document.getElementById(modalId).classList.add('hidden');
            });
        });

        fetchPantis();
    });
    </script>
</x-admin-layout>