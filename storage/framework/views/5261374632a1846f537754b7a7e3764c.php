<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing | Privacy Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #9945FF;
            --secondary: #14F195;
            --accent: #00C2FF; 
            --text-main: #1e1e2f;
            --text-muted: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.8);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F7FF;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Background */
        .ambient-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -3;
            background: #ffffff; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.6;
            animation: floatOrb 25s infinite ease-in-out alternate;
        }
        .orb-1 { width: 800px; height: 800px; background: #C084FC; top: -200px; left: -100px; opacity: 0.4; }
        .orb-2 { width: 600px; height: 600px; background: #2DD4BF; bottom: -100px; right: -100px; animation-duration: 35s; }
        .noise-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2;
            opacity: 0.03; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        @keyframes floatOrb {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(30px, 30px) rotate(5deg); }
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

        /* HERO */
        .pricing-hero {
            text-align: center; padding: 100px 0 60px;
        }
        .pricing-hero h1 {
            font-family: 'Space Grotesk', sans-serif; font-size: 3.5rem; font-weight: 800;
            margin-bottom: 20px; color: #0f172a;
        }
        .pricing-hero h1 span {
            background: linear-gradient(135deg, var(--primary), #4F46E5); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .pricing-hero p {
            color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto 40px;
        }

        /* TOGGLE */
        .toggle-container {
            display: inline-flex; align-items: center; background: #e2e8f0; padding: 4px; border-radius: 50px;
            position: relative; margin-bottom: 60px;
        }
        .toggle-btn {
            padding: 10px 24px; border-radius: 40px; border: none; cursor: pointer;
            font-weight: 600; font-size: 0.9rem; transition: all 0.3s; z-index: 2;
            background: transparent; color: var(--text-muted);
        }
        .toggle-btn.active {
            background: white; color: #0f172a; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .discount-badge {
            position: absolute; top: -12px; right: -20px;
            background: #10B981; color: white; font-size: 0.7rem; font-weight: 700;
            padding: 4px 8px; border-radius: 20px; transform: rotate(12deg);
        }

        /* PRICING GRID */
        .pricing-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; margin-bottom: 100px;
        }
        .price-card {
            background: rgba(255,255,255,0.7); backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.6); border-radius: 24px; padding: 40px;
            transition: all 0.3s; position: relative; overflow: hidden;
            display: flex; flex-direction: column;
        }
        .price-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .price-card:active { transform: translateY(-5px) scale(0.99); transition: all 0.1s; }
        
        .price-card.popular {
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            background: rgba(255,255,255,0.9); border: 2px solid #e2e8f0;
        }
        
        /* TIER COLORS */
        .price-card.tier-1 { border-color: var(--secondary); box-shadow: 0 20px 40px rgba(20, 241, 149, 0.1); }
        .price-card.tier-2 { border-color: var(--primary); box-shadow: 0 20px 40px rgba(153, 69, 255, 0.15); }
        .price-card.tier-3 { 
            border-color: #F59E0B; 
            box-shadow: 0 20px 40px rgba(245, 158, 11, 0.2);
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,247,237,0.95));
        }

        .pop-badge {
            color: white; text-align: center; font-size: 0.75rem; font-weight: 700;
            padding: 6px; position: absolute; top: 0; left: 0; width: 100%; letter-spacing: 1px;
        }
        .tier-1 .pop-badge { background: var(--secondary); color: #064e3b; }
        .tier-2 .pop-badge { background: var(--primary); }
        .tier-3 .pop-badge { background: linear-gradient(90deg, #F59E0B, #EF4444); }

        .plan-name { font-family: 'Space Grotesk'; font-size: 1.5rem; font-weight: 700; margin-bottom: 16px; color: #334155; }
        .price-wrap { display: flex; align-items: baseline; gap: 4px; margin-bottom: 8px; height: 50px; }
        .currency { font-size: 1.5rem; font-weight: 600; color: #334155; }
        .amount { font-size: 3rem; font-weight: 800; color: #0f172a; letter-spacing: -1px; }
        .period { color: var(--text-muted); font-size: 1rem; }
        .plan-desc { color: var(--text-muted); margin-bottom: 32px; font-size: 0.95rem; line-height: 1.5; }

        .btn-plan {
            width: 100%; padding: 14px; border-radius: 12px; font-weight: 600; cursor: pointer;
            transition: all 0.2s; text-align: center; text-decoration: none; margin-bottom: 32px;
            border: 1px solid #cbd5e1; background: transparent; color: #334155;
        }
        .price-card.tier-1 .btn-plan { background: var(--secondary); color: #064e3b; border: none; }
        .price-card.tier-1 .btn-plan:hover { background: #10B981; }

        .price-card.tier-2 .btn-plan { background: var(--primary); color: white; border: none; }
        .price-card.tier-2 .btn-plan:hover { background: #7e22ce; }

        .price-card.tier-3 .btn-plan { background: linear-gradient(90deg, #F59E0B, #EF4444); color: white; border: none; }
        .price-card.tier-3 .btn-plan:hover { opacity: 0.9; transform: scale(1.02); }
        .btn-plan:hover { background: #f1f5f9; border-color: #94a3b8; }

        .features-list { list-style: none; margin-top: auto; }
        .features-list li {
            display: flex; align-items: center; gap: 12px; margin-bottom: 16px; color: #475569; font-size: 0.9rem;
        }
        .check-icon { color: #10B981; width: 18px; height: 18px; flex-shrink: 0; }

        /* FAQ */
        .faq-section { max-width: 800px; margin: 0 auto 100px; }
        .faq-header { text-align: center; margin-bottom: 40px; }
        .faq-item {
            background: white; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 16px; overflow: hidden;
            transition: all 0.3s;
        }
        .faq-question {
            padding: 24px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;
            font-weight: 600; color: #1e293b;
        }
        .faq-answer {
            padding: 0 24px; max-height: 0; overflow: hidden; color: #64748b; line-height: 1.6;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        .faq-item.active .faq-answer { padding-bottom: 24px; max-height: 200px; }
        .faq-toggle { transition: transform 0.3s; }
        .faq-item.active .faq-toggle { transform: rotate(180deg); }

        @media (max-width: 900px) {
            .pricing-grid { grid-template-columns: 1fr; max-width: 400px; margin-left: auto; margin-right: auto; }
            .pricing-hero h1 { font-size: 2.5rem; }
        }

        /* PAYMENT MODAL CSS */
        .modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
        }
        .modal-backdrop.active { opacity: 1; pointer-events: all; }

        .payment-modal {
            background: rgba(255, 255, 255, 0.95); width: 90%; max-width: 500px;
            border-radius: 32px; padding: 40px; position: relative;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.3);
            transform: scale(0.9) translateY(20px); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(255,255,255,0.8);
        }
        .modal-backdrop.active .payment-modal { transform: scale(1) translateY(0); }

        .close-modal {
            position: absolute; top: 24px; right: 24px; background: #f1f5f9; border: none;
            width: 36px; height: 36px; border-radius: 50%; cursor: pointer; color: #64748b;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }
        .close-modal:hover { background: #e2e8f0; color: #ef4444; transform: rotate(90deg); }

        .pm-header { text-align: center; margin-bottom: 32px; }
        .pm-title { font-family: 'Space Grotesk'; font-size: 1.75rem; color: #1e293b; margin-bottom: 8px; }
        .pm-summary { font-size: 0.95rem; color: #64748b; background: #f8fafc; display: inline-block; padding: 6px 16px; border-radius: 20px; font-weight: 600; }

        .pm-options { display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }
        .pm-option {
            display: flex; align-items: center; padding: 16px 20px; border: 2px solid #e2e8f0;
            border-radius: 16px; cursor: pointer; transition: all 0.2s; background: white;
        }
        .pm-option:hover { border-color: #cbd5e1; background: #f8fafc; }
        .pm-option.selected { border-color: var(--primary); background: #f5f3ff; }
        
        .pm-icon { width: 32px; height: 32px; margin-right: 16px; display: flex; align-items: center; justify-content: center; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .pm-name { font-weight: 600; color: #334155; flex: 1; }
        .pm-check { width: 20px; height: 20px; border: 2px solid #cbd5e1; border-radius: 50%; position: relative; }
        .pm-option.selected .pm-check { border-color: var(--primary); background: var(--primary); }
        .pm-option.selected .pm-check::after { content: ''; position: absolute; top: 5px; left: 5px; width: 6px; height: 6px; background: white; border-radius: 50%; }

        .btn-pay {
            width: 100%; background: #0f172a; color: white; border: none; padding: 16px;
            border-radius: 16px; font-weight: 700; font-size: 1.1rem; cursor: pointer;
            transition: all 0.2s; box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.2);
        }
        .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 20px 30px -5px rgba(15, 23, 42, 0.3); }

        /* CONTACT MODAL FORM */
        .input-group { margin-bottom: 16px; width: 100%; }
        .input-label { display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: 600; color: #334155; }
        .input-field { 
            width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px;
            font-family: inherit; font-size: 1rem; color: #1e293b; outline: none; transition: all 0.2s;
        }
        .input-field:focus { border-color: var(--primary); background: #f8fafc; }
        .input-area { min-height: 100px; resize: vertical; }
    </style>
</head>
<body>

    <div class="ambient-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="noise-overlay"></div>
    </div>

    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container">
        
        <!-- Hero -->
        <div class="pricing-hero">
            <h1 id="price-title">Transparent Pricing <br><span>For Everyone</span></h1>
            <p id="price-desc">Choose the plan that fits your needs. No hidden fees, cancel anytime.</p>
            
            <div class="toggle-container">
                <button class="toggle-btn active" onclick="setPeriod('monthly')" id="btn-monthly">Monthly</button>
                <button class="toggle-btn" onclick="setPeriod('yearly')" id="btn-yearly">Yearly</button>
                <div class="discount-badge" id="save-badge">Save 20%</div>
            </div>
        </div>

        <!-- Cards -->
        <div class="pricing-grid">
            
            <!-- STARTER -->
            <div class="price-card tier-1">
                <div class="pop-badge" id="badge-p1">BEST VALUE</div>
                <div class="plan-name" id="p1-name">Starter</div>
                <div class="price-wrap">
                    <span class="currency">$</span>
                    <span class="amount" id="p1-amount">0</span>
                    <span class="period" id="p1-period">/mo</span>
                </div>
                <p class="plan-desc" id="p1-desc">Perfect for hobbyists and testing the API.</p>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-plan" id="p1-btn">Get Started</a>
                <ul class="features-list">
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p1-f1">100 Calls / Month</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p1-f2">Basic Analysis</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p1-f3">Community Support</span></li>
                </ul>
            </div>

            <!-- PRO -->
            <div class="price-card tier-2 popular">
                <div class="pop-badge" id="badge-pop">MOST POPULAR</div>
                <div class="plan-name" id="p2-name">Pro</div>
                <div class="price-wrap">
                    <span class="currency">$</span>
                    <span class="amount" id="p2-amount">29</span>
                    <span class="period" id="p2-period">/mo</span>
                </div>
                <p class="plan-desc" id="p2-desc">For developers and startups building trust.</p>
                <button class="btn-plan" id="p2-btn" onclick="openPaymentModal('pro')">Get Started</button>
                <ul class="features-list">
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p2-f1">50,000 Calls / Month</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p2-f2">Deep Forensics (Video/Audio)</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p2-f3">Priority Email Support</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p2-f4">API Dashboard</span></li>
                </ul>
            </div>

            <!-- ENTERPRISE -->
            <div class="price-card tier-3">
                <div class="pop-badge" id="badge-p3">ULTIMATE</div>
                <div class="plan-name" id="p3-name">Enterprise</div>
                <div class="price-wrap">
                    <span class="currency"> </span>
                    <span class="amount" id="p3-amount" style="font-size: 2rem;">Custom</span>
                    <span class="period" id="p3-period"> </span>
                </div>
                <p class="plan-desc" id="p3-desc">Scalable solutions for large organizations.</p>
                <button class="btn-plan" id="p3-btn" onclick="openContactModal()">Contact Sales</button>
                <ul class="features-list">
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p3-f1">Unlimited Calls</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p3-f2">On-Premise Deployment</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p3-f3">24/7 Dedicated Support</span></li>
                    <li><svg class="check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> <span id="p3-f4">SLA Guarantees</span></li>
                </ul>
            </div>
        </div>

        <!-- FAQ -->
        <div class="faq-section">
            <h2 class="faq-header" id="faq-title">Frequently Asked Questions</h2>
            
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span id="q1">Is there a free trial?</span>
                    <svg class="faq-toggle" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <div class="faq-answer" id="a1">Yes! The Starter plan is completely free and allows you to test our API with up to 100 calls per month. No credit card required.</div>
            </div>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span id="q2">Can I cancel anytime?</span>
                    <svg class="faq-toggle" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <div class="faq-answer" id="a2">Absolutely. We don't believe in lock-in contracts for the Pro plan. You can cancel your subscription directly from the dashboard.</div>
            </div>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span id="q3">What happens if I exceed my limit?</span>
                    <svg class="faq-toggle" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <div class="faq-answer" id="a3">We will notify you when you reach 80% and 100% of your usage. For critical applications, we recommend the Enterprise plan which offers unlimited scaling.</div>
            </div>
        </div>

    </div>

    <!-- PAYMENT MODAL -->
    <div class="modal-backdrop" id="payModal" onclick="closePaymentModal(event)">
        <div class="payment-modal">
            <button class="close-modal" onclick="closePaymentModal(event)">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="pm-header">
                <h2 class="pm-title" id="pm-title">Select Payment Method</h2>
                <div class="pm-summary" id="pm-summary">Pro Plan - $29/month</div>
            </div>

            <div class="pm-options">
                <!-- Card -->
                <div class="pm-option selected" onclick="selectPay(this)">
                    <div class="pm-icon">💳</div>
                    <span class="pm-name" id="pm-card">Credit Card</span>
                    <div class="pm-check"></div>
                </div>
                <!-- PayPal -->
                <div class="pm-option" onclick="selectPay(this)">
                    <div class="pm-icon">🅿️</div>
                    <span class="pm-name" id="pm-paypal">PayPal</span>
                    <div class="pm-check"></div>
                </div>
                <!-- Crypto -->
                <div class="pm-option" onclick="selectPay(this)">
                    <div class="pm-icon">₿</div>
                    <span class="pm-name" id="pm-crypto">Crypto</span>
                    <div class="pm-check"></div>
                </div>
            </div>

            <button class="btn-pay" id="pm-btn">Pay Now</button>
        </div>
    </div>

    <!-- CONTACT MODAL -->
    <div class="modal-backdrop" id="contactModal" onclick="closeContactModal(event)">
        <div class="payment-modal">
            <button class="close-modal" onclick="closeContactModal(event)">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="pm-header">
                <h2 class="pm-title" id="cm-title">Contact Sales</h2>
                <div class="pm-summary" id="cm-desc">For Enterprise Solutions</div>
            </div>

            <div class="input-group">
                <label class="input-label" id="lbl-name">Full Name</label>
                <input type="text" class="input-field" placeholder="John Doe">
            </div>
            <div class="input-group">
                <label class="input-label" id="lbl-email">Company Email</label>
                <input type="email" class="input-field" placeholder="name@company.com">
            </div>
            <div class="input-group">
                <label class="input-label" id="lbl-msg">Requirements</label>
                <textarea class="input-field input-area" placeholder="Tell us about your needs..."></textarea>
            </div>

            <button class="btn-pay" id="cm-btn">Send Request</button>
        </div>
    </div>
    </div>

    <script>
        let currentLang = 'en';
        let isYearly = false;

        const translations = {
            en: {
                nav: { dash: "Dashboard", tech: "Technology", dev: "Developers", price: "Pricing", supp: "Support" },
                hero: { title: "Transparent Pricing <br><span>For Everyone</span>", desc: "Choose the plan that fits your needs. No hidden fees, cancel anytime.", m: "Monthly", y: "Yearly", save: "Save 20%" },
                p1: { name: "Starter", desc: "Perfect for hobbyists and testing the API.", btn: "Get Started", f1: "100 Calls / Month", f2: "Basic Analysis", f3: "Community Support", badge: "FREE FOREVER" },
                p2: { name: "Pro", desc: "For developers and startups building trust.", btn: "Get Started", f1: "50,000 Calls / Month", f2: "Deep Forensics", f3: "Priority Support", f4: "API Dashboard", badge: "MOST POPULAR" },
                p3: { name: "Enterprise", amount: "Custom", desc: "Scalable solutions for large organizations.", btn: "Contact Sales", f1: "Unlimited Calls", f2: "On-Premise", f3: "24/7 Support", f4: "SLA Guarantees", badge: "ULTIMATE FORCE" },
                faq: { 
                    title: "Frequently Asked Questions",
                    q1: "Is there a free trial?", a1: "Yes! The Starter plan is completely free and allows you to test our API with up to 100 calls per month.",
                    q2: "Can I cancel anytime?", a2: "Absolutely. We don't believe in lock-in contracts for the Pro plan. You can cancel your subscription directly.",
                    q3: "What happens if I exceed my limit?", a3: "We will notify you when you reach 80% and 100% of your usage. For critical applications, consider Enterprise."
                },
                periods: { mo: "/mo", yr: "/yr" },
                pay: { title: "Select Payment Method", card: "Credit Card", paypal: "PayPal", crypto: "Crypto", btn: "Pay Now" },
                contact: { title: "Contact Sales", desc: "For Enterprise Solutions", name: "Full Name", email: "Company Email", msg: "Requirements", btn: "Send Request" }
            },
            id: {
                nav: { dash: "Dasbor", tech: "Teknologi", dev: "Developers", price: "Harga", supp: "Bantuan" },
                hero: { title: "Harga Transparan <br><span>Untuk Semua</span>", desc: "Pilih paket yang sesuai kebutuhan Anda. Tanpa biaya tersembunyi.", m: "Bulanan", y: "Tahunan", save: "Hemat 20%" },
                p1: { name: "Pemula", desc: "Sempurna untuk hobi dan tes API.", btn: "Mulai Sekarang", f1: "100 Panggilan / Bulan", f2: "Analisis Dasar", f3: "Dukungan Komunitas", badge: "GRATIS SELAMANYA" },
                p2: { name: "Pro", desc: "Untuk developer dan startup.", btn: "Langganan", f1: "50,000 Panggilan / Bulan", f2: "Forensik Mendalam", f3: "Dukungan Prioritas", f4: "Dashboard API", badge: "TERPOPULER" },
                p3: { name: "Enterprise", amount: "Kontak", desc: "Solusi skala besar untuk korporat.", btn: "Hubungi Sales", f1: "Panggilan Tanpa Batas", f2: "On-Premise", f3: "Dukungan 24/7", f4: "Jaminan SLA", badge: "ULTIMATE" },
                faq: { 
                    title: "Pertanyaan Umum",
                    q1: "Apakah ada uji coba gratis?", a1: "Ya! Paket Pemula 100% gratis dan memungkinkan Anda menguji API hingga 100 panggilan per bulan.",
                    q2: "Bisakah saya membatalkan kapan saja?", a2: "Tentu. Kami tidak memberlakukan kontrak penguncian untuk paket Pro. Batalkan langsung dari dashboard.",
                    q3: "Bagaimana jika melebihi batas?", a3: "Kami akan memberi tahu saat penggunaan mencapai 80% dan 100%. Untuk skala besar, pertimbangkan Enterprise."
                },
                periods: { mo: "/bln", yr: "/thn" },
                pay: { title: "Pilih Metode Pembayaran", card: "Kartu Kredit", paypal: "PayPal", crypto: "Kripto", btn: "Bayar Sekarang" },
                contact: { title: "Hubungi Sales", desc: "Untuk Solusi Perusahaan", name: "Nama Lengkap", email: "Email Perusahaan", msg: "Kebutuhan", btn: "Kirim Permintaan" }
            },
            es: {
                nav: { dash: "Tablero", tech: "Tecnología", dev: "Desarrolladores", price: "Precios", supp: "Ayuda" },
                hero: { title: "Precios Transparentes <br><span>Para Todos</span>", desc: "Elige el plan que se adapte a ti. Sin tarifas ocultas.", m: "Mensual", y: "Anual", save: "Ahoora 20%" },
                p1: { name: "Inicial", desc: "Perfecto para aficionados.", btn: "Empezar", f1: "100 Llamadas / Mes", f2: "Análisis Básico", f3: "Soporte Comunitario", badge: "GRATIS SIEMPRE" },
                p2: { name: "Pro", desc: "Para desarrolladores y startups.", btn: "Suscribirse", f1: "50,000 Llamadas / Mes", f2: "Forense Profundo", f3: "Soporte Prioritario", f4: "Panel API", badge: "POPULAR" },
                p3: { name: "Enterprise", amount: "Medida", desc: "Soluciones escalables corporativas.", btn: "Contactar Ventas", f1: "Llamadas Ilimitadas", f2: "On-Premise", f3: "Soporte 24/7", f4: "Garantía SLA", badge: "ULTIMATE" },
                faq: { 
                    title: "Preguntas Frecuentes",
                    q1: "¿Hay prueba gratuita?", a1: "¡Sí! El plan Inicial es gratis y permite probar nuestra API con hasta 100 llamadas.",
                    q2: "¿Puedo cancelar cuando sea?", a2: "Absolutamente. Puedes cancelar tu suscripción Pro directamente desde el panel.",
                    q3: "¿Qué pasa si excedo mi límite?", a3: "Te avisaremos al 80% y 100%. Para aplicaciones críticas, considera Enterprise."
                },
                periods: { mo: "/mes", yr: "/año" },
                pay: { title: "Método de Pago", card: "Tarjeta Crédito", paypal: "PayPal", crypto: "Cripto", btn: "Pagar Ahora" },
                contact: { title: "Contactar Ventas", desc: "Soluciones Empresariales", name: "Nombre Completo", email: "Email Corporativo", msg: "Requisitos", btn: "Enviar Solicitud" }
            },
            fr: {
                nav: { dash: "Tableau de bord", tech: "Technologies", dev: "Développeurs", price: "Tarifs", supp: "Support" },
                hero: { title: "Tarification Transparente <br><span>Pour Tous</span>", desc: "Choisissez le plan adapté. Pas de frais cachés.", m: "Mensuel", y: "Annuel", save: "-20%" },
                p1: { name: "Débutant", desc: "Parfait pour tester l'API.", btn: "Commencer", f1: "100 Appels / Mois", f2: "Analyse de Base", f3: "Support Communautaire", badge: "GRATUIT" },
                p2: { name: "Pro", desc: "Pour développeurs et startups.", btn: "S'abonner", f1: "50,000 Appels / Mois", f2: "Forensique Avancée", f3: "Support Prioritaire", f4: "Tableau de Bord", badge: "POPULAIRE" },
                p3: { name: "Enterprise", amount: "Contact", desc: "Solutions évolutives.", btn: "Contacter Ventes", f1: "Appels Illimités", f2: "Sur Site", f3: "Support 24/7", f4: "Garantie SLA", badge: "ULTIME" },
                faq: { 
                    title: "Questions Fréquentes",
                    q1: "Y a-t-il un essai gratuit ?", a1: "Oui ! Le plan Débutant est gratuit jusqu'à 100 appels par mois.",
                    q2: "Puis-je annuler à tout moment ?", a2: "Absolument. Vous pouvez annuler votre abonnement Pro directement depuis le tableau de bord.",
                    q3: "Si je dépasse ma limite ?", a3: "Nous vous informerons à 80% et 100%. Pour un usage critique, choisissez Enterprise."
                },
                periods: { mo: "/mois", yr: "/an" },
                pay: { title: "Moyen de Paiement", card: "Carte Crédit", paypal: "PayPal", crypto: "Crypto", btn: "Payer" },
                contact: { title: "Contacter Ventes", desc: "Solutions Entreprise", name: "Nom Complet", email: "Email Pro", msg: "Besoins", btn: "Envoyer" }
            },
            de: {
                nav: { dash: "Dashboard", tech: "Technologien", dev: "Entwickler", price: "Preise", supp: "Support" },
                hero: { title: "Transparente Preise <br><span>Für Alle</span>", desc: "Wählen Sie Ihren Plan. Keine versteckten Gebühren.", m: "Monatlich", y: "Jährlich", save: "-20%" },
                p1: { name: "Starter", desc: "Perfekt zum Testen.", btn: "Loslegen", f1: "100 Anrufe / Monat", f2: "Basisanalyse", f3: "Community-Support", badge: "KOSTENLOS" },
                p2: { name: "Pro", desc: "Für Entwickler & Startups.", btn: "Abonnieren", f1: "50.000 Anrufe / Monat", f2: "Tiefe Forensik", f3: "Prioritäts-Support", f4: "API-Dashboard", badge: "BELIEBT" },
                p3: { name: "Enterprise", amount: "Kontakt", desc: "Skalierbare Lösungen.", btn: "Vertrieb", f1: "Unbegrenzte Anrufe", f2: "On-Premise", f3: "24/7 Support", f4: "SLA-Garantien", badge: "ULTIMATIV" },
                faq: { 
                    title: "Häufig gestellte Fragen",
                    q1: "Gibt es eine kostenlose Testversion?", a1: "Ja! Der Starter-Plan ist kostenlos und erlaubt bis zu 100 Anrufe.",
                    q2: "Kann ich jederzeit kündigen?", a2: "Absolut. Sie können das Pro-Abo direkt im Dashboard kündigen.",
                    q3: "Was passiert bei Limitüberschreitung?", a3: "Wir informieren Sie bei 80% und 100%. Für kritische Anwendungen empfehlen wir Enterprise."
                },
                periods: { mo: "/mon", yr: "/jahr" },
                pay: { title: "Zahlungsmethode", card: "Kreditkarte", paypal: "PayPal", crypto: "Krypto", btn: "Jetzt Zahlen" },
                contact: { title: "Vertrieb Kontaktieren", desc: "Für Unternehmenslösungen", name: "Vollständiger Name", email: "Firmen-E-Mail", msg: "Anforderungen", btn: "Anfrage Senden" }
            },
            jp: {
                nav: { dash: "ダッシュボード", tech: "技術", dev: "開発者", price: "価格", supp: "サポート" },
                hero: { title: "透明性のある価格設定 <br><span>すべての人に</span>", desc: "ニーズに合ったプランをお選びください。隠れた料金はありません。", m: "月次", y: "年次", save: "20%オフ" },
                p1: { name: "スターター", desc: "テストに最適。", btn: "始める", f1: "100コール / 月", f2: "基本分析", f3: "コミュニティサポート", badge: "無料" },
                p2: { name: "プロ", desc: "開発者とスタートアップ向け。", btn: "登録する", f1: "50,000コール / 月", f2: "詳細なフォレンジック", f3: "優先サポート", f4: "APIダッシュボード", badge: "人気" },
                p3: { name: "エンタープライズ", amount: "要相談", desc: "大規模組織向け。", btn: "営業に連絡", f1: "無制限コール", f2: "オンプレミス", f3: "24時間サポート", f4: "SLA保証", badge: "究極" },
                faq: { 
                    title: "よくある質問",
                    q1: "無料トライアルはありますか？", a1: "はい！スタータープランは無料で、月100コールまで試せます。",
                    q2: "いつでもキャンセルできますか？", a2: "もちろんです。ダッシュボードから直接キャンセルできます。",
                    q3: "制限を超えた場合は？", a3: "80%と100%の時点で通知します。重要な用途にはエンタープライズを検討してください。"
                },
                periods: { mo: "/月", yr: "/年" },
                pay: { title: "支払方法の選択", card: "クレジットカード", paypal: "PayPal", crypto: "仮想通貨", btn: "支払う" },
                contact: { title: "営業に連絡", desc: "エンタープライズソリューション", name: "氏名", email: "会社メール", msg: "要件", btn: "リクエスト送信" }
            },
            cn: {
                nav: { dash: "仪表板", tech: "技术", dev: "开发者", price: "价格", supp: "支持" },
                hero: { title: "透明定价 <br><span>人人适用</span>", desc: "选择适合您的计划。无隐藏费用。", m: "月付", y: "年付", save: "省20%" },
                p1: { name: "入门", desc: "适合测试。", btn: "开始", f1: "100次调用 / 月", f2: "基础分析", f3: "社区支持", badge: "免费" },
                p2: { name: "专业版", desc: "适合开发者。", btn: "订阅", f1: "50,000次调用 / 月", f2: "深度取证", f3: "优先支持", f4: "API仪表板", badge: "热门" },
                p3: { name: "企业版", amount: "定制", desc: "大规模解决方案。", btn: "联系销售", f1: "无限调用", f2: "本地部署", f3: "24/7支持", f4: "SLA保证", badge: "旗舰" },
                faq: { 
                    title: "常见问题",
                    q1: "有免费试用吗？", a1: "有！入门计划完全免费，每月可进行100次调用。",
                    q2: "我可以随时取消吗？", a2: "当然。您可以直接在仪表板中取消专业版订阅。",
                    q3: "如果超出限制怎么办？", a3: "达到80%和100%时我们会通知您。关键应用建议使用企业版。"
                },
                periods: { mo: "/月", yr: "/年" },
                pay: { title: "选择付款方式", card: "信用卡", paypal: "PayPal", crypto: "加密货币", btn: "立即支付" },
                contact: { title: "联系销售", desc: "企业解决方案", name: "全名", email: "公司邮箱", msg: "需求", btn: "发送请求" }
            },
            ru: {
                nav: { dash: "Дашборд", tech: "Технологии", dev: "Разработчики", price: "Цены", supp: "Поддержка" },
                hero: { title: "Честные цены <br><span>Для всех</span>", desc: "Выберите план, который подходит вам. Никаких скрытых комиссий.", m: "Месяц", y: "Год", save: "-20%" },
                p1: { name: "Стартовый", desc: "Идеально для тестов.", btn: "Начать", f1: "100 запросов / мес", f2: "Базовый анализ", f3: "Поддержка сообщества", badge: "БЕСПЛАТНО" },
                p2: { name: "Про", desc: "Для стартапов.", btn: "Подписаться", f1: "50,000 запросов / мес", f2: "Глубокая криминалистика", f3: "Приоритетная поддержка", f4: "API Дашборд", badge: "ПОПУЛЯРНЫЙ" },
                p3: { name: "Enterprise", amount: "Заказ", desc: "Для корпораций.", btn: "Связаться", f1: "Безлимитные запросы", f2: "On-Premise", f3: "Поддержка 24/7", f4: "Гарантия SLA", badge: "ТОП" },
                faq: { 
                    title: "Частые вопросы",
                    q1: "Есть ли бесплатная пробная версия?", a1: "Да! Стартовый план бесплатен и дает 100 запросов в месяц.",
                    q2: "Можно ли отменить в любое время?", a2: "Конечно. Вы можете отменить подписку прямо в дашборде.",
                    q3: "Что если я превышу лимит?", a3: "Мы уведомим вас при 80% и 100%. Для критических задач рассмотрите Enterprise."
                },
                periods: { mo: "/мес", yr: "/год" },
                pay: { title: "Способ оплаты", card: "Кредитная карта", paypal: "PayPal", crypto: "Криптовалюта", btn: "Оплатить" },
                contact: { title: "Связаться с отделом продаж", desc: "Для корпоративных решений", name: "ФИО", email: "Корпоративная почта", msg: "Требования", btn: "Отправить запрос" }
            }
        };

        function setPeriod(period) {
            isYearly = (period === 'yearly');
            
            // Toggle visual state
            document.getElementById('btn-monthly').classList.toggle('active', !isYearly);
            document.getElementById('btn-yearly').classList.toggle('active', isYearly);

            updateContent();
        }

        // PAYMENT MODAL LOGIC
        function openPaymentModal(plan) {
            const modal = document.getElementById('payModal');
            modal.classList.add('active');
            
            // Update summary based on period and plan
            const t = translations[currentLang] || translations['en'];
            const price = isYearly ? "$290" : "$29";
            const period = isYearly ? (currentLang === 'en' ? "/year" : t.periods.yr) : (currentLang === 'en' ? "/month" : t.periods.mo);
            
            // Assuming only PRO opens this for now
            const summaryText = `${t.p2.name} - ${price}${period}`;
            document.getElementById('pm-summary').innerText = summaryText;

            // Prevent scroll
            document.body.style.overflow = 'hidden';
        }

        function closePaymentModal(e) {
             if (e.target === document.getElementById('payModal') || e.target.classList.contains('close-modal') || e.target.closest('.close-modal')) {
                document.getElementById('payModal').classList.remove('active');
                document.body.style.overflow = '';
             }
        }

        function selectPay(el) {
            document.querySelectorAll('.pm-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
        }

        // CONTACT MODAL LOGIC
        function openContactModal() {
            document.getElementById('contactModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeContactModal(e) {
             if (e.target === document.getElementById('contactModal') || e.target.classList.contains('close-modal') || e.target.closest('.close-modal')) {
                document.getElementById('contactModal').classList.remove('active');
                document.body.style.overflow = '';
             }
        }

        function toggleFaq(el) {
            // Close others 
            // document.querySelectorAll('.faq-item').forEach(item => {
            //     if(item !== el) item.classList.remove('active');
            // });
            el.classList.toggle('active');
        }

        function updateContent() {
            let t = translations[currentLang] || translations['en'];
            
            // Helper for ID safely
            const setTxt = (id, txt) => { const el = document.getElementById(id); if(el) el.innerHTML = txt; };

            // Navbar (Assuming IDs exist from component)
            if(document.getElementById('nav-dash')) {
                setTxt('nav-dash', t.nav.dash);
                setTxt('nav-tech', t.nav.tech);
                setTxt('nav-dev', t.nav.dev);
                setTxt('nav-price', t.nav.price);
                setTxt('nav-support', t.nav.supp);
            }

            // Hero
            setTxt('price-title', t.hero.title);
            setTxt('price-desc', t.hero.desc);
            setTxt('btn-monthly', t.hero.m);
            setTxt('btn-yearly', t.hero.y);
            setTxt('save-badge', t.hero.save);

            // Prices Logic
            const periodTxt = isYearly ? t.periods.yr : t.periods.mo;
            const proPrice = isYearly ? 290 : 29;

            // Plan 1
            setTxt('p1-name', t.p1.name);
            setTxt('badge-p1', t.p1.badge);
            setTxt('p1-desc', t.p1.desc);
            setTxt('p1-btn', t.p1.btn);
            setTxt('p1-period', periodTxt);
            setTxt('p1-f1', t.p1.f1);
            setTxt('p1-f2', t.p1.f2);
            setTxt('p1-f3', t.p1.f3);

            // Plan 2
            setTxt('p2-name', t.p2.name);
            setTxt('badge-pop', t.p2.badge);
            setTxt('p2-amount', proPrice);
            setTxt('p2-period', periodTxt);
            setTxt('p2-desc', t.p2.desc);
            setTxt('p2-btn', t.p2.btn);
            setTxt('p2-f1', t.p2.f1);
            setTxt('p2-f2', t.p2.f2);
            setTxt('p2-f3', t.p2.f3);
            setTxt('p2-f4', t.p2.f4);

            // Plan 3
            setTxt('p3-name', t.p3.name);
            setTxt('badge-p3', t.p3.badge);
            setTxt('p3-amount', t.p3.amount);
            setTxt('p3-desc', t.p3.desc);
            setTxt('p3-btn', t.p3.btn);
            setTxt('p3-f1', t.p3.f1);
            setTxt('p3-f2', t.p3.f2);
            setTxt('p3-f3', t.p3.f3);
            setTxt('p3-f4', t.p3.f4);

            // FAQ
            setTxt('faq-title', t.faq.title);
            setTxt('q1', t.faq.q1); setTxt('a1', t.faq.a1);
            setTxt('q2', t.faq.q2); setTxt('a2', t.faq.a2);
            setTxt('q3', t.faq.q3); setTxt('a3', t.faq.a3);

            // Payment Modal
            if(t.pay) {
                setTxt('pm-title', t.pay.title);
                setTxt('pm-card', t.pay.card);
                setTxt('pm-paypal', t.pay.paypal);
                setTxt('pm-crypto', t.pay.crypto);
                setTxt('pm-btn', t.pay.btn);
            }
            
            // Contact Modal
            if(t.contact) {
                setTxt('cm-title', t.contact.title);
                setTxt('cm-desc', t.contact.desc);
                setTxt('lbl-name', t.contact.name);
                setTxt('lbl-email', t.contact.email);
                setTxt('lbl-msg', t.contact.msg);
                setTxt('cm-btn', t.contact.btn);
            }
        }

        function applyLang(lang) {
            currentLang = lang;
            updateContent();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedLang = localStorage.getItem('privasi_lang') || 'en';
            applyLang(savedLang);

            // Listen for global language change
            window.addEventListener('languageChanged', () => {
                const newLang = localStorage.getItem('privasi_lang') || 'en';
                applyLang(newLang);
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\privasi-app\resources\views/pages/pricing.blade.php ENDPATH**/ ?>