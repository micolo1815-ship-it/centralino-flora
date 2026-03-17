const treeFacts = document.getElementById('treefactbullet');
const taggedTrees = document.getElementById('taggedtreesbullet');

function addBulletHandler(textarea) {
  textarea.addEventListener('focus', () => {
    if (textarea.value.trim() === '') {
      textarea.value = '• ';
    }
  });

  textarea.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const cursorPos = textarea.selectionStart;
      const textBefore = textarea.value.substring(0, cursorPos);
      const textAfter = textarea.value.substring(cursorPos);
      const newText = textBefore + '\n• ' + textAfter;
      textarea.value = newText;

      textarea.selectionStart = textarea.selectionEnd = cursorPos + 3;
    }
  });
}

addBulletHandler(treeFacts);
addBulletHandler(taggedTrees);