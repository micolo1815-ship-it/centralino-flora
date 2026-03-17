const mainLocDropArea = document.getElementById('main-loc-drop-area');
const mainLocInput = document.getElementById('MainLocImage');
const mainLocPreview = document.getElementById('mainLocImagePreview');

// Drag events
mainLocDropArea.addEventListener('dragover', (e) => {
  e.preventDefault();
  mainLocDropArea.classList.add('drag-over');
});

mainLocDropArea.addEventListener('dragleave', () => {
  mainLocDropArea.classList.remove('drag-over');
});

mainLocDropArea.addEventListener('drop', (e) => {
  e.preventDefault();
  mainLocDropArea.classList.remove('drag-over');
  const files = e.dataTransfer.files;
  if (files.length > 0) {
    mainLocInput.files = files;
    previewMainImage(files[0]);
  }
});

mainLocInput.addEventListener('change', () => {
  if (mainLocInput.files.length > 0) {
    previewMainImage(mainLocInput.files[0]);
  }
});

function previewMainImage(file) {
  if (!file.type.startsWith('image/')) return;

  const reader = new FileReader();
  reader.onload = e => {
    mainLocPreview.innerHTML = ''; // Clear any previous image

    const col = document.createElement('div');
    col.className = 'col-12 col-md-4 position-relative';

    const img = document.createElement('img');
    img.src = e.target.result;
    img.className = 'img-fluid';
    img.alt = 'Main image preview';

    const closeBtn = document.createElement('button');
    closeBtn.className = 'close-btn';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => {
      mainLocInput.value = '';
      mainLocPreview.innerHTML = '';
    });

    col.appendChild(img);
    col.appendChild(closeBtn);
    mainLocPreview.appendChild(col);
  };

  reader.readAsDataURL(file);
}
