{{-- 1. BAGIAN UTAMA --}}
<div class="mb-6">
    <h4 class="mb-2 font-medium text-slate-800">Rincian Kerugian</h4>
    <input type="hidden" id="kerugian_details_input" name="kerugian_details" value="{{ $kerugianDetailsJson ?? '[]' }}">
</div>

{{-- 2. MODAL DENGAN ISOLASI VALIDASI FORM --}}
<div id="kerugianDetailModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 px-4 py-6 left-0 top-0 w-full h-full" onclick="if(event.target === this) KerugianDetailManager.closeModal()">
    <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl" onclick="event.stopPropagation()">
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

            {{-- FORM INPUT PANEL --}}
            <div id="kerugianDetailFormPanel" class="hidden mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <h4 id="kerugianDetailFormTitle" class="text-sm font-semibold text-slate-800 mb-3">Tambah Rincian</h4>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Nominal (Rp) *</label>
                        {{-- formnovalidate disuntikkan agar diabaikan oleh checkValidity() --}}
                        <input type="text" id="detail_nominal" formnovalidate class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Nomor Rekening</label>
                        <input type="text" id="detail_no_rek" formnovalidate class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="">
                    </div>
                </div>
                <div class="mt-3 flex justify-end gap-2">
                    <button type="button" onclick="KerugianDetailManager.cancelForm()" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-white">Batal</button>
                    <button type="button" onclick="KerugianDetailManager.saveForm(event)" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Simpan Rincian</button>
                </div>
            </div>

            <button type="button" onclick="KerugianDetailManager.showAddForm()" class="mt-4 w-full rounded-lg border-2 border-dashed border-blue-300 py-2.5 text-sm font-medium text-blue-700 hover:bg-blue-50">
                + Tambah Rincian
            </button>
        </div>
    </div>
</div>

{{-- 3. JAVASCRIPT ENGINE (ANTI-MANDAL) --}}
<script>
window.KerugianDetailManager = (function () {
    let activeKategori = '';
    let activeTipe = '';
    let editingIndex = null;

    function getList() {
        const input = document.getElementById('kerugian_details_input');
        if (!input) return [];
        try { 
            let data = JSON.parse(input.value || '[]'); 
            return data.map(item => {
                return {
                    id: item.id || null,
                    kategori: String(item.kategori || activeKategori).trim().toLowerCase(),
                    tipe: String(item.tipe || activeTipe).trim().toLowerCase(),
                    nominal: parseInt(String(item.nominal).replace(/\D/g, ''), 10) || 0,
                    no_rekening: item.no_rekening || item.no_rek || ''
                };
            });
        } catch (e) { 
            return []; 
        }
    }

    function setList(list) {
        const input = document.getElementById('kerugian_details_input');
        if (input) {
            input.value = JSON.stringify(list);
        }
    }

    function formatRp(value) {
        const n = parseInt(String(value).replace(/\D/g, ''), 10) || 0;
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    // Pasang event listener input nominal rupiah
    document.addEventListener('DOMContentLoaded', function() {
        const nominalInput = document.getElementById('detail_nominal');
        if(nominalInput) {
            nominalInput.addEventListener('input', function (e) {
                // Hentikan propagasi agar tidak memicu checkFormValidity() di file edit
                e.stopPropagation(); 
                let value = e.target.value.replace(/\D/g, ''); 
                if (value) {
                    value = new Intl.NumberFormat('id-ID').format(value);
                }
                e.target.value = value;
            });
        }
    });

    function getActiveIndices(list) {
        const indices = [];
        const targetKategori = String(activeKategori).trim().toLowerCase();
        const targetTipe = String(activeTipe).trim().toLowerCase();

        list.forEach((item, idx) => {
            if (!item._deleted) {
                const itemKategori = String(item.kategori || '').trim().toLowerCase();
                const itemTipe = String(item.tipe || '').trim().toLowerCase();
                if (itemKategori === targetKategori && itemTipe === targetTipe) {
                    indices.push(idx);
                }
            }
        });
        return indices;
    }

    function renderModalTable() {
        const list = getList();
        const indices = getActiveIndices(list);
        const tbody = document.getElementById('kerugianDetailModalTableBody');
        const emptyRow = document.getElementById('kerugianDetailEmptyRow');
        
        if(!tbody) return;
        
        // Bersihkan baris tabel lama
        tbody.querySelectorAll('tr:not(#kerugianDetailEmptyRow)').forEach(r => r.remove());

        if (indices.length === 0) {
            if(emptyRow) emptyRow.classList.remove('hidden');
        } else {
            if(emptyRow) emptyRow.classList.add('hidden');
            indices.forEach((listIndex, displayNo) => {
                const item = list[listIndex];
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50 border-b border-slate-100';
                tr.innerHTML = `
                    <td class="px-3 py-2 text-slate-600">${displayNo + 1}</td>
                    <td class="px-3 py-2 text-right font-medium text-slate-900">${formatRp(item.nominal)}</td>
                    <td class="px-3 py-2 text-slate-600">${item.no_rekening || '-'}</td>
                    <td class="px-3 py-2 text-center whitespace-nowrap">
                        <button type="button" onclick="KerugianDetailManager.showEditForm(${listIndex})" class="text-blue-600 hover:text-blue-800 text-xs font-semibold mr-3">Edit</button>
                        <button type="button" onclick="KerugianDetailManager.deleteItem(${listIndex})" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                    </td>`;
                tbody.appendChild(tr);
            });
        }
    }

    function showToast(msg) {
        let toast = document.getElementById('detailSavedToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'detailSavedToast';
            toast.className = 'fixed bottom-6 right-6 z-[99999] rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-lg';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2500);
    }

    function openModal(kategori, tipe) {
        activeKategori = String(kategori).trim().toLowerCase();
        activeTipe = String(tipe).trim().toLowerCase();
        editingIndex = null;
        cancelForm();

        const label = activeKategori.toUpperCase() + ' - ' + (activeTipe === 'riil' ? 'Riil' : 'Potensial');
        const modalTitle = document.getElementById('kerugianDetailModalTitle');
        if (modalTitle) modalTitle.textContent = 'Kelola Rincian Kerugian (' + label + ')';

        renderModalTable();
        const modal = document.getElementById('kerugianDetailModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal() {
        cancelForm();
        const modal = document.getElementById('kerugianDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
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
        
        let formattedNominal = String(item.nominal).replace(/\D/g, '');
        if(formattedNominal) {
            formattedNominal = new Intl.NumberFormat('id-ID').format(formattedNominal);
        }

        document.getElementById('detail_nominal').value = formattedNominal;
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

    function saveForm(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation(); // Kunci rapat-rapat agar tidak memicu form edit utama
        }

        const nominalRaw = document.getElementById('detail_nominal').value.trim();
        const noRek = document.getElementById('detail_no_rek').value.trim();
        
        if (!nominalRaw || nominalRaw === '0') {
            alert('Nominal wajib diisi.');
            return;
        }

        const nominalNumeric = parseInt(nominalRaw.replace(/\D/g, ''), 10) || 0;
        const list = getList();
        
        const payload = {
            kategori: activeKategori, 
            tipe: activeTipe,         
            nominal: nominalNumeric,
            no_rekening: noRek,
        };

        if (editingIndex !== null && list[editingIndex]) {
            const oldId = list[editingIndex].id ? { id: list[editingIndex].id } : {};
            list[editingIndex] = { ...oldId, ...payload };
            showToast('Rincian berhasil diperbarui.');
        } else {
            list.push(payload);
            showToast('Rincian berhasil disimpan.');
        }

        setList(list);
        cancelForm();
        renderModalTable(); // UPDATE INSTAN KE TABEL ATAS!
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

    return { openModal, closeModal, showAddForm, showEditForm, cancelForm, saveForm, deleteItem, renderModalTable };
})();

function openDetailModal(kategori, tipe) {
    KerugianDetailManager.openModal(kategori, tipe);
}
</script>