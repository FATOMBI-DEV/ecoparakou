document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.querySelector('input[name="q"]');
  const searchForm = document.querySelector('form[action="/recherche.php"]');
  const resultBox = document.createElement('div');
  resultBox.className = 'search-results';
  searchInput.parentNode.appendChild(resultBox);

  searchInput.addEventListener('keyup', () => {
    const query = searchInput.value.trim();
    if (query.length > 1) {
      fetch(`/includes/recherche.php?q=${encodeURIComponent(query)}`)
        .then(res => res.text())
        .then(html => {
          resultBox.innerHTML = html;
        });
    } else {
      resultBox.innerHTML = '';
    }
  });

  document.addEventListener('click', (e) => {
    if (!searchForm.contains(e.target)) {
      resultBox.innerHTML = '';
    }
  });
});