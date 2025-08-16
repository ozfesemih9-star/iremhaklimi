<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>İrem her zaman haklı mı?</title>
  <style>
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    body {
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      background: #f7f7fb;
      color: #111;
      user-select: none;
    }
    .wrap {
      position: relative;
      min-height: 100vh;
      overflow: hidden;
      display: grid;
      place-items: center;
      padding: 24px;
    }
    .card {
      text-align: center;
      padding: 28px 24px 80px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,.08);
      position: relative;
      width: min(680px, 92vw);
    }
    h1 {
      margin: 0 0 20px;
      font-size: clamp(22px, 3vw + 12px, 36px);
      letter-spacing: 0.2px;
    }
    .arena {
      position: relative;
      height: 46vh;            /* kaçma alanı */
      min-height: 260px;
      max-height: 520px;
      margin-top: 8px;
      border-radius: 12px;
      background: #f0f2ff;
      outline: 1px dashed #d2d5ff;
      outline-offset: -8px;
    }
    button {
      position: absolute;
      padding: 12px 18px;
      font-size: 18px;
      border-radius: 999px;
      border: 0;
      cursor: pointer;
      box-shadow: 0 6px 18px rgba(0,0,0,.08);
      transition: transform .06s ease;
      white-space: nowrap;
    }
    #evet {
      background: #111;
      color: #fff;
    }
    #hayir {
      background: #ffffff;
      color: #111;
      border: 1px solid #e5e5e5;
    }
    button:active { transform: scale(.98); }
    .notice {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      bottom: 14px;
      opacity: .7;
      font-size: 12px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card" role="dialog" aria-labelledby="q">
      <h1 id="q">İrem her zaman haklı mı?</h1>

      <div class="arena" id="arena" aria-live="polite">
        <button id="evet" type="button" aria-label="Evet">Evet</button>
        <button id="hayir" type="button" aria-label="Hayır">Hayır</button>
        <div class="notice">İpucu: Evet yakalanmıyor 😅</div>
      </div>
    </div>
  </div>

  <script>
    const arena = document.getElementById('arena');
    const evet  = document.getElementById('evet');
    const hayir = document.getElementById('hayir');

    // Başlangıç: Hayır ortada, Evet yakınlarında
    function placeInitially() {
      const { width: aw, height: ah } = arena.getBoundingClientRect();
      const ev = evet.getBoundingClientRect();
      const hy = hayir.getBoundingClientRect();

      const centerX = (aw - hy.width) / 2;
      const centerY = (ah - hy.height) / 2;
      hayir.style.left = `${centerX}px`;
      hayir.style.top  = `${centerY}px`;

      // Evet'i biraz yukarı-sağa koy
      const ex = Math.min(aw - ev.width - 12, Math.max(12, centerX + 120));
      const ey = Math.min(ah - ev.height - 12, Math.max(12, centerY - 90));
      evet.style.left = `${ex}px`;
      evet.style.top  = `${ey}px`;
    }

    // Evet butonunu güvenli alanda rastgele konumlandır
    function moveEvet() {
      const pad = 8; // kenarlardan boşluk
      const a = arena.getBoundingClientRect();
      const b = evet.getBoundingClientRect();

      const maxX = a.width - b.width - pad;
      const maxY = a.height - b.height - pad;

      // Tamamen farklı bir yere kaçma (son konumdan uzak tutmaya çalış)
      const prevX = parseFloat(evet.style.left || 0);
      const prevY = parseFloat(evet.style.top  || 0);

      let x, y, tries = 0;
      do {
        x = pad + Math.random() * maxX;
        y = pad + Math.random() * maxY;
        tries++;
        // önceki konumdan yeterince uzak olsun (80px)
      } while (Math.hypot((x - prevX), (y - prevY)) < 80 && tries < 10);

      evet.style.left = `${x}px`;
      evet.style.top  = `${y}px`;
    }

    // Evet'i sürekli yaklaştırınca kaçır
    const evasionEvents = ['mouseover', 'mouseenter', 'pointerover', 'touchstart', 'focus'];
    evasionEvents.forEach(evt => evet.addEventListener(evt, moveEvet, { passive: true }));

    // Hayır'a tıklanabilir tek cevap
    hayir.addEventListener('click', () => {
      alert('Doğru cevap! 😄');
    });

    // Pencere boyutu değişince sınırlar güncellensin
    window.addEventListener('resize', () => {
      // butonlar alandan taşarsa içeri al
      const a = arena.getBoundingClientRect();
      [evet, hayir].forEach(btn => {
        const r = btn.getBoundingClientRect();
        const left = Math.min(Math.max(0, r.left - a.left), a.width - r.width);
        const top  = Math.min(Math.max(0, r.top  - a.top ), a.height - r.height);
        btn.style.left = `${left}px`;
        btn.style.top  = `${top}px`;
      });
    });

    // İlk yerleşim
    window.requestAnimationFrame(placeInitially);
  </script>
</body>
</html>
