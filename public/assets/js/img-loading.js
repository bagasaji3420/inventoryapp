document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll("img").forEach(img => {

        // skip kalau sudah diproses
        if (img.closest('.img-skeleton-wrapper')) return;

        // buat wrapper
        const wrapper = document.createElement('div');
        wrapper.classList.add('img-skeleton-wrapper');

        // buat skeleton
        const skeleton = document.createElement('div');
        skeleton.classList.add('img-skeleton');

        // bungkus img
        img.parentNode.insertBefore(wrapper, img);
        wrapper.appendChild(img);
        wrapper.appendChild(skeleton);

        // saat gambar selesai load
        img.onload = () => {
            img.style.opacity = 1;
            skeleton.remove();
        };

        // fallback kalau udah ke-cache
        if (img.complete) {
            img.style.opacity = 1;
            skeleton.remove();
        }
    });

});