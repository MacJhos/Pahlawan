document.addEventListener("DOMContentLoaded", function () {
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

                            const imgPath =
                                hero.type === "hero" &&
                                !hero.image_path.includes("img/")
                                    ? `/storage/img/${hero.image_path}`
                                    : `/storage/${hero.image_path}`;

                            item.innerHTML = `
                                <div class="h-10 w-10 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-700">
                                    <img src="${imgPath}" class="h-full w-full object-cover">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700 dark:text-white group-hover:text-primary transition-colors">${hero.name}</span>
                                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">${hero.type || "Item"}</span>
                                </div>
                            `;
                            item.onclick = () => {
                                const type = hero.type || "hero";
                                const idOrSlug =
                                    type === "hero" ? hero.slug : hero.id;
                                window.location.href = `/gallery/${type}/${idOrSlug}`;
                            };
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
    }

    const filters = document.querySelectorAll(".filter-btn");
    const galleryItems = document.querySelectorAll(".gallery-item");

    if (filters.length > 0) {
        filters.forEach((btn) => {
            btn.addEventListener("click", function () {
                const filterValue = this.getAttribute("data-filter");

                // Update UI Tombol Filter
                filters.forEach((b) => {
                    b.classList.remove(
                        "bg-primary",
                        "text-white",
                        "shadow-lg",
                        "shadow-primary/20",
                    );
                    b.classList.add(
                        "bg-white",
                        "dark:bg-slate-800",
                        "text-slate-600",
                        "dark:text-slate-300",
                        "border-transparent",
                    );
                });
                this.classList.add(
                    "bg-primary",
                    "text-white",
                    "shadow-lg",
                    "shadow-primary/20",
                );
                this.classList.remove(
                    "bg-white",
                    "dark:bg-slate-800",
                    "text-slate-600",
                    "dark:text-slate-300",
                    "border-transparent",
                );

                galleryItems.forEach((item) => {
                    if (
                        filterValue === "all" ||
                        item.getAttribute("data-type") === filterValue
                    ) {
                        item.style.display = "block";
                        item.style.opacity = "1";
                    } else {
                        item.style.display = "none";
                    }
                });

                updateCount();
            });
        });
    }

    const loadMoreBtn = document.getElementById("load-more-btn");
    const currentCountDisplay = document.getElementById("current-count");
    const perPage = 6;

    function updateCount() {
        const visibleItems = Array.from(galleryItems).filter(
            (item) => item.style.display !== "none",
        ).length;
        if (currentCountDisplay) currentCountDisplay.textContent = visibleItems;
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener("click", function (e) {
            e.preventDefault();
            const hiddenItems = Array.from(galleryItems).filter(
                (item) => item.style.display === "none",
            );

            if (hiddenItems.length > 0) {
                hiddenItems.slice(0, perPage).forEach((item, index) => {
                    item.style.display = "block";
                    item.style.opacity = "0";
                    setTimeout(() => {
                        item.style.transition = "opacity 0.6s ease";
                        item.style.opacity = "1";
                    }, index * 100);
                });
            }
            updateCount();
        });
    }

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
