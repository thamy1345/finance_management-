{{-- resources/views/payments/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Record Fee Payment')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:ital,wght@0,300;0,400;0,500;1,300&family=Fraunces:ital,opsz,wght@0,9..144,700;0,9..144,900;1,9..144,400;1,9..144,700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   GOD MODE · FEE PAYMENT CREATE
   Aesthetic: Void Terminal × Command Search × Swiss Precision
   Palette: Deep Void + Electric Teal + Amber Warning
   ═══════════════════════════════════════════════════ */

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --void:         #060709;
    --void-2:       #0A0C10;
    --surface:      #0E1016;
    --raised:       #13161E;
    --elevated:     #181C26;
    --rim:          #1F2436;
    --rim-2:        #272D40;
    --line:         rgba(255,255,255,0.055);
    --line-2:       rgba(255,255,255,0.1);
    --line-3:       rgba(255,255,255,0.17);
    --muted:        #424861;
    --dim:          #636880;
    --body:         #B8BBCC;
    --bright:       #E8EAFA;
    --white:        #FFFFFF;

    --teal:         #00D4AA;
    --teal-2:       #00F0C0;
    --teal-dim:     rgba(0,212,170,0.1);
    --teal-glow:    rgba(0,212,170,0.06);
    --teal-ring:    rgba(0,212,170,0.2);
    --teal-border:  rgba(0,212,170,0.3);

    --amber:        #FFAA00;
    --amber-dim:    rgba(255,170,0,0.1);
    --amber-border: rgba(255,170,0,0.25);

    --rose:         #FF5570;
    --rose-dim:     rgba(255,85,112,0.1);
    --rose-border:  rgba(255,85,112,0.3);

    --sapphire:     #4D8EFF;
    --emerald:      #22D66A;

    --radius-xs:  3px;
    --radius-sm:  6px;
    --radius:     10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --radius-2xl: 28px;

    --font-display: 'Syne', sans-serif;
    --font-serif:   'Fraunces', Georgia, serif;
    --font-mono:    'JetBrains Mono', monospace;

    --ease: cubic-bezier(0.4, 0, 0.2, 1);
    --spring: cubic-bezier(0.34, 1.56, 0.64, 1);
    --t: 0.22s;
}

/* ── PAGE ────────────────────────────────────── */
.cr-page {
    font-family: var(--font-display);
    background: var(--void);
    min-height: 100vh;
    color: var(--body);
    position: relative;
    overflow-x: hidden;
    padding-bottom: 5rem;
}

/* atmospheric layers */
.cr-page::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 50% at 0% 0%, rgba(0,212,170,0.04) 0%, transparent 55%),
        radial-gradient(ellipse 60% 60% at 100% 100%, rgba(77,142,255,0.04) 0%, transparent 55%);
    pointer-events: none;
    z-index: 0;
}

/* micro grid */
.cr-page::after {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.012) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.012) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    z-index: 0;
}

.cr-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 1;
}

/* ── TOP NAV ─────────────────────────────────── */
.cr-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--line);
    margin-bottom: 2.5rem;
    animation: navIn 0.4s var(--ease) both;
}

@keyframes navIn {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.cr-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    letter-spacing: 0.08em;
    color: var(--muted);
}

.cr-breadcrumb a { color: var(--muted); text-decoration: none; transition: color var(--t); }
.cr-breadcrumb a:hover { color: var(--body); }
.cr-breadcrumb .sep { opacity: 0.3; font-size: 14px; }
.cr-breadcrumb .cur { color: var(--teal); font-weight: 600; }

.cr-header-title {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

.cr-header-title h1 {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--bright);
    letter-spacing: -0.01em;
    text-align: center;
}

.cr-header-title p {
    font-size: 10px;
    color: var(--muted);
    text-align: center;
    letter-spacing: 0.06em;
    margin-top: 2px;
    text-transform: uppercase;
}

.cr-nav-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── BUTTONS ─────────────────────────────────── */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    border-radius: var(--radius-sm);
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: all var(--t) var(--ease);
    letter-spacing: 0.04em;
    white-space: nowrap;
}
.btn svg { width: 14px; height: 14px; }

.btn-teal {
    background: var(--teal);
    color: var(--void);
}
.btn-teal:hover { background: var(--teal-2); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,212,170,0.28); }
.btn-teal:active { transform: translateY(0); }
.btn-teal:disabled { opacity: 0.45; pointer-events: none; }

.btn-ghost {
    background: transparent;
    color: var(--dim);
    border: 1px solid var(--rim-2);
}
.btn-ghost:hover { background: var(--raised); color: var(--bright); border-color: var(--line-3); }

/* ── SPLIT LAYOUT ────────────────────────────── */
.cr-split {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.75rem;
    align-items: start;
}

@media (max-width: 1000px) {
    .cr-split { grid-template-columns: 1fr; }
    .cr-aside  { order: -1; }
}

/* ── FORM COLUMN ─────────────────────────────── */
.cr-form-col { display: flex; flex-direction: column; gap: 1.25rem; }

/* ── SECTION CARD ────────────────────────────── */
.cr-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    overflow: hidden;
    animation: cardIn 0.5s var(--ease) both;
}

.cr-card:nth-child(1) { animation-delay: 0.08s; }
.cr-card:nth-child(2) { animation-delay: 0.16s; }
.cr-card:nth-child(3) { animation-delay: 0.22s; }

@keyframes cardIn {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.cr-card-head {
    padding: 14px 20px;
    border-bottom: 1px solid var(--line);
    background: rgba(255,255,255,0.018);
    display: flex;
    align-items: center;
    gap: 10px;
}

.cr-card-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: var(--teal-dim);
    border: 1px solid var(--teal-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--teal);
    flex-shrink: 0;
}

.cr-card-icon.amber {
    background: var(--amber-dim);
    border-color: var(--amber-border);
    color: var(--amber);
}

.cr-card-icon.sapphire {
    background: rgba(77,142,255,0.1);
    border-color: rgba(77,142,255,0.3);
    color: var(--sapphire);
}

.cr-card-label {
    font-size: 10px;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    font-weight: 700;
    color: var(--bright);
}

.cr-card-badge {
    margin-left: auto;
    font-family: var(--font-mono);
    font-size: 9px;
    color: var(--teal);
    background: var(--teal-dim);
    border: 1px solid var(--teal-ring);
    padding: 2px 8px;
    border-radius: 99px;
}

.cr-card-body { padding: 20px; }

/* ══════════════════════════════════════════════
   STUDENT COMMAND SEARCH
══════════════════════════════════════════════ */

/* search bar */
.student-search-bar {
    position: relative;
    margin-bottom: 12px;
}

.student-search-bar svg.search-icon {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    width: 15px; height: 15px;
    pointer-events: none;
    transition: color var(--t);
}

#studentSearchInput {
    width: 100%;
    background: var(--raised);
    border: 1px solid var(--rim-2);
    border-radius: var(--radius);
    padding: 11px 13px 11px 40px;
    font-family: var(--font-mono);
    font-size: 12.5px;
    color: var(--bright);
    outline: none;
    letter-spacing: 0.04em;
    transition: all var(--t) var(--ease);
}

#studentSearchInput::placeholder { color: var(--muted); }

#studentSearchInput:focus {
    border-color: var(--teal);
    background: var(--elevated);
    box-shadow: 0 0 0 3px var(--teal-glow);
}

#studentSearchInput:focus ~ svg.search-icon,
.student-search-bar:focus-within svg.search-icon { color: var(--teal); }

.search-clear-btn {
    position: absolute;
    right: 10px; top: 50%;
    transform: translateY(-50%);
    width: 22px; height: 22px;
    border-radius: 50%;
    background: var(--rim-2);
    border: none;
    color: var(--dim);
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all var(--t);
    line-height: 1;
}

.search-clear-btn.visible { display: flex; }
.search-clear-btn:hover { background: var(--rose); color: var(--white); }

/* filter tabs */
.filter-tabs {
    display: flex;
    gap: 4px;
    margin-bottom: 12px;
    background: var(--raised);
    border: 1px solid var(--rim);
    border-radius: var(--radius-sm);
    padding: 3px;
}

.filter-tab {
    flex: 1;
    padding: 6px 8px;
    border-radius: 5px;
    font-family: var(--font-mono);
    font-size: 9.5px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--muted);
    cursor: pointer;
    border: none;
    background: transparent;
    transition: all var(--t) var(--ease);
    text-align: center;
    white-space: nowrap;
}

.filter-tab:hover { color: var(--body); background: var(--elevated); }

.filter-tab.active {
    background: var(--teal);
    color: var(--void);
    font-weight: 700;
}

/* results meta bar */
.results-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: var(--font-mono);
    font-size: 9.5px;
    letter-spacing: 0.06em;
    color: var(--muted);
    margin-bottom: 8px;
    padding: 0 2px;
}

.results-count { color: var(--teal); font-weight: 500; }

/* student results list */
.student-results-list {
    max-height: 280px;
    overflow-y: auto;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-lg);
    background: var(--void-2);
    scrollbar-width: thin;
    scrollbar-color: var(--rim-2) transparent;
}

.student-results-list::-webkit-scrollbar { width: 5px; }
.student-results-list::-webkit-scrollbar-thumb { background: var(--rim-2); border-radius: 3px; }

.student-result-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-bottom: 1px solid var(--line);
    cursor: pointer;
    transition: background var(--t) var(--ease);
    position: relative;
    animation: resultIn 0.25s var(--ease) both;
}

@keyframes resultIn {
    from { opacity: 0; transform: translateX(-8px); }
    to   { opacity: 1; transform: translateX(0); }
}

.student-result-item:last-child { border-bottom: none; }
.student-result-item:hover { background: var(--raised); }
.student-result-item.selected { background: var(--teal-dim); border-left: 2px solid var(--teal); }

/* avatar */
.student-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: var(--elevated);
    border: 1px solid var(--rim-2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    color: var(--teal);
    flex-shrink: 0;
    transition: all var(--t);
}

.student-result-item.selected .student-avatar {
    background: var(--teal-dim);
    border-color: var(--teal-border);
}

.student-result-item:hover .student-avatar { border-color: var(--line-3); }

.student-result-info { flex: 1; overflow: hidden; }

.student-result-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--bright);
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.student-result-name mark {
    background: var(--teal-dim);
    color: var(--teal);
    border-radius: 2px;
    padding: 0 1px;
}

.student-result-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 3px;
}

.student-adm {
    font-family: var(--font-mono);
    font-size: 10px;
    color: var(--dim);
    letter-spacing: 0.05em;
}

.student-adm mark {
    background: var(--amber-dim);
    color: var(--amber);
    border-radius: 2px;
    padding: 0 1px;
}

.grade-badge {
    font-family: var(--font-mono);
    font-size: 9px;
    padding: 1px 7px;
    border-radius: 99px;
    background: rgba(77,142,255,0.12);
    border: 1px solid rgba(77,142,255,0.25);
    color: var(--sapphire);
    letter-spacing: 0.04em;
    white-space: nowrap;
}

.student-select-check {
    width: 20px; height: 20px;
    border-radius: 50%;
    background: var(--teal);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: var(--void);
    flex-shrink: 0;
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.25s var(--spring);
}

.student-result-item.selected .student-select-check {
    opacity: 1;
    transform: scale(1);
}

/* empty state */
.search-empty {
    text-align: center;
    padding: 2rem;
    color: var(--muted);
}

.search-empty-icon { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.4; }
.search-empty-text { font-size: 12px; letter-spacing: 0.04em; }

/* selected student panel */
.selected-student-panel {
    display: none;
    border: 1px solid var(--teal-border);
    border-radius: var(--radius-lg);
    background: linear-gradient(135deg, var(--teal-dim) 0%, rgba(77,142,255,0.06) 100%);
    padding: 14px 16px;
    animation: selectedIn 0.35s var(--spring) both;
}

.selected-student-panel.show { display: block; }

@keyframes selectedIn {
    from { opacity: 0; transform: scale(0.96); }
    to   { opacity: 1; transform: scale(1); }
}

.selected-student-inner {
    display: flex;
    align-items: center;
    gap: 12px;
}

.selected-avatar {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: var(--teal-dim);
    border: 1.5px solid var(--teal-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    color: var(--teal);
    flex-shrink: 0;
}

.selected-info { flex: 1; }
.selected-name {
    font-weight: 700;
    font-size: 14px;
    color: var(--bright);
    margin-bottom: 3px;
}

.selected-tags {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
}

.selected-tag {
    font-family: var(--font-mono);
    font-size: 9.5px;
    letter-spacing: 0.06em;
    color: var(--dim);
}

.selected-tag.adm { color: var(--amber); }

.change-btn {
    background: transparent;
    border: 1px solid var(--teal-border);
    border-radius: var(--radius-sm);
    color: var(--teal);
    cursor: pointer;
    font-family: var(--font-mono);
    font-size: 9px;
    padding: 4px 10px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: all var(--t);
    flex-shrink: 0;
}

.change-btn:hover { background: var(--teal-dim); }

/* hidden input */
input[type="hidden"]#student_id_hidden { display: none; }

/* ══════════════════════════════════════════════
   PAYMENT DETAILS SECTION
══════════════════════════════════════════════ */

.field-group { margin-bottom: 14px; }

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

@media (max-width: 640px) { .field-row { grid-template-columns: 1fr; } }

label.field-label {
    display: block;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 5px;
    font-family: var(--font-mono);
}

label.field-label .req { color: var(--teal); margin-left: 2px; }

.inp, .sel, .tex {
    width: 100%;
    background: var(--raised);
    border: 1px solid var(--rim-2);
    border-radius: var(--radius-sm);
    padding: 10px 12px;
    font-family: var(--font-mono);
    font-size: 12.5px;
    color: var(--bright);
    outline: none;
    transition: all var(--t) var(--ease);
    appearance: none;
    letter-spacing: 0.03em;
}

.inp::placeholder, .tex::placeholder { color: var(--muted); }

.inp:hover, .sel:hover { border-color: var(--rim); }
.inp:focus, .sel:focus, .tex:focus {
    border-color: var(--teal);
    background: var(--elevated);
    box-shadow: 0 0 0 3px var(--teal-glow);
}

.inp.error { border-color: var(--rose) !important; box-shadow: 0 0 0 3px var(--rose-dim) !important; }

.sel {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23424861' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 11px center;
    padding-right: 32px;
}

.sel option { background: var(--raised); color: var(--bright); }
.tex { resize: vertical; min-height: 80px; line-height: 1.6; }

/* error hint */
.field-err {
    font-family: var(--font-mono);
    font-size: 10px;
    color: var(--rose);
    margin-top: 4px;
    letter-spacing: 0.04em;
}

/* ── PAYMENT METHOD CARDS ────────────────────── */
.method-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}

@media (max-width: 600px) { .method-cards { grid-template-columns: repeat(2, 1fr); } }

.method-card {
    position: relative;
    cursor: pointer;
    border: 1px solid var(--rim-2);
    border-radius: var(--radius);
    padding: 14px 10px;
    text-align: center;
    background: var(--raised);
    transition: all var(--t) var(--ease);
    overflow: hidden;
}

.method-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: var(--mc, var(--teal));
    transform: scaleX(0);
    transition: transform 0.25s var(--ease);
}

.method-card:hover { background: var(--elevated); border-color: var(--line-3); transform: translateY(-2px); }
.method-card:hover::after { transform: scaleX(1); }

.method-card.selected {
    border-color: var(--mc, var(--teal));
    background: rgba(0,212,170,0.06);
    box-shadow: inset 0 0 0 1px var(--mc, var(--teal));
    transform: translateY(-2px);
}
.method-card.selected::after { transform: scaleX(1); }
.method-card.selected .method-card-name { color: var(--bright); }

.method-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

.method-card-emoji { font-size: 20px; display: block; margin-bottom: 6px; }
.method-card-name {
    font-family: var(--font-mono);
    font-size: 9px;
    font-weight: 500;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--dim);
    line-height: 1.3;
}

/* ── AMOUNT DISPLAY ──────────────────────────── */
.amount-big {
    font-family: var(--font-serif);
    font-size: 3.4rem;
    font-weight: 700;
    font-style: italic;
    color: var(--teal);
    line-height: 1;
    letter-spacing: -0.02em;
    margin: 0.6rem 0;
    display: flex;
    align-items: baseline;
    gap: 6px;
    transition: color 0.3s;
}

.amount-big.zero { color: var(--muted); }

.amount-sym {
    font-size: 1.4rem;
    font-weight: 300;
    opacity: 0.6;
    font-style: normal;
    font-family: var(--font-mono);
}

/* ── CONDITIONAL FIELDS ───────────────────────── */
.cond-field {
    display: none;
    animation: condIn 0.3s var(--ease) both;
}
.cond-field.show { display: block; }
@keyframes condIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ════════════════════════════════════════════════
   ASIDE / SUMMARY PANEL
════════════════════════════════════════════════ */
.cr-aside {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    position: sticky;
    top: 20px;
    animation: asideIn 0.55s 0.22s var(--ease) both;
}

@keyframes asideIn {
    from { opacity: 0; transform: translateX(18px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* receipt preview card */
.receipt-preview {
    background: var(--surface);
    border: 1px solid var(--line-2);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.rp-header {
    background: var(--raised);
    padding: 12px 16px;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.rp-header-label {
    font-family: var(--font-mono);
    font-size: 9.5px;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--muted);
}

.rp-live {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-mono);
    font-size: 9.5px;
    color: var(--teal);
    letter-spacing: 0.06em;
}

.rp-dot {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--teal);
    animation: rpPulse 2s ease infinite;
}
@keyframes rpPulse { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

/* thermal receipt body */
.rp-body {
    background: #F2EFE7;
    padding: 1.25rem;
    font-family: var(--font-mono);
    color: #1C1C1C;
}

.rp-institution {
    text-align: center;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #333;
    padding-bottom: 0.75rem;
    border-bottom: 1px dashed #BBBAB0;
    margin-bottom: 0.75rem;
}

.rp-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 10.5px;
    padding: 3px 0;
    color: #555;
}

.rp-row .rk { opacity: 0.6; }
.rp-row .rv { font-weight: 500; color: #222; }

hr.rp-dash { border: none; border-top: 1px dashed #BBBAB0; margin: 8px 0; }

.rp-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    font-weight: 700;
    color: #111;
    padding-top: 8px;
    border-top: 1px solid #CCC;
}

.rp-total .rv { color: #059669; font-size: 14px; }

.rp-footer {
    text-align: center;
    font-size: 9px;
    color: #999;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px dashed #BBBAB0;
}

/* checklist card */
.cr-checklist {
    background: var(--surface);
    border: 1px solid var(--line-2);
    border-radius: var(--radius-xl);
    padding: 1.25rem;
}

.checklist-title {
    font-family: var(--font-mono);
    font-size: 9.5px;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 1rem;
}

.checklist-items { display: flex; flex-direction: column; gap: 8px; }

.checklist-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11.5px;
}

.cl-icon {
    width: 22px; height: 22px;
    border-radius: 6px;
    background: var(--raised);
    border: 1px solid var(--rim-2);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
    transition: all 0.3s;
}
.cl-icon.done { background: rgba(34,214,106,0.12); border-color: rgba(34,214,106,0.3); color: var(--emerald); }
.cl-icon.warn { background: var(--amber-dim); border-color: var(--amber-border); color: var(--amber); }

.cl-text { color: var(--dim); transition: color 0.3s; }
.cl-text.done { color: var(--body); }

/* ── VALIDATION ERROR BOX ────────────────────── */
.error-box {
    background: var(--rose-dim);
    border: 1px solid var(--rose-border);
    border-left: 3px solid var(--rose);
    border-radius: var(--radius-lg);
    padding: 14px 16px;
    margin-bottom: 1.25rem;
    animation: errorIn 0.4s var(--ease) both;
}
@keyframes errorIn {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
.error-box-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--rose);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-mono);
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
.error-box ul { list-style: none; }
.error-box li {
    font-size: 10.5px;
    color: rgba(255,85,112,0.85);
    padding: 2px 0;
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-mono);
}
.error-box li::before { content: '▸'; font-size: 9px; }

/* ── FIXED FOOTER ────────────────────────────── */
.cr-footer {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: rgba(6,7,9,0.94);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-top: 1px solid var(--line-2);
    padding: 12px 24px;
    z-index: 100;
}

.cr-footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.cr-footer-left {
    font-family: var(--font-mono);
    font-size: 10px;
    color: var(--muted);
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 12px;
}

.cr-footer-right { display: flex; gap: 8px; }

/* ── KEYBOARD HINT ───────────────────────────── */
.kbd {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--rim-2);
    border: 1px solid var(--rim);
    border-bottom-width: 2px;
    border-radius: 4px;
    padding: 1px 5px;
    font-family: var(--font-mono);
    font-size: 9px;
    color: var(--dim);
    letter-spacing: 0;
}

/* ── SCROLLBAR ───────────────────────────────── */
* { scrollbar-width: thin; scrollbar-color: var(--rim-2) transparent; }
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-thumb { background: var(--rim-2); border-radius: 3px; }

/* responsive */
@media (max-width: 768px) {
    .cr-wrap { padding: 0 1rem; }
    .cr-header-title { display: none; }
}
</style>

<div class="cr-page">

    {{-- ── TOP NAV ──────────────────────────────── --}}
    <div class="cr-wrap" style="padding-top:0;">
        <nav class="cr-nav">
            <div class="cr-breadcrumb">
                <a href="{{ route('fee-payments.index') }}">Payments</a>
                <span class="sep">/</span>
                <span class="cur">Record New</span>
            </div>

            <div class="cr-header-title">
                <h1>Record Fee Payment</h1>
                <p>Enter payment details below</p>
            </div>

            <div class="cr-nav-right">
                <a href="{{ route('fee-payments.index') }}" class="btn btn-ghost">
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <path d="M9 2L4 7l5 5"/>
                    </svg>
                    Back
                </a>
            </div>
        </nav>
    </div>

    <div class="cr-wrap">

        {{-- ERRORS --}}
        @if($errors->any())
        <div class="error-box">
            <div class="error-box-title">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M8 1L15 14H1L8 1z"/><path d="M8 6v4M8 11.5v.5" stroke-linecap="round"/>
                </svg>
                Validation Errors
            </div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- SPLIT LAYOUT --}}
        <div class="cr-split">

            {{-- ═══ FORM COLUMN ═══ --}}
            <div class="cr-form-col">

                <form action="{{ route('fee-payments.store') }}" method="POST" id="paymentForm" novalidate>
                @csrf

                {{-- ── CARD 1: STUDENT SEARCH ── --}}
                <div class="cr-card">
                    <div class="cr-card-head">
                        <div class="cr-card-icon">
                            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" width="14" height="14">
                                <circle cx="8" cy="5" r="3"/><path d="M1 14c0-3.866 3.134-7 7-7s7 3.134 7 7" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span class="cr-card-label">Select Student</span>
                        <span class="cr-card-badge" id="studentCountBadge">{{ $students->count() }} students</span>
                    </div>

                    <div class="cr-card-body">

                        {{-- Hidden real input --}}
                        <input type="hidden" name="student_id" id="student_id_hidden" value="{{ old('student_id', $student->id ?? '') }}" required>

                        {{-- ── SELECTED STUDENT (shown after selection) ── --}}
                        <div class="selected-student-panel" id="selectedStudentPanel">
                            <div class="selected-student-inner">
                                <div class="selected-avatar" id="selectedAvatar">?</div>
                                <div class="selected-info">
                                    <div class="selected-name" id="selectedName">—</div>
                                    <div class="selected-tags">
                                        <span class="selected-tag adm" id="selectedAdm">—</span>
                                        <span style="color:var(--muted)">·</span>
                                        <span class="selected-tag" id="selectedGrade">—</span>
                                    </div>
                                </div>
                                <button type="button" class="change-btn" onclick="resetStudentSearch()">Change</button>
                            </div>
                        </div>

                        {{-- ── SEARCH PANEL ── --}}
                        <div id="studentSearchPanel">

                            {{-- Search input --}}
                            <div class="student-search-bar">
                                <svg class="search-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="6.5" cy="6.5" r="5"/><path d="M11 11l3 3" stroke-linecap="round"/>
                                </svg>
                                <input
                                    type="text"
                                    id="studentSearchInput"
                                    placeholder="Search by name, admission #, or grade…"
                                    autocomplete="off"
                                    spellcheck="false"
                                >
                                <button type="button" class="search-clear-btn" id="searchClearBtn" onclick="clearSearch()">✕</button>
                            </div>

                            {{-- Filter tabs --}}
                            <div class="filter-tabs">
                                <button type="button" class="filter-tab active" data-filter="all" onclick="setFilter('all', this)">All</button>
                                <button type="button" class="filter-tab" data-filter="name" onclick="setFilter('name', this)">By Name</button>
                                <button type="button" class="filter-tab" data-filter="adm" onclick="setFilter('adm', this)">By Adm #</button>
                                <button type="button" class="filter-tab" data-filter="grade" onclick="setFilter('grade', this)">By Grade</button>
                            </div>

                            {{-- Results meta --}}
                            <div class="results-meta">
                                <span>
                                    <span class="results-count" id="resultsCount">{{ $students->count() }}</span>
                                    / {{ $students->count() }} students
                                </span>
                                <span id="filterHint" style="color:var(--muted);">Type to search…</span>
                            </div>

                            {{-- Results list --}}
                            <div class="student-results-list" id="studentResultsList">
                                @forelse($students as $s)
                                    <div
                                        class="student-result-item {{ old('student_id') == $s->id || (isset($student) && $student->id == $s->id) ? 'selected' : '' }}"
                                        data-id="{{ $s->id }}"
                                        data-name="{{ $s->first_name }} {{ $s->last_name }}"
                                        data-firstname="{{ $s->first_name }}"
                                        data-lastname="{{ $s->last_name }}"
                                        data-adm="{{ $s->admission_number }}"
                                        data-grade="{{ $s->grade }}"
                                        onclick="selectStudent(this)"
                                    >
                                        <div class="student-avatar">
                                            {{ strtoupper(substr($s->first_name, 0, 1)) }}{{ strtoupper(substr($s->last_name, 0, 1)) }}
                                        </div>
                                        <div class="student-result-info">
                                            <div class="student-result-name">
                                                {{ $s->first_name }} {{ $s->last_name }}
                                            </div>
                                            <div class="student-result-meta">
                                                <span class="student-adm">{{ $s->admission_number }}</span>
                                                <span class="grade-badge">Grade {{ $s->grade }}</span>
                                            </div>
                                        </div>
                                        <div class="student-select-check">✓</div>
                                    </div>
                                @empty
                                    <div class="search-empty">
                                        <div class="search-empty-icon">🎓</div>
                                        <div class="search-empty-text">No active students found</div>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                        {{-- /studentSearchPanel --}}

                    </div>
                </div>
                {{-- /student card --}}

                {{-- ── CARD 2: PAYMENT DETAILS ── --}}
                <div class="cr-card">
                    <div class="cr-card-head">
                        <div class="cr-card-icon amber">
                            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" width="14" height="14">
                                <rect x="1" y="4" width="14" height="9" rx="2"/><path d="M1 7h14M5 11h2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span class="cr-card-label">Payment Details</span>
                    </div>

                    <div class="cr-card-body">

                        {{-- Amount big display --}}
                        <div class="amount-big zero" id="amountDisplay">
                            <span class="amount-sym">KES</span>
                            <span id="amountFormatted">0</span>
                        </div>

                        {{-- Amount + Date row --}}
                        <div class="field-row" style="margin-bottom:14px;">
                            <div class="field-group">
                                <label class="field-label" for="amount_paid">
                                    Amount (KES) <span class="req">*</span>
                                </label>
                                <input
                                    type="number"
                                    name="amount_paid"
                                    id="amount_paid"
                                    class="inp {{ $errors->has('amount_paid') ? 'error' : '' }}"
                                    placeholder="0.00"
                                    min="1"
                                    step="0.01"
                                    value="{{ old('amount_paid') }}"
                                    required
                                >
                                @error('amount_paid')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="payment_date">
                                    Payment Date <span class="req">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="payment_date"
                                    id="payment_date"
                                    class="inp {{ $errors->has('payment_date') ? 'error' : '' }}"
                                    value="{{ old('payment_date', date('Y-m-d')) }}"
                                    required
                                >
                                @error('payment_date')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Year + Term --}}
                        <div class="field-row" style="margin-bottom:14px;">
                            <div class="field-group">
                                <label class="field-label" for="academic_year">
                                    Academic Year <span class="req">*</span>
                                </label>
                                <select name="academic_year" id="academic_year" class="sel {{ $errors->has('academic_year') ? 'error' : '' }}" required>
                                    @foreach(range(date('Y'), date('Y')-3) as $yr)
                                        <option value="{{ $yr }}" {{ old('academic_year', date('Y')) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                    @endforeach
                                </select>
                                @error('academic_year')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="term">
                                    Term <span class="req">*</span>
                                </label>
                                <select name="term" id="term" class="sel {{ $errors->has('term') ? 'error' : '' }}" required>
                                    @foreach(['Term 1','Term 2','Term 3'] as $t)
                                        <option value="{{ $t }}" {{ old('term','Term 1') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                @error('term')<div class="field-err">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Payment Method Cards --}}
                        <div class="field-group" style="margin-bottom:14px;">
                            <label class="field-label">Payment Method <span class="req">*</span></label>
                            <div class="method-cards" id="methodCards">
                                @foreach([
                                    ['cash','💵','Cash','--teal'],
                                    ['mpesa','📱','M-Pesa','#22C55E'],
                                    ['bank_transfer','🏦','Bank Txfr','#4D8EFF'],
                                    ['cheque','📄','Cheque','#A78BFA'],
                                ] as [$val, $emoji, $name, $color])
                                    <label class="method-card {{ old('payment_method') === $val ? 'selected' : '' }}" style="--mc:{{ $color }}">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="{{ $val }}"
                                            {{ old('payment_method') === $val ? 'checked' : '' }}
                                        >
                                        <span class="method-card-emoji">{{ $emoji }}</span>
                                        <span class="method-card-name">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('payment_method')<div class="field-err" style="margin-top:6px;">{{ $message }}</div>@enderror
                        </div>

                        {{-- M-PESA Code (conditional) --}}
                        <div class="field-group cond-field {{ old('payment_method') === 'mpesa' ? 'show' : '' }}" id="mpesaCodeField">
                            <label class="field-label">M-PESA Transaction Code</label>
                            <input
                                type="text"
                                name="mpesa_code"
                                id="mpesaCode"
                                class="inp {{ $errors->has('mpesa_code') ? 'error' : '' }}"
                                placeholder="e.g. QHX7F3KL9A"
                                maxlength="50"
                                value="{{ old('mpesa_code') }}"
                                style="text-transform:uppercase; letter-spacing:0.12em;"
                            >
                            @error('mpesa_code')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        {{-- Bank / Cheque Ref (conditional) --}}
                        <div class="field-group cond-field {{ in_array(old('payment_method'),['bank_transfer','cheque']) ? 'show' : '' }}" id="bankRefField">
                            <label class="field-label" id="bankRefLabel">Bank Reference</label>
                            <input
                                type="text"
                                name="bank_reference"
                                id="bankRef"
                                class="inp {{ $errors->has('bank_reference') ? 'error' : '' }}"
                                placeholder="Reference or cheque number"
                                value="{{ old('bank_reference') }}"
                            >
                        </div>

                    </div>
                </div>

                {{-- ── CARD 3: NOTES ── --}}
                <div class="cr-card">
                    <div class="cr-card-head">
                        <div class="cr-card-icon sapphire">
                            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" width="14" height="14">
                                <path d="M2 3h12M2 7h8M2 11h5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span class="cr-card-label">Notes (Optional)</span>
                    </div>
                    <div class="cr-card-body">
                        <textarea
                            name="notes"
                            id="notes"
                            class="inp tex {{ $errors->has('notes') ? 'error' : '' }}"
                            placeholder="e.g. Late payment for Term 1, balance pending for exam registration…"
                            rows="3"
                        >{{ old('notes') }}</textarea>
                        @error('notes')<div class="field-err">{{ $message }}</div>@enderror
                    </div>
                </div>

                </form>
            </div>
            {{-- /form col --}}

            {{-- ═══ ASIDE / SUMMARY ═══ --}}
            <div class="cr-aside">

                {{-- Live Receipt Preview --}}
                <div class="receipt-preview">
                    <div class="rp-header">
                        <span class="rp-header-label">Live Receipt Preview</span>
                        <span class="rp-live"><span class="rp-dot"></span> Live</span>
                    </div>
                    <div class="rp-body">
                        <div class="rp-institution">
                            {{ config('app.name', 'Institution') }}<br>
                            <span style="font-weight:300; font-size:8.5px; letter-spacing:0.08em;">OFFICIAL FEE RECEIPT</span>
                        </div>

                        <div class="rp-row">
                            <span class="rk">Student</span>
                            <span class="rv" id="rp_student">—</span>
                        </div>
                        <div class="rp-row">
                            <span class="rk">Adm. No.</span>
                            <span class="rv" id="rp_adm">—</span>
                        </div>
                        <div class="rp-row">
                            <span class="rk">Grade</span>
                            <span class="rv" id="rp_grade">—</span>
                        </div>

                        <hr class="rp-dash">

                        <div class="rp-row">
                            <span class="rk">Year / Term</span>
                            <span class="rv" id="rp_term">—</span>
                        </div>
                        <div class="rp-row">
                            <span class="rk">Date</span>
                            <span class="rv" id="rp_date">{{ date('d M Y') }}</span>
                        </div>
                        <div class="rp-row">
                            <span class="rk">Method</span>
                            <span class="rv" id="rp_method">—</span>
                        </div>
                        <div class="rp-row" id="rp_ref_row" style="display:none">
                            <span class="rk">Reference</span>
                            <span class="rv" id="rp_ref">—</span>
                        </div>

                        <hr class="rp-dash">

                        <div class="rp-total">
                            <span>AMOUNT PAID</span>
                            <span class="rv" id="rp_amount">KES —</span>
                        </div>

                        <div class="rp-footer">
                            Recorded by: {{ auth()->user()->name }}<br>
                            {{ now()->format('d M Y H:i') }} EAT
                        </div>
                    </div>
                </div>

                {{-- Form Readiness Checklist --}}
                <div class="cr-checklist">
                    <p class="checklist-title">Form Readiness</p>
                    <div class="checklist-items">
                        <div class="checklist-item">
                            <div class="cl-icon" id="cl_student">○</div>
                            <span class="cl-text" id="clt_student">Student selected</span>
                        </div>
                        <div class="checklist-item">
                            <div class="cl-icon" id="cl_amount">○</div>
                            <span class="cl-text" id="clt_amount">Amount entered</span>
                        </div>
                        <div class="checklist-item">
                            <div class="cl-icon" id="cl_method">○</div>
                            <span class="cl-text" id="clt_method">Payment method</span>
                        </div>
                        <div class="checklist-item">
                            <div class="cl-icon" id="cl_date">○</div>
                            <span class="cl-text" id="clt_date">Payment date</span>
                        </div>
                        <div class="checklist-item">
                            <div class="cl-icon" id="cl_term">○</div>
                            <span class="cl-text" id="clt_term">Academic period</span>
                        </div>
                    </div>
                </div>

            </div>
            {{-- /aside --}}

        </div>
        {{-- /split --}}

    </div>
    {{-- /wrap --}}

</div>
{{-- /page --}}

{{-- ── FIXED FOOTER ─────────────────────────────── --}}
<div class="cr-footer">
    <div class="cr-footer-inner">
        <div class="cr-footer-left">
            <span id="footerStatus" style="color:var(--muted);">Fill in all required fields</span>
            <span>·</span>
            <span><kbd class="kbd">⌘</kbd>&nbsp;<kbd class="kbd">S</kbd> to save</span>
        </div>
        <div class="cr-footer-right">
            <a href="{{ route('fee-payments.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" form="paymentForm" class="btn btn-teal" id="submitBtn">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="8" r="7"/><path d="M5 8l2 2 4-4"/>
                </svg>
                Record Payment
            </button>
        </div>
    </div>
</div>

@php
    $studentsJson = $students->map(function($s) {
        $first = $s->first_name ?? '';
        $last  = $s->last_name  ?? '';
        return [
            'id'    => $s->id,
            'name'  => trim($first . ' ' . $last),
            'first' => $first,
            'last'  => $last,
            'adm'   => $s->admission_number ?? '',
            'grade' => (string)($s->grade ?? ''),
            'init'  => strtoupper(substr($first, 0, 1) . substr($last, 0, 1)),
        ];
    })->values()->all();
@endphp

<script>
/* ══════════════════════════════════════════════════
   GOD MODE JAVASCRIPT
   - Live fuzzy student search with filter tabs
   - Payment method conditional fields
   - Live receipt preview sync
   - Form readiness checklist
   - Keyboard shortcuts
══════════════════════════════════════════════════ */

// ── STUDENT DATA FROM BLADE ───────────────────
const STUDENTS = @json($studentsJson);

// ── STATE ─────────────────────────────────────
let currentFilter = 'all';
let currentQuery  = '';
let selectedStudentData = null;

// ── DOM REFS ──────────────────────────────────
const searchInput     = document.getElementById('studentSearchInput');
const clearBtn        = document.getElementById('searchClearBtn');
const resultsList     = document.getElementById('studentResultsList');
const searchPanel     = document.getElementById('studentSearchPanel');
const selectedPanel   = document.getElementById('selectedStudentPanel');
const hiddenStudentId = document.getElementById('student_id_hidden');
const countBadge      = document.getElementById('studentCountBadge');
const resultsCount    = document.getElementById('resultsCount');
const filterHint      = document.getElementById('filterHint');

// ── FILTER HELPER ─────────────────────────────
function setFilter(filter, btn) {
    currentFilter = filter;
    document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const hints = {
        all:   'Search by name, admission # or grade…',
        name:  'Type a student name…',
        adm:   'Type an admission number…',
        grade: 'Type a grade (e.g. 6, 10)…',
    };

    searchInput.placeholder = hints[filter] || 'Search…';
    renderResults(currentQuery);
    searchInput.focus();
}

// ── SEARCH LOGIC ──────────────────────────────
function matchStudent(s, q, filter) {
    if (!q) return true;
    const lq = q.toLowerCase();
    if (filter === 'adm')   return s.adm.toLowerCase().includes(lq);
    if (filter === 'grade') return s.grade.toLowerCase().includes(lq);
    if (filter === 'name')  return s.name.toLowerCase().includes(lq);
    // all: match any field
    return (
        s.name.toLowerCase().includes(lq) ||
        s.adm.toLowerCase().includes(lq)  ||
        s.grade.toLowerCase().includes(lq)
    );
}

function highlight(text, query) {
    if (!query) return text;
    const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark>$1</mark>');
}

function renderResults(query) {
    const q = query.trim();
    const filtered = STUDENTS.filter(s => matchStudent(s, q, currentFilter));

    resultsCount.textContent = filtered.length;

    if (filtered.length === 0) {
        resultsList.innerHTML = `
            <div class="search-empty">
                <div class="search-empty-icon">🔍</div>
                <div class="search-empty-text">No students match "<em>${q}</em>"</div>
            </div>`;
        return;
    }

    resultsList.innerHTML = filtered.map((s, i) => {
        const isSelected = selectedStudentData && selectedStudentData.id === s.id;
        const hlName = highlight(s.name, currentFilter !== 'adm' && currentFilter !== 'grade' ? q : '');
        const hlAdm  = highlight(s.adm,  currentFilter === 'adm' || currentFilter === 'all' ? q : '');
        const hlGrade = highlight('Grade ' + s.grade, currentFilter === 'grade' ? q : 'Grade ' + s.grade);

        return `
            <div
                class="student-result-item ${isSelected ? 'selected' : ''}"
                data-id="${s.id}"
                data-name="${s.name}"
                data-first="${s.first}"
                data-last="${s.last}"
                data-adm="${s.adm}"
                data-grade="${s.grade}"
                data-init="${s.init}"
                onclick="selectStudent(this)"
                style="animation-delay:${Math.min(i * 0.03, 0.3)}s"
            >
                <div class="student-avatar">${s.init}</div>
                <div class="student-result-info">
                    <div class="student-result-name">${hlName}</div>
                    <div class="student-result-meta">
                        <span class="student-adm">${hlAdm}</span>
                        <span class="grade-badge">${hlGrade}</span>
                    </div>
                </div>
                <div class="student-select-check">✓</div>
            </div>
        `;
    }).join('');
}

// ── SELECT STUDENT ────────────────────────────
function selectStudent(el) {
    const data = {
        id:    el.dataset.id,
        name:  el.dataset.name,
        adm:   el.dataset.adm,
        grade: el.dataset.grade,
        init:  el.dataset.init || (el.dataset.name.substring(0,2).toUpperCase()),
    };

    selectedStudentData = data;

    // set hidden input
    hiddenStudentId.value = data.id;

    // populate selected panel
    document.getElementById('selectedAvatar').textContent = data.init;
    document.getElementById('selectedName').textContent   = data.name;
    document.getElementById('selectedAdm').textContent    = data.adm;
    document.getElementById('selectedGrade').textContent  = 'Grade ' + data.grade;

    // show/hide panels
    selectedPanel.classList.add('show');
    searchPanel.style.display = 'none';

    // update receipt preview
    document.getElementById('rp_student').textContent = data.name;
    document.getElementById('rp_adm').textContent     = data.adm;
    document.getElementById('rp_grade').textContent   = 'Grade ' + data.grade;

    // update checklist
    setCheck('cl_student', 'clt_student', true, 'Student selected');

    updateFooterStatus();
}

function resetStudentSearch() {
    selectedStudentData = null;
    hiddenStudentId.value = '';
    selectedPanel.classList.remove('show');
    searchPanel.style.display = '';
    searchInput.value = '';
    currentQuery = '';
    clearBtn.classList.remove('visible');
    renderResults('');
    searchInput.focus();

    // reset receipt
    document.getElementById('rp_student').textContent = '—';
    document.getElementById('rp_adm').textContent     = '—';
    document.getElementById('rp_grade').textContent   = '—';

    setCheck('cl_student', 'clt_student', false, 'Student selected');
    updateFooterStatus();
}

function clearSearch() {
    searchInput.value = '';
    currentQuery = '';
    clearBtn.classList.remove('visible');
    renderResults('');
    searchInput.focus();
}

// ── SEARCH INPUT EVENT ────────────────────────
searchInput.addEventListener('input', function() {
    currentQuery = this.value;
    clearBtn.classList.toggle('visible', currentQuery.length > 0);
    filterHint.textContent = currentQuery ? `Searching "${currentQuery}"…` : 'Type to search…';
    renderResults(currentQuery);
});

searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') clearSearch();
    // Enter selects first result
    if (e.key === 'Enter') {
        e.preventDefault();
        const first = resultsList.querySelector('.student-result-item');
        if (first) selectStudent(first);
    }
});

// ── PAYMENT METHOD ────────────────────────────
document.querySelectorAll('#methodCards .method-card input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
        this.closest('.method-card').classList.add('selected');

        const val = this.value;

        // conditional fields
        document.getElementById('mpesaCodeField').classList.toggle('show', val === 'mpesa');
        document.getElementById('bankRefField').classList.toggle('show', val === 'bank_transfer' || val === 'cheque');
        document.getElementById('bankRefLabel').textContent = val === 'cheque' ? 'Cheque Number' : 'Bank Reference';

        // receipt preview
        const methodNames = { cash:'Cash', mpesa:'M-PESA', bank_transfer:'Bank Transfer', cheque:'Cheque' };
        document.getElementById('rp_method').textContent = methodNames[val] || '—';

        setCheck('cl_method', 'clt_method', true, 'Payment method set');
        updateFooterStatus();
    });
});

// ── AMOUNT ────────────────────────────────────
document.getElementById('amount_paid').addEventListener('input', function() {
    const val = parseFloat(this.value) || 0;
    const displayEl = document.getElementById('amountDisplay');
    const fmtEl = document.getElementById('amountFormatted');

    fmtEl.textContent = val > 0 ? val.toLocaleString('en-KE', { minimumFractionDigits: 0, maximumFractionDigits: 2 }) : '0';
    displayEl.classList.toggle('zero', val <= 0);

    document.getElementById('rp_amount').textContent = val > 0
        ? 'KES ' + val.toLocaleString()
        : 'KES —';

    setCheck('cl_amount', 'clt_amount', val > 0, 'Amount entered');
    updateFooterStatus();
});

// ── PAYMENT DATE ──────────────────────────────
document.getElementById('payment_date').addEventListener('change', function() {
    if (this.value) {
        const d = new Date(this.value + 'T00:00:00');
        document.getElementById('rp_date').textContent = d.toLocaleDateString('en-KE', { day:'2-digit', month:'short', year:'numeric' });
        setCheck('cl_date', 'clt_date', true, 'Date confirmed');
    }
    updateFooterStatus();
});

// ── TERM / YEAR ───────────────────────────────
function syncTerm() {
    const yr   = document.getElementById('academic_year').value;
    const term = document.getElementById('term').value;
    document.getElementById('rp_term').textContent = yr && term ? `${yr} / ${term}` : '—';
    setCheck('cl_term', 'clt_term', !!(yr && term), 'Academic period set');
    updateFooterStatus();
}
document.getElementById('academic_year').addEventListener('change', syncTerm);
document.getElementById('term').addEventListener('change', syncTerm);

// ── MPESA CODE auto-uppercase ─────────────────
document.getElementById('mpesaCode').addEventListener('input', function() {
    const pos = this.selectionStart;
    this.value = this.value.toUpperCase();
    this.setSelectionRange(pos, pos);
    const row = document.getElementById('rp_ref_row');
    const ref = document.getElementById('rp_ref');
    row.style.display = this.value ? '' : 'none';
    ref.textContent = this.value;
});

document.getElementById('bankRef').addEventListener('input', function() {
    const row = document.getElementById('rp_ref_row');
    const ref = document.getElementById('rp_ref');
    row.style.display = this.value ? '' : 'none';
    ref.textContent = this.value;
});

// ── CHECKLIST ─────────────────────────────────
function setCheck(iconId, textId, done, text) {
    const icon = document.getElementById(iconId);
    const txt  = document.getElementById(textId);
    if (!icon || !txt) return;
    icon.textContent = done ? '✓' : '○';
    icon.classList.toggle('done', done);
    txt.textContent = text;
    txt.classList.toggle('done', done);
}

// ── FOOTER STATUS ─────────────────────────────
function updateFooterStatus() {
    const hasStudent = !!hiddenStudentId.value;
    const hasAmount  = parseFloat(document.getElementById('amount_paid').value) > 0;
    const hasMethod  = !!document.querySelector('#methodCards input[type="radio"]:checked');
    const hasDate    = !!document.getElementById('payment_date').value;

    const all = hasStudent && hasAmount && hasMethod && hasDate;
    const statusEl = document.getElementById('footerStatus');

    if (all) {
        statusEl.style.color = 'var(--teal)';
        statusEl.textContent = '✓ Ready to record payment';
    } else {
        statusEl.style.color = 'var(--muted)';
        const missing = [];
        if (!hasStudent) missing.push('student');
        if (!hasAmount)  missing.push('amount');
        if (!hasMethod)  missing.push('method');
        if (!hasDate)    missing.push('date');
        statusEl.textContent = 'Missing: ' + missing.join(', ');
    }
}

// ── FORM SUBMIT ───────────────────────────────
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    if (!hiddenStudentId.value) {
        e.preventDefault();
        searchInput.focus();
        searchInput.style.borderColor = 'var(--rose)';
        setTimeout(() => searchInput.style.borderColor = '', 1500);
        return;
    }
    const btn = document.getElementById('submitBtn');
    btn.textContent = 'Recording…';
    btn.disabled = true;
});

// ── KEYBOARD SHORTCUT ─────────────────────────
document.addEventListener('keydown', e => {
    if ((e.metaKey || e.ctrlKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('paymentForm').requestSubmit();
    }
    // '/' focuses search
    if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
        e.preventDefault();
        searchInput.focus();
    }
});

// ── INIT ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    renderResults('');
    syncTerm();

    // restore old() state if validation failed
    @if(old('student_id'))
        const preselect = STUDENTS.find(s => s.id == {{ old('student_id') }});
        if (preselect) {
            const fakeEl = {
                dataset: {
                    id: preselect.id,
                    name: preselect.name,
                    adm: preselect.adm,
                    grade: preselect.grade,
                    init: preselect.init,
                }
            };
            selectStudent(fakeEl);
        }
    @endif

    // Init checklist
    const hasDate   = !!document.getElementById('payment_date').value;
    const hasMethod = !!document.querySelector('#methodCards input[type="radio"]:checked');
    const hasTerm   = !!document.getElementById('academic_year').value;

    if (hasDate)   setCheck('cl_date',   'clt_date',   true, 'Date confirmed');
    if (hasMethod) setCheck('cl_method', 'clt_method', true, 'Payment method set');
    if (hasTerm)   setCheck('cl_term',   'clt_term',   true, 'Academic period set');

    updateFooterStatus();
    syncTerm();
});
</script>

@endsection