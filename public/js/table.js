document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('treeTable');
    const tbody = document.getElementById('tableBody');
    const searchInput = document.getElementById('searchInput');
    const entriesPerPage = document.getElementById('entriesPerPage');
    const statusFilter = document.getElementById('statusFilter');
    const pagination = document.getElementById('pagination');
    const entriesInfo = document.getElementById('entriesInfo');
    
    let originalRows = Array.from(tbody.querySelectorAll('tr'));
    let currentPage = 1;
    let sortDirection = {}; // Track sorting per column
    
    function filterRows() {
      const search = searchInput.value.toLowerCase();
      const status = statusFilter.value;
      
      return originalRows.filter(row => {
        const text = row.innerText.toLowerCase();
        const rowStatus = row.querySelector('td:nth-child(3) span')?.innerText.trim();
        return text.includes(search) && (status === "" || rowStatus === status);
      });
    }
    
    function renderTable() {
      const filteredRows = filterRows();
      const maxEntries = parseInt(entriesPerPage.value);
      const totalPages = Math.ceil(filteredRows.length / maxEntries);
      
      if (currentPage > totalPages) currentPage = totalPages || 1;
      
      tbody.innerHTML = "";
      
      const start = (currentPage - 1) * maxEntries;
      const end = currentPage * maxEntries;
      filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));
      
      const showingStart = filteredRows.length === 0 ? 0 : start + 1;
      const showingEnd = Math.min(end, filteredRows.length);
      entriesInfo.textContent = `Showing ${showingStart} to ${showingEnd} of ${filteredRows.length} entries`;
      
      renderPagination(totalPages);
    }
    
    function renderPagination(totalPages) {
      pagination.innerHTML = "";
      
      const createPageButton = (page, label = page, disabled = false) => {
        const li = document.createElement('li');
        li.className = `page-item ${disabled ? 'disabled' : ''}`;
        li.innerHTML = `<a class="page-link ${page === currentPage ? 'bg-primary-600 text-white' : 'bg-neutral-200 text-secondary-light'} fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="#">${label}</a>`;
        if (!disabled) {
          li.addEventListener('click', (e) => {
            e.preventDefault();
            currentPage = page;
            renderTable();
          });
        }
        return li;
      };
      
      if (totalPages > 1) {
        pagination.appendChild(createPageButton(currentPage - 1, '<', currentPage === 1));
        for (let i = 1; i <= totalPages; i++) {
          pagination.appendChild(createPageButton(i));
        }
        pagination.appendChild(createPageButton(currentPage + 1, '>', currentPage === totalPages));
      }
    }
    
    function sortTable(columnIndex) {
      const filteredRows = filterRows();
      
      sortDirection[columnIndex] = !sortDirection[columnIndex];
      const direction = sortDirection[columnIndex] ? 1 : -1;
      
      originalRows.sort((a, b) => {
        const aText = a.children[columnIndex]?.innerText.trim().toLowerCase() || '';
        const bText = b.children[columnIndex]?.innerText.trim().toLowerCase() || '';
        
        if (!isNaN(aText) && !isNaN(bText)) {
          return (parseFloat(aText) - parseFloat(bText)) * direction;
        }
        return aText.localeCompare(bText) * direction;
      });
      
      currentPage = 1;
      renderTable();
      
      updateSortIcons(columnIndex, direction);
    }
    
    function updateSortIcons(columnIndex, direction) {
      document.querySelectorAll('.sort-icon').forEach(icon => {
        icon.innerHTML = ''; // Clear all icons
      });
      
      const currentHeader = document.querySelector(`th[data-column="${columnIndex}"] .sort-icon`);
      if (direction === 1) {
        currentHeader.innerHTML = '↑'; // Ascending icon
      } else {
        currentHeader.innerHTML = '↓'; // Descending icon
      }
    }
    
    searchInput.addEventListener('input', () => { currentPage = 1; renderTable(); });
    entriesPerPage.addEventListener('change', () => { currentPage = 1; renderTable(); });
    statusFilter.addEventListener('change', () => { currentPage = 1; renderTable(); });
    
    document.querySelectorAll('th.sortable').forEach(th => {
      th.style.cursor = 'pointer';
      th.addEventListener('click', () => {
        sortTable(parseInt(th.dataset.column));
      });
    });
    
    renderTable();
  });
  