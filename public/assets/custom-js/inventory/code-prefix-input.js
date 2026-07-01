function formatCodePrefix(value) {
    const letters = value.toUpperCase().replace(/[^A-Z]/g, "");
    const chunks = letters.match(/.{1,3}/g);
    return chunks ? chunks.join("-") : "";
}

function updateExample(input) {
    const targetId = input.dataset.exampleTarget;
    if (!targetId) {
        return;
    }

    const target = document.getElementById(targetId);
    if (!target) {
        return;
    }

    const suffix = input.dataset.exampleSuffix || "";
    const prefix = input.value || "...";
    target.textContent = `Contoh hasil: ${prefix}${suffix}`;
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".code-prefix-input").forEach((input) => {
        input.addEventListener("input", function () {
            const cursorAtEnd = this.selectionEnd === this.value.length;
            this.value = formatCodePrefix(this.value);

            if (cursorAtEnd) {
                this.setSelectionRange(this.value.length, this.value.length);
            }

            updateExample(this);
        });
    });
});
