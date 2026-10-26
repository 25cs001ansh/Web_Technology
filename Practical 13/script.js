
const API_URL = 'api.php';

document.addEventListener('DOMContentLoaded', () => {
    loadMenu();
    loadItems();
});

// Show User Feedback Alerts (Success / Error / Validation)
function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    toast.innerText = message;
    toast.className = 'toast-msg ' + (isError ? 'toast-error' : 'toast-success');
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3500);
}

// 1. Load Dynamic Navigation Menu based on Active Role (RBAC)
async function loadMenu() {
    const role = document.getElementById('roleSelector').value;
    try {
        const res = await fetch(`${API_URL}?action=get_menu&role=${role}`);
        const result = await res.json();
        const menuUl = document.getElementById('dynamicMenu');
        menuUl.innerHTML = '';
        
        if (result.status === 'success') {
            result.data.forEach(item => {
                menuUl.innerHTML += `<li><a href="${item.link}">${item.title}</a></li>`;
            });
        }
    } catch (err) {
        showToast('Failed to load menu: ' + err.message, true);
    }
}

// 2. Read All Items (Asynchronous Inventory Load)
async function loadItems() {
    const loadingElem = document.getElementById('loadingIndicator');
    const tbody = document.getElementById('itemsTableBody');
    
    if (loadingElem) loadingElem.style.display = 'block';
    
    try {
        const res = await fetch(`${API_URL}?action=read`);
        const result = await res.json();
        
        if (loadingElem) loadingElem.style.display = 'none';
        tbody.innerHTML = '';

        if (result.status === 'success') {
            if (result.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#94a3b8; padding:20px;">No items in inventory.</td></tr>`;
                return;
            }
            result.data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td><strong>#${item.id}</strong></td>
                        <td>${item.name}</td>
                        <td><span class="badge-cat">${item.category}</span></td>
                        <td>$${parseFloat(item.price).toFixed(2)}</td>
                        <td>
                            <button class="btn-edit" onclick="editItem(${item.id}, '${item.name.replace(/'/g, "\\'")}', '${item.category.replace(/'/g, "\\'")}', ${item.price})">Edit</button>
                            <button class="btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            showToast(result.message || 'Error fetching records', true);
        }
    } catch (err) {
        if (loadingElem) loadingElem.style.display = 'none';
        showToast('Network error while loading items: ' + err.message, true);
    }
}

// 3. Create or Update Item (Zero Page Reload)
async function saveItem(event) {
    event.preventDefault(); // Prevent standard browser page reload
    
    const id = document.getElementById('itemId').value;
    const name = document.getElementById('itemName').value.trim();
    const category = document.getElementById('itemCategory').value.trim();
    const price = document.getElementById('itemPrice').value.trim();

    // Client-side validation check
    if (!name || !category || price === '') {
        showToast('Please fill out all required fields.', true);
        return;
    }

    const action = id ? 'update' : 'create';
    const payload = { id, name, category, price };
    
    try {
        const res = await fetch(`${API_URL}?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        
        if (result.status === 'success') {
            showToast(result.message);
            resetForm();
            loadItems();
        } else {
            showToast(result.message, true);
        }
    } catch (err) {
        showToast('Network error: ' + err.message, true);
    }
}

// 4. Populate Form for Editing (Update State)
function editItem(id, name, category, price) {
    document.getElementById('itemId').value = id;
    document.getElementById('itemName').value = name;
    document.getElementById('itemCategory').value = category;
    document.getElementById('itemPrice').value = price;
    
    document.getElementById('formTitle').innerText = 'Edit Item #' + id;
    document.getElementById('submitBtn').innerText = 'Update Item';
    document.getElementById('cancelBtn').style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// 5. Delete Item (Asynchronous with Confirmation)
async function deleteItem(id) {
    if (!confirm('Are you sure you want to delete item #' + id + '?')) return;
    
    try {
        const res = await fetch(`${API_URL}?action=delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await res.json();
        
        if (result.status === 'success') {
            showToast(result.message);
            loadItems();
        } else {
            showToast(result.message, true);
        }
    } catch (err) {
        showToast('Network error: ' + err.message, true);
    }
}

// Reset Form to Initial Add State
function resetForm() {
    document.getElementById('itemId').value = '';
    document.getElementById('itemForm').reset();
    document.getElementById('formTitle').innerText = 'Add Item';
    document.getElementById('submitBtn').innerText = 'Save Item';
    document.getElementById('cancelBtn').style.display = 'none';
}
