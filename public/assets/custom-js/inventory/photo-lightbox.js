document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.createElement("div");
    overlay.className = "photo-lightbox-overlay";
    overlay.innerHTML = `
        <span class="photo-lightbox-close">&times;</span>
        <img src="" alt="Zoom">
    `;
    document.body.appendChild(overlay);

    const overlayImg = overlay.querySelector("img");

    function openLightbox(src) {
        overlayImg.src = src;
        overlay.classList.add("active");
    }

    function closeLightbox() {
        overlay.classList.remove("active");
        overlayImg.src = "";
    }

    document.addEventListener("click", (e) => {
        const img = e.target.closest(".item-thumb[src]");
        if (img) {
            openLightbox(img.src);
        }
    });

    overlay.addEventListener("click", closeLightbox);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeLightbox();
        }
    });
});
