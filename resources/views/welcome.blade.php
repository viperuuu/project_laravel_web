<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlipPlay REST API — Live Interactive Dashboard</title>
    <!-- Tailwind CSS CDN untuk styling UI/UX modern -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    <!-- HEADER / NAVIGATION -->
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-play"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-emerald-400 to-teal-200 bg-clip-text text-transparent">
                        FlipPlay REST API
                    </h1>
                    <p class="text-xs text-slate-400">Modul 4 Web • Laravel 12 Backend & Interactive Tester</p>
                </div>
            </div>

            <!-- Server Status & Actions -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-950/60 border border-emerald-500/30 text-emerald-400 text-xs font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    API Active: <code class="font-mono">/api/media</code>
                </div>
                <a href="https://github.com/viperuuu/project_laravel_web" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-medium text-slate-200 transition flex items-center gap-2 border border-slate-700">
                    <i class="fa-brands fa-github text-sm"></i> GitHub Repo
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- NOTIFICATION BANNER / LOG TOAST -->
        <div id="toastNotification" class="hidden rounded-xl p-4 border transition duration-300">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div id="toastIcon" class="mt-0.5 text-lg"></div>
                    <div>
                        <h4 id="toastTitle" class="text-sm font-semibold"></h4>
                        <p id="toastMessage" class="text-xs mt-0.5 opacity-90"></p>
                    </div>
                </div>
                <button onclick="hideToast()" class="text-xs opacity-60 hover:opacity-100">✕</button>
            </div>
        </div>

        <!-- STATS & INFO BAR -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
                <div class="flex items-center justify-between text-slate-400 text-xs uppercase font-semibold">
                    <span>Total Katalog</span>
                    <i class="fa-solid fa-film text-emerald-400 text-base"></i>
                </div>
                <div id="statTotalMedia" class="text-2xl font-bold text-slate-100 mt-2">Loading...</div>
                <div class="text-xs text-slate-500 mt-1">Anime & Manga di sistem</div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
                <div class="flex items-center justify-between text-slate-400 text-xs uppercase font-semibold">
                    <span>Relasi Eloquent</span>
                    <i class="fa-solid fa-diagram-project text-teal-400 text-base"></i>
                </div>
                <div class="text-lg font-bold text-slate-100 mt-2 font-mono">Many-to-Many</div>
                <div class="text-xs text-slate-500 mt-1">Media ⇄ User (<code class="text-emerald-400">media_user</code>)</div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
                <div class="flex items-center justify-between text-slate-400 text-xs uppercase font-semibold">
                    <span>Validasi Input</span>
                    <i class="fa-solid fa-shield-halved text-cyan-400 text-base"></i>
                </div>
                <div class="text-lg font-bold text-slate-100 mt-2 font-mono">Form Request</div>
                <div class="text-xs text-slate-500 mt-1">HTTP 422 Unprocessable</div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
                <div class="flex items-center justify-between text-slate-400 text-xs uppercase font-semibold">
                    <span>Pengujian Otomatis</span>
                    <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                </div>
                <div class="text-2xl font-bold text-emerald-400 mt-2">17 Passed</div>
                <div class="text-xs text-slate-500 mt-1">PHPUnit (64 Assertions)</div>
            </div>
        </div>

        <!-- 2-COLUMN LAYOUT: KATALOG MEDIA (CRUD) & KOLEKSI USER (CUSTOM ACTION) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT 2 COLS: KATALOG MEDIA (CRUD) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- CONTROL BAR: Search, Filter & Tambah -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-bold text-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-emerald-400"></i> Katalog Media FlipPlay
                        </h2>
                        <button onclick="toggleModalTambah()" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-xs rounded-xl transition shadow-lg shadow-emerald-500/20 flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Tambah Media Baru
                        </button>
                    </div>

                    <!-- Search Input & Type Filter Pills -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative flex-1 min-w-[200px]">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-500 text-xs"></i>
                            <input type="text" id="searchInput" oninput="debounceSearch()" placeholder="Cari judul media (misal: Hutan, Frieren)..." class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                        </div>

                        <!-- Filter Type Pills -->
                        <div class="flex items-center bg-slate-950 p-1 rounded-xl border border-slate-800 text-xs">
                            <button onclick="setFilterType('')" id="btnFilterAll" class="px-3 py-1.5 rounded-lg font-medium transition bg-emerald-500 text-slate-950">Semua</button>
                            <button onclick="setFilterType('anime')" id="btnFilterAnime" class="px-3 py-1.5 rounded-lg font-medium transition text-slate-400 hover:text-slate-200">Anime</button>
                            <button onclick="setFilterType('manga')" id="btnFilterManga" class="px-3 py-1.5 rounded-lg font-medium transition text-slate-400 hover:text-slate-200">Manga</button>
                        </div>
                    </div>
                </div>

                <!-- MEDIA GRID CONTAINER -->
                <div id="mediaGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Cards will be populated by JS -->
                </div>

            </div>

            <!-- RIGHT 1 COL: KOLEKSI PENGGUNA (CUSTOM ACTION ATTACH / DETACH) -->
            <div class="space-y-6">

                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-bookmark text-teal-400"></i> Koleksi Pengguna
                        </h2>
                        <span class="text-xs px-2.5 py-1 bg-teal-950 text-teal-400 border border-teal-500/30 rounded-full font-mono">Custom Action</span>
                    </div>

                    <!-- Pilih User Dropdown -->
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Pilih Pengguna Aktif:</label>
                        <select id="userSelect" onchange="loadUserCollection()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500">
                            <!-- Populated by JS -->
                        </select>
                    </div>

                    <div class="border-t border-slate-800/80 pt-4">
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-3">
                            <span>Media yang Dikoleksi:</span>
                            <span id="userCollectionCount" class="font-bold text-teal-400">0 Item</span>
                        </div>

                        <!-- User Collection List -->
                        <div id="userCollectionList" class="space-y-2.5 max-h-[500px] overflow-y-auto pr-1">
                            <!-- Populated by JS -->
                        </div>
                    </div>
                </div>

                <!-- LIVE JSON RESPONSE VIEWER (Untuk kemudahan presentasi demo) -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-terminal text-emerald-400"></i> Raw JSON Response
                        </h3>
                        <span id="responseStatusBadge" class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-400">Status 200 OK</span>
                    </div>
                    <pre id="jsonViewer" class="bg-slate-950 border border-slate-800/80 rounded-xl p-3 text-[11px] font-mono text-emerald-300 max-h-56 overflow-auto leading-relaxed select-all">Pilih atau klik aksi API untuk melihat respons JSON langsung...</pre>
                </div>

            </div>

        </div>

    </main>

    <!-- MODAL TAMBAH MEDIA (POST /api/media) -->
    <div id="modalTambah" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-emerald-400"></i> Tambah Media Baru (POST /api/media)
                </h3>
                <button onclick="toggleModalTambah()" class="text-slate-400 hover:text-slate-100 text-sm">✕</button>
            </div>

            <form id="formTambahMedia" onsubmit="submitTambahMedia(event)" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Judul Media *</label>
                    <input type="text" id="inputTitle" placeholder="Contoh: Sousou no Frieren S2" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 focus:outline-none focus:border-emerald-500">
                    <p id="errTitle" class="text-rose-400 text-[11px] mt-1 hidden"></p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Jenis Media *</label>
                        <select id="inputType" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 focus:outline-none focus:border-emerald-500">
                            <option value="anime">Anime</option>
                            <option value="manga">Manga</option>
                        </select>
                        <p id="errType" class="text-rose-400 text-[11px] mt-1 hidden"></p>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Tahun Rilis</label>
                        <input type="number" id="inputYear" placeholder="2025" value="2024" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Genre *</label>
                        <input type="text" id="inputGenre" placeholder="Contoh: Fantasi, Petualangan" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 focus:outline-none focus:border-emerald-500">
                        <p id="errGenre" class="text-rose-400 text-[11px] mt-1 hidden"></p>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Kreator / Studio</label>
                        <input type="text" id="inputCreator" placeholder="Contoh: Madhouse" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Sinopsis / Deskripsi</label>
                    <textarea id="inputDescription" rows="3" placeholder="Sinopsis singkat alur cerita..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <!-- Info Validasi -->
                <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800 text-[11px] text-slate-400 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-emerald-400"></i>
                    <span>Coba kosongkan judul untuk menguji penanganan error <strong>422 Unprocessable</strong>.</span>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="toggleModalTambah()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold transition shadow-lg shadow-emerald-500/20">Simpan Media</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC CONNECTING TO LARAVEL REST API -->
    <script>
        let currentFilterType = '';
        let currentSearchQuery = '';
        let searchTimeout = null;
        let currentUser = null;

        // Inisialisasi saat halaman dibuka
        document.addEventListener('DOMContentLoaded', () => {
            loadUsers();
            loadMedia();
        });

        // 1. LOAD USERS DROPDOWN
        async function loadUsers() {
            try {
                const res = await fetch('/api/users');
                const json = await res.json();
                const select = document.getElementById('userSelect');
                select.innerHTML = '';

                if (json.data && json.data.length > 0) {
                    json.data.forEach((u, i) => {
                        const opt = document.createElement('option');
                        opt.value = u.id;
                        opt.textContent = `${u.name} (ID: ${u.id})`;
                        select.appendChild(opt);
                    });
                    currentUser = json.data[0].id;
                    loadUserCollection();
                }
            } catch (err) {
                console.error("Gagal load users:", err);
            }
        }

        // 2. LOAD MEDIA LIST (INDEX + FILTER + SEARCH)
        async function loadMedia() {
            const grid = document.getElementById('mediaGrid');
            grid.innerHTML = `<div class="col-span-2 text-center py-12 text-slate-500 text-xs"><i class="fa-solid fa-spinner fa-spin text-emerald-400 text-lg mb-2"></i><br>Memuat katalog media...</div>`;

            let url = `/api/media?`;
            if (currentSearchQuery) url += `title=${encodeURIComponent(currentSearchQuery)}&`;
            if (currentFilterType) url += `type=${encodeURIComponent(currentFilterType)}&`;

            try {
                const res = await fetch(url);
                const json = await res.json();
                updateJsonViewer(res.status, json);

                document.getElementById('statTotalMedia').textContent = (json.meta ? json.meta.total : json.data.length) + ' Media';

                if (!json.data || json.data.length === 0) {
                    grid.innerHTML = `<div class="col-span-2 text-center py-12 text-slate-500 text-xs bg-slate-900/40 rounded-2xl border border-slate-800">Tidak ada media yang cocok dengan filter.</div>`;
                    return;
                }

                grid.innerHTML = '';
                json.data.forEach(item => {
                    const isAnime = item.type === 'anime';
                    const badgeClass = isAnime ? 'bg-sky-500/10 text-sky-400 border-sky-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20';

                    const card = document.createElement('div');
                    card.className = "bg-slate-900/70 border border-slate-800 hover:border-slate-700 rounded-2xl p-4 flex flex-col justify-between transition group shadow-sm";
                    card.innerHTML = `
                        <div class="space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full border ${badgeClass}">
                                    ${item.type}
                                </span>
                                <span class="text-xs text-slate-500 font-mono">${item.release_year || '-'}</span>
                            </div>
                            <h3 class="font-bold text-slate-100 text-sm group-hover:text-emerald-400 transition">${item.title}</h3>
                            <p class="text-[11px] text-slate-400"><i class="fa-solid fa-tag text-emerald-400 mr-1"></i> ${item.genre}</p>
                            <p class="text-[11px] text-slate-500"><i class="fa-solid fa-user-pen mr-1"></i> ${item.creator || 'FlipPlay Studio'}</p>
                            <p class="text-[11px] text-slate-400 line-clamp-2 mt-1">${item.description || 'Tidak ada deskripsi.'}</p>
                        </div>

                        <div class="border-t border-slate-800/80 pt-3 mt-4 flex items-center justify-between gap-2">
                            <button onclick="tambahKeKoleksi(${item.id})" class="flex-1 py-1.5 px-3 bg-teal-500/10 hover:bg-teal-500 hover:text-slate-950 text-teal-400 text-xs font-semibold rounded-xl border border-teal-500/30 transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-bookmark text-[10px]"></i> Koleksi
                            </button>
                            <button onclick="hapusMedia(${item.id})" title="Hapus Media dari Sistem" class="py-1.5 px-2.5 bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-400 text-xs rounded-xl border border-rose-500/20 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                    grid.appendChild(card);
                });

            } catch (err) {
                console.error("Gagal load media:", err);
                grid.innerHTML = `<div class="col-span-2 text-rose-400 text-xs text-center py-6">Gagal memuat data dari API.</div>`;
            }
        }

        // 3. LOAD USER COLLECTION (GET /api/users/{id}/collections)
        async function loadUserCollection() {
            const select = document.getElementById('userSelect');
            currentUser = select.value;
            if (!currentUser) return;

            const list = document.getElementById('userCollectionList');
            list.innerHTML = `<div class="text-center py-6 text-slate-500 text-xs"><i class="fa-solid fa-spinner fa-spin text-teal-400"></i> Memuat koleksi...</div>`;

            try {
                const res = await fetch(`/api/users/${currentUser}/collections`);
                const json = await res.json();

                const collection = json.data?.media_collection || [];
                document.getElementById('userCollectionCount').textContent = `${collection.length} Item`;

                if (collection.length === 0) {
                    list.innerHTML = `<div class="text-center py-8 text-slate-500 text-xs bg-slate-950/60 rounded-xl border border-slate-800/80 p-4">Koleksi masih kosong.<br>Klik tombol <strong>"Koleksi"</strong> pada media di sebelah kiri.</div>`;
                    return;
                }

                list.innerHTML = '';
                collection.forEach(m => {
                    const el = document.createElement('div');
                    el.className = "flex items-center justify-between p-3 rounded-xl bg-slate-950 border border-slate-800/80 text-xs gap-3 hover:border-teal-500/40 transition";
                    el.innerHTML = `
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-200 truncate">${m.title}</h4>
                            <div class="flex items-center gap-2 text-[10px] text-slate-500 mt-0.5">
                                <span class="capitalize text-teal-400">${m.type}</span> •
                                <span class="px-1.5 py-0.2 rounded bg-slate-800 text-slate-400">${m.collection_status || 'watching'}</span>
                            </div>
                        </div>
                        <button onclick="hapusDariKoleksi(${m.id})" title="Hapus dari Koleksi" class="text-slate-500 hover:text-rose-400 p-1.5 transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    `;
                    list.appendChild(el);
                });

            } catch (err) {
                console.error("Gagal load koleksi:", err);
            }
        }

        // 4. CUSTOM ACTION: TAMBAH MEDIA KE KOLEKSI (POST /api/users/{id}/collections)
        async function tambahKeKoleksi(mediaId) {
            if (!currentUser) {
                showToast("Peringatan", "Pilih pengguna terlebih dahulu", "error");
                return;
            }

            try {
                const res = await fetch(`/api/users/${currentUser}/collections`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ media_id: mediaId, status: 'watching' })
                });
                const json = await res.json();
                updateJsonViewer(res.status, json);

                if (res.status === 201) {
                    showToast("Berhasil (201 Created)", json.message, "success");
                    loadUserCollection();
                } else if (res.status === 409) {
                    showToast("Konflik Duplikasi (409 Conflict)", json.message, "warning");
                } else {
                    showToast("Gagal", json.message || "Gagal menambahkan ke koleksi", "error");
                }
            } catch (err) {
                console.error("Error attach collection:", err);
            }
        }

        // 5. CUSTOM ACTION: HAPUS DARI KOLEKSI (DELETE /api/users/{id}/collections/{mediaId})
        async function hapusDariKoleksi(mediaId) {
            try {
                const res = await fetch(`/api/users/${currentUser}/collections/${mediaId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                updateJsonViewer(res.status, json);

                if (res.ok) {
                    showToast("Berhasil Dihapus (200 OK)", json.message, "success");
                    loadUserCollection();
                }
            } catch (err) {
                console.error("Error detach collection:", err);
            }
        }

        // 6. SUBMIT TAMBAH MEDIA (POST /api/media) - Mendemonstrasikan Validasi 422
        async function submitTambahMedia(e) {
            e.preventDefault();
            clearErrors();

            const payload = {
                title: document.getElementById('inputTitle').value,
                type: document.getElementById('inputType').value,
                genre: document.getElementById('inputGenre').value,
                creator: document.getElementById('inputCreator').value,
                release_year: document.getElementById('inputYear').value ? parseInt(document.getElementById('inputYear').value) : null,
                description: document.getElementById('inputDescription').value
            };

            try {
                const res = await fetch('/api/media', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                updateJsonViewer(res.status, json);

                if (res.status === 201) {
                    showToast("Berhasil Dibuat (201 Created)", `Media '${json.data.title}' berhasil ditambahkan ke katalog!`, "success");
                    toggleModalTambah();
                    document.getElementById('formTambahMedia').reset();
                    loadMedia();
                } else if (res.status === 422) {
                    showToast("Validasi Gagal (422 Unprocessable)", "Mohon lengkapi seluruh field wajib.", "error");
                    if (json.errors) {
                        if (json.errors.title) showError('errTitle', json.errors.title[0]);
                        if (json.errors.type) showError('errType', json.errors.type[0]);
                        if (json.errors.genre) showError('errGenre', json.errors.genre[0]);
                    }
                }
            } catch (err) {
                console.error("Error store media:", err);
            }
        }

        // 7. HAPUS MEDIA DARI SISTEM (DELETE /api/media/{id})
        async function hapusMedia(mediaId) {
            if (!confirm("Apakah Anda yakin ingin menghapus media ini dari katalog FlipPlay?")) return;

            try {
                const res = await fetch(`/api/media/${mediaId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                updateJsonViewer(res.status, json);

                if (res.ok) {
                    showToast("Berhasil Dihapus (200 OK)", json.message, "success");
                    loadMedia();
                    loadUserCollection();
                }
            } catch (err) {
                console.error("Error delete media:", err);
            }
        }

        // HELPER FUNCTIONS
        function setFilterType(type) {
            currentFilterType = type;
            const buttons = {
                '': document.getElementById('btnFilterAll'),
                'anime': document.getElementById('btnFilterAnime'),
                'manga': document.getElementById('btnFilterManga')
            };

            for (const key in buttons) {
                if (key === type) {
                    buttons[key].className = "px-3 py-1.5 rounded-lg font-medium transition bg-emerald-500 text-slate-950";
                } else {
                    buttons[key].className = "px-3 py-1.5 rounded-lg font-medium transition text-slate-400 hover:text-slate-200";
                }
            }
            loadMedia();
        }

        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearchQuery = document.getElementById('searchInput').value.trim();
                loadMedia();
            }, 300);
        }

        function toggleModalTambah() {
            const modal = document.getElementById('modalTambah');
            modal.classList.toggle('hidden');
            clearErrors();
        }

        function showError(id, msg) {
            const el = document.getElementById(id);
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function clearErrors() {
            ['errTitle', 'errType', 'errGenre'].forEach(id => {
                const el = document.getElementById(id);
                el.textContent = '';
                el.classList.add('hidden');
            });
        }

        function updateJsonViewer(status, json) {
            const badge = document.getElementById('responseStatusBadge');
            badge.textContent = `HTTP ${status}`;
            if (status >= 200 && status < 300) {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-950 text-emerald-400 border border-emerald-500/30";
            } else if (status === 409) {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-amber-950 text-amber-400 border border-amber-500/30";
            } else {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-rose-950 text-rose-400 border border-rose-500/30";
            }
            document.getElementById('jsonViewer').textContent = JSON.stringify(json, null, 2);
        }

        function showToast(title, msg, type) {
            const toast = document.getElementById('toastNotification');
            const icon = document.getElementById('toastIcon');
            const titleEl = document.getElementById('toastTitle');
            const msgEl = document.getElementById('toastMessage');

            titleEl.textContent = title;
            msgEl.textContent = msg;

            if (type === 'success') {
                toast.className = "rounded-xl p-4 border bg-emerald-950/80 border-emerald-500/40 text-emerald-300 block";
                icon.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-400"></i>`;
            } else if (type === 'warning') {
                toast.className = "rounded-xl p-4 border bg-amber-950/80 border-amber-500/40 text-amber-300 block";
                icon.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-amber-400"></i>`;
            } else {
                toast.className = "rounded-xl p-4 border bg-rose-950/80 border-rose-500/40 text-rose-300 block";
                icon.innerHTML = `<i class="fa-solid fa-circle-xmark text-rose-400"></i>`;
            }

            setTimeout(hideToast, 5000);
        }

        function hideToast() {
            document.getElementById('toastNotification').className = "hidden";
        }
    </script>
</body>
</html>
