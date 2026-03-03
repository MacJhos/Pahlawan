document.addEventListener("DOMContentLoaded", function () {
    /** * BAGIAN 1: LIVE SEARCH SUGGESTIONS
     */
    const searchInput = document.getElementById("search-input");
    if (searchInput) {
        const suggestionBox = document.createElement("div");
        suggestionBox.id = "search-suggestions";
        suggestionBox.className =
            "absolute top-full left-0 w-full bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-b-xl shadow-2xl z-[100] hidden overflow-hidden mt-1";

        searchInput.parentElement.classList.add("relative");
        searchInput.parentElement.appendChild(suggestionBox);

        const performSearch = (query) => {
            if (query.length < 2) {
                suggestionBox.classList.add("hidden");
                return;
            }

            fetch(`/galeri?search=${encodeURIComponent(query)}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            })
                .then((res) => res.json())
                .then((data) => {
                    suggestionBox.innerHTML = "";
                    if (data.heroes && data.heroes.length > 0) {
                        data.heroes.forEach((hero) => {
                            const item = document.createElement("div");
                            item.className =
                                "flex items-center gap-3 p-3 hover:bg-primary/5 dark:hover:bg-slate-800 cursor-pointer transition-colors border-b border-slate-50 dark:border-slate-800 last:border-0 group";
                            item.innerHTML = `
                            <div class="h-10 w-10 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-700">
                                <img src="/storage/img/${hero.image_path}" class="h-full w-full object-cover">
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700 dark:text-white group-hover:text-primary transition-colors">${hero.name}</span>
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">${hero.category}</span>
                            </div>
                        `;
                            item.onclick = () =>
                                (window.location.href = `/pahlawan/${hero.slug}`);
                            suggestionBox.appendChild(item);
                        });
                        suggestionBox.classList.remove("hidden");
                    } else {
                        suggestionBox.innerHTML = `<div class="p-4 text-center text-xs text-slate-500 italic">Tidak ditemukan "${query}"</div>`;
                        suggestionBox.classList.remove("hidden");
                    }
                })
                .catch((err) => console.error("Search Error:", err));
        };

        let searchTimeout;
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(
                () => performSearch(this.value.trim()),
                300,
            );
        });

        searchInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                window.location.href = `/galeri?search=${encodeURIComponent(this.value.trim())}`;
            }
        });

        searchInput.addEventListener("focus", () => {
            if (searchInput.value.trim().length >= 2)
                suggestionBox.classList.remove("hidden");
        });
    }

    /** * BAGIAN 2: LOAD MORE & SHOW LESS (TOGGLE)
     * Menggunakan atribut data-more dan data-less untuk bahasa
     */
    const loadMoreBtn = document.getElementById("load-more-btn");
    const btnText = document.getElementById("btn-text");
    const currentCountDisplay = document.getElementById("current-count");
    const perPage = 6;
    const initialDisplay = 6;

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener("click", function (e) {
            e.preventDefault();

            // AMBIL TEKS BAHASA DARI ATRIBUT DATA (DIISI OLEH BLADE)
            const textMore = loadMoreBtn.getAttribute("data-more");
            const textLess = loadMoreBtn.getAttribute("data-less");

            const allItems = Array.from(
                document.querySelectorAll(".hero-item"),
            );
            const hiddenItems = allItems.filter(
                (item) => item.style.display === "none",
            );

            // LOGIKA 1: JIKA MASIH ADA DATA TERSEMBUNYI (LOAD MORE)
            if (hiddenItems.length > 0) {
                const toShow = hiddenItems.slice(0, perPage);

                toShow.forEach((item, index) => {
                    item.style.display = "block";
                    item.style.opacity = "0";
                    setTimeout(() => {
                        item.style.transition =
                            "opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1)";
                        item.style.opacity = "1";
                    }, index * 100);
                });

                const remainingAfterClick = allItems.filter(
                    (item) => item.style.display === "none",
                );

                if (remainingAfterClick.length === 0) {
                    if (btnText) btnText.innerText = textLess; // Gunakan teks dari data-less
                    loadMoreBtn.querySelector(
                        ".material-symbols-outlined",
                    ).style.transform = "rotate(180deg)";
                }
            }
            // LOGIKA 2: JIKA DATA SUDAH TAMPIL SEMUA (SHOW LESS)
            else {
                allItems.forEach((item, index) => {
                    if (index >= initialDisplay) {
                        item.style.opacity = "0";
                        setTimeout(() => {
                            item.style.display = "none";
                        }, 500);
                    }
                });

                if (btnText) btnText.innerText = textMore; // Gunakan teks dari data-more
                loadMoreBtn.querySelector(
                    ".material-symbols-outlined",
                ).style.transform = "rotate(0deg)";

                document
                    .getElementById("hero-grid")
                    .scrollIntoView({ behavior: "smooth" });
            }

            // Update counter angka secara dinamis
            setTimeout(() => {
                const nowVisible = allItems.filter(
                    (item) => item.style.display !== "none",
                ).length;
                if (currentCountDisplay)
                    currentCountDisplay.textContent = nowVisible;
            }, 600);
        });
    }

    /** * GLOBAL CLOSE SEARCH
     */
    document.addEventListener("click", (e) => {
        const sBox = document.getElementById("search-suggestions");
        if (
            sBox &&
            searchInput &&
            !searchInput.contains(e.target) &&
            !sBox.contains(e.target)
        ) {
            sBox.classList.add("hidden");
        }
    });
});
