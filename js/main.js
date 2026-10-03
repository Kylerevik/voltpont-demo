const PHONE_PATTERN = /^(\+36|06)\d{8,9}$/;

const form = document.getElementById('quote-form');
const phoneField = document.getElementById('phone');
const statusBox = document.getElementById('form-status');

function isValidPhone(value) {
  return PHONE_PATTERN.test(value.replace(/[\s\-\/()]/g, ''));
}

function showStatus(type, message) {
  const alert = document.createElement('div');
  alert.className = `alert alert-${type}`;
  alert.textContent = message;
  statusBox.replaceChildren(alert);
}

function describeErrors(result) {
  const details = Object.values(result.errors ?? {});
  return [result.message, ...details].join(' ');
}

async function sendQuote(submitButton) {
  submitButton.disabled = true;
  try {
    const response = await fetch(form.action, { method: 'POST', body: new FormData(form) });
    const result = await response.json();

    if (result.ok) {
      showStatus('success', result.message);
      form.reset();
      form.classList.remove('was-validated');
    } else {
      showStatus('danger', describeErrors(result));
    }
  } catch {
    showStatus('danger', 'A küldés nem sikerült. Kérjük, próbálja újra, vagy hívjon minket telefonon.');
  } finally {
    submitButton.disabled = false;
  }
}

function initQuoteForm() {
  phoneField.addEventListener('input', () => {
    phoneField.setCustomValidity(isValidPhone(phoneField.value) ? '' : 'invalid');
  });

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    phoneField.setCustomValidity(isValidPhone(phoneField.value) ? '' : 'invalid');
    form.classList.add('was-validated');

    if (form.checkValidity()) {
      sendQuote(event.submitter);
    }
  });
}

function initServiceLinks() {
  const serviceSelect = document.getElementById('service');

  document.querySelectorAll('[data-service]').forEach((link) => {
    link.addEventListener('click', () => {
      serviceSelect.value = link.dataset.service;
    });
  });
}

function initReferenceFilter() {
  const buttons = document.querySelectorAll('[data-filter]');
  const items = document.querySelectorAll('[data-category]');

  buttons.forEach((button) => {
    button.addEventListener('click', () => {
      const filter = button.dataset.filter;

      buttons.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));
      items.forEach((item) => {
        item.hidden = filter !== 'all' && item.dataset.category !== filter;
      });
      items[0].parentElement.scrollLeft = 0;
    });
  });
}

function initNavbar() {
  const menu = document.getElementById('main-nav');

  menu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => bootstrap.Collapse.getInstance(menu)?.hide());
  });
}

initQuoteForm();
initServiceLinks();
initReferenceFilter();
initNavbar();
