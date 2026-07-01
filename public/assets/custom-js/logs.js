function formatDate(val) {
    if (!val) return "-";

    // coba parse ke Date
    const date = new Date(val);

    // kalau valid date
    if (!isNaN(date)) {
        const pad = (n) => n.toString().padStart(2, "0");

        const hours = pad(date.getHours());
        const minutes = pad(date.getMinutes());
        const day = pad(date.getDate());
        const month = pad(date.getMonth() + 1);
        const year = date.getFullYear().toString().slice(-2);

        return `${hours}:${minutes} ${day}/${month}/${year}`;
    }

    return val;
}

function showLog(attributeChanges) {
    let html = "";

    const oldData = attributeChanges.old || {};
    const newData = attributeChanges.attributes || {};

    const allKeys = new Set([...Object.keys(oldData), ...Object.keys(newData)]);

    html += '<table class="table table-bordered">';
    html += "<thead><tr><th>Field</th><th>Old</th><th>New</th></tr></thead>";
    html += "<tbody>";

    allKeys.forEach((key) => {
        let oldVal = oldData[key] ?? "-";
        let newVal = newData[key] ?? "-";

        // 🔥 format kalau itu tanggal
        if (key.includes("date") || key.includes("at")) {
            oldVal = formatDate(oldVal);
            newVal = formatDate(newVal);
        }

        html += `
                <tr>
                    <td><strong>${key}</strong></td>
                    <td class="text-danger">${oldVal}</td>
                    <td class="text-success">${newVal}</td>
                </tr>
            `;
    });

    html += "</tbody></table>";

    document.getElementById("logContent").innerHTML = html;
}
