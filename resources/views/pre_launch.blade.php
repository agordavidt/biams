<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Benue Agriculture Gateway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Page layout */
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(110deg,#f6f7fb 0%, #eef2f6 100%);
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      padding: 2rem;
      border: solid rgb(90, 172, 90) 2em; /* Agriculture theme border */
    }
    .card-gate {
      max-width: 700px;
      width: 100%;
      box-shadow: 0 8px 30px rgba(20,20,40,0.06);
      border-radius: .75rem;
      overflow: hidden;
    }
    .left-photo {
      background: url('{{ asset('frontend/assets/img/farm_photo.jpeg') }}') center/cover no-repeat;
      min-height: 360px;
    }
    .login-col {
      padding: 2.2rem;
      background: #fff;
    }
    .logo {
      height: 48px;
      width: 48px;
      border-radius: 8px;
      display: inline-block;
      background: linear-gradient(135deg,#0e8048,#0e8048);
      margin-right: .75rem;
    }
    .protected-content {
      display: none;
      margin-top: 1.2rem;
    }
    @keyframes fadeIn {
      from {opacity:0; transform: translateY(6px);}
      to {opacity:1; transform: translateY(0);}
    }
    .small-muted { 
      font-size: .7rem; 
      color: #6c757d; 
    }
    .powered-by {
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f5f5f5;
      padding: 15px;
      border-top: 2px solid #003366;
      font-family: Arial, sans-serif;
    }
    .powered-by img {
      height: 50px;
      margin-right: 10px;
    }
    .powered-by span {
      font-size: 16px;
      color: #003366;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="card card-gate d-flex flex-row">
    <div class="left-photo d-none d-md-block" style="width:48%;"></div>

    <div class="login-col" style="width:52%;">
      <div class="d-flex align-items-center mb-3">
        <div class="logo" aria-hidden="true"></div>
        <div>
          <h5 class="mb-0">Benue Agriculture Gateway</h5>
          <div class="small-muted">The Smart Agriculture System is in Pre-Launch Mode.</div>
        </div>
        <div id="protected" class="protected-content"></div>
      </div>

      <div class="mb-3">
        <button id="btnWelcome" type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#welcomeModal">
          About The Platform
        </button>
      </div>

      <form id="passwordForm" class="mb-3" novalidate>
        <label for="passwordInput" class="form-label">Enter Launch Key</label>
        <div class="input-group mb-2">
          <input id="passwordInput" type="password" class="form-control" placeholder="Launch Key" aria-label="Launch Key" required>
          <button id="togglePwd" class="btn btn-outline-secondary" type="button" title="Show / hide launch key" aria-pressed="false">Show</button>
        </div>
        <div id="pwdFeedback" class="form-text small-muted">Launch Key is Required to Access the System</div>

        <div class="d-flex gap-2 mt-3">
          <button id="btnUnlock" class="btn btn-primary" type="submit">Access System</button>
          <button id="btnReset" class="btn btn-secondary" type="button">Reset</button>
        </div>
      </form>

      <div id="preview" class="mt-3">
        <hr>
        <p class="small-muted mb-0" style="text-align: justify; font-size: 14px;">
          This platform aims to modernize agriculture in Benue State through accurate data capture, transparency, and innovation.
        </p>
      </div>

      <footer class="powered-by">
        <img src="{{ asset('frontend/assets/img/bdicl.jpg') }}" alt="BDIC Logo">
        <span style="text-align: center; font-size: 10px;">Powered by Benue Digital Infrastructure Company (BDIC)</span>
      </footer>
    </div>
  </div>

  <!-- Welcome Modal -->
  <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="welcomeModalLabel">Benue State Smart Agricultural System and Data Management Platform</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p style="text-align: justify;">
            The Benue State Smart Agricultural System and Data Management Platform is a digital solution designed to modernize agriculture in Benue State through accurate data capture, transparency, and innovation. The platform registers and profiles every farmer, cooperative, and farm enterprise, creating a reliable database for planning, investment, and service delivery.
          </p>
          <p style="text-align: justify;">This system integrates several core modules for efficient management and service delivery:</p>
          <ul>
            <li><strong>Farmer Registration and Data Capture:</strong> Captures structured, verified farmer and farm data, including geolocation.</li>
            <li><strong>Input Marketplace:</strong> Provides farmers access to inputs with installment and wallet support.</li>
            <li><strong>Distribution and Logistics Tracking:</strong> Aggregates demand per LGA and manages delivery manifests.</li>
            <li><strong>Cooperative and Association Registration:</strong> Automates cooperative and association registration with certification.</li>
            <li><strong>Data Analytics and Reporting:</strong> Provides analytics and insights for decision-making, including geospatial visualization.</li>
            <li><strong>Mobile Apps:</strong> Ensures accessibility for agents and clients.</li>
          </ul>
        </div>
        <div class="modal-footer border-0">
          <footer class="powered-by">
            <img src="{{ asset('frontend/assets/img/bdicl.jpg') }}" alt="BDIC Logo">
            <span style="text-align: center;">Powered by Benue Digital Infrastructure Company (BDIC)</span>
          </footer>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // --- Pre-launch access control (client-side only) ---
    const DEMO_PASSWORD = "AgriPortalTest"; // Change this before launch

    const passwordInput = document.getElementById('passwordInput');
    const togglePwd = document.getElementById('togglePwd');
    const passwordForm = document.getElementById('passwordForm');
    const pwdFeedback = document.getElementById('pwdFeedback');
    const btnReset = document.getElementById('btnReset');

    function unlockContent() {
      sessionStorage.setItem('access_granted', '1');
      window.location.href = "{{ route('welcome') }}";
    }

    // Auto-redirect if already granted
    if (sessionStorage.getItem('access_granted') === '1') {
      window.location.href = "{{ route('welcome') }}";
    }

    // Toggle password visibility
    togglePwd.addEventListener('click', () => {
      const isHidden = passwordInput.type === 'password';
      passwordInput.type = isHidden ? 'text' : 'password';
      togglePwd.textContent = isHidden ? 'Hide' : 'Show';
      togglePwd.setAttribute('aria-pressed', String(!isHidden));
      passwordInput.focus();
    });

    // Form submission
    passwordForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const val = passwordInput.value.trim();

      if (!val) {
        pwdFeedback.textContent = 'Please enter the Launch Key.';
        pwdFeedback.classList.add('text-danger');
        return;
      }

      if (val === DEMO_PASSWORD) {
        pwdFeedback.textContent = 'Launch Key accepted. Redirecting to System Portal...';
        pwdFeedback.classList.remove('text-danger');
        pwdFeedback.classList.add('text-success');
        document.getElementById('btnUnlock').disabled = true;
        setTimeout(unlockContent, 600);
      } else {
        pwdFeedback.textContent = 'Incorrect Launch Key. Please try again or contact the administrator.';
        pwdFeedback.classList.remove('text-success');
        pwdFeedback.classList.add('text-danger');
        passwordInput.select();
      }
    });

    // Reset button
    btnReset.addEventListener('click', () => {
      passwordInput.value = '';
      pwdFeedback.textContent = 'Launch Key cleared. Launch Key is Required to Access the System';
      pwdFeedback.classList.remove('text-danger', 'text-success');
      passwordInput.focus();
    });

    // Focus input after modal closes
    document.getElementById('btnWelcome').addEventListener('hidden.bs.modal', () => {
      setTimeout(() => passwordInput.focus(), 300);
    });

    // Allow Enter key to submit
    document.addEventListener('keydown', (ev) => {
      if (ev.key === 'Enter' && document.activeElement.tagName !== 'TEXTAREA') {
        ev.preventDefault();
        passwordForm.dispatchEvent(new Event('submit', { cancelable: true }));
      }
    });
  </script>
</body>
</html>