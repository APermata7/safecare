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
                <form id="sendMessageForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Subjek</label>
                        <select id="judul" name="judul" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50" required>
                            <option value="Feedback">Feedback</option>
                            @if(auth()->check() && auth()->user()->role === 'donatur')
                                <option value="Request panti user">Request Panti Asuhan Baru</option>
                            @endif
                        </select>
                        <p id="judulError" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>
                    <div>
                        <label for="messageContent" class="block text-sm font-medium text-gray-700">Isi Pesan</label>
                        <textarea id="messageContent" name="message" rows="5" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50" required></textarea>
                        <p id="messageContentError" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>
                    <div>
                        <label for="fileAttachment" class="block text-sm font-medium text-gray-700">Lampiran (Opsional)</label>
                        <input type="file" id="fileAttachment" name="file" class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200" accept="image/*,application/pdf">
                        <p id="fileAttachmentError" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>
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
                    <a id="userModalMessageFile" href="#" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a>
                </div>
                <div id="userModalMessageReplyContainer" class="p-4 bg-gray-100 rounded-xl border border-gray-200">
                    <p class="text-sm text-gray-500">Balasan Admin:</p>
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
            const userMessageDetailModal = document.getElementById('userMessageDetailModal');

            // Error message elements
            const judulError = document.getElementById('judulError');
            const messageContentError = document.getElementById('messageContentError');
            const fileAttachmentError = document.getElementById('fileAttachmentError');

            // Function to clear form errors
            function clearFormErrors() {
                judulError.classList.add('hidden');
                messageContentError.classList.add('hidden');
                fileAttachmentError.classList.add('hidden');
            }

            // Handle send message form submission
            sendMessageForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                clearFormErrors(); // Clear errors before new submission

                const formData = new FormData(this);
                sendMessageButton.disabled = true;
                sendMessageButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        if (errorData.errors) {
                            if (errorData.errors.judul) {
                                judulError.textContent = errorData.errors.judul[0];
                                judulError.classList.remove('hidden');
                            }
                            if (errorData.errors.message) {
                                messageContentError.textContent = errorData.errors.message[0];
                                messageContentError.classList.remove('hidden');
                            }
                            if (errorData.errors.file) {
                                fileAttachmentError.textContent = errorData.errors.file[0];
                                fileAttachmentError.classList.remove('hidden');
                            }
                        }
                        throw new Error(errorData.message || 'Gagal mengirim pesan.');
                    }

                    const data = await response.json();
                    alert(data.success);
                    sendMessageForm.reset(); // Clear form
                    fetchUserMessages(); // Refresh messages list
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('Error: ' + error.message);
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
                    if (message.reply) {
                        replyDisplay.textContent = message.reply;
                        replyDisplayContainer.classList.remove('hidden');
                    } else {
                        replyDisplay.textContent = '';
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