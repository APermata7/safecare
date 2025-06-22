<x-app-layout>
    <div class="pt-24 sm:pt-4 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex justify-center">
                <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                    Manajemen Pesan (Admin)
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                <div class="space-y-4" id="messages-list">
                    <div class="text-center py-12 border-2 border-dashed rounded-xl">
                        <i class="fa-solid fa-envelope-open-text text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Memuat pesan...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="messageDetailModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="modalMessageTitle" class="text-lg font-semibold text-gray-800">Detail Pesan</h3>
                <button data-modal-close="messageDetailModal" class="text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>
            </div>
            <div class="p-4 md:p-6 flex-grow overflow-auto space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Dari:</p>
                    <p id="modalUserName" class="font-semibold text-gray-800"></p>
                    <p id="modalUserEmail" class="text-sm text-gray-600"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Subjek:</p>
                    <p id="modalMessageSubject" class="font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pesan:</p>
                    <p id="modalMessageContent" class="text-gray-700 whitespace-pre-line"></p>
                </div>
                <div id="modalMessageFileContainer" class="hidden">
                    <p class="text-sm text-gray-500">Lampiran:</p>
                    <a id="modalMessageFile" href="#" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a>
                </div>
                <div id="modalMessageReplyContainer" class="p-4 bg-gray-100 rounded-xl border border-gray-200">
                    <p class="text-sm text-gray-500">Balasan Admin:</p>
                    <p id="modalMessageReply" class="text-gray-700 whitespace-pre-line font-medium"></p>
                </div>

                <form id="replyForm" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="replyMessageId">
                    <div>
                        <label for="replyContent" class="block text-sm font-medium text-gray-700">Tulis Balasan:</label>
                        <textarea id="replyContent" name="reply" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50"></textarea>
                        <p id="replyError" class="mt-2 text-sm text-red-600 hidden">Balasan tidak boleh kosong.</p>
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button type="submit" id="replyButton">Kirim Balasan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesListContainer = document.getElementById('messages-list');
            const messageDetailModal = document.getElementById('messageDetailModal');
            const replyForm = document.getElementById('replyForm');
            const replyContentInput = document.getElementById('replyContent');
            const replyMessageIdInput = document.getElementById('replyMessageId');
            const replyButton = document.getElementById('replyButton');
            const replyError = document.getElementById('replyError');

            // Function to fetch and display messages
            async function fetchMessages() {
                try {
                    const response = await fetch('/admin'); // Adjust if your route is different
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    messagesListContainer.innerHTML = ''; // Clear existing content

                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            const messageCard = `
                                <div class="p-4 border rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-gray-50 transition cursor-pointer" data-message-id="${message.id}">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="font-bold text-gray-800">Subjek: ${message.judul}</p>
                                        <p class="text-sm text-gray-500">Dari: ${message.user ? message.user.name : 'User Dihapus'} (${message.role})</p>
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
                            messagesListContainer.innerHTML += messageCard;
                        });
                        addMessageCardListeners();
                    } else {
                        messagesListContainer.innerHTML = `
                            <div class="text-center py-12 border-2 border-dashed rounded-xl">
                                <i class="fa-solid fa-envelope-open-text text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Belum ada pesan masuk.</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error fetching messages:', error);
                    messagesListContainer.innerHTML = `
                        <div class="text-center py-12 text-red-500">
                            <p>Gagal memuat pesan. Silakan coba lagi.</p>
                        </div>
                    `;
                }
            }

            // Function to handle opening message detail modal
            async function openMessageDetail(messageId) {
                try {
                    const response = await fetch(`/admin/${messageId}`); // Adjust if your route is different
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();
                    const message = data.message;

                    document.getElementById('modalUserName').textContent = `${message.user ? message.user.name : 'User Dihapus'} (${message.role})`;
                    document.getElementById('modalUserEmail').textContent = message.user ? message.user.email : 'N/A';
                    document.getElementById('modalMessageSubject').textContent = message.judul;
                    document.getElementById('modalMessageContent').textContent = message.message;
                    replyMessageIdInput.value = message.id;

                    const fileContainer = document.getElementById('modalMessageFileContainer');
                    const fileLink = document.getElementById('modalMessageFile');
                    if (message.file_path) {
                        fileLink.href = `/storage/${message.file_path}`; // Assuming 'public' disk for message_files
                        fileContainer.classList.remove('hidden');
                    } else {
                        fileContainer.classList.add('hidden');
                    }

                    const replyDisplay = document.getElementById('modalMessageReply');
                    const replyDisplayContainer = document.getElementById('modalMessageReplyContainer');
                    if (message.reply) {
                        replyDisplay.textContent = message.reply;
                        replyDisplayContainer.classList.remove('hidden');
                        replyContentInput.value = message.reply; // Populate textarea if already replied
                        replyButton.textContent = 'Update Balasan';
                    } else {
                        replyDisplay.textContent = '';
                        replyDisplayContainer.classList.add('hidden');
                        replyContentInput.value = '';
                        replyButton.textContent = 'Kirim Balasan';
                    }

                    messageDetailModal.classList.remove('hidden');
                } catch (error) {
                    console.error('Error fetching message detail:', error);
                    alert('Gagal memuat detail pesan.');
                }
            }

            // Add event listeners to message cards
            function addMessageCardListeners() {
                const messageCards = document.querySelectorAll('#messages-list > div');
                messageCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const messageId = this.dataset.messageId;
                        openMessageDetail(messageId);
                    });
                });
            }

            // Close modal functionality
            const modalCloses = document.querySelectorAll('[data-modal-close]');
            modalCloses.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.dataset.modalClose;
                    document.getElementById(modalId).classList.add('hidden');
                    replyError.classList.add('hidden'); // Hide error on close
                });
            });

            // Handle reply form submission
            replyForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                replyError.classList.add('hidden'); // Hide previous errors

                const messageId = replyMessageIdInput.value;
                const replyText = replyContentInput.value.trim();

                if (!replyText) {
                    replyError.classList.remove('hidden');
                    return;
                }

                replyButton.disabled = true;
                replyButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

                try {
                    const response = await fetch(`/admin/pesan/${messageId}/reply`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ reply: replyText })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Gagal mengirim balasan.');
                    }

                    const data = await response.json();
                    alert(data.success);
                    messageDetailModal.classList.add('hidden');
                    fetchMessages(); // Refresh messages list
                } catch (error) {
                    console.error('Error sending reply:', error);
                    alert('Error: ' + error.message);
                } finally {
                    replyButton.disabled = false;
                    replyButton.innerHTML = 'Kirim Balasan';
                }
            });

            fetchMessages(); // Initial fetch when page loads
        });
    </script>
</x-app-layout>