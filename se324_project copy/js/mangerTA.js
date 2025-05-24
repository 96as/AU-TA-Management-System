// JS-Team/managerTA.js
console.log("Loaded managerTA.js — fetching TAs now…");

// 1) ELEMENTS
const summaryContainer = document.getElementById('summary-cards');
const tbody            = document.getElementById('ta-table-body');
const footer           = document.getElementById('table-footer');
const addBtn           = document.getElementById('add-ta');
const removeBtn        = document.getElementById('remove-ta');
const selectAll        = document.getElementById('select-all');
const modal            = document.getElementById('ta-modal');
const closeModalBtn    = document.getElementById('close-modal-btn');
const saveBtn          = document.getElementById('save-btn');
const inputName        = document.getElementById('ta-name');
const inputId          = document.getElementById('ta-id');
const inputEmail       = document.getElementById('ta-email');
const inputYear        = document.getElementById('ta-year');

// 2) STATE
let taList = [];

// 3) RENDER
function render() {
  const total = taList.length;
  summaryContainer.innerHTML = `
    <div class="card">
      <i class="fas fa-users icon"></i>
      <div class="info">
        <div class="value">${total}</div>
        <div class="label">Total TAs</div>
      </div>
    </div>`;
  tbody.innerHTML = '';
  taList.forEach((ta, i) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="checkbox" class="select-ta" data-index="${i}"></td>
      <td>${ta.name}</td>
      <td>${ta.id}</td>
      <td>${ta.email}</td>
      <td>${ta.year}</td>`;
    tbody.appendChild(tr);
  });
  footer.textContent = `Showing ${total} TAs in the system`;
}

// 4) FETCH ALL
function fetchTAsFromDatabase() {
  fetch('/se324_project copy/php/get_TAs.php')
    .then(r => r.json())
    .then(arr => {
      taList = Array.isArray(arr) ? arr : [];
      console.log("get_TAs.php returned:", arr);
      render();
    })
    .catch(err => {
      console.error('Fetch TAs failed', err);
      taList = [];
      render();
    });
}

// 5) ADD TA
function openModal()  { modal.classList.add('active'); }
function closeModal() {
  modal.classList.remove('active');
  inputName.value = inputId.value = inputEmail.value = inputYear.value = '';
}
saveBtn.addEventListener('click', () => {
  const name = inputName.value.trim();
  const id   = inputId.value.trim();
  const mail = inputEmail.value.trim();
  const yr   = inputYear.value.trim();
  if (!name||!id||!mail||!yr) return alert('All fields required');
  fetch('/se324_project copy/php/add_TAs.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: `id=${encodeURIComponent(id)}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(mail)}&year=${encodeURIComponent(yr)}`
  })
  .then(r => r.text())
  .then(t => {
    if (t.trim()==='Success') {
      fetchTAsFromDatabase();
      closeModal();
    } else alert('Add failed: '+t);
  })
  .catch(e => { console.error(e); alert('Error adding TA'); });
});

// 6) REMOVE SELECTED TAs (now deletes in DB)
removeBtn.addEventListener('click', () => {
  console.log("🗑 Remove TA clicked");
  // 1) which checkboxes are checked?
  const checks = Array.from(document.querySelectorAll('.select-ta:checked'));
  console.log("Checked boxes:", checks);
  if (checks.length === 0) {
    return alert('Please select at least one TA to remove');
  }

  // 2) map checkbox → taList index → actual TA id
  const idsToRemove = checks
    .map(cb => {
      const idx = parseInt(cb.dataset.index, 10);
      return taList[idx]?.id;
    })
    .filter(Boolean);
  console.log("IDs to remove:", idsToRemove);

  // Get names of selected TAs
  const selectedTAs = checks
    .map(cb => {
      const idx = parseInt(cb.dataset.index, 10);
      return taList[idx]?.name;
    })
    .filter(Boolean);

  // Add confirmation dialog with TA names
  if (!confirm(`Are you sure you want to remove the following TA(s)?\n\n${selectedTAs.join('\n')}\n\nThis action cannot be undone.`)) {
    return;
  }

  // 3) POST to delete_TA.php
  fetch('/se324_project copy/php/delete_TAs.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `ids=${encodeURIComponent(idsToRemove.join(','))}`
  })
    .then(res => res.text().then(txt => ({ ok: res.ok, txt })))
    .then(({ ok, txt }) => {
      console.log("Delete response:", txt);
      if (!ok) throw new Error(txt);
      if (txt.trim() === 'Success') {
        // 4) update front-end list & re-render
        taList = taList.filter(ta => !idsToRemove.includes(ta.id));
        
      } else {
        alert('Delete failed: ' + txt);
      }
      fetchTAsFromDatabase();
    })
    .catch(err => {
      console.error('Delete error:', err);
      alert('An error occurred deleting TAs; check console or delete_log.txt');
    });

});


// 7) INIT & BIND
addBtn.addEventListener('click', openModal);
closeModalBtn.addEventListener('click', closeModal);
selectAll.addEventListener('change', e=>{
  document.querySelectorAll('.select-ta')
          .forEach(cb=>cb.checked=e.target.checked);
});
window.addEventListener('DOMContentLoaded', fetchTAsFromDatabase);

// Allow closing modal by clicking outside modal-content
modal.addEventListener('click', function(e) {
  if (e.target === modal) closeModal();
});
// Allow closing modal with Escape key
window.addEventListener('keydown', function(e) {
  if (modal.classList.contains('active') && e.key === 'Escape') closeModal();
});
