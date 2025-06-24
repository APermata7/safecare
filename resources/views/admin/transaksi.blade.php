<x-admin-layout>
    <div class="pt-24 sm:pt-4 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex justify-center">
                <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                    Manajemen Transaksi
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                <div class="space-y-4" id="transactions-list">
                    <div class="text-center py-12 border-2 border-dashed rounded-xl">
                        <i class="fa-solid fa-exchange-alt text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Memuat transaksi...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="transactionDetailModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi</h3>
                <button data-modal-close="transactionDetailModal" class="text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>
            </div>
            <div class="p-4 md:p-6 flex-grow overflow-auto space-y-4">
                <div>
                    <p class="text-sm text-gray-500">ID Pesanan:</p>
                    <p id="modalOrderId" class="font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Donatur:</p>
                    <p id="modalDonorName" class="font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Panti Asuhan:</p>
                    <p id="modalPantiName" class="font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah Donasi:</p>
                    <p id="modalAmount" class="font-bold text-lg text-primary-green"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Saat Ini:</p>
                    <span id="modalStatus" class="px-2 py-1 text-xs font-semibold rounded-full"></span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Metode Pembayaran:</p>
                    <p id="modalPaymentMethod" class="text-gray-700"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Transaksi:</p>
                    <p id="modalCreatedAt" class="text-gray-700"></p>
                </div>

                <form id="updateStatusForm" class="space-y-4 pt-4 border-t mt-4">
    @csrf
    @method('PUT')
    <input type="hidden" id="updateTransactionId">
    <div>
        <label for="newStatus" class="block text-sm font-medium text-gray-700">Update Status:</label>
        <select id="newStatus" name="status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50" required>
            <option value="waiting confirmation">Waiting Confirmation</option>
            <option value="success">Success</option>
            <option value="canceled">Canceled</option>
        </select>
    </div>
    <div>
        <label for="newPaymentMethod" class="block text-sm font-medium text-gray-700">Update Metode Pembayaran:</label>
        <select id="newPaymentMethod" name="payment_method" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50">
            <option value="bank transfer">Bank Transfer</option>
            <option value="QRIS">QRIS</option>
        </select>
    </div>
    <div class="flex justify-end">
        <x-primary-button type="submit" id="updateStatusButton">Update</x-primary-button>
    </div>
</form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const transactionsListContainer = document.getElementById('transactions-list');
            const transactionDetailModal = document.getElementById('transactionDetailModal');
            const updateStatusForm = document.getElementById('updateStatusForm');
            const updateTransactionIdInput = document.getElementById('updateTransactionId');
            const newStatusSelect = document.getElementById('newStatus');
            const newPaymentMethodSelect = document.getElementById('newPaymentMethod');
            const updateStatusButton = document.getElementById('updateStatusButton');

            // Function to fetch and display transactions
            async function fetchTransactions() {
                try {
                    const response = await fetch('/admin/transaksi/api'); // New route for admin transactions
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    transactionsListContainer.innerHTML = ''; // Clear existing content

                    if (data.transaksis && data.transaksis.length > 0) {
                        data.transaksis.forEach(transaksi => {
                            const statusClass = getStatusClass(transaksi.status);
                            const transactionCard = `
                                <div class="p-4 border rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-gray-50 transition cursor-pointer" data-transaction-id="${transaksi.id}">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="font-bold text-gray-800">Order ID: ${transaksi.order_id}</p>
                                        <p class="text-sm text-gray-500">Donatur: ${transaksi.user ? transaksi.user.name : 'Donatur Dihapus'}</p>
                                        <p class="text-sm text-gray-500">Panti: ${transaksi.panti ? transaksi.panti.nama_panti : 'Panti Dihapus'}</p>
                                        <p class="text-sm text-gray-500">${new Date(transaksi.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric' })}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-primary-green">Rp${numberFormat(transaksi.amount)}</p>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                            ${transaksi.status.toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                            `;
                            transactionsListContainer.innerHTML += transactionCard;
                        });
                        addTransactionCardListeners();
                    } else {
                        transactionsListContainer.innerHTML = `
                            <div class="text-center py-12 border-2 border-dashed rounded-xl">
                                <i class="fa-solid fa-exchange-alt text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Belum ada transaksi.</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error fetching transactions:', error);
                    transactionsListContainer.innerHTML = `
                        <div class="text-center py-12 text-red-500">
                            <p>Gagal memuat transaksi. Silakan coba lagi.</p>
                        </div>
                    `;
                }
            }

            // Helper to get status class
            function getStatusClass(status) {
                switch (status) {
                    case 'success': return 'bg-green-100 text-green-800';
                    case 'waiting confirmation': return 'bg-yellow-100 text-yellow-800';
                    case 'canceled': return 'bg-red-100 text-red-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            }

            // Helper to format number
            function numberFormat(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            }

            // Function to handle opening transaction detail modal
            async function openTransactionDetail(transactionId) {
                try {
                    const response = await fetch(`/admin/transaksi/${transactionId}`); // Use a new route for individual transaction
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();
                    const transaksi = data.transaksi;

                    document.getElementById('modalOrderId').textContent = transaksi.order_id;
                    document.getElementById('modalDonorName').textContent = transaksi.user ? transaksi.user.name : 'Donatur Dihapus';
                    document.getElementById('modalPantiName').textContent = transaksi.panti ? transaksi.panti.nama_panti : 'Panti Dihapus';
                    document.getElementById('modalAmount').textContent = `Rp${numberFormat(transaksi.amount)}`;
                    document.getElementById('modalPaymentMethod').textContent = transaksi.payment_method || 'N/A';
                    document.getElementById('modalCreatedAt').textContent = new Date(transaksi.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric' });

                    const statusSpan = document.getElementById('modalStatus');
                    statusSpan.textContent = transaksi.status.toUpperCase();
                    statusSpan.className = `px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(transaksi.status)}`;

                    updateTransactionIdInput.value = transaksi.id;
                    newStatusSelect.value = transaksi.status; // Set current status in dropdown
                    newPaymentMethodSelect.value = transaksi.payment_method || 'bank transfer';
                    transactionDetailModal.classList.remove('hidden');
                } catch (error) {
                    console.error('Error fetching transaction detail:', error);
                    alert('Gagal memuat detail transaksi.');
                }
            }

            // Add event listeners to transaction cards
            function addTransactionCardListeners() {
                const transactionCards = document.querySelectorAll('#transactions-list > div');
                transactionCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const transactionId = this.dataset.transactionId;
                        openTransactionDetail(transactionId);
                    });
                });
            }

            // Close modal functionality
            const modalCloses = document.querySelectorAll('[data-modal-close]');
            modalCloses.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.dataset.modalClose;
                    document.getElementById(modalId).classList.add('hidden');
                });
            });

            // Handle update status form submission
            updateStatusForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const transactionId = updateTransactionIdInput.value;
                const newStatus = newStatusSelect.value;
                const newPaymentMethod = newPaymentMethodSelect.value;

                updateStatusButton.disabled = true;
                updateStatusButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengupdate...';

                try {
                    const response = await fetch(`/admin/transaksi/${transactionId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: newStatus, payment_method: newPaymentMethod })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Gagal memperbarui status transaksi.');
                    }

                    const data = await response.json();
                    alert(data.message);
                    transactionDetailModal.classList.add('hidden');
                    fetchTransactions(); // Refresh transactions list
                } catch (error) {
                    console.error('Error updating transaction status:', error);
                    alert('Error: ' + error.message);
                } finally {
                    updateStatusButton.disabled = false;
                    updateStatusButton.innerHTML = 'Update Status';
                }
            });

            fetchTransactions(); // Initial fetch when page loads
        });
    </script>
</x-admin-layout>