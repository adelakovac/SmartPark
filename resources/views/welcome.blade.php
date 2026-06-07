<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark — Smart Parking for Sarajevo</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, Arial, sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 48px;
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(15,23,42,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .brand-icon {
            width:36px; height:36px; background:#2563eb;
            border-radius:10px; display:flex; align-items:center;
            justify-content:center; font-size:18px;
        }
        .brand-text { font-size:22px; font-weight:700; color:white; }
        .nav-btns { display:flex; gap:12px; align-items:center; }
        .btn-ghost {
            padding:9px 20px; border-radius:10px; font-size:14px;
            font-weight:600; color:#94a3b8; text-decoration:none;
            border: 1px solid #334155; transition:all 0.15s;
        }
        .btn-ghost:hover { color:white; border-color:#64748b; }
        .btn-solid {
            padding:9px 20px; border-radius:10px; font-size:14px;
            font-weight:600; color:white; text-decoration:none;
            background:#2563eb; transition:all 0.15s;
        }
        .btn-solid:hover { background:#1d4ed8; }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center;
            padding: 120px 24px 80px;
            background: radial-gradient(ellipse at 50% 0%, rgba(37,99,235,0.15) 0%, transparent 70%);
        }
        .hero-inner { max-width: 760px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3);
            color: #60a5fa; padding: 6px 16px; border-radius: 20px;
            font-size: 13px; font-weight: 600; margin-bottom: 28px;
        }
        .hero-title {
            font-size: 64px; font-weight: 800; line-height: 1.1;
            margin-bottom: 20px; letter-spacing: -1px;
        }
        .hero-title span { color: #2563eb; }
        .hero-subtitle {
            font-size: 20px; color: #94a3b8; line-height: 1.6;
            margin-bottom: 40px; max-width: 560px; margin-left: auto; margin-right: auto;
        }
        .hero-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
        .btn-hero-primary {
            padding: 14px 32px; border-radius: 12px; font-size: 16px;
            font-weight: 700; color: white; text-decoration: none;
            background: #2563eb; transition: all 0.15s;
            box-shadow: 0 8px 24px rgba(37,99,235,0.4);
        }
        .btn-hero-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-hero-secondary {
            padding: 14px 32px; border-radius: 12px; font-size: 16px;
            font-weight: 700; color: white; text-decoration: none;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            transition: all 0.15s;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,0.12); }

        /* STATS */
        .stats-row {
            display: flex; justify-content: center; gap: 48px;
            padding: 48px 24px;
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.02);
        }
        .stat-item { text-align: center; }
        .stat-num { font-size: 36px; font-weight: 800; color: #2563eb; }
        .stat-lbl { font-size: 13px; color: #64748b; margin-top: 4px; }

        /* FEATURES */
        .features {
            padding: 96px 24px;
            max-width: 1100px; margin: 0 auto;
        }
        .section-label {
            text-align: center; font-size: 13px; font-weight: 700;
            color: #2563eb; text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .section-title {
            text-align: center; font-size: 40px; font-weight: 800;
            margin-bottom: 12px; line-height: 1.2;
        }
        .section-sub {
            text-align: center; font-size: 17px; color: #64748b;
            margin-bottom: 64px; max-width: 500px; margin-left: auto; margin-right: auto;
        }
        .features-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
        }
        .feature-card {
            background: #1e293b; border-radius: 16px; padding: 28px;
            border: 1px solid rgba(255,255,255,0.06);
            transition: transform 0.15s, border-color 0.15s;
        }
        .feature-card:hover { transform: translateY(-3px); border-color: rgba(37,99,235,0.3); }
        .feature-icon {
            width: 48px; height: 48px; background: rgba(37,99,235,0.15);
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; font-size: 22px; margin-bottom: 16px;
        }
        .feature-title { font-size: 17px; font-weight: 700; margin-bottom: 8px; }
        .feature-desc { font-size: 14px; color: #64748b; line-height: 1.6; }

        /* HOW IT WORKS */
        .how {
            padding: 96px 24px;
            background: rgba(255,255,255,0.02);
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .how-inner { max-width: 900px; margin: 0 auto; }
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 64px; }
        .step { text-align: center; }
        .step-num {
            width: 52px; height: 52px; background: #2563eb;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 20px; font-weight: 800;
            margin: 0 auto 16px;
        }
        .step-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .step-desc { font-size: 13px; color: #64748b; line-height: 1.5; }

        /* CTA */
        .cta {
            padding: 96px 24px; text-align: center;
        }
        .cta-card {
            background: linear-gradient(135deg, #1e3a8a, #1e293b);
            border-radius: 24px; padding: 64px;
            max-width: 700px; margin: 0 auto;
            border: 1px solid rgba(37,99,235,0.3);
        }
        .cta-title { font-size: 40px; font-weight: 800; margin-bottom: 16px; }
        .cta-sub { font-size: 17px; color: #94a3b8; margin-bottom: 36px; }

        /* FOOTER */
        .footer {
            padding: 32px 48px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex; justify-content: space-between; align-items: center;
        }
        .footer-brand { font-size: 16px; font-weight: 700; color: #475569; }
        .footer-text { font-size: 13px; color: #334155; }

        @media (max-width: 768px) {
            .hero-title { font-size: 40px; }
            .features-grid { grid-template-columns: 1fr; }
            .steps { grid-template-columns: 1fr 1fr; }
            .navbar { padding: 16px 24px; }
            .stats-row { gap: 24px; flex-wrap: wrap; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="/" class="brand">
        <div class="brand-icon">🅿</div>
        <span class="brand-text">SmartPark</span>
    </a>
    <div class="nav-btns">
        <a href="{{ route('login') }}" class="btn-ghost">Sign In</a>
        <a href="{{ route('register') }}" class="btn-solid">Get Started</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            🇧🇦 Built for Sarajevo
        </div>
        <h1 class="hero-title">
            Find & Reserve<br>
            <span>Parking Instantly</span>
        </h1>
        <p class="hero-subtitle">
            SmartPark shows you real-time parking availability across Sarajevo. Reserve your spot in seconds, no more circling the block.
        </p>
        <div class="hero-btns">
            <a href="{{ route('register') }}" class="btn-hero-primary">🅿 Find Parking Now</a>
            <a href="{{ route('login') }}" class="btn-hero-secondary">Sign In →</a>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="stats-row">
    <div class="stat-item">
        <div class="stat-num">8+</div>
        <div class="stat-lbl">Parking Locations</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">1,300+</div>
        <div class="stat-lbl">Parking Spots</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">24/7</div>
        <div class="stat-lbl">Live Availability</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">2min</div>
        <div class="stat-lbl">Average Reserve Time</div>
    </div>
</div>

<!-- FEATURES -->
<section class="features">
    <div class="section-label">Why SmartPark</div>
    <h2 class="section-title">Everything you need</h2>
    <p class="section-sub">No apps to download. No phone calls. Just open, find, and reserve.</p>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <div class="feature-title">Live Map View</div>
            <div class="feature-desc">See all parking locations on an interactive map with real-time availability. Green means free, red means full.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <div class="feature-title">Instant Reservation</div>
            <div class="feature-desc">Reserve a spot in one click. Choose your duration — 1, 2, 4 or 8 hours. Your spot is held until you arrive.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⏰</div>
            <div class="feature-title">Auto Expiry</div>
            <div class="feature-desc">Reservations automatically expire if unused. No manual management needed — spots free up on their own.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <div class="feature-title">Secure Accounts</div>
            <div class="feature-desc">Your reservations are tied to your account. Cancel anytime, view history, manage everything in one place.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🏢</div>
            <div class="feature-title">Multiple Locations</div>
            <div class="feature-desc">BBI Centar, Skenderija, Baščaršija and more — all major Sarajevo parking locations in one system.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💶</div>
            <div class="feature-title">Clear Pricing</div>
            <div class="feature-desc">See the hourly rate and opening hours for every location before you decide. No hidden fees or surprises.</div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how">
    <div class="how-inner">
        <div class="section-label">How it works</div>
        <h2 class="section-title" style="text-align:center;">Reserve in 4 steps</h2>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-title">Create Account</div>
                <div class="step-desc">Sign up for free in under a minute. No payment required.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-title">Browse the Map</div>
                <div class="step-desc">Open the live map and find a parking location near your destination.</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-title">Pick a Spot</div>
                <div class="step-desc">Choose an available spot and select how long you need it.</div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-title">Park & Go</div>
                <div class="step-desc">Your spot is reserved. Cancel anytime if your plans change.</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <div class="cta-card">
        <div class="cta-title">Ready to park smarter?</div>
        <div class="cta-sub">Join SmartPark today. It's completely free.</div>
        <a href="{{ route('register') }}" class="btn-hero-primary" style="display:inline-block;">
            Create Free Account →
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-brand">🅿 SmartPark</div>
    <div class="footer-text">© 2026 SmartPark · International Burch University · Senior Design Project</div>
</footer>

</body>
</html>