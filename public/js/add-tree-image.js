function handleBulletInput(textarea) {
  if (!textarea) return; // ✅ guard — element may not exist on edit page

  textarea.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const start = this.selectionStart;
      const end = this.selectionEnd;
      const bulletText = "\n• ";
      this.value = this.value.substring(0, start) + bulletText + this.value.substring(end);
      this.selectionStart = this.selectionEnd = start + bulletText.length;
    }
  });

  textarea.addEventListener('focus', function() {
    if (this.value.trim() === '') {
      this.value = '• ';
    }
  });
}

// ✅ Guard — only run if element exists (add page uses these IDs)
handleBulletInput(document.getElementById('treefactbullet'));
handleBulletInput(document.getElementById('taggedtreesbullet'));


// ========================
// Gallery Upload
// ========================
const dropArea  = document.getElementById('drop-area');
const input     = document.getElementById('fileElem');
const preview   = document.getElementById('galleryPreview');

if (dropArea && input && preview) {

  dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.classList.add('drag-over');
  });

  dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('drag-over');
  });

  dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.classList.remove('drag-over');
    const files = e.dataTransfer.files;
    input.files = files;
    appendImagePreviews(files); // ✅ append, not replace
  });

  input.addEventListener('change', () => {
    appendImagePreviews(input.files); // ✅ append, not replace
  });

}

// ✅ Renamed to appendImagePreviews — does NOT clear existing previews
function appendImagePreviews(files) {
  const preview = document.getElementById('galleryPreview');
  if (!preview) return;

  [...files].forEach(file => {
    if (!file.type.startsWith('image/')) return;

    const reader = new FileReader();
    reader.onload = e => {
      const col = document.createElement('div');
      col.className = 'col-6 col-md-4 col-lg-3 position-relative';

      const img = document.createElement('img');
      img.src = e.target.result;
      img.className = 'img-fluid';
      img.alt = 'preview';

      const closeBtn = document.createElement('button');
      closeBtn.type = 'button';
      closeBtn.className = 'close-btn';
      closeBtn.innerHTML = '&times;';
      closeBtn.addEventListener('click', function() {
        col.remove();
      });

      col.appendChild(img);
      col.appendChild(closeBtn);
      preview.appendChild(col); // ✅ append to existing content
    };
    reader.readAsDataURL(file);
  });
}


// ========================
// Main / Cover Image Upload
// ========================
const mainDropArea = document.getElementById('main-drop-area');
const mainInput    = document.getElementById('mainFileElem');
const mainPreview  = document.getElementById('mainImagePreview');

if (mainDropArea && mainInput && mainPreview) {

  mainDropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    mainDropArea.classList.add('drag-over');
  });

  mainDropArea.addEventListener('dragleave', () => {
    mainDropArea.classList.remove('drag-over');
  });

  mainDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    mainDropArea.classList.remove('drag-over');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
      mainInput.files = files;
      previewMainImage(files[0]);
    }
  });

  mainInput.addEventListener('change', () => {
    if (mainInput.files.length > 0) {
      previewMainImage(mainInput.files[0]);
    }
  });

}

function previewMainImage(file) {
  const mainPreview = document.getElementById('mainImagePreview');
  if (!mainPreview || !file || !file.type.startsWith('image/')) return;

  const reader = new FileReader();
  reader.onload = e => {
    mainPreview.innerHTML = ''; // ✅ clear old cover preview — only 1 cover allowed

    const col = document.createElement('div');
    col.className = 'col-12 col-md-4 position-relative';
    col.id = 'coverPreviewItem'; // ✅ keep id so removeCoverImage() still works

    const img = document.createElement('img');
    img.src = e.target.result;
    img.className = 'img-fluid';
    img.alt = 'Main image preview';

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'close-btn';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => {
      const removeCoverInput = document.getElementById('removeCoverInput');
      if (removeCoverInput) removeCoverInput.value = '1'; // ✅ flag for removal
      const mainInput = document.getElementById('mainFileElem');
      if (mainInput) mainInput.value = '';
      mainPreview.innerHTML = '';
    });

    col.appendChild(img);
    col.appendChild(closeBtn);
    mainPreview.appendChild(col);
  };

  reader.readAsDataURL(file);
}