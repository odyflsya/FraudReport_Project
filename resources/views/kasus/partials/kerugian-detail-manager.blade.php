{{-- Modal kelola rincian kerugian (Create & Edit) --}}
<div class="mb-6">
    <h4 class="mb-2 font-medium text-slate-800">Rincian Kerugian</h4>
    <input type="hidden" id="kerugian_details_input" name="kerugian_details" value="{{ $kerugianDetailsJson ?? '' }}">
    <div id="kerugian_detail_counts" class="text-xs text-slate-600"></div>
</div>

<div id="kerugianDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6">
    <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h3 id="kerugianDetailModalTitle" class="text-lg font-semibold text-slate-900">Kelola Rincian Kerugian</h3>
            <button type="button" onclick="KerugianDetailManager.closeModal()" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50">Tutup</button>
        </div>
        <div class="px-5 py-4 max-h-[70vh] overflow-y-auto">
            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="border-b px-3 py-2 text-left font-semibold text-slate-700">No</th>
                            <th class="border-b px-3 py-2 text-right font-semibold text-slate-700">Nominal</th>
                            <th class="border-b px-3 py-2 text-left font-semibold text-slate-700">Nomor Rekening</th>
                            <th class="border-b px-3 py-2 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kerugianDetailModalTableBody">
                        <tr id="kerugianDetailEmptyRow">
                            <td colspan="4" class="px-3 py-6 text-center text-slate-500">Belum ada rincian.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="kerugianDetailFormPanel" class="hidden mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <h4 id="kerugianDetailFormTitle" class="text-sm font-semibold text-slate-800 mb-3">Tambah Rincian</h4>
                <input type="hidden" id="detail_edit_index" value="">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Nominal (Rp) *</label>
                        <input type="text" id="detail_nominal" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Nomor Rekening</label>
                        <input type="text" id="detail_no_rek" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="">
                    </div>
                </div>
                <div class="mt-3 flex justify-end gap-2">
                    <button type="button" onclick="KerugianDetailManager.cancelForm()" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-white">Batal</button>
                    <button type="button" onclick="KerugianDetailManager.saveForm()" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Simpan Rincian</button>
                </div>
            </div>

            <button type="button" onclick="KerugianDetailManager.showAddForm()" class="mt-4 w-full rounded-lg border-2 border-dashed border-blue-300 py-2.5 text-sm font-medium text-blue-700 hover:bg-blue-50">
                + Tambah Rincian
            </button>
            <p id="kerugianDetailModalCount" class="mt-3 text-center text-xs font-medium text-slate-600"></p>
        </div>
    </div>
</div>

<script>
window.KerugianDetailManager = (function () {
    let activeKategori = '';
    let activeTipe = '';
    let editingIndex = null;

    function getList() {
        const input = document.getElementById('kerugian_details_input');
        try { return JSON.parse(input.value || '[]'); } catch (e) { return []; }
    }

    function setList(list) {
        document.getElementById('kerugian_details_input').value = JSON.stringify(list);
        updateGlobalCounts();
    }

    function formatRp(value) {
        const n = parseInt(String(value).replace(/\D/g, ''), 10) || 0;
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function filterActive(list) {
        return list.filter(item => item.kategori === activeKategori && item.tipe === activeTipe && !item._deleted);
    }

    function getActiveIndices(list) {
        const indices = [];
        list.forEach((item, idx) => {
            if (item.kategori === activeKategori && item.tipe === activeTipe && !item._deleted) {
                indices.push(idx);
            }
        });
        return indices;
    }

    function updateGlobalCounts() {
        const list = getList().filter(i => !i._deleted);
        const container = document.getElementById('kerugian_detail_counts');
        if (!container) return;
        if (list.length === 0) {
            container.textContent = '';
            return;
        }
        container.textContent = list.length + ' rincian telah dicatat';
    }

    function renderModalTable() {
        const list = getList();
        const indices = getActiveIndices(list);
        const tbody = document.getElementById('kerugianDetailModalTableBody');
        const emptyRow = document.getElementById('kerugianDetailEmptyRow');
        tbody.querySelectorAll('tr:not(#kerugianDetailEmptyRow)').forEach(r => r.remove());

        if (indices.length === 0) {
            emptyRow.classList.remove('hidden');
        } else {
            emptyRow.classList.add('hidden');
            indices.forEach((listIndex, displayNo) => {
                const item = list[listIndex];
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50';
                tr.innerHTML = `
                    <td class="border-b px-3 py-2">${displayNo + 1}</td>
                    <td class="border-b px-3 py-2 text-right font-medium">${formatRp(item.nominal)}</td>
                    <td class="border-b px-3 py-2">${item.no_rekening || '-'}</td>
                    <td class="border-b px-3 py-2 text-center whitespace-nowrap">
                        <button type="button" class="text-blue-600 hover:text-blue-800 text-xs font-semibold mr-2" data-action="edit" data-index="${listIndex}">Edit</button>
                        <button type="button" class="text-red-600 hover:text-red-800 text-xs font-semibold" data-action="delete" data-index="${listIndex}">Hapus</button>
                    </td>`;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('kerugianDetailModalCount').textContent =
            indices.length > 0 ? indices.length + ' rincian telah dicatat' : '';

        tbody.querySelectorAll('[data-action="edit"]').forEach(btn => {
            btn.addEventListener('click', () => showEditForm(parseInt(btn.dataset.index, 10)));
        });
        tbody.querySelectorAll('[data-action="delete"]').forEach(btn => {
            btn.addEventListener('click', () => deleteItem(parseInt(btn.dataset.index, 10)));
        });
    }

    function showToast(msg) {
        let toast = document.getElementById('detailSavedToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'detailSavedToast';
            toast.className = 'fixed bottom-6 right-6 z-[60] rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-lg';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2500);
    }

    function openModal(kategori, tipe) {
        activeKategori = kategori;
        activeTipe = tipe;
        editingIndex = null;
        cancelForm();

        const label = (kategori || '').toUpperCase() + (tipe ? ' - ' + (tipe === 'riil' ? 'Riil' : 'Potensial') : '');
        document.getElementById('kerugianDetailModalTitle').textContent = 'Kelola Rincian Kerugian (' + label + ')';

        renderModalTable();
        const modal = document.getElementById('kerugianDetailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        cancelForm();
        const modal = document.getElementById('kerugianDetailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function showAddForm() {
        editingIndex = null;
        document.getElementById('detail_nominal').value = '';
        document.getElementById('detail_no_rek').value = '';
        document.getElementById('kerugianDetailFormTitle').textContent = 'Tambah Rincian';
        document.getElementById('kerugianDetailFormPanel').classList.remove('hidden');
    }

    function showEditForm(listIndex) {
        const list = getList();
        const item = list[listIndex];
        if (!item) return;
        editingIndex = listIndex;
        document.getElementById('detail_nominal').value = item.nominal || '';
        document.getElementById('detail_no_rek').value = item.no_rekening || '';
        document.getElementById('kerugianDetailFormTitle').textContent = 'Edit Rincian';
        document.getElementById('kerugianDetailFormPanel').classList.remove('hidden');
    }

    function cancelForm() {
        editingIndex = null;
        document.getElementById('detail_nominal').value = '';
        document.getElementById('detail_no_rek').value = '';
        document.getElementById('kerugianDetailFormPanel').classList.add('hidden');
    }

    function saveForm() {
        const nominal = document.getElementById('detail_nominal').value.trim();
        const noRek = document.getElementById('detail_no_rek').value.trim();
        if (!nominal) {
            alert('Nominal wajib diisi.');
            return;
        }

        const list = getList();
        const payload = {
            kategori: activeKategori,
            tipe: activeTipe,
            nominal: nominal,
            no_rekening: noRek,
        };

        if (editingIndex !== null && list[editingIndex]) {
            list[editingIndex] = { ...list[editingIndex], ...payload };
            showToast('Rincian berhasil diperbarui.');
        } else {
            list.push(payload);
            showToast('Rincian berhasil disimpan.');
        }

        setList(list);
        cancelForm();
        renderModalTable();
    }

    function deleteItem(listIndex) {
        if (!confirm('Yakin ingin menghapus rincian ini?')) return;
        const list = getList();
        if (list[listIndex] && list[listIndex].id) {
            list[listIndex]._deleted = true;
        } else {
            list.splice(listIndex, 1);
        }
        setList(list);
        renderModalTable();
        showToast('Rincian berhasil dihapus.');
    }

    document.addEventListener('DOMContentLoaded', updateGlobalCounts);

    return { openModal, closeModal, showAddForm, showEditForm, cancelForm, saveForm, deleteItem };
})();

function openDetailModal(kategori, tipe) {
    KerugianDetailManager.openModal(kategori, tipe);
}
</script>
