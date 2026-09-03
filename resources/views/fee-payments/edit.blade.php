@extends('layouts.admin')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,300;0,400;0,500;0,600;1,300&family=Syne:wght@400;600;700;800&family=Instrument+Serif:ital@1&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   GOD MODE · FEE PAYMENT EDIT
   Aesthetic: Obsidian Terminal × Swiss Precision
   Palette:   Deep Obsidian + Neon Mint + Receipt Cream
   ═══════════════════════════════════════════════════ */

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --obs:        #0D0F0E;
    --obs-2:      #131614;
    --obs-3:      #191C1A;
    --obs-4:      #222825;
    --obs-5:      #2C332F;
    --line:       rgba(255,255,255,0.06);
    --line-2:     rgba(255,255,255,0.11);
    --line-3:     rgba(255,255,255,0.18);
    --muted:      #5A6660;
    --text:       #D8E8E2;
    --text-dim:   #8AA89E;
    --mint:       #2DFFC4;
    --mint-dim:   rgba(45,255,196,0.1);
    --mint-glow:  rgba(45,255,196,0.06);
    --mint-ring:  rgba(45,255,196,0.2);
    --amber:      #FFB830;
    --amber-dim:  rgba(255,184,48,0.1);
    --ruby:       #FF4D6A;
    --ruby-dim:   rgba(255,77,106,0.1);
    --cream:      #F5F0E8;
    --cream-2:    #EDE6D8;

    --font-mono:  'IBM Plex Mono', 'Courier New', monospace;
    --font-ui:    'Syne', sans-serif;
    --font-serif: 'Instrument Serif', Georgia, serif;

    --radius:     4px;
    --radius-lg:  10px;
    --radius-xl:  18px;
    --ease:       cubic-bezier(0.4, 0, 0.2, 1);
    --spring:     cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ── PAGE ────────────────────────────────────── */
.ep-page {
    background: var(--obs);
    min-height: 100vh;
    font-family: var(--font-mono);
    color: var(--text);
    position: relative;
    overflow-x: hidden;
    padding-bottom: 6rem;
}

/* scan-line overlay */
.ep-page::before {
    content: '';
    position: fixed;
    inset: 0;
    background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent 2px,
        rgba(45,255,196,0.008) 2px,
        rgba(45,255,196,0.008) 4px
    );
    pointer-events: none;
    z-index: 0;
}

/* ambient glow top-left */
.ep-page::after {
    content: '';
    position: fixed;
    top: -20%;
    left: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(45,255,196,0.04) 0%, transparent 65%);
    pointer-events: none;
    z-index: 0;
}

.ep-wrap {
    position: relative;
    z-index: 1;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* ── STICKY COMMAND HEADER ───────────────────── */
.ep-topbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(13,15,14,0.9);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid var(--line-2);
    padding: 0;
    animation: topIn 0.35s var(--ease) both;
}

@keyframes topIn {
    from { opacity: 0; transform: translateY(-100%); }
    to   { opacity: 1; transform: translateY(0); }
}

.ep-topbar-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

.ep-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 10px;
    letter-spacing: 0.1em;
    color: var(--muted);
    text-transform: uppercase;
}

.ep-breadcrumb a { color: var(--muted); text-decoration: none; transition: color 0.2s; }
.ep-breadcrumb a:hover { color: var(--text); }
.ep-breadcrumb .sep { opacity: 0.3; }
.ep-breadcrumb .current { color: var(--mint); }

.ep-receipt-num {
    font-size: 11px;
    letter-spacing: 0.12em;
    color: var(--mint);
    background: var(--mint-dim);
    border: 1px solid var(--mint-ring);
    padding: 3px 12px;
    border-radius: var(--radius);
}

.ep-topbar-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── BUTTONS ─────────────────────────────────── */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--radius);
    font-family: var(--font-mono);
    font-size: 10.5px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.22s var(--ease);
    white-space: nowrap;
}

.btn svg { width: 13px; height: 13px; }

.btn-ghost {
    background: transparent;
    color: var(--text-dim);
    border: 1px solid var(--line-2);
}
.btn-ghost:hover { background: var(--obs-4); color: var(--text); border-color: var(--line-3); }

.btn-mint {
    background: var(--mint);
    color: var(--obs);
    font-weight: 600;
}
.btn-mint:hover { background: #50FFCE; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(45,255,196,0.25); }
.btn-mint:active { transform: translateY(0); }
.btn-mint:disabled { opacity: 0.45; pointer-events: none; }

.btn-ruby {
    background: transparent;
    color: var(--ruby);
    border: 1px solid rgba(255,77,106,0.3);
}
.btn-ruby:hover { background: var(--ruby-dim); border-color: var(--ruby); }

/* ── RECEIPT HEADER ──────────────────────────── */
.receipt-header {
    margin: 2.5rem 0 2rem;
    padding: 2rem;
    background: var(--obs-2);
    border: 1px solid var(--line-2);
    border-top: 2px solid var(--mint);
    border-radius: var(--radius-xl);
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 2rem;
    align-items: center;
    animation: receiptIn 0.5s 0.08s var(--ease) both;
    position: relative;
    overflow: hidden;
}

.receipt-header::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 300px; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(45,255,196,0.03));
    pointer-events: none;
}

@keyframes receiptIn {
    from { opacity: 0; transform: translateY(-12px); }
    to   { opacity: 1; transform: translateY(0); }
}

.receipt-icon {
    width: 60px; height: 60px;
    background: var(--mint-dim);
    border: 1px solid var(--mint-ring);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
}

.receipt-meta {}
.receipt-eyebrow {
    font-size: 9.5px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 0.4rem;
}

.receipt-title {
    font-family: var(--font-ui);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.02em;
    margin-bottom: 0.3rem;
}

.receipt-subtitle {
    font-size: 11px;
    color: var(--text-dim);
    letter-spacing: 0.04em;
}

.receipt-stats {
    display: flex;
    gap: 2rem;
}

.receipt-stat {
    text-align: right;
}

.receipt-stat-label {
    font-size: 9.5px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 0.3rem;
}

.receipt-stat-val {
    font-family: var(--font-ui);
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.02em;
}

.receipt-stat-val.mint { color: var(--mint); }

/* ── VALIDATION BOX ──────────────────────────── */
.validation-box {
    background: var(--ruby-dim);
    border: 1px solid rgba(255,77,106,0.3);
    border-left: 3px solid var(--ruby);
    border-radius: var(--radius-lg);
    padding: 1rem 1.25rem;
    margin-bottom: 1.75rem;
    animation: shakeIn 0.4s var(--ease) both;
}

@keyframes shakeIn {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-6px); }
    75% { transform: translateX(6px); }
}

.validation-box ul { list-style: none; display: flex; flex-direction: column; gap: 5px; }
.validation-box li {
    font-size: 11px;
    color: var(--ruby);
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.04em;
}
.validation-box li::before { content: '▸'; font-size: 10px; }

/* ── MAIN GRID ───────────────────────────────── */
.ep-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.75rem;
    align-items: start;
    animation: gridIn 0.5s 0.15s var(--ease) both;
}

@keyframes gridIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media (max-width: 1000px) { .ep-grid { grid-template-columns: 1fr; } }

/* ── FORM PANEL ──────────────────────────────── */
.form-panel {
    background: var(--obs-2);
    border: 1px solid var(--line-2);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

/* ── SECTION TABS ────────────────────────────── */
.section-tabs {
    display: flex;
    background: var(--obs-3);
    border-bottom: 1px solid var(--line-2);
    padding: 6px 6px 0;
    gap: 2px;
    overflow-x: auto;
    scrollbar-width: none;
}
.section-tabs::-webkit-scrollbar { display: none; }

.stab {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px 9px;
    border-radius: var(--radius) var(--radius) 0 0;
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--muted);
    cursor: pointer;
    border: none;
    background: transparent;
    transition: all 0.2s var(--ease);
    white-space: nowrap;
    border: 1px solid transparent;
    border-bottom: none;
    position: relative;
}

.stab:hover { color: var(--text-dim); background: var(--obs-4); }

.stab.active {
    color: var(--mint);
    background: var(--obs-2);
    border-color: var(--line-2);
    border-bottom-color: var(--obs-2);
    margin-bottom: -1px;
}

.stab.dirty::after {
    content: '';
    position: absolute;
    top: 6px; right: 6px;
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--amber);
}

/* ── SECTION PANE ────────────────────────────── */
.spane {
    display: none;
    padding: 2rem;
    animation: paneIn 0.3s var(--ease) both;
}
.spane.active { display: block; }

@keyframes paneIn {
    from { opacity: 0; transform: translateX(10px); }
    to   { opacity: 1; transform: translateX(0); }
}

.pane-heading {
    font-size: 9px;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 1.75rem;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* ── FORM FIELDS ─────────────────────────────── */
.field-grp { margin-bottom: 1.4rem; }

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
@media (max-width: 640px) { .field-row { grid-template-columns: 1fr; } }

label {
    display: block;
    font-size: 9.5px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--text-dim);
    margin-bottom: 6px;
    font-family: var(--font-mono);
}

label .req { color: var(--mint); margin-left: 3px; }
label .hint { float: right; font-size: 9px; color: var(--muted); text-transform: none; letter-spacing: 0; }

/* changed dot on label */
label .chg {
    display: inline-block;
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--amber);
    margin-left: 5px;
    vertical-align: middle;
    opacity: 0;
    transition: opacity 0.25s;
}
label .chg.on { opacity: 1; }

.inp, .sel, .tex {
    width: 100%;
    background: var(--obs-3);
    border: 1px solid var(--line-2);
    border-radius: var(--radius);
    padding: 10px 13px;
    font-family: var(--font-mono);
    font-size: 12.5px;
    color: var(--text);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    appearance: none;
    letter-spacing: 0.03em;
}

.inp::placeholder, .tex::placeholder { color: var(--muted); }

.inp:focus, .sel:focus, .tex:focus {
    border-color: var(--mint);
    background: var(--obs-4);
    box-shadow: 0 0 0 3px var(--mint-glow);
}

.inp.changed, .sel.changed, .tex.changed {
    border-color: rgba(255,184,48,0.35);
    background: rgba(255,184,48,0.03);
}

.inp.error { border-color: rgba(255,77,106,0.5) !important; box-shadow: 0 0 0 3px var(--ruby-dim) !important; }

.sel {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%235A6660' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
}

.sel option { background: #131614; color: var(--text); }

.tex { resize: vertical; min-height: 90px; line-height: 1.6; }

/* ── PAYMENT METHOD GRID ──────────────────────── */
.method-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}
@media (max-width: 640px) { .method-grid { grid-template-columns: repeat(2, 1fr); } }

.method-tile {
    position: relative;
    cursor: pointer;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-lg);
    padding: 14px 10px;
    text-align: center;
    transition: all 0.22s var(--ease));
    background: var(--obs-3);
    overflow: hidden;
}

.method-tile::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: var(--mc, var(--mint));
    transform: scaleX(0);
    transition: transform 0.25s var(--ease);
}

.method-tile:hover { background: var(--obs-4); border-color: var(--line-3); }
.method-tile:hover::after { transform: scaleX(1); }

.method-tile.selected {
    border-color: var(--mc, var(--mint));
    background: var(--obs-5);
    box-shadow: inset 0 0 0 1px var(--mc, var(--mint));
}
.method-tile.selected::after { transform: scaleX(1); }

.method-tile input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

.method-emoji { font-size: 22px; display: block; margin-bottom: 6px; }
.method-name {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--text-dim);
    line-height: 1.3;
}
.method-tile.selected .method-name { color: var(--text); }

/* ── CONDITIONAL FIELDS ───────────────────────── */
.conditional-field {
    display: none;
    animation: condIn 0.3s var(--ease) both;
}
.conditional-field.visible { display: block; }

@keyframes condIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── AMOUNT DISPLAY ──────────────────────────── */
.amount-display {
    font-family: var(--font-ui);
    font-size: 3.2rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: var(--mint);
    line-height: 1;
    margin: 0.8rem 0;
    display: flex;
    align-items: baseline;
    gap: 6px;
    transition: color 0.3s;
}

.amount-sym {
    font-size: 1.3rem;
    opacity: 0.6;
    font-family: var(--font-mono);
    font-weight: 300;
}

/* ── FORM FOOTER ─────────────────────────────── */
.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 2rem;
    border-top: 1px solid var(--line-2);
    background: var(--obs-3);
    flex-wrap: wrap;
    gap: 1rem;
}

.footer-meta {
    font-size: 9.5px;
    color: var(--muted);
    letter-spacing: 0.06em;
}

.footer-actions { display: flex; align-items: center; gap: 8px; }

/* ── RIGHT COLUMN ────────────────────────────── */
.right-col {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    position: sticky;
    top: 68px;
    animation: rightIn 0.5s 0.2s var(--ease) both;
}

@keyframes rightIn {
    from { opacity: 0; transform: translateX(16px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* ── LIVE RECEIPT CARD ───────────────────────── */
.live-card {
    background: var(--obs-2);
    border: 1px solid var(--line-2);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.live-card-header {
    background: var(--obs-3);
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--line-2);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.live-card-label {
    font-size: 9.5px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--muted);
}

.live-indicator {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 9.5px;
    color: var(--mint);
    letter-spacing: 0.06em;
}
.live-dot {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--mint);
    animation: livePulse 1.8s ease infinite;
}
@keyframes livePulse { 0%,100% { opacity:1; } 50% { opacity:0.3; } }

/* receipt body (thermal paper style) */
.receipt-body {
    background: var(--cream);
    padding: 1.5rem 1.25rem;
    font-family: var(--font-mono);
    color: #1A1A1A;
    position: relative;
}

.receipt-body::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: repeating-linear-gradient(
        90deg,
        var(--cream) 0px, var(--cream) 6px,
        var(--cream-2) 6px, var(--cream-2) 12px
    );
}

.rcpt-logo {
    text-align: center;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #333;
    padding-top: 0.5rem;
    margin-bottom: 0.75rem;
}

.rcpt-divider {
    border: none;
    border-top: 1px dashed #9CA3AF;
    margin: 0.75rem 0;
}

.rcpt-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 10.5px;
    padding: 3px 0;
    color: #444;
}

.rcpt-row.total {
    font-weight: 600;
    font-size: 13px;
    color: #111;
    border-top: 1px solid #DDD;
    margin-top: 6px;
    padding-top: 8px;
}

.rcpt-row .rk { opacity: 0.65; }
.rcpt-row .rv { font-weight: 500; color: #111; }
.rcpt-row.total .rv { color: #059669; }

.rcpt-number {
    text-align: center;
    font-size: 9.5px;
    letter-spacing: 0.1em;
    color: #9CA3AF;
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px dashed #D1D5DB;
}

/* ── CHANGE TRACKER ──────────────────────────── */
.change-card {
    background: var(--obs-2);
    border: 1px solid var(--line-2);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.change-card-header {
    background: var(--obs-3);
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid var(--line-2);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.change-card-label {
    font-size: 9.5px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--muted);
}

.change-count {
    font-size: 9.5px;
    color: var(--amber);
    background: var(--amber-dim);
    border: 1px solid rgba(255,184,48,0.2);
    padding: 2px 9px;
    border-radius: var(--radius);
    transition: all 0.3s;
}

.change-list {
    padding: 0.5rem 0;
    min-height: 60px;
    max-height: 220px;
    overflow-y: auto;
}

.change-empty {
    padding: 1.25rem;
    text-align: center;
    font-size: 10px;
    color: var(--muted);
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.change-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 8px 1.25rem;
    border-bottom: 1px solid var(--line);
    animation: chgIn 0.3s var(--ease) both;
}
.change-item:last-child { border-bottom: none; }

@keyframes chgIn {
    from { opacity: 0; transform: translateX(-8px); }
    to   { opacity: 1; transform: translateX(0); }
}

.chg-field {
    font-size: 9px;
    color: var(--amber);
    letter-spacing: 0.08em;
    text-transform: uppercase;
    min-width: 72px;
    padding-top: 1px;
    flex-shrink: 0;
}

.chg-diff { flex: 1; }
.chg-old {
    font-size: 9.5px;
    color: var(--ruby);
    text-decoration: line-through;
    opacity: 0.7;
    word-break: break-all;
}
.chg-new {
    font-size: 9.5px;
    color: var(--mint);
    word-break: break-all;
    margin-top: 1px;
}

/* ── AUDIT INFO ──────────────────────────────── */
.audit-card {
    background: var(--obs-2);
    border: 1px solid var(--line-2);
    border-radius: var(--radius-xl);
    padding: 1.25rem;
}

.audit-title {
    font-size: 9.5px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 1rem;
}

.audit-rows { display: flex; flex-direction: column; gap: 8px; }

.audit-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 10.5px;
    padding: 6px 0;
    border-bottom: 1px solid var(--line);
}
.audit-row:last-child { border-bottom: none; }

.audit-key { color: var(--muted); letter-spacing: 0.04em; }
.audit-val { color: var(--text-dim); font-size: 10px; }
.audit-val.mint { color: var(--mint); }

/* ── DANGER ZONE ─────────────────────────────── */
.danger-zone {
    background: var(--ruby-dim);
    border: 1px solid rgba(255,77,106,0.2);
    border-radius: var(--radius-xl);
    padding: 1.25rem;
}

.danger-title {
    font-size: 9.5px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--ruby);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 7px;
}

.danger-title::before { content: '⚠'; }

.danger-desc {
    font-size: 10.5px;
    color: var(--text-dim);
    margin-bottom: 1rem;
    line-height: 1.5;
    letter-spacing: 0.02em;
}

/* ── MODAL ───────────────────────────────────── */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    z-index: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
    backdrop-filter: blur(6px);
}
.modal-backdrop.open { opacity: 1; pointer-events: all; }

.modal-box {
    background: var(--obs-3);
    border: 1px solid rgba(255,77,106,0.3);
    border-radius: var(--radius-xl);
    padding: 2rem;
    width: 100%;
    max-width: 420px;
    transform: scale(0.92);
    transition: transform 0.35s var(--spring);
}
.modal-backdrop.open .modal-box { transform: scale(1); }

.modal-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: var(--ruby-dim);
    border: 1px solid rgba(255,77,106,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 1rem;
}

.modal-title { font-family: var(--font-ui); font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem; }
.modal-body  { font-size: 11.5px; color: var(--text-dim); line-height: 1.6; margin-bottom: 1.25rem; }
.modal-confirm {
    width: 100%;
    background: var(--obs-4);
    border: 1px solid rgba(255,77,106,0.3);
    border-radius: var(--radius);
    padding: 9px 13px;
    font-family: var(--font-mono);
    font-size: 12px;
    color: var(--text);
    outline: none;
    margin-bottom: 1.25rem;
    letter-spacing: 0.05em;
    transition: border-color 0.2s;
}
.modal-confirm:focus { border-color: var(--ruby); }
.modal-acts { display: flex; gap: 8px; }

/* ── SCROLLBAR ───────────────────────────────── */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--line-3); }

/* ── RESPONSIVE ──────────────────────────────── */
@media (max-width: 768px) {
    .ep-wrap { padding: 0 1rem; }
    .ep-topbar-inner { padding: 0 1rem; }
    .receipt-header { grid-template-columns: 1fr; }
    .receipt-stats { justify-content: flex-start; }
    .spane { padding: 1.5rem 1.25rem; }
    .form-footer { padding: 1rem 1.25rem; }
    .method-grid { grid-template-columns: repeat(2,1fr); }
}
</style>
@endpush

@section('content')

<div class="ep-page">

    {{-- ── STICKY TOP BAR ──────────────────────── --}}
    <div class="ep-topbar">
        <div class="ep-topbar-inner">
            <div class="ep-breadcrumb">
                <a href="{{ route('fee-payments.index') }}">Payments</a>
                <span class="sep">/</span>
                <a href="{{ route('fee-payments.show', $feePayment) }}">{{ $feePayment->receipt_number }}</a>
                <span class="sep">/</span>
                <span class="current">Edit</span>
            </div>

            <span class="ep-receipt-num">{{ $feePayment->receipt_number }}</span>

            <div class="ep-topbar-actions">
                <a href="{{ route('fee-payments.show', $feePayment) }}" class="btn btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    View
                </a>
                <button class="btn btn-mint" onclick="document.getElementById('paymentForm').requestSubmit()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save · <kbd style="font-size:9px; opacity:0.7;">⌘S</kbd>
                </button>
            </div>
        </div>
    </div>

    <div class="ep-wrap">

        {{-- ── RECEIPT HEADER ─────────────────── --}}
        <div class="receipt-header">
            <div class="receipt-icon">🧾</div>

            <div class="receipt-meta">
                <p class="receipt-eyebrow">Fee Payment Record · Edit Mode</p>
                <h1 class="receipt-title">Editing Payment</h1>
                <p class="receipt-subtitle">
                    {{ $feePayment->receipt_number }} &nbsp;·&nbsp;
                    Recorded {{ $feePayment->created_at->format('d M Y') }} &nbsp;·&nbsp;
                    {{ $feePayment->term }}, {{ $feePayment->academic_year }}
                </p>
            </div>

            <div class="receipt-stats">
                <div class="receipt-stat">
                    <div class="receipt-stat-label">Original Amount</div>
                    <div class="receipt-stat-val">KES {{ number_format($feePayment->amount_paid) }}</div>
                </div>
                <div class="receipt-stat">
                    <div class="receipt-stat-label">Method</div>
                    <div class="receipt-stat-val mint">{{ strtoupper(str_replace('_',' ',$feePayment->payment_method)) }}</div>
                </div>
            </div>
        </div>

        {{-- ── VALIDATION ERRORS ───────────────── --}}
        @if($errors->any())
        <div class="validation-box">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── MAIN GRID ───────────────────────── --}}
        <div class="ep-grid">

            {{-- ═══ FORM PANEL ═══ --}}
            <div class="form-panel">

                {{-- Section Tabs --}}
                <div class="section-tabs" id="sectionTabs">
                    <button class="stab active" data-tab="payment" onclick="switchTab('payment',this)">
                        💳 Payment Details
                    </button>
                    <button class="stab" data-tab="student" onclick="switchTab('student',this)">
                        🎓 Student & Term
                    </button>
                    <button class="stab" data-tab="notes" onclick="switchTab('notes',this)">
                        📝 Notes & Ref
                    </button>
                </div>

                <form method="POST" action="{{ route('fee-payments.update', $feePayment) }}" id="paymentForm" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- ══ PAYMENT DETAILS TAB ══ --}}
                    <div class="spane active" id="tab-payment">
                        <p class="pane-heading">
                            <span>Financial Information</span>
                            <span>ID #{{ str_pad($feePayment->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </p>

                        {{-- Amount --}}
                        <div class="field-grp">
                            <label for="amount_paid">
                                Amount Paid (KES) <span class="req">*</span>
                                <span class="chg" id="chg-amount_paid"></span>
                            </label>
                            <div class="amount-display">
                                <span class="amount-sym">KES</span>
                                <span id="amountDisplay">{{ number_format($feePayment->amount_paid) }}</span>
                            </div>
                            <input
                                type="number"
                                name="amount_paid"
                                id="amount_paid"
                                class="inp"
                                min="1"
                                step="0.01"
                                value="{{ old('amount_paid', $feePayment->amount_paid) }}"
                                data-original="{{ $feePayment->amount_paid }}"
                                data-label="Amount"
                                required
                                placeholder="Enter amount in KES"
                            >
                        </div>

                        {{-- Payment Method --}}
                        <div class="field-grp">
                            <label>
                                Payment Method <span class="req">*</span>
                                <span class="chg" id="chg-payment_method"></span>
                            </label>
                            <div class="method-grid" id="methodGrid">

                                <label class="method-tile {{ old('payment_method', $feePayment->payment_method) === 'cash' ? 'selected' : '' }}" style="--mc: #22c55e">
                                    <input type="radio" name="payment_method" value="cash"
                                        {{ old('payment_method', $feePayment->payment_method) === 'cash' ? 'checked' : '' }}
                                        data-original="{{ $feePayment->payment_method }}"
                                        data-label="Payment Method">
                                    <span class="method-emoji">💵</span>
                                    <span class="method-name">Cash</span>
                                </label>

                                <label class="method-tile {{ old('payment_method', $feePayment->payment_method) === 'mpesa' ? 'selected' : '' }}" style="--mc: #16a34a">
                                    <input type="radio" name="payment_method" value="mpesa"
                                        {{ old('payment_method', $feePayment->payment_method) === 'mpesa' ? 'checked' : '' }}
                                        data-original="{{ $feePayment->payment_method }}"
                                        data-label="Payment Method">
                                    <span class="method-emoji">📱</span>
                                    <span class="method-name">M-PESA</span>
                                </label>

                                <label class="method-tile {{ old('payment_method', $feePayment->payment_method) === 'bank_transfer' ? 'selected' : '' }}" style="--mc: #2DFFC4">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                        {{ old('payment_method', $feePayment->payment_method) === 'bank_transfer' ? 'checked' : '' }}
                                        data-original="{{ $feePayment->payment_method }}"
                                        data-label="Payment Method">
                                    <span class="method-emoji">🏦</span>
                                    <span class="method-name">Bank Transfer</span>
                                </label>

                                <label class="method-tile {{ old('payment_method', $feePayment->payment_method) === 'cheque' ? 'selected' : '' }}" style="--mc: #a78bfa">
                                    <input type="radio" name="payment_method" value="cheque"
                                        {{ old('payment_method', $feePayment->payment_method) === 'cheque' ? 'checked' : '' }}
                                        data-original="{{ $feePayment->payment_method }}"
                                        data-label="Payment Method">
                                    <span class="method-emoji">📄</span>
                                    <span class="method-name">Cheque</span>
                                </label>

                            </div>
                        </div>

                        {{-- M-PESA Code (conditional) --}}
                        <div class="field-grp conditional-field {{ in_array(old('payment_method', $feePayment->payment_method), ['mpesa']) ? 'visible' : '' }}" id="mpesaField">
                            <label for="mpesa_code">
                                M-PESA Transaction Code
                                <span class="chg" id="chg-mpesa_code"></span>
                                <span class="hint">e.g. QKJ7XXXXXZ</span>
                            </label>
                            <input
                                type="text"
                                name="mpesa_code"
                                id="mpesa_code"
                                class="inp"
                                maxlength="50"
                                value="{{ old('mpesa_code', $feePayment->mpesa_code) }}"
                                data-original="{{ $feePayment->mpesa_code }}"
                                data-label="M-PESA Code"
                                placeholder="QKJ7XXXXXZ"
                                style="text-transform: uppercase; letter-spacing: 0.12em;"
                            >
                        </div>

                        {{-- Bank Reference (conditional) --}}
                        <div class="field-grp conditional-field {{ in_array(old('payment_method', $feePayment->payment_method), ['bank_transfer','cheque']) ? 'visible' : '' }}" id="bankRefField">
                            <label for="bank_reference">
                                Bank / Cheque Reference
                                <span class="chg" id="chg-bank_reference"></span>
                            </label>
                            <input
                                type="text"
                                name="bank_reference"
                                id="bank_reference"
                                class="inp"
                                value="{{ old('bank_reference', $feePayment->bank_reference) }}"
                                data-original="{{ $feePayment->bank_reference }}"
                                data-label="Bank Reference"
                                placeholder="Reference number or cheque no."
                            >
                        </div>

                        {{-- Payment Date --}}
                        <div class="field-grp">
                            <label for="payment_date">
                                Payment Date <span class="req">*</span>
                                <span class="chg" id="chg-payment_date"></span>
                            </label>
                            <input
                                type="date"
                                name="payment_date"
                                id="payment_date"
                                class="inp"
                                value="{{ old('payment_date', $feePayment->payment_date ? \Carbon\Carbon::parse($feePayment->payment_date)->format('Y-m-d') : '') }}"
                                data-original="{{ $feePayment->payment_date ? \Carbon\Carbon::parse($feePayment->payment_date)->format('Y-m-d') : '' }}"
                                data-label="Payment Date"
                                required
                            >
                        </div>

                    </div>
                    {{-- /payment tab --}}

                    {{-- ══ STUDENT & TERM TAB ══ --}}
                    <div class="spane" id="tab-student">
                        <p class="pane-heading">Student & Academic Period</p>

                        {{-- Student Select --}}
                        <div class="field-grp">
                            <label for="student_id">
                                Student <span class="req">*</span>
                                <span class="chg" id="chg-student_id"></span>
                            </label>
                            <select
                                name="student_id"
                                id="student_id"
                                class="inp sel"
                                required
                                data-original="{{ $feePayment->student_id }}"
                                data-label="Student"
                            >
                                <option value="">— Select student</option>
                                @foreach($students as $student)
                                    <option
                                        value="{{ $student->id }}"
                                        {{ old('student_id', $feePayment->student_id) == $student->id ? 'selected' : '' }}
                                    >
                                        Grade {{ $student->grade }} — {{ $student->last_name }}, {{ $student->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Academic Year & Term --}}
                        <div class="field-row">
                            <div class="field-grp">
                                <label for="academic_year">
                                    Academic Year <span class="req">*</span>
                                    <span class="chg" id="chg-academic_year"></span>
                                </label>
                                <input
                                    type="text"
                                    name="academic_year"
                                    id="academic_year"
                                    class="inp"
                                    value="{{ old('academic_year', $feePayment->academic_year) }}"
                                    data-original="{{ $feePayment->academic_year }}"
                                    data-label="Academic Year"
                                    required
                                    placeholder="e.g. 2026"
                                >
                            </div>
                            <div class="field-grp">
                                <label for="term">
                                    Term <span class="req">*</span>
                                    <span class="chg" id="chg-term"></span>
                                </label>
                                <select
                                    name="term"
                                    id="term"
                                    class="inp sel"
                                    required
                                    data-original="{{ $feePayment->term }}"
                                    data-label="Term"
                                >
                                    @foreach(['Term 1','Term 2','Term 3'] as $t)
                                        <option value="{{ $t }}" {{ old('term', $feePayment->term) === $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    {{-- /student tab --}}

                    {{-- ══ NOTES & REF TAB ══ --}}
                    <div class="spane" id="tab-notes">
                        <p class="pane-heading">Additional Notes & Administration</p>

                        <div class="field-grp">
                            <label for="notes">
                                Internal Notes
                                <span class="chg" id="chg-notes"></span>
                                <span class="hint" id="notesCount">{{ strlen(old('notes', $feePayment->notes ?? '')) }} / 500</span>
                            </label>
                            <textarea
                                name="notes"
                                id="notes"
                                class="inp tex"
                                maxlength="500"
                                placeholder="Any additional context, corrections, or administrative notes…"
                                data-original="{{ $feePayment->notes }}"
                                data-label="Notes"
                                rows="5"
                            >{{ old('notes', $feePayment->notes) }}</textarea>
                        </div>

                        {{-- Receipt Number (read-only display) --}}
                        <div class="field-grp">
                            <label>Receipt Number <span class="hint">auto-generated · read only</span></label>
                            <input
                                type="text"
                                class="inp"
                                value="{{ $feePayment->receipt_number }}"
                                readonly
                                style="opacity:0.5; cursor:not-allowed; letter-spacing:0.08em;"
                            >
                        </div>

                        {{-- Recorded By (read-only) --}}
                        <div class="field-grp">
                            <label>Originally Recorded By <span class="hint">read only</span></label>
                            <input
                                type="text"
                                class="inp"
                                value="{{ $feePayment->recorder->name ?? 'Administrator' }}"
                                readonly
                                style="opacity:0.5; cursor:not-allowed;"
                            >
                        </div>

                    </div>
                    {{-- /notes tab --}}

                    {{-- Form Footer --}}
                    <div class="form-footer">
                        <span class="footer-meta">
                            Last modified: {{ $feePayment->updated_at->diffForHumans() }}
                        </span>
                        <div class="footer-actions">
                            <button type="button" class="btn btn-ghost" onclick="discardChanges()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.37"/>
                                </svg>
                                Discard
                            </button>
                            <button type="submit" class="btn btn-mint" id="saveBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>

                </form>

                {{-- Danger Zone (outside form) --}}
                <div class="danger-zone" style="margin: 0 1.5rem 1.5rem;">
                    <p class="danger-title">Danger Zone</p>
                    <p class="danger-desc">Permanently delete this payment record. This will remove it from all reports and cannot be undone.</p>
                    <button class="btn btn-ruby" onclick="openDeleteModal()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4h6v2"/>
                        </svg>
                        Delete Payment Record
                    </button>
                </div>

            </div>
            {{-- /form panel --}}

            {{-- ═══ RIGHT COLUMN ═══ --}}
            <div class="right-col">

                {{-- Live Receipt Preview --}}
                <div class="live-card">
                    <div class="live-card-header">
                        <span class="live-card-label">Live Receipt Preview</span>
                        <span class="live-indicator">
                            <span class="live-dot"></span> Updating
                        </span>
                    </div>
                    <div class="receipt-body">
                        <div class="rcpt-logo">
                            {{ config('app.name', 'Institution') }}<br>
                            <span style="font-weight:300; font-size:9px; letter-spacing:0.08em;">OFFICIAL FEE RECEIPT</span>
                        </div>

                        <hr class="rcpt-divider">

                        <div class="rcpt-row">
                            <span class="rk">Student</span>
                            <span class="rv" id="rcpt-student">
                                @php
                                    $sel = $students->find($feePayment->student_id);
                                @endphp
                                {{ $sel ? $sel->last_name.', '.$sel->first_name : '—' }}
                            </span>
                        </div>
                        <div class="rcpt-row">
                            <span class="rk">Grade</span>
                            <span class="rv" id="rcpt-grade">{{ $sel ? 'Grade '.$sel->grade : '—' }}</span>
                        </div>
                        <div class="rcpt-row">
                            <span class="rk">Academic Year</span>
                            <span class="rv" id="rcpt-year">{{ $feePayment->academic_year }}</span>
                        </div>
                        <div class="rcpt-row">
                            <span class="rk">Term</span>
                            <span class="rv" id="rcpt-term">{{ $feePayment->term }}</span>
                        </div>
                        <div class="rcpt-row">
                            <span class="rk">Date</span>
                            <span class="rv" id="rcpt-date">
                                {{ \Carbon\Carbon::parse($feePayment->payment_date)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="rcpt-row">
                            <span class="rk">Method</span>
                            <span class="rv" id="rcpt-method">{{ strtoupper(str_replace('_',' ',$feePayment->payment_method)) }}</span>
                        </div>
                        <div class="rcpt-row" id="rcpt-code-row" style="{{ !$feePayment->mpesa_code && !$feePayment->bank_reference ? 'display:none' : '' }}">
                            <span class="rk">Reference</span>
                            <span class="rv" id="rcpt-code">{{ $feePayment->mpesa_code ?? $feePayment->bank_reference ?? '' }}</span>
                        </div>

                        <hr class="rcpt-divider">

                        <div class="rcpt-row total">
                            <span>AMOUNT PAID</span>
                            <span class="rv" id="rcpt-amount">KES {{ number_format($feePayment->amount_paid) }}</span>
                        </div>

                        <div class="rcpt-number">
                            {{ $feePayment->receipt_number }}<br>
                            <span style="font-size:8.5px;">Edited by {{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>

                {{-- Change Tracker --}}
                <div class="change-card">
                    <div class="change-card-header">
                        <span class="change-card-label">Change Tracker</span>
                        <span class="change-count" id="changeCount">0 edits</span>
                    </div>
                    <div class="change-list" id="changeList">
                        <div class="change-empty" id="changeEmpty">No changes yet</div>
                    </div>
                </div>

                {{-- Audit Info --}}
                <div class="audit-card">
                    <p class="audit-title">Audit Information</p>
                    <div class="audit-rows">
                        <div class="audit-row">
                            <span class="audit-key">Receipt No.</span>
                            <span class="audit-val mint">{{ $feePayment->receipt_number }}</span>
                        </div>
                        <div class="audit-row">
                            <span class="audit-key">Created</span>
                            <span class="audit-val">{{ $feePayment->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="audit-row">
                            <span class="audit-key">Last Updated</span>
                            <span class="audit-val">{{ $feePayment->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="audit-row">
                            <span class="audit-key">Recorded By</span>
                            <span class="audit-val">{{ $feePayment->recorder->name ?? 'Admin' }}</span>
                        </div>
                        <div class="audit-row">
                            <span class="audit-key">Editing As</span>
                            <span class="audit-val mint">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>

            </div>
            {{-- /right col --}}

        </div>
        {{-- /ep-grid --}}

    </div>
</div>

{{-- ── DELETE MODAL ────────────────────────────── --}}
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Payment Record?</h3>
        <p class="modal-body">
            This will permanently delete <strong>{{ $feePayment->receipt_number }}</strong>
            (KES {{ number_format($feePayment->amount_paid) }}) and remove it from all financial reports.
            This action cannot be undone.
        </p>
        <p style="font-size:10.5px; color:var(--muted); margin-bottom:0.5rem; letter-spacing:0.06em;">
            Type <strong style="color:var(--ruby);">DELETE</strong> to confirm
        </p>
        <input
            type="text"
            class="modal-confirm"
            id="deleteConfirmInput"
            placeholder="Type DELETE"
            autocomplete="off"
        >
        <div class="modal-acts">
            <button class="btn btn-ghost" style="flex:1;" onclick="closeDeleteModal()">Cancel</button>
            <form method="POST" action="{{ route('fee-payments.destroy', $feePayment) }}" id="deleteForm" style="flex:1;">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="btn btn-ruby"
                    id="deleteConfirmBtn"
                    style="width:100%;"
                    disabled
                >Delete Forever</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── ORIGINAL VALUES ──────────────────────── */
    const originals = {};
    document.querySelectorAll('[data-original]').forEach(el => {
        originals[el.name || el.id] = el.dataset.original ?? '';
    });

    /* ── CHANGE TRACKING STATE ────────────────── */
    const changes = new Map();

    function trackChange(key, label, orig, cur) {
        const s = v => String(v ?? '').trim();
        if (s(orig) !== s(cur)) {
            changes.set(key, { label, orig: s(orig), cur: s(cur) });
        } else {
            changes.delete(key);
        }
        renderChanges();
    }

    function renderChanges() {
        const list  = document.getElementById('changeList');
        const empty = document.getElementById('changeEmpty');
        const count = document.getElementById('changeCount');
        const n = changes.size;

        count.textContent = `${n} edit${n !== 1 ? 's' : ''}`;
        Array.from(list.querySelectorAll('.change-item')).forEach(el => el.remove());

        if (n === 0) { empty.style.display = ''; return; }
        empty.style.display = 'none';

        changes.forEach((v, key) => {
            const el = document.createElement('div');
            el.className = 'change-item';
            el.innerHTML = `
                <span class="chg-field">${v.label}</span>
                <div class="chg-diff">
                    <div class="chg-old">${v.orig || '(empty)'}</div>
                    <div class="chg-new">${v.cur || '(empty)'}</div>
                </div>
            `;
            list.appendChild(el);
        });

        // mark save button active state
        document.getElementById('saveBtn').classList.toggle('btn-mint', n > 0);
    }

    /* ── WIRE TEXT INPUTS ─────────────────────── */
    function wireInput(el, onUpdate) {
        const key   = el.name || el.id;
        const label = el.dataset.label || key;
        const orig  = originals[key] ?? '';
        const chgDot = document.getElementById('chg-' + key);
        const tabId  = el.closest('.spane')?.id?.replace('tab-', '');

        const handle = () => {
            const cur = el.value;
            const changed = String(cur).trim() !== String(orig).trim();
            el.classList.toggle('changed', changed);
            if (chgDot) chgDot.classList.toggle('on', changed);
            trackChange(key, label, orig, cur);
            if (changed && tabId) markTabDirty(tabId);
            if (onUpdate) onUpdate(cur);
        };

        el.addEventListener('input', handle);
        el.addEventListener('change', handle);
    }

    function markTabDirty(tabId) {
        const btn = document.querySelector(`.stab[data-tab="${tabId}"]`);
        if (btn) btn.classList.add('dirty');
    }

    /* ── AMOUNT FIELD ─────────────────────────── */
    const amountInput = document.getElementById('amount_paid');
    wireInput(amountInput, val => {
        const num = parseFloat(val) || 0;
        document.getElementById('amountDisplay').textContent = num.toLocaleString('en-KE', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
        document.getElementById('rcpt-amount').textContent = 'KES ' + num.toLocaleString();
        document.getElementById('amountDisplay').style.color = num > 0 ? 'var(--mint)' : 'var(--ruby)';
    });

    /* ── PAYMENT METHOD ───────────────────────── */
    const methodInputs = document.querySelectorAll('input[name="payment_method"]');
    const mpesaField   = document.getElementById('mpesaField');
    const bankRefField = document.getElementById('bankRefField');

    methodInputs.forEach(radio => {
        radio.addEventListener('change', () => {
            // update tile selection
            document.querySelectorAll('.method-tile').forEach(t => t.classList.remove('selected'));
            radio.closest('.method-tile').classList.add('selected');

            // conditional fields
            const val = radio.value;
            mpesaField.classList.toggle('visible', val === 'mpesa');
            bankRefField.classList.toggle('visible', val === 'bank_transfer' || val === 'cheque');

            // live receipt
            document.getElementById('rcpt-method').textContent = val.replace('_',' ').toUpperCase();

            // track change
            const orig = radio.dataset.original;
            const chgDot = document.getElementById('chg-payment_method');
            const changed = val !== orig;
            if (chgDot) chgDot.classList.toggle('on', changed);
            trackChange('payment_method', 'Payment Method', orig, val);
            if (changed) markTabDirty('payment');
        });
    });

    /* ── M-PESA CODE ──────────────────────────── */
    const mpesaInput = document.getElementById('mpesa_code');
    wireInput(mpesaInput, val => {
        const codeRow = document.getElementById('rcpt-code-row');
        const codeEl  = document.getElementById('rcpt-code');
        codeEl.textContent = val.toUpperCase();
        codeRow.style.display = val ? '' : 'none';
    });

    /* ── BANK REF ─────────────────────────────── */
    wireInput(document.getElementById('bank_reference'), val => {
        const codeRow = document.getElementById('rcpt-code-row');
        const codeEl  = document.getElementById('rcpt-code');
        if (!document.getElementById('mpesa_code').value) {
            codeEl.textContent = val;
            codeRow.style.display = val ? '' : 'none';
        }
    });

    /* ── PAYMENT DATE ─────────────────────────── */
    wireInput(document.getElementById('payment_date'), val => {
        const rcptDate = document.getElementById('rcpt-date');
        if (val) {
            const d = new Date(val + 'T00:00:00');
            rcptDate.textContent = d.toLocaleDateString('en-KE', { day:'2-digit', month:'short', year:'numeric' });
        }
    });

    /* ── STUDENT SELECT ───────────────────────── */
    const studentSel = document.getElementById('student_id');
    wireInput(studentSel, val => {
        const opt = studentSel.options[studentSel.selectedIndex];
        if (opt && opt.value) {
            const text = opt.text.split('—')[1]?.trim() || opt.text;
            const grade = opt.text.split('—')[0]?.trim() || '';
            document.getElementById('rcpt-student').textContent = text;
            document.getElementById('rcpt-grade').textContent = grade;
        }
    });

    /* ── TERM & YEAR ──────────────────────────── */
    wireInput(document.getElementById('academic_year'), val => {
        document.getElementById('rcpt-year').textContent = val;
    });

    const termSel = document.getElementById('term');
    wireInput(termSel, val => {
        document.getElementById('rcpt-term').textContent = val;
    });

    /* ── NOTES COUNTER ────────────────────────── */
    const notesEl = document.getElementById('notes');
    if (notesEl) {
        wireInput(notesEl);
        notesEl.addEventListener('input', () => {
            document.getElementById('notesCount').textContent = `${notesEl.value.length} / 500`;
        });
    }

    /* ── TABS ─────────────────────────────────── */
    window.switchTab = function(tabId, btn) {
        document.querySelectorAll('.stab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.spane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + tabId).classList.add('active');
    };

    /* ── DISCARD ──────────────────────────────── */
    window.discardChanges = function() {
        if (changes.size === 0) return;
        if (!confirm('Discard all unsaved changes?')) return;
        document.getElementById('paymentForm').reset();
        changes.clear();
        renderChanges();
        document.querySelectorAll('.changed').forEach(el => el.classList.remove('changed'));
        document.querySelectorAll('.chg.on').forEach(el => el.classList.remove('on'));
        document.querySelectorAll('.stab.dirty').forEach(el => el.classList.remove('dirty'));
        // reset amount display
        document.getElementById('amountDisplay').textContent = '{{ number_format($feePayment->amount_paid) }}';
        document.getElementById('rcpt-amount').textContent = 'KES {{ number_format($feePayment->amount_paid) }}';
    };

    /* ── SUBMIT LOADING ───────────────────────── */
    document.getElementById('paymentForm').addEventListener('submit', function() {
        const btn = document.getElementById('saveBtn');
        btn.textContent = 'Saving…';
        btn.disabled = true;
    });

    /* ── DELETE MODAL ─────────────────────────── */
    window.openDeleteModal  = () => document.getElementById('deleteModal').classList.add('open');
    window.closeDeleteModal = () => {
        document.getElementById('deleteModal').classList.remove('open');
        document.getElementById('deleteConfirmInput').value = '';
        document.getElementById('deleteConfirmBtn').disabled = true;
    };

    document.getElementById('deleteConfirmInput').addEventListener('input', function() {
        document.getElementById('deleteConfirmBtn').disabled = this.value.trim() !== 'DELETE';
    });

    document.getElementById('deleteModal').addEventListener('click', e => {
        if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
    });

    /* ── KEYBOARD ─────────────────────────────── */
    document.addEventListener('keydown', e => {
        if ((e.metaKey || e.ctrlKey) && e.key === 's') {
            e.preventDefault();
            document.getElementById('paymentForm').requestSubmit();
        }
        if (e.key === 'Escape') closeDeleteModal();
    });

    /* ── FLASH SUCCESS ────────────────────────── */
    @if(session('success'))
        console.log('{{ session("success") }}');
    @endif

});
</script>
@endpush