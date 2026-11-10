<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#691C32">
    <title>Página no encontrada | CONABIO</title>
    <style>
        :root { color-scheme: light; font-family: "Montserrat", "Segoe UI", Arial, sans-serif; color: #20242d; background: #f4f6f9; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; padding: 24px; display: grid; place-items: center; background: radial-gradient(circle at 12% 15%, #eadfe2 0, transparent 28%), #f4f6f9; }
        .error-card { display: grid; grid-template-columns: minmax(270px, .82fr) minmax(380px, 1.18fr); width: min(980px, 100%); min-height: 540px; overflow: hidden; border: 1px solid #e5e7eb; border-radius: 28px; background: #fff; box-shadow: 0 28px 80px rgba(35, 8, 17, .15); animation: arrive .55s cubic-bezier(.2,.8,.2,1) both; }
        .brand-panel { position: relative; display: flex; flex-direction: column; justify-content: space-between; padding: 38px; overflow: hidden; color: #fff; background: linear-gradient(145deg, #691c32, #380616 78%, #22040d); }
        .brand-panel::before { position: absolute; top: 42%; left: -95px; width: 320px; height: 320px; border: 1px solid rgba(212,193,156,.22); border-radius: 50%; box-shadow: 0 0 0 28px rgba(212,193,156,.04), 0 0 0 58px rgba(212,193,156,.035); content: ""; }
        .logo { position: relative; z-index: 1; width: 170px; height: auto; object-fit: contain; filter: brightness(0) invert(1) drop-shadow(0 4px 10px rgba(0,0,0,.18)); }
        .brand-copy { position: relative; z-index: 1; }
        .brand-copy strong { display: block; margin-bottom: 12px; color: #d4c19c; font-size: 11px; line-height: 1.6; letter-spacing: .14em; text-transform: uppercase; }
        .brand-copy p { max-width: 290px; margin: 0; color: #f2e8eb; font-size: 13px; line-height: 1.8; }
        .brand-foot { position: relative; z-index: 1; margin-top: 24px; color: rgba(255,255,255,.55); font-size: 10px; }
        .content { display: flex; flex-direction: column; align-items: flex-start; justify-content: center; padding: clamp(32px, 6vw, 68px); }
        .eyebrow { display: inline-flex; align-items: center; gap: 8px; margin: 0 0 14px; padding: 7px 11px; border: 1px solid #f0e4e7; border-radius: 999px; color: #691c32; background: #fcf7f8; font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #bc955c; box-shadow: 0 0 0 4px rgba(188,149,92,.14); }
        .scene { position: relative; display: grid; place-items: center; width: 100%; height: 125px; margin: 2px 0 15px; overflow: hidden; border: 1px solid #edf0f3; border-radius: 18px; background: linear-gradient(120deg,#f7f9f8,#fffaf2); }
        .scene svg { width: min(350px, 86%); height: 112px; }
        .path { stroke-dasharray: 7 8; animation: dash 12s linear infinite; }
        .leaf { transform-box: fill-box; transform-origin: center; animation: sway 3.8s ease-in-out infinite; }
        .leaf.second { animation-delay: -.9s; }
        .spark { animation: blink 2.2s ease-in-out infinite; }
        .code { margin: 0; color: #691c32; font-size: clamp(62px, 9vw, 84px); font-weight: 900; letter-spacing: -.09em; line-height: .95; }
        h1 { margin: 12px 0 0; color: #20242d; font-size: clamp(23px, 3vw, 30px); letter-spacing: -.04em; }
        .description { max-width: 430px; margin: 12px 0 22px; color: #667085; font-size: 14px; line-height: 1.75; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; }
        .button { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px; padding: 0 17px; border: 1px solid transparent; border-radius: 11px; font: inherit; font-size: 12px; font-weight: 800; text-decoration: none; cursor: pointer; transition: transform .18s ease, box-shadow .18s ease, background .18s ease; }
        .button:hover { transform: translateY(-2px); }
        .button:focus-visible, summary:focus-visible { outline: 3px solid #d4c19c; outline-offset: 3px; }
        .button-primary { color: #fff; background: #691c32; box-shadow: 0 7px 16px rgba(105,28,50,.2); }
        .button-primary:hover { background: #4d0a1f; box-shadow: 0 10px 22px rgba(105,28,50,.25); }
        .button-secondary { color: #4b5563; border-color: #d1d5db; background: #fff; }
        .button-secondary:hover { background: #f9fafb; }
        .help { width: 100%; max-width: 430px; margin-top: 23px; border-top: 1px solid #eef0f3; padding-top: 14px; color: #667085; font-size: 12px; }
        .help summary { display: inline-flex; align-items: center; gap: 7px; color: #691c32; font-weight: 800; cursor: pointer; list-style: none; }
        .help summary::-webkit-details-marker { display: none; }
        .help summary::after { content: "+"; display: grid; place-items: center; width: 18px; height: 18px; border-radius: 50%; color: #691c32; background: #f6eaee; font-size: 14px; transition: transform .2s ease; }
        .help[open] summary::after { content: "−"; transform: rotate(180deg); }
        .help p { margin: 10px 0 0; line-height: 1.7; animation: reveal .25s ease both; }
        @keyframes arrive { from { opacity: 0; transform: translateY(12px) scale(.99); } to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes dash { to { stroke-dashoffset: -120; } }
        @keyframes sway { 0%,100% { transform: rotate(-5deg) translateY(0); } 50% { transform: rotate(7deg) translateY(-3px); } }
        @keyframes blink { 0%,100% { opacity: .35; transform: scale(.85); } 50% { opacity: 1; transform: scale(1.12); } }
        @keyframes reveal { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 700px) {
            body { padding: 14px; }
            .error-card { grid-template-columns: 1fr; min-height: 0; border-radius: 22px; }
            .brand-panel { min-height: 120px; padding: 22px 25px; }
            .logo { width: 135px; }
            .brand-copy, .brand-foot { display: none; }
            .content { padding: 27px 24px 30px; }
            .scene { height: 108px; }
            .scene svg { height: 98px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <main class="error-card" aria-labelledby="error-title">
        <aside class="brand-panel" aria-label="CONABIO">
            <img class="logo" src="{{ asset('images/logo_conabio_transparente.png') }}" alt="CONABIO">
            <div class="brand-copy">
                <strong>Sistema de Seguimiento de Capacitación</strong>
                <p>Control de Profesionalización de las Personas Servidoras Públicas asignadas a la Comisión Nacional para el Conocimiento y Uso de la Biodiversidad.</p>
            </div>
            <span class="brand-foot">© {{ date('Y') }} CONABIO</span>
        </aside>

        <section class="content">
            <div class="scene" role="img" aria-label="Un sendero natural que busca su camino">
                <svg viewBox="0 0 360 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M18 101C70 96 74 59 124 71C164 81 173 106 220 91C270 75 275 34 342 28" stroke="#D9E4D9" stroke-width="18" stroke-linecap="round"/>
                    <path class="path" d="M18 101C70 96 74 59 124 71C164 81 173 106 220 91C270 75 275 34 342 28" stroke="#7A9B83" stroke-width="2.5" stroke-linecap="round"/>
                    <circle class="spark" cx="342" cy="28" r="8" fill="#D4C19C"/>
                    <circle cx="342" cy="28" r="15" stroke="#D4C19C" stroke-opacity=".35"/>
                    <g class="leaf" transform="translate(89 30)"><path d="M0 14C1 3 12-2 23 1C21 12 13 21 0 14Z" fill="#79A386"/><path d="M2 15L17 4" stroke="#F4F7F2" stroke-width="1.5" stroke-linecap="round"/></g>
                    <g class="leaf second" transform="translate(248 57) rotate(-25)"><path d="M0 12C1 3 10-2 20 1C18 11 11 18 0 12Z" fill="#BC955C"/><path d="M2 13L15 4" stroke="#FFF9EF" stroke-width="1.4" stroke-linecap="round"/></g>
                    <path d="M35 92V77M28 84C29 76 36 74 41 77C40 84 35 88 28 84Z" stroke="#6C8B70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M319 66V52M313 58C314 51 320 49 325 52C324 59 320 62 313 58Z" stroke="#6C8B70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="code" aria-hidden="true">404</p>
            <h1 id="error-title">¡Ups! Nos salimos del camino</h1>
            <p class="description">La página que buscas no aparece en el mapa. Puede que el enlace haya cambiado o tenga algún error. Te ayudamos a encontrar el camino de regreso.</p>
            <div class="actions">
                <button class="button button-secondary" type="button" onclick="if (history.length > 1) history.back(); else window.location.href='{{ route('login') }}'">
                    <span aria-hidden="true">←</span> Volver
                </button>
                <a class="button button-primary" href="{{ route('login') }}">
                    Ir al inicio de sesión <span aria-hidden="true">→</span>
                </a>
            </div>
            <details class="help">
                <summary>¿Necesitas una pista?</summary>
                <p>Si llegaste desde un enlace guardado, vuelve al inicio de sesión y entra de nuevo al sistema para abrir tu expediente.</p>
            </details>
        </section>
    </main>
</body>
</html>
