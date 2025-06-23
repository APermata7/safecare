<x-admin-layout>
    <div class="pt-24 sm:pt-4 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex justify-center">
                <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                    Manajemen User
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                <div id="users-list" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center py-12 border-2 border-dashed rounded-xl col-span-full">
                        <i class="fa-solid fa-users text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Memuat data user...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const usersListContainer = document.getElementById('users-list');

        async function fetchUsers() {
            try {
                const response = await fetch('/admin/users/api');
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                usersListContainer.innerHTML = '';

                const users = data.data ?? [];
                if (users.length > 0) {
                    users.forEach(user => {
                        const statusClass = user.status === 'active'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800';

                        const avatarUrl = user.avatar
                            ? user.avatar
                            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name);

                        usersListContainer.innerHTML += `
                        <div class="relative flex flex-col sm:flex-row bg-white border rounded-2xl shadow-sm p-6 gap-4">
                            <div class="flex-shrink-0 flex items-center">
                                <img src="${avatarUrl}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border">
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-lg text-gray-800">${user.name}</p>
                                            <p class="text-sm text-gray-500">${user.email}</p>
                                            <span class="inline-block mt-1 px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">${user.role}</span>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                            ${user.status === 'active' ? 'Active' : 'Banned'}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    ${user.status === 'active' ? `
                                        <button class="ban-btn px-3 py-1 rounded bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition flex items-center gap-1" data-id="${user.id}">
                                            <i class="fa-solid fa-ban"></i> Ban
                                        </button>
                                    ` : ''}
                                    ${user.status === 'banned' ? `
                                        <button class="unban-btn px-3 py-1 rounded bg-green-100 text-green-700 hover:bg-green-200 transition flex items-center gap-1" data-id="${user.id}">
                                            <i class="fa-solid fa-unlock"></i> Unban
                                        </button>
                                    ` : ''}
                                    <button class="delete-btn px-3 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 transition flex items-center gap-1" data-id="${user.id}">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                    addUserActionListeners();
                } else {
                    usersListContainer.innerHTML = `
                        <div class="text-center py-12 border-2 border-dashed rounded-xl col-span-full">
                            <i class="fa-solid fa-users text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada user.</p>
                        </div>
                    `;
                }
            } catch (error) {
                usersListContainer.innerHTML = `
                    <div class="text-center py-12 text-red-500 col-span-full">
                        <p>Gagal memuat data user. Silakan coba lagi.</p>
                    </div>
                `;
            }
        }

        function addUserActionListeners() {
            // Ban
            document.querySelectorAll('.ban-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (!confirm('Ban user ini?')) return;
                    await userAction(`/admin/users/${this.dataset.id}/ban`, 'PUT');
                });
            });
            // Unban
            document.querySelectorAll('.unban-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (!confirm('Unban user ini?')) return;
                    await userAction(`/admin/users/${this.dataset.id}/unban`, 'PUT');
                });
            });
            // Delete
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    if (!confirm('Hapus user ini?')) return;
                    await userAction(`/admin/users/${this.dataset.id}`, 'DELETE');
                });
            });
        }

        async function userAction(url, method) {
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                alert(data.message || 'Aksi berhasil');
                fetchUsers();
            } catch (error) {
                alert('Gagal melakukan aksi. Silakan coba lagi.');
            }
        }

        fetchUsers();
    });
    </script>
</x-admin-layout>