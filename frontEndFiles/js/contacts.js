const API_BASE = "/api/contacts";

let allContacts = [];

// ===============================
// Fetch & Render
// ===============================
async function loadContacts() {
    const res = await fetch("/api/contacts", {
        credentials: "include"
    });

    const data = await res.json();

    // Defensive handling
    if (!data.result || !Array.isArray(data.result)) {
        allContacts = [];
    } else {
        allContacts = data.result;
    }

    renderContacts(allContacts);
}


function renderContacts(list) {
    const table = document.getElementById("contactsTable");
    table.innerHTML = "";

    list.forEach(c => {
        const row = document.createElement("tr");

        row.innerHTML = `
      <td>${c.first_name}</td>
      <td>${c.last_name}</td>
      <td>${c.phone}</td>
      <td>${c.email}</td>
      <td>
        <button class="delete" onclick="deleteContact(${c.id})">
          Delete
        </button>
      </td>
    `;

        table.appendChild(row);
    });
}

// ===============================
// Add Contact
// ===============================
document.getElementById("addBtn").addEventListener("click", async () => {
    const body = {
        first_name: firstName.value.trim(),
        last_name: lastName.value.trim(),
        phone: phone.value.trim(),
        email: email.value.trim()
    };

    const res = await fetch("/api/contacts", {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body)
    });

    const data = await res.json();

    if (data.error) {
        alert(data.error);
        return;
    }

    clearForm();

    // 🔑 THIS is what refreshes the UI
    await loadContacts();
});


// ===============================
// Delete Contact
// ===============================
async function deleteContact(id) {
    if (!confirm("Delete this contact?")) return;

    await fetch(`${API_BASE}/${id}`, {
        method: "DELETE",
        credentials: "include"
    });

    loadContacts();
}

// ===============================
// Partial Search (client-side)
// ===============================
document.getElementById("searchInput").addEventListener("input", e => {
    const term = e.target.value.toLowerCase();

    const filtered = allContacts.filter(c =>
        c.first_name.toLowerCase().includes(term) ||
        c.last_name.toLowerCase().includes(term) ||
        c.phone.includes(term) ||
        c.email.toLowerCase().includes(term)
    );

    renderContacts(filtered);
});

// ===============================
// Refresh
// ===============================
document.getElementById("refreshBtn").addEventListener("click", () => {
    searchInput.value = "";
    loadContacts();
});

// ===============================
// Helpers
// ===============================
function clearForm() {
    firstName.value = "";
    lastName.value = "";
    phone.value = "";
    email.value = "";
}

// Initial load
loadContacts();
