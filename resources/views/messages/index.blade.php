<x-app-layout>
    <div class="pt-24 sm:pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex justify-center">
                <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                    Pusat Bantuan & Riwayat Pesan
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Kirim Pesan Baru</h3>
                <form id="sendMessageForm" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Subjek</label>
                        <select id="judul" name="judul" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50" required>
                            <option value="Feedback">Feedback</option>
                            @if(auth()->check() && auth()->user()->role === 'donatur')
                                <option value="Request panti user">Request Mendaftar Panti Asuhan</option>
                            @endif
                        </select>
                        <p id="judulError" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>
                    <div>
    <label for="messageContent" class="block text-sm font-medium text-gray-700">Isi Pesan</label>
    <textarea id="messageContent" name="message" rows="5"
        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"
        required placeholder="Isikan pesan..."></textarea>
    <p id="messageContentError" class="mt-2 text-sm text-red-600 hidden"></p>
</div>
<div>
    <label for="fileAttachment" class="block text-sm font-medium text-gray-700">Lampiran (Opsional)</label>
    <input type="file" id="fileAttachment" name="file"
        class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200"
        accept="image/*,application/pdf">
    <p class="text-xs text-gray-500 mt-1">Lampirkan gambar atau dokumen pendukung jika diperlukan.</p>
    <p id="fileAttachmentError" class="mt-2 text-sm text-red-600 hidden"></p>
</div>

<!-- Form tambahan untuk Request panti user -->
<div id="pantiFields" class="space-y-4 hidden">
<p class="text-xs text-gray-500 mt-1">Silahkan isi lampiran di atas dengan screenshot isi form panti asuhan Anda di bawah ini.</p>
    <div>
        <label for="nama_panti" class="block text-sm font-medium text-gray-700">Nama Panti</label>
        <input type="text" id="nama_panti" name="nama_panti"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"
            placeholder="Isikan nama panti asuhan..." />
        <p id="namaPantiError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
    <div>
        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
        <input type="text" id="alamat" name="alamat"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"
            placeholder="Isikan alamat lengkap panti..." />
        <p id="alamatError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="3"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"
            placeholder="Isikan deskripsi tentang panti..."></textarea>
        <p id="deskripsiError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
    <div>
        <label for="foto_profil" class="block text-sm font-medium text-gray-700">Foto Profil Panti</label>
        <input type="file" id="foto_profil" name="foto_profil"
            class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200"
            accept="image/*">
        <p class="text-xs text-gray-500 mt-1">Unggah foto profil panti (format gambar).</p>
        <p id="fotoProfilError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
    <div>
        <label for="dokumen_verifikasi" class="block text-sm font-medium text-gray-700">Dokumen Verifikasi (PDF)</label>
        <input type="file" id="dokumen_verifikasi" name="dokumen_verifikasi"
            class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200"
            accept="application/pdf">
        <p class="text-xs text-gray-500 mt-1">Unggah dokumen verifikasi panti (format PDF).</p>
        <p id="dokumenVerifikasiError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
    <div>
        <label for="nomor_rekening" class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
        <input type="text" id="nomor_rekening" name="nomor_rekening"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"
            placeholder="Isikan nomor rekening panti..." />
        <p id="nomorRekeningError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
    <div>
        <label for="bank" class="block text-sm font-medium text-gray-700">Bank</label>
        <input type="text" id="bank" name="bank"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"
            placeholder="Isikan nama bank nomor rekening panti..." />
            <p class="text-xs text-gray-500 mt-1">Contoh: BCA, BRI, Mandiri, dll.</p>
        <p id="bankError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
    <div>
        <label for="kontak" class="block text-sm font-medium text-gray-700">Kontak</label>
        <input type="text" id="kontak" name="kontak"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"
            placeholder="nomor telepon / email panti" />
        <p class="text-xs text-gray-500 mt-1">Contoh: 08123456789 / email@panti.com</p>
        <p id="kontakError" class="mt-2 text-sm text-red-600 hidden"></p>
    </div>
</div>
                    <!-- End form tambahan -->

                    <div class="flex justify-end">
                        <x-primary-button type="submit" id="sendMessageButton">Kirim Pesan</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Pesan Saya</h3>
                <div class="space-y-4" id="user-messages-list">
                    <div class="text-center py-12 border-2 border-dashed rounded-xl">
                        <i class="fa-solid fa-hourglass-start text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Memuat riwayat pesan...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="userMessageDetailModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="userModalMessageTitle" class="text-lg font-semibold text-gray-800">Detail Pesan Anda</h3>
                <button data-modal-close="userMessageDetailModal" class="text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>
            </div>
            <div class="p-4 md:p-6 flex-grow overflow-auto space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Subjek:</p>
                    <p id="userModalMessageSubject" class="font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pesan Anda:</p>
                    <p id="userModalMessageContent" class="text-gray-700 whitespace-pre-line"></p>
                </div>
                <div id="userModalMessageFileContainer" class="hidden">
                    <p class="text-sm text-gray-500">Lampiran:</p>
                    <a id="userModalMessageFile" href="#" target="_blank" class="text-primary-green hover:underline">Lihat Lampiran</a>
                </div>
                <div id="userModalMessageReplyContainer" class="p-4 bg-gray-100 rounded-xl border border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-gray-500">Balasan Admin:</p>
                        <p id="userModalMessageReplyDate" class="text-xs text-gray-400"></p>
                    </div>
                    <p id="userModalMessageReply" class="text-gray-700 whitespace-pre-line font-medium"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendMessageForm = document.getElementById('sendMessageForm');
            const sendMessageButton = document.getElementById('sendMessageButton');
            const userMessagesListContainer = document.getElementById('user-messages-list');
            const pantiFields = document.getElementById('pantiFields');
            const judulSelect = document.getElementById('judul');

            // Error message elements
            const judulError = document.getElementById('judulError');
            const messageContentError = document.getElementById('messageContentError');
            const fileAttachmentError = document.getElementById('fileAttachmentError');
            const namaPantiError = document.getElementById('namaPantiError');
            const alamatError = document.getElementById('alamatError');
            const deskripsiError = document.getElementById('deskripsiError');
            const fotoProfilError = document.getElementById('fotoProfilError');
            const dokumenVerifikasiError = document.getElementById('dokumenVerifikasiError');
            const nomorRekeningError = document.getElementById('nomorRekeningError');
            const bankError = document.getElementById('bankError');
            const kontakError = document.getElementById('kontakError');

            // Tampilkan/hidden field panti sesuai pilihan dropdown
            judulSelect.addEventListener('change', function() {
                if (this.value === 'Request panti user') {
                    pantiFields.classList.remove('hidden');
                } else {
                    pantiFields.classList.add('hidden');
                }
            });

            // Function to clear form errors
            function clearFormErrors() {
                judulError.classList.add('hidden');
                messageContentError.classList.add('hidden');
                fileAttachmentError.classList.add('hidden');
                namaPantiError.classList.add('hidden');
                alamatError.classList.add('hidden');
                deskripsiError.classList.add('hidden');
                fotoProfilError.classList.add('hidden');
                dokumenVerifikasiError.classList.add('hidden');
                nomorRekeningError.classList.add('hidden');
                bankError.classList.add('hidden');
                kontakError.classList.add('hidden');
            }

            // Handle send message form submission
            sendMessageForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                clearFormErrors();

                const formData = new FormData(this);
                sendMessageButton.disabled = true;
                sendMessageButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

                try {
                    const response = await axios.post('/pesan', formData, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    });

                    alert(response.data.success);
                    sendMessageForm.reset();
                    pantiFields.classList.add('hidden');
                    fetchUserMessages();
                } catch (error) {
                    if (error.response && error.response.data && error.response.data.errors) {
                        const errors = error.response.data.errors;
                        if (errors.judul) {
                            judulError.textContent = errors.judul[0];
                            judulError.classList.remove('hidden');
                        }
                        if (errors.message) {
                            messageContentError.textContent = errors.message[0];
                            messageContentError.classList.remove('hidden');
                        }
                        if (errors.file) {
                            fileAttachmentError.textContent = errors.file[0];
                            fileAttachmentError.classList.remove('hidden');
                        }
                        if (errors.nama_panti) {
                            namaPantiError.textContent = errors.nama_panti[0];
                            namaPantiError.classList.remove('hidden');
                        }
                        if (errors.alamat) {
                            alamatError.textContent = errors.alamat[0];
                            alamatError.classList.remove('hidden');
                        }
                        if (errors.deskripsi) {
                            deskripsiError.textContent = errors.deskripsi[0];
                            deskripsiError.classList.remove('hidden');
                        }
                        if (errors.foto_profil) {
                            fotoProfilError.textContent = errors.foto_profil[0];
                            fotoProfilError.classList.remove('hidden');
                        }
                        if (errors.dokumen_verifikasi) {
                            dokumenVerifikasiError.textContent = errors.dokumen_verifikasi[0];
                            dokumenVerifikasiError.classList.remove('hidden');
                        }
                        if (errors.nomor_rekening) {
                            nomorRekeningError.textContent = errors.nomor_rekening[0];
                            nomorRekeningError.classList.remove('hidden');
                        }
                        if (errors.bank) {
                            bankError.textContent = errors.bank[0];
                            bankError.classList.remove('hidden');
                        }
                        if (errors.kontak) {
                            kontakError.textContent = errors.kontak[0];
                            kontakError.classList.remove('hidden');
                        }
                    }
                    alert('Error: ' + (error.response?.data?.message || error.message));
                } finally {
                    sendMessageButton.disabled = false;
                    sendMessageButton.innerHTML = 'Kirim Pesan';
                }
            });

            // Function to fetch and display user's messages
            async function fetchUserMessages() {
                try {
                    const response = await fetch('/pesan'); // Adjust if your route is different
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    userMessagesListContainer.innerHTML = ''; // Clear existing content

                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            const messageCard = `
                                <div class="p-4 border rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-gray-50 transition cursor-pointer" data-message-id="${message.id}">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="font-bold text-gray-800">Subjek: ${message.judul}</p>
                                        <p class="text-sm text-gray-500">${new Date(message.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric' })}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        ${message.reply ?
                                            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">SUDAH DIBALAS</span>' :
                                            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">BELUM DIBALAS</span>'
                                        }
                                    </div>
                                </div>
                            `;
                            userMessagesListContainer.innerHTML += messageCard;
                        });
                        addUserMessageCardListeners();
                    } else {
                        userMessagesListContainer.innerHTML = `
                            <div class="text-center py-12 border-2 border-dashed rounded-xl">
                                <i class="fa-solid fa-receipt text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Anda belum pernah mengirim pesan.</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error fetching user messages:', error);
                    userMessagesListContainer.innerHTML = `
                        <div class="text-center py-12 text-red-500">
                            <p>Gagal memuat riwayat pesan. Silakan coba lagi.</p>
                        </div>
                    `;
                }
            }

            // Function to handle opening user message detail modal
            async function openUserMessageDetail(messageId) {
                try {
                    const response = await fetch(`/pesan/${messageId}`); // Adjust if your route is different
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();
                    const message = data.message;

                    document.getElementById('userModalMessageTitle').textContent = `Detail Pesan: ${message.judul}`;
                    document.getElementById('userModalMessageSubject').textContent = message.judul;
                    document.getElementById('userModalMessageContent').textContent = message.message;

                    const fileContainer = document.getElementById('userModalMessageFileContainer');
                    const fileLink = document.getElementById('userModalMessageFile');
                    if (message.file_path) {
                        fileLink.href = `/storage/${message.file_path}`; // Assuming 'public' disk for message_files
                        fileContainer.classList.remove('hidden');
                    } else {
                        fileContainer.classList.add('hidden');
                    }

                    const replyDisplay = document.getElementById('userModalMessageReply');
                    const replyDisplayContainer = document.getElementById('userModalMessageReplyContainer');
                    const replyDate = document.getElementById('userModalMessageReplyDate');
                    if (message.reply) {
                        replyDisplay.textContent = message.reply;
                        // Tampilkan tanggal updated_at jika ada
                        if (message.updated_at) {
                            const date = new Date(message.updated_at);
                            replyDate.textContent = 'Dibalas pada: ' + date.toLocaleString('id-ID', {
                                day: 'numeric', month: 'long', year: 'numeric',
                                hour: '2-digit', minute: '2-digit'
                            });
                        } else {
                            replyDate.textContent = '';
                        }
                        replyDisplayContainer.classList.remove('hidden');
                    } else {
                        replyDisplay.textContent = '';
                        replyDate.textContent = '';
                        replyDisplayContainer.classList.add('hidden');
                    }

                    userMessageDetailModal.classList.remove('hidden');
                } catch (error) {
                    console.error('Error fetching user message detail:', error);
                    alert('Gagal memuat detail pesan Anda.');
                }
            }

            // Add event listeners to user message cards
            function addUserMessageCardListeners() {
                const messageCards = document.querySelectorAll('#user-messages-list > div');
                messageCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const messageId = this.dataset.messageId;
                        openUserMessageDetail(messageId);
                    });
                });
            }

            // Close modal functionality for user message detail
            const userModalCloses = document.querySelectorAll('[data-modal-close="userMessageDetailModal"]');
            userModalCloses.forEach(button => {
                button.addEventListener('click', function() {
                    userMessageDetailModal.classList.add('hidden');
                });
            });

            fetchUserMessages(); // Initial fetch when page loads
        });
    </script>
</x-app-layout>